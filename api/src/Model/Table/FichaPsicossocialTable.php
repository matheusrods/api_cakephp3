<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use App\Utils\Comum;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * FichaPsicossocial Model
 *
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsToMany $Respostas
 *
 * @method \App\Model\Entity\FichaPsicossocial get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichaPsicossocial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocial|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocial saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocial findOrCreate($search, callable $callback = null, $options = [])
 */
class FichaPsicossocialTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('ficha_psicossocial');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Respostas', [
            'foreignKey' => 'ficha_psicossocial_id',
            'targetForeignKey' => 'resposta_id',
            'joinTable' => 'ficha_psicossocial_respostas'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_pedido_exame')
            ->requirePresence('codigo_pedido_exame', 'create')
            ->notEmptyString('codigo_pedido_exame');

        $validator
            ->scalar('total_sim')
            ->maxLength('total_sim', 2)
            ->allowEmptyString('total_sim');

        $validator
            ->scalar('total_nao')
            ->maxLength('total_nao', 2)
            ->allowEmptyString('total_nao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_medico')
            ->allowEmptyString('codigo_medico');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * [getCamposPsicossocial description]
     * @param  [type] $codigo_usuario      [description]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function getCamposPsicossocial($codigo_pedido_exame)
    {
        //instancia as outras tabeles
        $this->PedidosExames = TableRegistry::get('PedidosExames');
        $this->FichaPsicossocialPerguntas = TableRegistry::get('FichaPsicossocialPerguntas');

        //seta a variavel auxiliar
        $dados = $this->PedidosExames->obtemDadosComplementares($codigo_pedido_exame);

        $questoes['grupo_header'] = array(
            array(
                'descricao' => "DADOS PRINCIPAIS",
                'questao' => array(
                    array(
                        "name" => "FichaPsicossocial.codigo_medico",
                        "tipo" => "SELECT",
                        "tamanho" => "12",
                        "label" => "Médico:",
                        "obrigatorio"=> 1,
                        "conteudo" => $dados['Medico'],
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => $dados['Medico'],
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                )
            ),
        );

        $questoes['grupo'] = array(
            "1" => array(
                "codigo" => 1,
                'descricao' => "Informativos",
                'questao' => array(
                    array(
                        "name" => "info01",
                        "tipo" => "INFO",
                        "tamanho" => "12",
                        "label" => "TESTE SRQ 20 - Self Report Questionare",
                        "obrigatorio"=> 1,
                        "conteudo" => "Teste que avalia a saúde mental. Por favor, leia estas instruções antes de preencher as questões abaixo.
        É muito importante que todos que estao preenchendo o questionário sigam as mesmas instruções.",
                        "default" => "",
                        "lyn" => null,

                        "codigo" => 501,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array()
                    ),
                    array(
                        "name" => "info02",
                        "tipo" => "INFO",
                        "tamanho" => "12",
                        "label" => "Instruções",
                        "obrigatorio"=> 1,
                        "conteudo" => "Estas questões são relacionadas a certas dores e problemas que podem ter lhe incomodado nos ultimos 30 dias.
    Se você acha que a questão se aplica a você e você teve o problema descrito nos ultimos 30 dias responda SIM. Por outro lado, se a questão não se aplica a você e você não teve o problema nos ultimos 30 dias, responda NÃO.
    OBS. Lembre-se que o diagnostico definitivo só pode ser fornecido por um profissional.",
                        "default" => "",
                        "lyn" => null,

                        "codigo" => 502,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array()
                    )
                )
            )
        );

        //campos
        $fields = array(
            'FichaPsicossocialPerguntas.codigo',
            'FichaPsicossocialPerguntas.ordem',
            'FichaPsicossocialPerguntas.pergunta',
            'resposta'=>"'{0:Não,1:Sim}'"
        );
        $perguntas = $this->FichaPsicossocialPerguntas->find()
            ->select($fields)
            ->hydrate(false)
            ->toArray();
        // debug($perguntas);exit;
        if(!empty($perguntas)) {

            //varre as perguntas da psicossocial
            $dados_perguntas = array();
            foreach($perguntas AS $key => $perg) {

                $codigo_psicossocial = $perg['codigo'];

                //seta os dados das perguntas no padrao
                $dados_perguntas[] = array(
                    "codigo" => $codigo_psicossocial,
                    "name" => "FichaPsicossocialPergunta.".$codigo_psicossocial,
                    "tipo" => "RADIO",
                    "tamanho" => "12",
                    "label" => $perg['pergunta'],
                    "obrigatorio"=> 1,
                    "conteudo" => array('1' => 'Sim','0' => 'Não'),
                    "default" => null,
                    "lyn" => null,
                    "farmaco_campo_exibir" => 0,
                    "campo_livre" => null,
                    "menstruacao" => null,
                    "risco_campo_exibir" => 0,
                    "multiplos_riscos" => false,
                    "risco_formulario" => null,
                    "options" => null,
                    "multiplos_farmacos" => false,
                    "farmaco" => null,
                    "cids_campo_exibir" => 0,
                    "multiplos_cids" => false,
                    "cids" => null,
                    'sub_questao_campo_exibir' => null,
                    "sub_questao" => array()
                );

            }//fim foreach

            $questoes['grupo'][] = array(
                    'codigo' => 2,
                    'descricao' => "Perguntas",
                    'questao' => $dados_perguntas
            );
        }

        //valida se existe o pedido de exame selecionado, senao retorna a index e exibe erro
        // $ficha_psicossocial = $this->FichaPsicossocial->find('first', array('conditions' => array('codigo_pedido_exame' => $codigoPedidoExame)));

        $dados_principais = array(
            'codigo_pedido_exame' => $dados['codigo'],
            'empresa' => $dados['Empresa']['razao_social'],
            'unidade' => $dados['Unidade']['razao_social'],
            'setor' => $dados['setor'],
            'funcionario' => $dados['Funcionario']['nome'],
            'cpf' => $dados['Funcionario']['cpf'],
            'idade' => $dados['idade'],
            'data_admissao' => $dados['ClienteFuncionario']['admissao'],
            'sexo' => $dados['sexo'],
            'cargo' => $dados['cargo'],
            'tipo_pedido_exame' => $dados['tipo_pedido_exame'],
        );

        $dados_array = [
            'dados_principais' => $dados_principais,
            'historico' => false,
            'formulario' => $questoes,
        ];


        return $dados_array;

    }//fim getCamposPsicossocial

    public function salvarRespostas($payload)
    {


        $FichaPsicossocialRespostas = TableRegistry::getTableLocator()->get('FichaPsicossocialRespostas');

        ##################### SALVAR FICHA PSICOSSOCIAL ################
        $fichaPsicossocial = $this->getQuestionarioAtivo($payload['codigo_pedido_exame']);

        $data_agora = date('Y-m-d H:i:s');

        if (!$fichaPsicossocial){    
            $params = [ 
                'codigo_pedido_exame'       => $payload['codigo_pedido_exame'],
                'codigo_medico'             => $payload['codigo_medico'],
                'codigo_empresa'            => $payload['codigo_empresa'],
                'ativo'                     => $payload['ativo'],
                'data_inclusao'             => $data_agora,
                'codigo_usuario_inclusao'   => $payload['codigo_usuario'],
            ];
    
            $newEntity = $this->newEntity($params);
    
            $fichaPsicossocial = $this->save($newEntity);
            if (!$fichaPsicossocial) {
                $error = $newEntity->getValidationErrors();
                return ['error'=> $error];
            }
        } else {
            $FichaPsicossocialRespostas->deleteAll(['codigo_ficha_psicossocial' => $fichaPsicossocial['codigo']]);
        }

        ##################### SALVAR RESPOSTAS FICHA PSICOSSOCIAL ################
        $total_sim = 0;
        $total_nao = 0;
        foreach ($payload['respostas'] as $key => $resposta) {
            if($resposta['codigo_resposta']){
                $total_sim++;
            } else {
                $total_nao++;
            }

            $paramRespostas = [ 
                'codigo_ficha_psicossocial'             => $fichaPsicossocial['codigo'],
                'codigo_ficha_psicossocial_perguntas'   => $resposta['codigo_pergunta'],
                'resposta'                              => $resposta['codigo_resposta'],
                'data_inclusao'                         => $data_agora,
                'ativo'                                 => 1,
            ];

            $respostasEntity = $FichaPsicossocialRespostas->newEntity($paramRespostas);

            $fichaPsicossocialResposta = $FichaPsicossocialRespostas->save($respostasEntity);
            if (!$fichaPsicossocialResposta) {
                $error = $respostasEntity->getValidationErrors();
                return ['error'=> $error];
            }
        }

        ##################### ATUALIZAR TOTAL FICHA PSICOSSOCIAL ################
        $params = [ 
            'total_sim'                 => $total_sim,
            'total_nao'                 => $total_nao,
            'codigo_usuario_alteracao'  => $payload['codigo_usuario'],
            'data_alteracao'            => $data_agora,
        ];   
        
        $patchEntity = $this->patchEntity($fichaPsicossocial, $params);
        
        $fichaPsicossocial = $this->save($patchEntity);

        if (!$fichaPsicossocial) {
            $error = $patchEntity->getValidationErrors();
            return ['error'=> $error];
        }

        $dadosEmail = [
            'codigo_pedido_exame' => $fichaPsicossocial['codigo_pedido_exame'],
            'funcionario_nome' => $payload['funcionario_nome'],
            'fornecedor_nome' => $payload['fornecedor_nome'],
            'fornecedor_email' => $payload['fornecedor_email'],
        ];

        ##################### SALVAR VALIDADE DO PDF 3 MESES ################
        $FichaPsicossocialValidadeAnexo = TableRegistry::getTableLocator()->get('FichaPsicossocialValidadeAnexo');

        $param = [ 
            'codigo_ficha_psicossocial' => $fichaPsicossocial['codigo'],
            'data_validade' => date("Y-m-d", strtotime("+3 month")) . ' 23:59:59',
            'codigo_usuario_inclusao' => $fichaPsicossocial['codigo_usuario_inclusao'],
            'data_inclusao' => date("Y-m-d H:i:s"),
        ];

        $entity = $FichaPsicossocialValidadeAnexo->newEntity($param);

        $retorno = $FichaPsicossocialValidadeAnexo->save($entity);
        if (!$retorno) {
            $error = $entity->getValidationErrors();
            return ['error'=> $error];
        }

        ##################### ENVIAR E-MAIL ################
        $retornoEmail = $this->enviarEmail($dadosEmail);
        if(!$retornoEmail){
            return ['error'=> 'Erro ao enviar o E-mail'];
        }

        ##################### ENVIAR E-MAIL PARA FORN ASO ################
        
        if(!empty($payload['fornecedor_aso_email'])) {
            $dadosEmailAso = [
                'codigo_pedido_exame' => $fichaPsicossocial['codigo_pedido_exame'],
                'funcionario_nome' => $payload['funcionario_nome'],
                'fornecedor_nome' => $payload['fornecedor_aso_nome'],
                'fornecedor_email' => $payload['fornecedor_aso_email'],
            ];
            $retornoEmail = $this->enviarEmail($dadosEmailAso);
            if(!$retornoEmail){
                return ['error'=> 'Erro ao enviar o E-mail'];
            }
        }

        ##################### SALVAR ANEXO EXAME ################
        $retornoAnexoExame = $this->anexoExame($fichaPsicossocial['codigo_pedido_exame'], $payload['codigo_usuario']);
        if(isset($retornoAnexoExame['error'])){
            return $retornoAnexoExame;
        }

        ##################### BAIXA PEDIDO ################
        $retornoBaixaPedido = $this->baixaPedido($fichaPsicossocial['codigo_pedido_exame'], $payload['codigo_usuario'], $fichaPsicossocial['data_alteracao'], $fichaPsicossocial['total_sim']);
        if(isset($retornoBaixaPedido['error'])){
            return $retornoBaixaPedido;
        }

        ##################### ENVIAR ALERTA ################
        // envia o alerta somente se for maior que 7
        if($total_sim > 7){
            $resultado = 'Prov&aacute;vel';
            if($total_sim > 7 && $total_sim < 12){
                $resultado = 'Poss&iacute;vel';
            }

            $usuarios = $this->getUsuariosAlerta($payload['codigo_cliente']);
            // debug($usuarios);exit;            
            if($usuarios){
                foreach ($usuarios as $key => $usuario) {
                    $dadosAlerta = [
                        'codigo_pedido_exame'       => $fichaPsicossocial['codigo_pedido_exame'],
                        'codigo_ficha_psicossocial' => $fichaPsicossocial['codigo'],
                        'funcionario_nome'          => $payload['funcionario_nome'],
                        'setor'                     => $payload['setor'],
                        'cargo'                     => $payload['cargo'],
                        'medico_nome'               => $usuario['nome'],
                        'cliente_razao_social'      => $payload['cliente_razao_social'],
                        'resultado'                 => $resultado,
                                    
                        'codigo_cliente'            => $payload['codigo_cliente'],
                        'data_inclusao'             => $data_agora,
                        'data_tratamento'           => NULL,
                        'observacao_tratamento'     => NULL,
                        'codigo_usuario_tratamento' => NULL,
                        'email_agendados'           => '',
                        'sms_agendados'             => '',
                        'codigo_alerta_tipo'        => $usuario['codigo_alerta_tipo'],
                        'model'                     => 'Usuario',
                        'foreign_key'               => $usuario['codigo_usuario'],
                        'ws_agendados'              => false,
                        'caminho_arquivo'           => $retornoAnexoExame['caminho_arquivo']
                    ];
            
                    $retornoEmail = $this->enviarAlerta($dadosAlerta);
                    if(isset($retornoEmail['error'])){
                        return $retornoEmail;
                    }   
                }
            }
        }

        return $fichaPsicossocial;
    }

    public function getUsuariosAlerta($codigo_cliente)
    {
        $Usuario = TableRegistry::getTableLocator()->get('Usuario');

        $fields = [
            'codigo_usuario'        => 'Usuario.codigo',
            'nome'                  => 'Usuario.nome',
            'codigo_cliente'        => 'Usuario.codigo_cliente',
            'codigo_alerta_tipo'    => 'UsuarioAlertaTipo.codigo_alerta_tipo',
        ];

        $joins = [
            [
                'table' => 'usuarios_alertas_tipos',
                'alias' => 'UsuarioAlertaTipo',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioAlertaTipo.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => "Usuario.codigo = UsuarioMultiCliente.codigo_usuario AND UsuarioMultiCliente.codigo_cliente = $codigo_cliente",
            ],
        ];

        $where = [
            'Usuario.ativo' => 1,
            'Usuario.codigo_uperfil IN ('.UperfisTable::MEDICO_CLIENTE.','.UperfisTable::MEDICO_INTERNO.','.UperfisTable::MEDICO_COORDENADOR.','.UperfisTable::ENFERMAGEM_CLIENTE.','.UperfisTable::GERAL.','.UperfisTable::ADMIN.')',
            'Usuario.codigo_cliente' => $codigo_cliente,
            'UsuarioAlertaTipo.codigo_alerta_tipo IN (' . AlertasTiposTable::AVALIACAO_PSICOSSOCIAL . ', ' . AlertasTiposTable::AVALIACAO_PSICOSSOCIAL_EXTERNO . ')'
        ];

        $order = ['Usuario.codigo ASC'];
        
        return $Usuario->find()
            ->select($fields)
            ->join($joins)
            ->where($where)
            ->order($order)
            ->toArray();
    }

    public function enviarAlerta($dados)
    {
        $default_charset = 'UTF-8';

        ini_set('default_charset', $default_charset);

        $Alertas = TableRegistry::getTableLocator()->get('Alertas');

        $titulo = 'AVALIAÇÃO PSICOSSOCIAL - PEDIDO N° ' . $dados['codigo_pedido_exame'];

        $decricao = 'AVALIAÇÃO PSICOSSOCIAL - PEDIDO N° ' . $dados['codigo_pedido_exame'];

        $url = '';
        if(BASE_URL == 'https://api.rhhealth.com.br'){
            $url = 'https://portal.rhhealth.com.br/portal/ficha_psicossocial/editar/' . 
                $dados['codigo_pedido_exame'] . '/' . $dados['codigo_ficha_psicossocial'];
        } else {
            $url = 'https://tstportal.rhhealth.com.br/portal/ficha_psicossocial/editar/' . 
                $dados['codigo_pedido_exame'] . '/' . $dados['codigo_ficha_psicossocial'];
        }

        $descricao_email = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
            '<html xmlns="http://www.w3.org/1999/xhtml">' .
            '<head>' .
                '<meta http-equiv="Content-Type" content="text/html; charset='.$default_charset.'" />' .
                '<meta charset="'.$default_charset.'">' .
            '</head>' .
            '<body>' .
                '<div style="clear: both">' .
                '<div>' .
                    '<img style="display: block" src="https://portal.rhhealth.com.br/portal/img/logo-rhhealth.png" style="float: left"/>' .
                    '<hr style="border: 1px solid #eee; display: block" />' .
                '</div>' .
                '<div style="background: #fff;float: none;height: 10px;margin-top: 5px;padding: 8px 10px 0 0;width: 99%;"></div>' .
                '</div>' .
                '<div style="clear: both;padding-top: 50px;padding-left: 50px;width: 98.4%;min-height: 300px;">' .
                '<table >' .
                    '<tr>' .
                    '<td style="font-size: 12px">' .
                        'Ol&aacute; <b>' . htmlentities($this->converterEncodingPara($dados['medico_nome'], $default_charset)) . '</b>,<br/><br/>' .
                    '</td>' .
                    '</tr>' .
                    '<tr>' .
                    '<td style="font-size: 12px">' .
                        'O(a) funcion&aacute;rio(a) <b>' .  htmlentities($this->converterEncodingPara($dados['funcionario_nome'], $default_charset)) . '</b>, cargo ' . htmlentities($this->converterEncodingPara($dados['cargo'], $default_charset)) . ', setor ' . htmlentities($this->converterEncodingPara($dados['setor'], $default_charset)) . ' da empresa ' . htmlentities($this->converterEncodingPara($dados['cliente_razao_social'], $default_charset)) . ', respondeu a Avalia&ccedil;&atilde;o Psicossocial e teve o resultado <b>' . $dados['resultado'] . '</b>, para visualizar as respostas, acesse <a href="' . $url . '" target="_blank" >aqui</a>.' .
                    '</td>' .
                    '</tr>' .
                    '<td style="font-size: 12px">' .
                        'Clique <a href="' . $dados['caminho_arquivo'] . '" target="_blank">aqui</a> para baixar o exame.' .
                    '</td>' .
                    '</tr>' .
                    
                    '<tr>' .
                    '<td style="font-size: 12px">' .
                        '<br />' .
                        'Atenciosamente<br />' .
                        '<b>Equipe RH Health</b><br />' .
                        'Tel. 0800-014-2659<br />' .
                        '<a href="https://rhhealth.com.br/" target="_blank">www.rhhealth.com.br</a><br />' .
                    '</td>' .
                    '</tr>' .
                '</table>' .
                '</div>' .
                '<div><br /><br /></div>' .
            '</body>' .
            '</html>';

        // // $descricao_email = utf8_encode($descricao_email);
        // // $descricao_email = mb_convert_encoding($descricao_email, 'UTF-8', 'ISO-8859-1');

        // debug(mb_detect_encoding($descricao_email,'UTF-8','ISO-8859-1'));
        // debug(mb_detect_encoding($descricao_email,'ISO-8859-1','UTF-8'));
        // exit;

        $dados['assunto'] = $titulo;
        $dados['descricao'] = $decricao;
        $dados['descricao_email'] = $descricao_email;

        $entity = $Alertas->newEntity($dados);

        $retorno = $Alertas->save($entity);
        if (!$retorno) {
            $error = $entity->getValidationErrors();
            return ['error'=> $error];
        }

        return true;
    }

    public function anexoExame($codigo_pedido_exame, $codigo_usuario)
    {
        $ItensPedidosExames = TableRegistry::getTableLocator()->get('ItensPedidosExames');

        $item = $ItensPedidosExames->find()->where(['codigo_exame' => '27', 'codigo_pedidos_exames' => $codigo_pedido_exame])->first();

        if(empty($item)) {
            return ['error'=> "Não encontramos o item pedido de exame psicossocial para dar a baixa!"];
        }

        $codigo_item_pedido_exame = $item->codigo;

        $url = '';
        if(BASE_URL == 'https://api.rhhealth.com.br'){
            $url = "https://portal.rhhealth.com.br/portal/impressoes/imp_psicossocial/$codigo_pedido_exame";
        } else {
            $url = "https://tstportal.rhhealth.com.br/portal/impressoes/imp_psicossocial/$codigo_pedido_exame";
        }

        $arquivo = file_get_contents($url);
        if(!$arquivo){
            return ['error'=> "Não foi possível gerar o arquivo de respostas."];
        }

        $arquivo_base64 = chunk_split(base64_encode($arquivo));
        
        $AnexosExames = TableRegistry::getTableLocator()->get('AnexosExames');

        $dados_exames = array(
            'file'   => 'data://application/pdf;base64,' . $arquivo_base64,
            'prefix' => 'nina',
            'type'   => 'base64'
        );

        // envia a foto para o systemstorage
        $url_imagem = Comum::sendFileToServer($dados_exames);
        
        $anexos = [];

        // verifica se foi possível obter o caminho da imagem
        if(isset($url_imagem->response->path) && !empty($url_imagem->response->path)) {
            $imagem_caminho_completo = FILE_SERVER . $url_imagem->response->path;

            $anexos = array(
                'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                'caminho_arquivo' => $imagem_caminho_completo,
                'codigo_usuario_inclusao' => $codigo_usuario,
                'data_inclusao' => date('Y-m-d H:i:s'),
                'status' => 1,
                'codigo_empresa' => 1,
            );

            $anexos_exames = $AnexosExames->find()->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])->first();
            if(!empty($anexos_exames)) {
                $anexos['codigo'] = $anexos_exames->codigo;
                //seta os dados para atualizacao
                $anexos = $AnexosExames->patchEntity($anexos_exames, $anexos);
            } else {
                $anexos = $AnexosExames->newEntity($anexos);
            }

            if (!$AnexosExames->save($anexos)) {
                return ['error'=> "Erro ao inserir o anexo do exame!"];
            }
        } else {
            return ['error'=> "Erro ao enviar o arquivo de respostas para o file-server"];
        }

        return $anexos;
    }

    public function baixaPedido($codigo_pedido_exame, $codigo_usuario, $data_realizacao_exame, $total_sim)
    {
        $ItensPedidosExames = TableRegistry::getTableLocator()->get('ItensPedidosExames');
        $item = $ItensPedidosExames->find()->where(['codigo_exame' => '27', 'codigo_pedidos_exames' => $codigo_pedido_exame])->first();

        if(empty($item)) {
            return ['error'=> "Não encontramos o item pedido de exame psicossocial para dar a baixa!"];
        }

        /****************pegar o valor do servico na assinatura ************/
        //pega o valor na assinatura do codigo_servico_lyn para psicossocial
        $PedidosExames = TableRegistry::getTableLocator()->get('PedidosExames');
        $dados_usuario_exame = $PedidosExames->getUsuariosResponderExame($codigo_usuario);


        $codigo_item_pedido_exame = $item->codigo;

        ##################### BAIXANDO NO ITENS_PEDIDOS_EXAMES ################
        $dados_itens_pedidos_exames = array(
            'data_realizacao_exame' => substr($data_realizacao_exame, 0, 10), // 'date',
            'hora_realizacao_exame' => substr($data_realizacao_exame, 11, 5), // 'hora',
            'compareceu' => 1,
            'recebimento_digital' => 0,
            'recebimento_enviado' => 0,

            'codigo_usuario_alteracao' => $codigo_usuario,
            'data_alteracao' => date('Y-m-d H:i:s'),
            'codigo_status_itens_pedidos_exames' => 1,
            
            'respondido_lyn' => 1,
            'valor' => $dados_usuario_exame->valor_servico, //valor para o faturamento
            'valor_receita' => $dados_usuario_exame->valor_servico, //valor para o faturamento
        );
        
        $patchEntityItensPedidosExames = $ItensPedidosExames->patchEntity($item, $dados_itens_pedidos_exames);
        if (!$ItensPedidosExames->save($patchEntityItensPedidosExames)) {
            return ['error'=> "Não foi possivel dar baixa no exame psicossocial."];
        }

        ##################### BAIXA NO ITENS_PEDIDOS_EXAMES_BAIXA ################
        $ItensPedidosExamesBaixa = TableRegistry::getTableLocator()->get('ItensPedidosExamesBaixa');
        //verifica se existe baixa para este item
        $itensPedidoBaixa = $ItensPedidosExamesBaixa->find()->where(['codigo_itens_pedidos_exames' => $codigo_item_pedido_exame])->first();

        //caso nao exista insere
        if(empty($itensPedidoBaixa)) {

            $resultado = 1;
            $descricao = '';
            if($total_sim > 7){
                $resultado = 2;
                $descricao = '';
            } 

            $dados_baixa = array(
                'codigo_itens_pedidos_exames' => $codigo_item_pedido_exame,
                'resultado' => $resultado,
                'descricao' => $descricao, // 'Para dar baixa com resultado alterado = 2, é necessária a descrição da anormalidade.'
                'data_realizacao_exame' => substr($data_realizacao_exame, 0, 10), // 'date'
                'codigo_usuario_inclusao' => $codigo_usuario,
                'data_inclusao' => date('Y-m-d H:i:s'),
            );

            $dados_item_pedido_exame_baixa = $ItensPedidosExamesBaixa->newEntity($dados_baixa);
            if(!$ItensPedidosExamesBaixa->save($dados_item_pedido_exame_baixa)) {
                return ['error'=> "Não foi possivel dar baixa no exame psicossocial."];
            }
        }

        ##################### BAIXA NO PEDIDOS_EXAMES ################
        $PedidosExames = TableRegistry::getTableLocator()->get('PedidosExames');
        
        return $PedidosExames->baixaTotalPedidoExame($codigo_pedido_exame);
    }

    public function enviarEmail($dados)
    {
        $default_charset = 'UTF-8';

        ini_set('default_charset', $default_charset);

        $encoded = urlencode( base64_encode($dados['codigo_pedido_exame']) );
        // $decoded = base64_decode( urldecode($encoded) );

        $to = $dados['fornecedor_email'];
        
        $url = '';

        if(BASE_URL == 'https://tstapi.rhhealth.com.br'){
            $url = "https://tstportal.rhhealth.com.br/portal/impressoes/psicossocial/$encoded";
        } else {
            $url = "https://portal.rhhealth.com.br/portal/impressoes/psicossocial/$encoded";            
        }       

        $titulo = 'RH HEALTH - EXAME DE AVALIAÇÃO PSICOSSOCIAL';

        $mensagem = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
        '<html xmlns="http://www.w3.org/1999/xhtml">' .
          '<head>' .
            '<meta http-equiv="Content-Type" content="text/html; charset='.$default_charset.'" />' .
            '<meta charset="'.$default_charset.'">' .
          '</head>' .
          '<body>' .
            '<div style="clear: both">' .
              '<div>' .
                '<img style="display: block" src="https://portal.rhhealth.com.br/portal/img/logo-rhhealth.png" style="float: left"/>' .
                '<hr style="border: 1px solid #eee; display: block" />' .
              '</div>' .
              '<div style="background: #fff;float: none;height: 10px;margin-top: 5px;padding: 8px 10px 0 0;width: 99%;"></div>' .
            '</div>' .
            '<div style="clear: both;padding-top: 50px;padding-left: 50px;width: 98.4%;min-height: 300px;">' .
              '<table  >' .
                '<tr>' .
                  '<td style="font-size: 12px">' .
                    'Ol&aacute; <strong>' . htmlentities($this->converterEncodingPara($dados['fornecedor_nome'], $default_charset)) . '</strong>, tudo bem? <br /><br />' .
                  '</td>' .
                '</tr>' .
                '<tr>' .
                  '<td style="font-size: 12px">' .
                    'Estamos entrando em contato para informar que o exame de <br>' .
                    'Avalia&ccedil;&atilde;o Psicossocial do(a) funcion&aacute;rio(a) <b>' . htmlentities($this->converterEncodingPara($dados['funcionario_nome'], $default_charset)) . '</b> est&aacute; dispon&iacute;vel.<br/><br/>' .
                    'Clique <a href="' . $url . '" target="_blank">aqui</a> para baixar o exame, lembrando que o link tem validade de 3 meses.' .
                  '</td>' .
                '</tr>' .
                '<tr>' .
                  '<td style="font-size: 12px">' .
                    '<br />' .
                    'Atenciosamente<br />' .
                    '<b>Equipe RH Health</b><br />' .
                    'Tel. 0800-014-2659<br />' .
                    '<a href="https://rhhealth.com.br/" target="_blank">www.rhhealth.com.br</a><br />' .
                  '</td>' .
                '</tr>' .
              '</table>' .
            '</div>' .
            '<div><br /><br /></div>' .
          '</body>' .
        '</html>';

        // $mensagem = utf8_encode($mensagem);
        // $mensagem = mb_convert_encoding($mensagem, 'UTF-8', 'ISO-8859-1');

        // print($mensagem);exit;

        // Esta dando erro ao tentar usar a MailerOutboxTable e por isso foi feito o insert em sql
        $conn = ConnectionManager::get('default');
        $insert = "INSERT INTO mailer_outbox ([to],[subject],[content],[from],[created],[modified]) VALUES ('$to','$titulo','$mensagem','portal@rhhealth.com.br','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "')";
        return $conn->execute($insert);

        /*$param = [ 
            '[to]' => $to,
            'subject' => 'Titulo',
            '[content]' => 'mensagem',
            '[from]' => 'portal@rhhealth.com.br',
            'cc' => $cc,
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ];

        $MailerOutbox = TableRegistry::getTableLocator()->get('MailerOutbox');

        $entity = $MailerOutbox->newEntity($param);

        $retorno = $MailerOutbox->save($entity);
        if (!$retorno) {
            $error = $entity->getValidationErrors();
            return ['error'=> $error];
        }*/
    }

    /**
     * Obter dados de um questionario pelo codigo_pedido_exame 
     *
     * @param int $codigo_questionario
     * 
     * @return array|null
     */
    public function getQuestionarioAtivo(int $codigo_pedido_exame){
        $conditions = [
            'codigo_pedido_exame' => $codigo_pedido_exame,
            'ativo' => 1
        ];
        
        return $this->find()
            ->where($conditions)
            ->first();
    }

    

	/**
	 * $strConvertEncoding é a configuração da saída do caracter na conversão. 
	 * Caso o caracter esteja no mesmo encoding ele irá ignorar a conversão
	 * 
	 * $strConvertEncoding =  'UTF-8';      // UTF funciona, mas exigiu conversão UTF pelo programa usado LibreOffice
     * $strConvertEncoding =  'ISO-8859-1'; // conforme importação padrão sugerida no LibreOffice ISO-8859-1 funcionou bem para 
     *                                      // Windows 1252/WinLatin 1 
     *                                      // Windows 1250/WinLatin 2
     *                                      // ISO-8859-15/EURO
     *                                      // ISO-8859-14
     *                                      // ASCII/Inglês Norte Americano
     *                                      // Europa oriental ISO 8859-2
     *                                      // Turco (ISO 8859-9)
     *                                      // Turco (Windows-1254)
     *                                      // Vietnamita (Windows-1258)
     *                                      // Sistema, Caso o sistema operacional seja Português Brasil
	 *
	 * @param string $strText 
	 * @param string $strConvertEncoding    // força uma conversão
	 * @return void
	 */
	function converterEncodingPara( $strText , $strConvertEncoding = 'ISO-8859-1' ){

        // encodings possíveis que podemos trabalhar
        // Alguns foram comenrados para não exigir mais processamento
        $arrEncodings = array(
            // 'CP1251',
            // 'UCS-2LE',
            // 'UCS-2BE',
            'UTF-8',
            // 'UTF-16',
            // 'UTF-16BE',
            // 'UTF-16LE',
            // 'UTF-32',
            // 'CP866',
            // 'CP850',
            'ISO-8859-1', // No mb_detect_encoding detecta que nosso banco tem campos neste encoding
            //'Windows-1252'
        );
		
		$encoding = mb_detect_encoding($strText, $arrEncodings, true);
		
		// detectou que a string é de encoding iso-8859-1 não é preciso fazer nada
		if($encoding == 'ISO-8859-1')
		{

			$strText = mb_convert_encoding($strText, 'Windows-1252', 'ISO-8859-1');

			// mas se esta forçando converter iso-8859 para UTF-8
			if($strConvertEncoding == "UTF-8")
			{
				$strText = mb_convert_encoding($strText, 'ISO-8859-1', "UTF-8");	
			}

		} else {
			
			// se estiver em encoding utf-8 ou outro apenas converta do sql-server para corrigir registros com acentuação irregular
			$strText = mb_convert_encoding($strText, 'Windows-1252', "UTF-8");
		}

		return $strText;
    }

}
