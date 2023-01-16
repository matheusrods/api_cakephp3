<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Exception\ApiException;
use App\Utils\ArrayUtil;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * Fornecedores Controller
 *
 * @property \App\Model\Table\FornecedoresTable $Fornecedores
 *
 * @method \App\Model\Entity\Fornecedore[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedoresController extends ApiController
{
    public $connection;
    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow(['obterImagens']);
    }

    /**
     * Obter Imagens de um fornecedor
     *
     * @param array $conditions
     * @return array|null
     */
    public function obterImagens(int $codigo_fornecedor = null, int $codigo_imagem = null)
    {

        $fields = [];
        $conditions = [];

        if (!empty($codigo_fornecedor)) {
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['codigo_fornecedor' => $codigo_fornecedor]);
        }

        if (!empty($codigo_imagem)) {
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['codigo' => $codigo_imagem]);
        }

        $this->loadModel('FornecedorFotos');

        try {

            // buscar dados usando a Model
            $data = $this->FornecedorFotos->obterImagens($codigo_fornecedor, $fields, $conditions);

            // estratégia para apresentar um erro interno
            if ($this->responseError($data)) {
                return; // finaliza
            }
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }

        $this->responseOK($data); // estratégia para apresentação de dados
    }

    /**
     * @param int $codigo_fornecedor
     */
    public function dadosDaEmpresaGet(int $codigo_fornecedor)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel('Fornecedores');
        $data = $this->Fornecedores->getDadosdaEmpresa($codigo_fornecedor);

        $this->responseOK($data);
    }

    /**
     *
     */
    public function dadosDaEmpresaPut()
    {
        $this->request->allowMethod(['put']);

        try {

            $this->loadModel('Fornecedores');
            $this->loadModel('FornecedoresEndereco');

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //CODIGO FORNECEDOR
            if (empty($dados['Fornecedor']['codigo'])) {
                $error[] = "Código Fornecedor é um campo obrigatório";
            }

            //CODIGO FORNECEDOR ENDEREÇO
            if (isset($dados['FornecedorEndereco']) && empty($dados['FornecedorEndereco']['codigo'])) {
                $error[] = "Está sendo enviado dados de endereço, isso torna o Código Fornecedor Endereço um campo obrigatório";
            }

            //CODIGO DOCUMENTO
            if (!empty($dados['Fornecedor']['codigo']) && !$this->validarCnpj($dados['Fornecedor']['codigo_documento'])) {
                $error[] = "CNPJ informado não é válido";
            }

            //        //RAZAO SOCIAL
            //        if (empty($campos['razao_social'])) {
            //            $error[] = "Razão Social é um campo obigatório";
            //        }
            //        if (!empty($campos['razao_social']) && strlen($campos['razao_social']) <= 4) {
            //            $error[] = "Razão Social deve conter mais de 4 caracteres";
            //        }
            //        //NOME
            //        if (empty($campos['nome'])) {
            //            $error[] = "Nome é um campo obigatório";
            //        }
            //        if (!empty($campos['nome']) && strlen($campos['nome']) <= 4) {
            //            $error[] = "Nome deve conter mais de 4 caracteres";
            //        }
            //
            //        //TIPO UNIDADE
            //        if (empty($campos['tipo_unidade']) || !in_array($campos['tipo_unidade'], ['F', 'O'])) {
            //            $error[] = "Tipo de unidade deve ser F ou O";
            //        }
            //
            //        //STATUS
            //        if (empty($campos['ativo'])) {
            //            $error[] = "Status é obrigatório";
            //        }
            //
            //        //ESTADO DESCRICAO
            //        if (empty($campos['estado_descricao'])) {
            //            $error[] = "Estado é obrigatório";
            //        }
            //
            //        //CIDADE
            //        if (empty($campos['cidade'])) {
            //            $error[] = "Cidade é obrigatório";
            //        }
            //
            //        //BAIRRO
            //        if (empty($campos['bairro'])) {
            //            $error[] = "Bairro é obrigatório";
            //        }
            //        if (count($error) > 0) {
            //            $this->set(compact('error'));
            //            return;
            //        }

            if (isset($error) && count($error) > 0) {
                $this->set(compact('error'));
                return;
            }

            $getFornecedor = $this->Fornecedores->get($dados['Fornecedor']['codigo']);
            $getFornecedorEndereco = $this->FornecedoresEndereco->get($dados['FornecedorEndereco']['codigo']);

            $fornecedor = $this->Fornecedores->patchEntity($getFornecedor, $dados['Fornecedor']);
            $fornecedorEndereco = $this->FornecedoresEndereco->patchEntity($getFornecedorEndereco, $dados['FornecedorEndereco']);

            if ($this->Fornecedores->save($fornecedor) && $this->FornecedoresEndereco->save($fornecedorEndereco)) {

                $data = array(
                    "success" => true,
                    "message" => "Dados da empresa salvos!"
                );
            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar os dados da empresa!",
                    "erro_fornecedor" => $fornecedor->errors(),
                    "erro_fornecedorEndereco" => $fornecedorEndereco->errors()
                );
            }

            $this->set(compact('data'));

        } catch (Exception $e) {

            $error[] = $e->getMessage();

            $this->set(compact('error'));
        }
    }

    /**
     * @param int $codigo_fornecedor
     */
    public function dadosGeraisGet(int $codigo_fornecedor)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel('Fornecedores');
        $data = $this->Fornecedores->getDadosGerais($codigo_fornecedor);

        $this->responseOK($data);
    }

    /**
     *
     */
    public function dadosGeraisPut()
    {
        $this->request->allowMethod(['put']);
        $this->loadModel('Fornecedores');

        try {
            $dados = [
                'codigo'                => $this->request->getData('codigo_fornecedor'),
                'acesso_portal'         => $this->request->getData('acesso_portal'),
                'prestador_qualificado' => $this->request->getData('prestador_qualificado'),
                'data_contratacao'      => $this->request->getData('data_contratacao')
            ];

            //CODIGO
            if (empty($dados['codigo'])) {
                $error[] = "Código Fornecedor é um campo obrigatório";
            }

            if (isset($error) && count($error) > 0) {
                $this->set(compact('error'));
                return;
            }

            $getFornecedor = $this->Fornecedores->get($dados['codigo']);
            $fornecedor = $this->Fornecedores->patchEntity($getFornecedor, $dados);
            //print_r($fornecedor);

            if ($this->Fornecedores->save($fornecedor)) {

                $data = array(
                    "success" => true,
                    "message" => "Dados gerais salvos!"
                );
            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar os dados gerais!",
                    "erro" => $fornecedor->errors()
                );
            }

            $this->set(compact('data'));

        } catch (Exception $e) {

            $error[] = $e->getMessage();

            $this->set(compact('error'));
        }
    }

    /**
     * @param int $codigo_fornecedor
     */
    public function responsavelAdministrativoGet(int $codigo_fornecedor)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel('Fornecedores');
        $data = $this->Fornecedores->getResponsavelAdministrativo($codigo_fornecedor);

        $this->responseOK($data);
    }

    /**
     *
     */
    public function responsavelAdministrativoPut()
    {
        $this->request->allowMethod(['put']);
        $this->loadModel('Fornecedores');
        //todo falta o campo responável pela baixa de exames, validar com will

        try {
            $dados = [
                'codigo'                     => $this->request->getData('codigo_fornecedor'),
                'responsavel_administrativo' => $this->request->getData('responsavel_administrativo'),
                'cnes'                       => $this->request->getData('cnes'),
                'interno'                    => $this->request->getData('interno'),
            ];

            //CODIGO
            if (empty($dados['codigo'])) {
                $error[] = "Código Fornecedor é um campo obrigatório";
            }

            if (isset($error) && count($error) > 0) {
                $this->set(compact('error'));
                return;
            }

            //SAVE
            $getFornecedor = $this->Fornecedores->get($dados['codigo']);
            $fornecedor = $this->Fornecedores->patchEntity($getFornecedor, $dados);
            if ($this->Fornecedores->save($fornecedor)) {

                $data = array(
                    "success" => true,
                    "message" => "Responsável administrativo salvos!"
                );
            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar os Administrativo salvo!"
                );
            }
            $this->set(compact('data'));
        } catch (Exception $e) {

            $error[] = $e->getMessage();

            $this->set(compact('error'));
        }
    }


    /**
     * [getFornecedorUnidades metodo para pegar as unidades do fornecedor]
     * @param  int    $codigo_fornecedor [description]
     * @return [type]                    [description]
     */
    public function getFornecedorUnidades(int $codigo_fornecedor)
    {

        //pega os dados de usuario
        $unidades = $this->Fornecedores->getUnidades($codigo_fornecedor);

        //verifica
        if (!empty($unidades)) {
            //monta o get dos fornecedores unidades
            $data = $unidades;
            $this->set(compact('data'));
        } else {
            //componente para log da api
            $error[] = "Não existe dados!";
            $this->set(compact('error'));
        }
    } //fim getUnidades

    /**
     * [getFornecedorMedico metodo para pegar os medicos que estão no corpo clinico do fornecedor]
     * @return [type] [description]
     */
    public function getFornecedorMedicoCalendario(int $codigo_fornecedor)
    {

        //pega os dados do calendario medico que tem configuracao a partir do fornecedor
        $this->loadModel("MedicoCalendario");
        $data = $this->MedicoCalendario->getMedicosCalendario($codigo_fornecedor);

        $this->set(compact('data'));
    } //fim getFornecedorMedico

    /**
     * [dias_semana retorna os dias da semana para colocar na tela]
     * @return [type] [description]
     */
    public function getDiasSemana()
    {

        $data = array(
            1 => "Segunda",
            2 => "Terça",
            3 => "Quarta",
            4 => "Quinta",
            5 => "Sexta",
            6 => "Sábado",
            7 => "Domingo",
        );

        $this->set(compact('data'));
    } //fim dias_semana

    /**
     * [setFornecedoresMedicosCalendario metodo com os verbos post/put]
     */
    public function setFornecedoresMedicosCalendario()
    {

        //abrir transacao
        $conn = ConnectionManager::get('default');

        try {

            //abre a transacao
            $conn->begin();

            //pega os dados do token
            $dados_token = $this->getDadosToken();

            //veifica se encontrou os dados do token
            if (empty($dados_token)) {
                $error = 'Não foi possivel encontrar os dados no Token!';
                $this->set(compact('error'));
                return;
            }

            //seta o codigo usuario
            $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

            if (empty($codigo_usuario)) {
                throw new Exception("Logar novamente o usuario");
            }

            //verifica qual metodo esta passando a chamada
            if ($this->request->is(['patch', 'post', 'put'])) {

                //pega os dados que veio do post
                $dados = $this->request->getData();

                //verifica se tem um codigo do cliente caso tenha edita os dados
                if (!isset($dados['codigo_fornecedor'])) {
                    throw new Exception("codigo_fornecedor requerido!");
                } //fim codigo

                if (!isset($dados['codigo_medico'])) {
                    throw new Exception("codigo_medico requerido!");
                } //fim codigo

                if (!isset($dados['calendario'][0]['dia_semana'])) {
                    throw new Exception("dia_semana requerido!");
                } //fim codigo

                //separa o array
                $codigo_fornecedor = $dados['codigo_fornecedor'];
                $codigo_medico = $dados['codigo_medico'];
                $codigo_especialidade = (isset($dados['codigo_especialidade']) && !empty($dados['codigo_especialidade'])) ? $dados['codigo_especialidade'] : null;

                //dados do calendario de configuracao
                $calendario = $dados['calendario'];

                //seta os dados para gravar na base os dados do medico que esta configurando
                $dados_medico_fornecedor = array(
                    "codigo_medico" => $codigo_medico,
                    "codigo_fornecedor" => $codigo_fornecedor,
                    "codigo_especialidade" => $codigo_especialidade,
                    'ativo' => 1,
                );

                //busca os dados para ver se existe na tabela
                $this->loadModel("MedicoCalendario");
                $medicos_calendario = $this->MedicoCalendario->find()->where(['codigo_medico' => $codigo_medico, 'codigo_fornecedor' => $codigo_fornecedor])->first();

                if (empty($medicos_calendario)) {

                    $dados_medico_fornecedor['codigo_usuario_inclusao'] = $codigo_usuario;
                    $dados_medico_fornecedor['data_inclusao'] = date('Y-m-d H:i:s');

                    //para criar um novo usuario
                    $medicos_calendario = $this->MedicoCalendario->newEntity($dados_medico_fornecedor);
                } else {
                    $dados_medico_fornecedor['codigo_usuario_alteracao'] = $codigo_usuario;
                    $dados_medico_fornecedor['data_alteracao'] = date('Y-m-d H:i:s');

                    $medicos_calendario = $this->MedicoCalendario->patchEntity($medicos_calendario, $dados_medico_fornecedor);
                }
                // debug($usuario->toArray());exit;
                //seta os dados para atualizacao
                if ($this->MedicoCalendario->save($medicos_calendario)) {

                    //pega o codigo do usuario
                    $novo_codigo = isset($medicos_calendario->codigo) ? $medicos_calendario->codigo : $medicos_calendario->id;

                    //insere na tabela de multi_fornecedores
                    $this->loadModel('MedicoCalendarioHorarios');
                    //deleta todos os codigos para inserir os novos
                    $del_calendarios = $this->MedicoCalendarioHorarios->deleteAll(['codigo_medico_calendario' => $novo_codigo]);

                    //varre os dados de config do calendario
                    foreach ($calendario as $cal) {

                        if (empty($cal['dia_semana'])) {
                            continue;
                        }

                        $dadosCalendario = array(
                            'codigo_medico_calendario' => $novo_codigo,
                            'dia_semana' => $cal['dia_semana'],
                            'hora_inicio_manha' => $cal['hora_inicio_manha'],
                            'hora_fim_manha' => $cal['hora_fim_manha'],
                            'hora_inicio_tarde' => $cal['hora_inicio_tarde'],
                            'hora_fim_tarde' => $cal['hora_fim_tarde'],
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                            'ativo' => 1
                        );

                        //instancia para um novo registro
                        $calendarios_medicos = $this->MedicoCalendarioHorarios->newEntity($dadosCalendario);

                        if (!$this->MedicoCalendarioHorarios->save($calendarios_medicos)) {
                            $error[]  = $calendarios_medicos->errors();

                            $message[] = $calendarios_medicos->errors();
                            throw new Exception("Erro ao relacionar a config de horario do medico: " . print_r($calendarios_medicos, 1));
                        }
                    } //fim foreach

                    $data = $medicos_calendario;
                } else {
                    $error[]  = $medicos_calendario->errors();

                    $message[] = $medicos_calendario->errors();
                    throw new Exception("Erro ao criar configuracao do calendario para o medico: " . print_r($message, 1));
                }
            } //fim metodo put

            $conn->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {

            //rollback da transacao
            $conn->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    } //fim setFornecedoresMedicosCalendario


    public function combos()
    {
        $dados = [
            'tipoFilial' => [
                'F' => 'Filial',
                'O' => 'Operacional'
            ],
            'status' => [
                1 => 'Ativo',
                0 => 'Inativo'
            ],
            'estados' => [
                'AC' => 'AC',
                'AL' => 'AL',
                'AM' => 'AM',
                'AP' => 'AP',
                'BA' => 'BA',
                'CE' => 'CE',
                'DF' => 'DF',
                'ES' => 'ES',
                'GO' => 'GO',
                'MA' => 'MA',
                'MG' => 'MG',
                'MS' => 'MS',
                'MT' => 'MT',
                'PA' => 'PA',
                'PB' => 'PB',
                'PE' => 'PE',
                'PI' => 'PI',
                'PR' => 'PR',
                'RJ' => 'RJ',
                'RN' => 'RN',
                'RO' => 'RO',
                'RR' => 'RR',
                'RS' => 'RS',
                'SC' => 'SC',
                'SE' => 'SE',
                'SP' => 'SP',
                'TO' => 'TO',
            ]


        ];
        $this->set(compact('dados'));
    }

    /**
     * @param String $cnpj
     * @return bool
     */
    private function validarCnpj(String $cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        if (strlen($cnpj) != 14)
            return false;
        if (preg_match('/(\d)\1{13}/', $cnpj))
            return false;
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    public function getUnidades($codigo_fornecedor)
    {

        $this->loadModel("FornecedoresUnidades");

        $getUnidades = $this->FornecedoresUnidades->getUnidadesByFornecedor($codigo_fornecedor);

        $this->set('data', $getUnidades);
    }

    public function getUnidade($codigo_fornecedor, $codigo_unidade)
    {

        $this->loadModel("FornecedoresUnidades");

        $getUnidade = $this->FornecedoresUnidades->getUnidadeByFornecedor($codigo_fornecedor, $codigo_unidade);

        $this->set('data', $getUnidade);
    }

    public function inserirEditarUnidades()
    {
        //declara transacao
        $conn = $this->connection;

        try {

            //abre a transacao
            $conn->begin();

            $data = array();

            //pega os dados do token
            $dados_token = $this->getDadosToken();

            //veifica se encontrou os dados do token
            if (empty($dados_token)) {
                $error = 'Não foi possivel encontrar os dados no Token!';
                $this->set(compact('error'));
                return;
            }

            //seta o codigo usuario
            $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

            if (empty($codigo_usuario)) {
                throw new Exception("Logar novamente o usuario");
            }

            //verifica qual metodo esta passando a chamada
            if ($this->request->is(['post'])) {

                //pega os dados que veio do post
                $dados = $this->request->getData();

                $dados['Fornecedor']['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['FornecedorEndereco']['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['FornecedorEndereco']['data_inclusao'] = date('Y-m-d H:i:s');
                $data = $this->incluir($dados);
            }

            if ($this->request->is(['put'])) {

                //pega os dados que veio do put
                $dados = $this->request->getData();

                $dados['Fornecedor']['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['FornecedorEndereco']['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['FornecedorEndereco']['data_alteracao'] = date('Y-m-d H:i:s');
                $data = $this->editar($dados);
            }

            $this->set(compact('data'));
        } catch (Exception $e) {

            //rollback da transacao
            $conn->rollback();
            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function incluir($dados)
    {
        $this->loadModel("FornecedoresUnidades");
        $this->loadModel("Fornecedores");

        //abrir transacao
        $conn = $this->connection;

        try {

            if (isset($dados['Fornecedor']['tipo_unidade']) && !empty($dados['Fornecedor']['tipo_unidade'])) {

                if ($dados['Fornecedor']['tipo_unidade'] == 'O') {

                    if (empty($dados['Fornecedor']['codigo_fornecedor_fiscal'])) {
                        return "Informe o Fornecedor Fiscal";
                    } else {
                        $dados['Fornecedor']['codigo_documento'] = $this->geraCnpjFicticio($dados['Fornecedor']['codigo_fornecedor_fiscal']);
                    }
                } else {
                    //verifica se existe cnpj já cadastrado
                    $fornecedores = $this->Fornecedores->find()->where(['codigo_documento' => $dados['Fornecedor']['codigo_documento']])->first();

                    //verifica se nao é vazio
                    if (!empty($fornecedores)) {
                        return "Já existe este cnpj cadastrado";
                    }
                }
            }
            //Seta a variavel $fornecedores_dados com os dados do fornecedor vindos do request
            $fornecedores_dados = $dados['Fornecedor'];

            $fornecedorEntity = $this->Fornecedores->newEntity($fornecedores_dados);

            //Salva novo fornecedor
            if ($novo_fornecedor = $this->Fornecedores->save($fornecedorEntity)) {
                $codigo_fornecedor = $novo_fornecedor['codigo'];
            } else {
                return $fornecedorEntity->errors();
            }

            //Add codigo_fornecedor
            $dados['Fornecedor']['codigo'] = $codigo_fornecedor;

            $resultEndereco = $this->atualizarEnderecoComercial($dados);

            if (isset($resultEndereco['error'])) {
                return $resultEndereco['FornecedorEndereco'];
            }

            if (isset($dados['Fornecedor']['tipo_unidade']) && !empty($dados['Fornecedor']['tipo_unidade'])) {
                if ($dados['Fornecedor']['tipo_unidade'] == 'O') {
                    $consulta_unidade = $this->FornecedoresUnidades->find()->where(['codigo_fornecedor_unidade' => $codigo_fornecedor])->first();

                    if (empty($consulta_unidade)) {

                        $data['FornecedorUnidade']['codigo_fornecedor_matriz'] = $dados['Fornecedor']['codigo_fornecedor_fiscal'];
                        $data['FornecedorUnidade']['codigo_fornecedor_unidade'] = $codigo_fornecedor;
                        $data['FornecedorUnidade']['codigo_usuario_inclusao'] = $dados['Fornecedor']['codigo_usuario_inclusao'];
                        $data['FornecedorUnidade']['data_inclusao'] = date('Y-m-d H:i:s');
                        $data['FornecedorUnidade']['ativo'] = 1;

                        $unidadesEntity = $this->FornecedoresUnidades->newEntity($data['FornecedorUnidade']);


                        if (!$this->FornecedoresUnidades->save($unidadesEntity)) {
                            return "Não foi possível cria uma unidade!";
                        }

                    } else {
                        if ($consulta_unidade['FornecedorUnidade']['codigo_fornecedor_matriz'] != $dados['Fornecedor']['codigo_fornecedor_fiscal']) {
                            $data['FornecedorUnidade']['codigo'] = $consulta_unidade['FornecedorUnidade']['codigo'];
                            $data['FornecedorUnidade']['codigo_fornecedor_matriz'] = $dados['Fornecedor']['codigo_fornecedor_fiscal'];
                            if (!($this->FornecedorUnidade->atualizar($data))) {
                                throw new Exception('Não é possivel Incluir uma Filial');
                            }
                        }
                    }
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {

            $conn->rollback();
            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function editar($dados)
    {
        $this->loadModel("FornecedoresUnidades");
        $this->loadModel("Fornecedores");

        //abrir transacao
        $conn = $this->connection;

        try {

            if (isset($dados['Fornecedor']['codigo']) && !empty($dados['Fornecedor']['codigo'])) {

                //verifica se existe cnpj já cadastrado
                $fornecedor = $this->Fornecedores->find()->where(['codigo' => $dados['Fornecedor']['codigo']])->first();

                //verifica se nao é vazio
                if (empty($fornecedor)) {
                    return "Fornecedor não encontrado";
                }
            } else {
                return "Insira um codigo de fornecedor";
            }

            //Seta a variavel $fornecedores_dados com os dados do fornecedor vindos do request
            $fornecedores_dados = $dados['Fornecedor'];

            $fornecedorEntity = $this->Fornecedores->patchEntity($fornecedor, $fornecedores_dados);

            //Salva novo fornecedor
            if ($novo_fornecedor = $this->Fornecedores->save($fornecedorEntity)) {
                $codigo_fornecedor = $novo_fornecedor['codigo'];
            } else {
                return $fornecedorEntity->errors();
            }

            //Add codigo_fornecedor
            $dados['Fornecedor']['codigo'] = $codigo_fornecedor;

            $resultEndereco = $this->atualizarEnderecoComercial($dados);

            if (isset($resultEndereco['error'])) {
                return $resultEndereco['FornecedorEndereco'];
            }

            if (isset($dados['Fornecedor']['tipo_unidade']) && !empty($dados['Fornecedor']['tipo_unidade'])) {
                if ($dados['Fornecedor']['tipo_unidade'] == 'O') {
                    $consulta_unidade = $this->FornecedoresUnidades->find()->where(['codigo_fornecedor_unidade' => $codigo_fornecedor])->first();

                    if (empty($consulta_unidade)) {

                        $data['FornecedorUnidade']['codigo_fornecedor_matriz'] = $dados['Fornecedor']['codigo_fornecedor_fiscal'];
                        $data['FornecedorUnidade']['codigo_fornecedor_unidade'] = $codigo_fornecedor;
                        $data['FornecedorUnidade']['codigo_usuario_inclusao'] = $dados['Fornecedor']['codigo_usuario_inclusao'];
                        $data['FornecedorUnidade']['data_inclusao'] = date('Y-m-d H:i:s');
                        $data['FornecedorUnidade']['ativo'] = 1;

                        $unidadesEntity = $this->FornecedoresUnidades->newEntity($data['FornecedorUnidade']);

                        if (!$this->FornecedoresUnidades->save($unidadesEntity)) {
                            return "Não foi possível cria uma unidade!";
                        }

                    } else {

                        if (isset($dados['Fornecedor']['codigo_fornecedor_fiscal']) && !empty($dados['Fornecedor']['codigo_fornecedor_fiscal'])){
                            if ($consulta_unidade['codigo_fornecedor_matriz'] != $dados['Fornecedor']['codigo_fornecedor_fiscal']) {

                                $data['FornecedorUnidade']['codigo_fornecedor_matriz'] = $dados['Fornecedor']['codigo_fornecedor_fiscal'];

                                $unidadesEntity = $this->FornecedoresUnidades->PatchEntity($data['FornecedorUnidade']);

                                if (!$this->FornecedorUnidade->save($unidadesEntity)) {
                                    return 'Não é possivel editar a unidade filial';
                                }
                            }
                        } else {
                            return 'Insira o codigo_fornecedor_fiscal';
                        }
                    }
                }
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {

            $conn->rollback();
            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function atualizarEnderecoComercial($dados)
    {
        //Declara variavel $validade para armazenar errors
        $validate = array();

        if (empty($dados['FornecedorEndereco']['cep'])) {
            $validate['error'] = false;
            $validate['FornecedorEndereco'][] = 'Informe o CEP';
        }

        if (empty($dados['FornecedorEndereco']['estado_descricao'])) {
            $validate['error'] = false;
            $validate['FornecedorEndereco'][] = 'Informe o UF';
        }

        if (empty($dados['FornecedorEndereco']['cidade'])) {
            $validate['error'] = false;
            $validate['FornecedorEndereco'][] = 'Informe o Cidade';
        }

        if (empty($dados['FornecedorEndereco']['bairro'])) {
            $validate['error'] = false;
            $validate['FornecedorEndereco'][] = 'Informe o Bairro';
        }

        if (empty($dados['FornecedorEndereco']['logradouro'])) {
            $validate['error'] = false;
            $validate['FornecedorEndereco'][] = 'Informe o Logradouro';
        }

        if (!empty($validate)) {
            return $validate;
        }

        $this->loadModel("FornecedoresEndereco");

        $dados_endereco = array('FornecedorEndereco' => $dados['FornecedorEndereco']);
        $dados_endereco['FornecedorEndereco']['codigo_fornecedor'] = $dados['Fornecedor']['codigo'];
        $dados_endereco['FornecedorEndereco']['codigo_tipo_contato'] = 2;
        $dados_endereco['FornecedorEndereco']['codigo_endereco'] = null;

        if (!isset($dados_endereco['FornecedorEndereco']['codigo']) || empty($dados_endereco['FornecedorEndereco']['codigo'])) {

            $fornecedorEnderecoEntity = $this->FornecedoresEndereco->newEntity($dados_endereco['FornecedorEndereco']);

            if (!$this->FornecedoresEndereco->save($fornecedorEnderecoEntity)) {
                return $fornecedorEnderecoEntity->errors();
            }

            return true;
        } else {
            $fornecedores_endereco_codigo = $dados_endereco['FornecedorEndereco']['codigo'];
            $dados_antigos = $this->FornecedoresEndereco->find()->where(['codigo' => $fornecedores_endereco_codigo])->first();

            $fornecedorEnderecoEntity = $this->FornecedoresEndereco->patchEntity($dados_antigos, $dados_endereco['FornecedorEndereco']);

            if (!$this->FornecedoresEndereco->save($fornecedorEnderecoEntity)) {
                return $fornecedorEnderecoEntity->errors();
            }

            return true;
        }
    }

    public function geraCnpjFicticio($codigo_fornecedor_matriz)
    {

        $consulta = $this->Fornecedores->find()->where(['codigo' => $codigo_fornecedor_matriz])->first();

        if (empty($consulta)) {
            return "Fornecedor Fiscal não encontrado.";
            return false;
        } else {
            $cnpj = $consulta['codigo_documento'];

            $parte1 = substr($cnpj, 0, 8);

            $qtd_cnpj = $this->Fornecedores->queryFakeCnpj($parte1);

            $parte2 = $qtd_cnpj['codigo_documento'] + 1;

            if (strlen($parte2) < 4) {
                $parte2 = str_pad($parte2, 3, 0, STR_PAD_LEFT);
            }

            if (substr($parte2, 0, 1) <> "9") {
                $parte2 = "9" . $parte2;
            }

            $digito_verificador = '99';

            $cnpj_ficticio = $parte1 . $parte2 . $digito_verificador;

            return $cnpj_ficticio;
        }
    }
}
