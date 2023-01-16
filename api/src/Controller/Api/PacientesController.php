<?php
namespace App\Controller\Api;
use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
use App\Utils\Comum;
use Cake\I18n\Time;
use Cake\Core\Exception\Exception;
use App\Model\Entity\Paciente;
/**
 * Pacientes Controller
 *
 * @property \App\Model\Table\PacientesTable $Paciente
 *
 * @method \App\Model\Entity\Paciente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PacientesController extends ApiController
{
    public $connection;
    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow(['add']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
    }

    /**
     * View method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Declara transação
        $conn = $this->connection;

        try {

            $pacientesTable = TableRegistry::getTableLocator()->get('Pacientes');
            $paciente = $pacientesTable->newEntity();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //variavel com os erros caso existam
            $validacoes = $this->validacao_paciente($dados);

            /** @var TYPE_NAME $validacoes */
            if (!isset($validacoes['error'])) {

                //pega os dados do token
                $dados_token = $this->getDadosToken();

                //veifica se encontrou os dados do token
                if(empty($dados_token)) {
                    $error = 'Não foi possivel encontrar os dados no Token!';
                    $this->set(compact('error'));
                    return;
                }

                if(!is_null($dados['cpf'])){
                    $cpf_unico = $this->Pacientes->getPacienteCpf($dados['cpf']);
                    if (!empty($cpf_unico)) {
                        $error = 'CPF já cadastrado!';
                        $this->set(compact('error'));
                        return;
                    }

                    $dados['cpf'] = Comum::soNumero($dados['cpf']);
                }

                //seta o codigo usuario
                $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;

                $paciente->nome                     = $dados['nome'];
                $paciente->cpf                      = $dados['cpf'];
                $paciente->rg                       = Comum::soNumero($dados['rg']);
                $paciente->data_nascimento          = $dados['data_nascimento'];
                $paciente->email                    = $dados['email'];
                $paciente->telefone                 = $dados['telefone'];
                $paciente->ativo                    = 1;
                $paciente->codigo_empresa           = 1;
                $paciente->codigo_usuario_inclusao  = $codigo_usuario;
                $paciente->data_inclusao            = date("Y-m-d H:i:s");
                $paciente->sexo                     = $dados['sexo'];

                //inicia transacao
                $conn->begin();
                $pacienteSalvo = $pacientesTable->save($paciente);
                if ($pacienteSalvo) {
                    $saveCodigo = $pacienteSalvo->codigo;

                    $pacientesDadosTrabalhoTable = TableRegistry::getTableLocator()->get('PacientesDadosTrabalho');
                    $pacienteDadosTrabalho = $pacientesTable->newEntity();

                    $pacienteDadosTrabalho->codigo_paciente            = $saveCodigo ;
                    $pacienteDadosTrabalho->setor                      = $dados['setor'];
                    $pacienteDadosTrabalho->codigo_pacientes_categoria = 2;
                    $pacienteDadosTrabalho->codigo_fornecedor          = $dados['codigo_fornecedor'];
                    $pacienteDadosTrabalho->codigo_empresa             = 1;
                    $pacienteDadosTrabalho->empresa                    = $dados['empresa'];
                    $pacienteDadosTrabalho->ativo                      = 1;
                    $pacienteDadosTrabalho->codigo_usuario_inclusao    = $codigo_usuario;
                    $pacienteDadosTrabalho->data_inclusao              = date("Y-m-d H:i:s");

                    if ($dadosPacienteDadosTrabalhos = $pacientesDadosTrabalhoTable->save($pacienteDadosTrabalho)) {
                        $data = array(
                            "codigo_paciente" => $saveCodigo
                        );
                    } else {
                        $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
                        $data = array(
                            "save" => false,
                            "message" => "Error ao adicionar novo paciente!"
                        );
                    }
                }

                //finaliza a transacao
                $conn->commit();

                $this->set(compact('data'));
            }else {
                $this->set('error', $validacoes);
            }

        } catch (Exception $e) {
            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }


    }//Fim metodo add

    /**
     * [validacao_paciente description]
     *
     * metodo para validar os dados do paciente
     *
     * validações:
     *     cpf invalido
     *     data nascimento invalida
     *
     * @param  [type] $dados [description]
     * @return [type]        [description]
     */
    public function validacao_paciente($dados)
    {

        //variavel de erro
        $error = array();

        $cpf = null;
        if (isset($dados['cpf'])) {
            //formata o cpf
            $cpf = Comum::soNumero($dados['cpf']);

            //Valida CPF
            if (!Comum::validarCPF($cpf)) {
                $error['error'] = true;
                $error['validations']['cpf'] = 'CPF inválido';
            }
        }

        if (isset($dados['email'])) {
            if(strlen($dados['email']) > 80){
                $error['error'] = true;
                $error['validations']['email'] = 'E-mail muito grande.';
            }
        }

        return $error;
    }//fim validacao_paciente

    /**
     * Edit method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        //Declara transação
        $conn = $this->connection;

        try {
            //pega os dados que veio do post
            $dados = $this->request->getData();

            $pacientesTable = TableRegistry::getTableLocator()->get('Pacientes');
            $paciente = $pacientesTable->get($dados['codigo']);

            //variavel com os erros caso existam
            $validacoes = $this->validacao_paciente($dados);

            /** @var TYPE_NAME $validacoes */
            if (!isset($validacoes['error'])) {

                //pega os dados do token
                $dados_token = $this->getDadosToken();

                //veifica se encontrou os dados do token
                if(empty($dados_token)) {
                    $error = 'Não foi possivel encontrar os dados no Token!';
                    $this->set(compact('error'));
                    return;
                }

                //seta o codigo usuario
                $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;

                $paciente->nome                     = $dados['nome'];
                $paciente->cpf                      = Comum::soNumero($dados['cpf']);
                $paciente->rg                       = Comum::soNumero($dados['rg']);
                $paciente->data_nascimento          = $dados['data_nascimento'];
                $paciente->email                    = $dados['email'];
                $paciente->telefone                 = $dados['telefone'];
                $paciente->ativo                    = 1;
                $paciente->codigo_empresa           = 1;
                $paciente->codigo_usuario_alteracao = $codigo_usuario;
                $paciente->data_alteracao           = date("Y-m-d H:i:s");
                $paciente->sexo                     = $dados['sexo'];

                //inicia transacao
                $conn->begin();

                if ($pacienteSalvo = $pacientesTable->save($paciente)) {
                    $saveCodigo = $pacienteSalvo->codigo;

                    $pacientesDadosTrabalhoTable = TableRegistry::getTableLocator()->get('PacientesDadosTrabalho');
                    $pacienteDadosTrabalho = $pacientesTable->get($saveCodigo);

                    $pacienteDadosTrabalho->setor                      = $dados['setor'];
                    $pacienteDadosTrabalho->codigo_pacientes_categoria = $dados['codigo_pacientes_categoria'];
                    $pacienteDadosTrabalho->codigo_fornecedor          = $dados['codigo_fornecedor'];
                    $pacienteDadosTrabalho->empresa                    = $dados['empresa'];
                    $pacienteDadosTrabalho->ativo                      = $dados['ativo'];
                    $pacienteDadosTrabalho->codigo_usuario_alteracao   = $codigo_usuario;
                    $pacienteDadosTrabalho->data_alteracao             = date("Y-m-d H:i:s");

                    if ($dadosPacienteDadosTrabalhos = $pacientesDadosTrabalhoTable->save($pacienteDadosTrabalho)) {
                        $data = array(
                            "edit" => true,
                            "message" => "Paciente ID: " . $saveCodigo . " Editado com sucesso!"
                        );
                    } else {
                        $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
                        $data = array(
                            "save" => false,
                            "message" => "Error ao editar o paciente!"
                        );
                    }
                }
                //finaliza a transacao
                $conn->commit();

                $this->set(compact('data'));
            }else {
                $this->set('error', $validacoes);
            }

        } catch (Exception $e) {
            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Delete method
     *
     * @param string|null $id Paciente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
    }

    public function getExamePaciente()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //pega os dados que veio do post
        $dados = $this->request->getData();

        try {
            //seta para o retorno do abjeto
            $data = array();
            $query_paciente = '';

            //Filtra os exames pela data
            if (isset($dados['data'])) {
                $query_data = " and ae.data = '". $dados['data'] ."' ";
            } else {
                $query_data = " ";
            }

            $this->loadModel('Funcionarios');

            $dados_paciente = $this->Funcionarios->getPacienteDetalhe($dados['codigo_paciente'])->hydrate(false)->all()->toArray();
            // debug($dados_paciente);exit;
            if(empty($dados_paciente)) {
                $error = "Não foi possivel encontrar o paciente!";
                $this->set(compact('error'));
            }

            //varre os pacientes que encontrou
            foreach($dados_paciente AS $paciente) {

                if(empty($paciente['foto'])){
                    $paciente['foto'] = 'https://api.rhhealth.com.br/ithealth/2020/05/21/9CDD7B5D-588C-1E2E-FD49-403D2B1DABBC.png';
                }

                $paciente['data_nascimento'] = $paciente['data_nascimento']->i18nFormat('yyyy-MM-dd');
                $data = $paciente;
                $data['riscos'] = array();

                if($paciente['tipo'] == 'funcionario') {
                    $query_paciente_select[] = " pe.codigo_funcionario as codigo_paciente ";
                    $query_paciente .= " and pe.codigo_funcionario = ".$dados['codigo_paciente']." ";

                    //busca os riscos
                    $this->loadModel('GrupoExposicao');
                    $riscos = $this->GrupoExposicao->getRiscos($paciente['codigo_funcionario_setor_cargo']);
                    if(!empty($riscos)) {
                        $data['riscos'] = array_unique($riscos->toArray());
                    }
                }
                if($paciente['tipo'] == 'paciente') {
                    $query_paciente_select[] = " pe.codigo_paciente ";
                    $query_paciente .= " and pe.codigo_paciente = ".$dados['codigo_paciente']." ";
                }
            }

            //coloca virgula caso tenha mais de um registro
            $query_paciente_select = implode(",",$query_paciente_select);

            if (isset($data['codigo'])) { //Verifica se existe paciente

                $strSql = "SELECT
                            " . $query_paciente_select . " ,
                            ae.codigo as codigo_agendamento_exame,
                            ipe.codigo as codigo_itens_pedidos_exames,

                            ipe.codigo_fornecedor,
                            RHHealth.dbo.ufn_decode_utf8_string(CONCAT(forn.nome,' / ',forn.razao_social)) as fornecedor_unidade,
                            RHHealth.dbo.ufn_decode_utf8_string(ge.descricao) as empresa,
                            ipe.codigo_exame,
                            (CASE WHEN pe.pontual =1 THEN RHHealth.dbo.ufn_decode_utf8_string(e.descricao)
                                ELSE
                                    CASE
                                        WHEN pe.exame_admissional = 1 THEN 'Exame admissional'
                                        WHEN pe.exame_periodico = 1 THEN 'Exame periódico'
                                        WHEN pe.exame_demissional = 1 THEN 'Exame demissional'
                                        WHEN pe.exame_retorno = 1 THEN 'Retorno ao trabalho'
                                        WHEN pe.exame_mudanca = 1 THEN 'Mudança de cargo'
                                        WHEN pe.exame_monitoracao = 1 THEN 'Monitoração pontual'
                                        ELSE ''
                                    END
                                END ) as tipo_exame,
                            CAST(ipe.observacao AS NVARCHAR(MAX)) observacao,

                            pe.codigo as codigo_pedidos_exames,
                            ae.data,
                            ae.hora,
                            pe.codigo_cliente_funcionario,
                            RHHealth.dbo.ufn_decode_utf8_string(e.descricao) as exame_descricao,
                            RHHealth.dbo.ufn_decode_utf8_string(e.recomendacoes) as requisito,
                            pe.exame_admissional,
                            pe.exame_demissional,
                            pe.exame_monitoracao,
                            pe.exame_mudanca,
                            pe.exame_periodico,
                            pe.exame_retorno,
                            pe.codigo_status_pedidos_exames,
                            RHHealth.dbo.ufn_decode_utf8_string(spe.descricao) as status_pedido_exame,
                            ipe.codigo_status_itens_pedidos_exames,
                            sipe.descicao as status_itens_pedidos_exames,
                            m.nome nome_medico,
                            pe.codigo as codigo_pedidos_exames,
                            ipe.codigo_medico as codigo_medico_item_pedidos_exame,
                            ae.codigo_medico as codigo_medico_agendamento_exame,
                            RHHealth.dbo.ufn_decode_utf8_string(m.nome) as nome_medico
                        FROM itens_pedidos_exames ipe
                            INNER JOIN pedidos_exames pe ON ipe.codigo_pedidos_exames = pe.codigo
                            " . $query_paciente . "
                            INNER JOIN status_pedidos_exames spe ON spe.codigo = pe.codigo_status_pedidos_exames
                            INNER JOIN exames e ON ipe.codigo_exame = e.codigo
                            INNER JOIN fornecedores forn ON forn.codigo = ipe.codigo_fornecedor

                            LEFT JOIN cliente_funcionario cf ON cf.codigo = pe.codigo_cliente_funcionario
                            LEFT JOIN grupos_economicos ge ON ge.codigo_cliente = cf.codigo_cliente

                            LEFT JOIN itens_pedidos_exames_baixa ipeb ON ipeb.codigo_itens_pedidos_exames = ipe.codigo
                            LEFT JOIN agendamento_exames ae ON ae.codigo_itens_pedidos_exames = ipe.codigo
                            " . $query_data . "

                            LEFT JOIN medicos m ON m.codigo = ipe.codigo_medico
                            LEFT JOIN status_itens_pedidos_exames sipe ON sipe.codigo = ipe.codigo_status_itens_pedidos_exames
                        WHERE pe.codigo_status_pedidos_exames NOT IN (5) ";

                //die($strSql);

                //Retorna os dados da consulta ao banco
                $result = $this->connection->execute($strSql)->fetchAll('assoc');

                foreach ($result as $key => $row) {

                    $result[$key]['hora'] = $this->formatHour($row['hora']);

                }

                $data['exames'] = $result;
            }

            //CARREGA OS CONTATOS, somente 1 Telefone e e-Mail
            $this->loadModel('FuncionariosContatos');
            $funcionariosContatos = $this->FuncionariosContatos->getContatosByCodigo($dados['codigo_paciente']);
            $contatos = [];
            if (!empty($funcionariosContatos)) {

                foreach ($funcionariosContatos as $contato) {

                    //verifica se é email
                    if($contato['codigo_tipo_retorno'] == 2) { //telefone
                        $data['email'] = "{$contato['descricao']}";
                    }

                    //verifica se é telefone
                    if($contato['codigo_tipo_retorno'] == 1) { //telefone
                        $data['telefone'] = "{$contato['ddi']}{$contato['ddd']}{$contato['descricao']}";
                        $data['phone'] = "{$contato['ddi']}{$contato['ddd']}{$contato['descricao']}";
                    }

                    $contatos[$contato['descricao_tipo_retorno']] = [
                        'codigo_contato'         => $contato['codigo'],
                        'codigo_tipo_contato'    => $contato['codigo_tipo_contato'],
                        'descricao_tipo_contato' => $contato['descricao_tipo_contato'],
                        'codigo_tipo_retorno'    => $contato['codigo_tipo_retorno'],
                        'descricao_tipo_retorno' => $contato['descricao_tipo_retorno'],
                        'descricao'              => "{$contato['ddi']}{$contato['ddd']}{$contato['descricao']}",
                        'data_inclusao'          => $contato['data_inclusao'],
                        'data_alteracao'         => $contato['data_alteracao'],
                    ];
                }
                sort($contatos);
            }
            $data['contatos'] = $contatos;
            $this->set('data', $data);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function formatHour($hora)
    {
        //Se o tamanho da campo 'hora' for igual a 3, adiciona um '0'
        // a frente para poder fazer a conversão do intereiro em horas
        if (strlen($hora) == 3) {
            $hora = 0 . $hora;
        } else {
            $hora = $hora;
        }
        //Formata a string para o formato de horas
        return  substr($hora, 0, 2) . ":" . substr($hora, 2);
    }

    public function putAgendamentoStatus()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        //Declara transação
        $conn = $this->connection;

        $data = array();

        try {
            //inicia transacao
            $conn->begin();

            //recebe os dados que veio do post
            $dados = $this->request->getData();

            $this->loadModel('ItensPedidosExames');
            $itensPedidoExame = $this->ItensPedidosExames->get($dados['codigo']);

            if (!empty($itensPedidoExame)) {
                $itensPedidoExame->codigo_status_itens_pedidos_exames = $dados['codigo_status_itens_pedidos_exames'];


                if ($result = $this->ItensPedidosExames->save($itensPedidoExame)) {
                    $data = $result;
                }
                else {
                    throw new Exception("Error ao editar o status do exame!");
                }
            }

            $conn->commit(); //Confirma a inserção dos dados no banco

            $this->set(compact('data'));

        } catch (Exception $e) {
            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

}
