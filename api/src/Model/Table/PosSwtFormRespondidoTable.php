<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Event\Event;

use App\Model\Table\PosSwtFormParticipantesTable;

/**
 * PosSwtFormRespondido Model
 *
 * @method \App\Model\Entity\PosSwtFormRespondido get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespondido findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormRespondidoTable extends AppTable
{

    const UTF8_DECODE_COLUMNS = [
        'nome',
        'localidade'
    ];

    const UTF8_DECODE_COLUMNS_RESPOSTAS = [
        'titulo',
        'questao',
        'criticidade_descricao',
        'criticidade_cor'
    ];

    public $connection;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->connection = ConnectionManager::get('default');

        $this->setTable('pos_swt_form_respondido');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_form_respondido');
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
            ->integer('codigo_form')
            ->allowEmptyString('codigo_form');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->allowEmptyString('codigo_acoes_melhorias_status');

        $validator
            ->integer('codigo_usuario_observador')
            ->allowEmptyString('codigo_usuario_observador');

        $validator
            ->integer('codigo_cliente_unidade')
            ->allowEmptyString('codigo_cliente_unidade');

        return $validator;
    }

    /**
     * [addFormResposta prepara os dados recebidos para gravar no banco calcular e devolver o resultado]
     * @param [type] $dados [description]
     */
    public function addFormResposta($codigo_usuario, $codigo_setor, $dados)
    {
        //variavel auxiliar
        $resp = array();

        $this->PosSwtForm                 = TableRegistry::getTableLocator()->get('PosSwtForm');
        $this->Usuario                    = TableRegistry::getTableLocator()->get('Usuario');
        $this->PosSwtFormResumo           = TableRegistry::getTableLocator()->get('PosSwtFormResumo');
        $this->PosSwtFormParticipantes    = TableRegistry::getTableLocator()->get('PosSwtFormParticipantes');
        $this->PosSwtFormFacilitadores    = TableRegistry::getTableLocator()->get('PosSwtFormFacilitadores');
        $this->PosSwtFormCompromisso      = TableRegistry::getTableLocator()->get('PosSwtFormCompromisso');
        $this->PosSwtFormAcaoMelhoria     = TableRegistry::getTableLocator()->get('PosSwtFormAcaoMelhoria');
        $this->PosSwtFormResposta         = TableRegistry::getTableLocator()->get('PosSwtFormResposta');
        $this->AcoesMelhorias             = TableRegistry::getTableLocator()->get('AcoesMelhorias');
        $this->AcoesMelhoriasSolicitacoes = TableRegistry::getTableLocator()->get('AcoesMelhoriasSolicitacoes');

        try {

            //transacao
            $this->connection->begin();

            //pega o codigo do cliente matriz que vem do formulario
            $form = $this->PosSwtForm->find()->select(['codigo_cliente'])->where(['codigo' => $dados['form_codigo']])->first();
            $codigo_cliente_matriz = $form->codigo_cliente;

            // debug($codigo_cliente_matriz);exit;

            //pega o codigo a empresa
            $codigo_empresa = $this->Usuario->getCodigoEmpresa($codigo_usuario);

            //verifica se existe o codigo_form_respondido para relacionar quando for o formulario de qualidade
            $codigo_form_respondido_swt = null;

            if (isset($dados['codigo_form_respondido_swt'])) {
                if (!empty($dados['codigo_form_respondido_swt'])) {
                    $codigo_form_respondido_swt = $dados['codigo_form_respondido_swt'];

                    // Alterar status para concluído para o responsável quando responder a classificação
                    $mainForm  = $this->find()
                        ->where(['codigo' => (int) $codigo_form_respondido_swt])
                        ->first();

                    if (!empty($mainForm)) {
                        $mainFormEntity = $this->patchEntity($mainForm, [
                            'codigo_am_status_responsavel' => 5
                        ]);

                        if (!$this->save($mainFormEntity)) {
                            throw new Exception("Erro ao criar uma nova resposta doprint_r( formulario! (,1)" . $mainFormEntity->errors() . ")");
                        }
                    }
                }
            }

            //grava na tabela principal os dados
            //pos_swt_form_respondido
            $dados_form_respondido = array(
                'codigo_form' => $dados['form_codigo'],
                'codigo_empresa' => $codigo_empresa,
                'ativo' => 1,
                'codigo_usuario_inclusao' => $codigo_usuario,
                'data_inclusao' => date('Y-m-d H:i:s'),
                'codigo_usuario_observador' => (isset($dados['facilitador'][0]['codigo_facilitador'])) ? $dados['facilitador'][0]['codigo_facilitador'] : $dados['codigo_usuario'],
                'codigo_cliente_unidade' => (isset($dados['resumo'])) ? $dados['resumo']['codigo_cliente_localidade'] : $codigo_cliente_matriz,
                'codigo_acoes_melhorias_status' => '5',
                'resultado' => (float) 0,
                'media_area' => (float) 0,
                'media_cliente' => (float) 0,
                'codigo_setor' => intval($codigo_setor),
                'codigo_am_status_responsavel' => (($dados['form_tipo'] == "1") ? 2 : 5),
                'codigo_form_respondido_swt' => $codigo_form_respondido_swt,
                'codigo_pos_local' => !empty($dados['resumo']['codigo_pos_local']) ? $dados['resumo']['codigo_pos_local'] : null,
            );


            // debug($dados_form_respondido);exit;

            //instancia para um novo registro
            $form_respondido = $this->newEntity($dados_form_respondido);

            //verifica se grava os dados corretamente no banco
            if (!$this->save($form_respondido)) {
                throw new Exception("Erro ao criar uma nova resposta doprint_r( formulario! (,1)" . $form_respondido->errors() . ")");
            }

            //codigo novo do formulario respondido
            $codigo_form_respondido = $form_respondido['codigo'];

            //verifica se é o form_tipo 1 walk talk
            if ($dados['form_tipo'] == "1") {

                //pos_swt_form_resumo
                $dados_from_resumo = array(
                    'codigo_form'               => $dados['form_codigo'],
                    'codigo_form_respondido'    => $codigo_form_respondido,
                    'codigo_cliente'            => $codigo_cliente_matriz,
                    'codigo_empresa'            => $codigo_empresa,
                    'data_obs'                  => Comum::formataData($dados['resumo']['data_obs'], "dmy", "ymd"),
                    'hora_obs'                  => $dados['resumo']['hora_obs'],
                    'desc_atividade'            => $dados['resumo']['desc_atividade'],
                    'codigo_cliente_localidade' => $dados['resumo']['codigo_cliente_localidade'],
                    'codigo_cliente_bu'         => $dados['resumo']['codigo_cliente_bu'],
                    'codigo_cliente_opco'       => $dados['resumo']['codigo_cliente_opco'],
                    'descricao'                 => $dados['resumo']['descricao'],
                    'ativo'                     => 1,
                    'codigo_usuario_inclusao'   => $codigo_usuario,
                    'data_inclusao'             => date('Y-m-d H:i:s'),
                    'codigo_pos_local'          => !empty($dados['resumo']['codigo_pos_local']) ? $dados['resumo']['codigo_pos_local'] : null,
                );

                // debug($dados_from_resumo);exit;

                //instancia para um novo registro
                $form_resumo = $this->PosSwtFormResumo->newEntity($dados_from_resumo);
                // debug($form_resumo);exit;
                //verifica se grava os dados corretamente no banco
                if (!$this->PosSwtFormResumo->save($form_resumo)) {
                    throw new Exception("Erro ao criar um novo resumo do formulario! (" . print_r($form_resumo->errors(), 1) . ")");
                }

                //pos_swt_form_participantes
                //varre os participantes que estão vindo do post
                if (!empty($dados['participantes'])) {
                    foreach ($dados['participantes'] as $part) {

                        //pega o cpf do participante
                        $cpf = null;

                        //monta o array para gravar no banco
                        $dados_form_participantes = array(
                            'codigo_form' => $dados['form_codigo'],
                            'codigo_form_respondido' => $codigo_form_respondido,
                            'codigo_cliente' => $codigo_cliente_matriz,
                            'codigo_empresa' => $codigo_empresa,
                            'codigo_usuario' => $part['codigo_usuario'],
                            'cpf' => $cpf,
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        //instancia para um novo registro
                        $form_participantes = $this->PosSwtFormParticipantes->newEntity($dados_form_participantes);
                        //verifica se grava os dados corretamente no banco
                        if (!$this->PosSwtFormParticipantes->save($form_participantes)) {
                            throw new Exception("Erro ao criar o relacionamento do participantes do formulario! (" . print_r($form_participantes->errors(), 1) . ")");
                        }
                    } //fim foreach
                } //fim participantes

                //pos_swt_form_facilitadores
                //varre os facilitador que estão vindo do post
                if (!empty($dados['facilitador'])) {
                    foreach ($dados['facilitador'] as $part) {

                        if (empty($part)) {
                            continue;
                        }

                        //pega o cpf do participante
                        $cpf = null;

                        //monta o array para gravar no banco
                        $dados_form_facilitador = array(
                            'codigo_form' => $dados['form_codigo'],
                            'codigo_form_respondido' => $codigo_form_respondido,
                            'codigo_cliente' => $codigo_cliente_matriz,
                            'codigo_empresa' => $codigo_empresa,
                            'codigo_usuario' => isset($part['codigo_usuario'])  ? $part['codigo_usuario'] : null,
                            'cpf' => $cpf,
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        //instancia para um novo registro
                        $form_facilitador = $this->PosSwtFormFacilitadores->newEntity($dados_form_facilitador);
                        //verifica se grava os dados corretamente no banco
                        if (!$this->PosSwtFormFacilitadores->save($form_facilitador)) {
                            throw new Exception("Erro ao criar o relacionamento do facilitador do formulario! (" . print_r($form_facilitador->errors(), 1) . ")");
                        }
                    } //fim foreach
                } //fim participantes

                //pos_swt_form_compromisso
                if (!empty($dados['compromissos'])) {
                    //monta o array para gravar no banco
                    $dados_form_compromissos = array(
                        'codigo_form' => $dados['form_codigo'],
                        'codigo_form_respondido' => $codigo_form_respondido,
                        'codigo_cliente' => $codigo_cliente_matriz,
                        'codigo_empresa' => $codigo_empresa,
                        'compromisso' => $dados['compromissos'],
                        'ativo' => 1,
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'data_inclusao' => date('Y-m-d H:i:s'),
                    );

                    //instancia para um novo registro
                    $form_compromissos = $this->PosSwtFormCompromisso->newEntity($dados_form_compromissos);
                    //verifica se grava os dados corretamente no banco
                    if (!$this->PosSwtFormCompromisso->save($form_compromissos)) {
                        throw new Exception("Erro ao criar o relacionamento do compromissos do formulario! (" . print_r($form_compromissos->errors(), 1) . ")");
                    }
                }

                //verifica se tem um novo cadastro de acao de melhoria
                if (!empty($dados['acao_melhoria'])) {

                    $dado_acoes_melhoria = $this->AcoesMelhorias->setFerramentaAcoesMelhoria($codigo_usuario, $dados['acao_melhoria']);

                    //verifica se deu algum erro para inserir a acao de melhoria
                    if (isset($dado_acoes_melhoria['error'])) {
                        throw new Exception($dado_acoes_melhoria['error']);
                    }

                    if (!empty($dado_acoes_melhoria['vinculo_acao'])) {
                        foreach ($dado_acoes_melhoria['vinculo_acao'] as $am) {
                            $dados['vinculo_acao'][] = $am;
                        }
                    }
                } //fim dados acoes_melhoria

                //pos_swt_form_acao_melhoria, aqui pode ser um vinculo ou uma nova acao de melhoria
                //verificação se tem indices de relacionamento
                if (!empty($dados['vinculo_acao'])) {
                    foreach ($dados['vinculo_acao'] as $va) {

                        //monta o array para gravar no banco
                        $dados_form_acao_melhoria = array(
                            'codigo_form' => $dados['form_codigo'],
                            'codigo_form_respondido' => $codigo_form_respondido,
                            'codigo_cliente' => $codigo_cliente_matriz,
                            'codigo_empresa' => $codigo_empresa,
                            'codigo_acao_melhoria' => $va['codigo'],
                            'codigo_form_questao' => $va['codigo_form_questao'],
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        //instancia para um novo registro
                        $form_acao_melhoria = $this->PosSwtFormAcaoMelhoria->newEntity($dados_form_acao_melhoria);
                        //verifica se grava os dados corretamente no banco
                        if (!$this->PosSwtFormAcaoMelhoria->save($form_acao_melhoria)) {
                            throw new Exception("Erro ao criar o relacionamento do form com a acao de melhoria! (" . print_r($form_acao_melhoria->errors(), 1) . ")");
                        }
                    } //fim foreach
                } // fim vinculo_acao

            } //fim tipo 1

            //pos_swt_form_respostas
            //grava as respostas
            if (!empty($dados['respostas'])) {
                //varre as respostas
                foreach ($dados['respostas'] as $t) {

                    //atribui o codigo do titulo
                    // $codigo_titulo = $t['codigo_titulo'];

                    foreach ($t['questao'] as $q) {

                        $params = array(
                            'form_codigo' => $dados['form_codigo'],
                            'codigo_form_respondido' => $codigo_form_respondido,
                            'codigo_empresa' => $codigo_empresa,
                            'codigo_form_questao' => $q['codigo'],
                            'codigo_criticidade' => $q['criticidade'],
                            'resposta' => $q['resposta'],
                            'motivo' => $q['motivo'],
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        $this->setVinculoSwtAcaoMelhoria($params);

                        //monta o array para gravar no banco
                        /*$dados_form_respostas = array(
                    'codigo_form' => $dados['form_codigo'],
                    'codigo_form_respondido' => $codigo_form_respondido,
                    'codigo_empresa' => $codigo_empresa,
                    'codigo_form_questao' => $q['codigo'],
                    'codigo_criticidade' => $q['criticidade'],
                    'resposta' => $q['resposta'],
                    'motivo' => $q['motivo'],
                    'ativo' => 1,
                    'codigo_usuario_inclusao' => $codigo_usuario,
                    'data_inclusao' => date('Y-m-d H:i:s'),
                    );

                    //instancia para um novo registro
                    $form_respostas = $this->PosSwtFormResposta->newEntity($dados_form_respostas);
                    //verifica se grava os dados corretamente no banco
                    if (!$this->PosSwtFormResposta->save($form_respostas)) {
                    throw new Exception("Erro ao criar o dado de resposta do formulario! (".print_r($form_respostas->errors(),1).")");
                    }*/
                    } //fim foreach questao

                } //fim foreach titulo

            } //fim respostas

            $this->connection->commit();

            $resp['form_tipo'] = $dados['form_tipo'];
            $resp['form_codigo'] = $dados['form_codigo'];
            $resp['codigo_form_respondido'] = $codigo_form_respondido;
            $resp['codigo_cliente_matriz'] = $codigo_cliente_matriz;
            $resp['codigo_setor'] = $codigo_setor;

            if ($dados['form_tipo'] == "1") {
                $evento_criacao = new Event('Model.PosSwtFormRespondido.criacao', $this, [
                    'form_respondido' => $form_respondido,
                    'participantes'   => $dados['participantes'],
                    'respostas'       => $dados['respostas'],
                    'observador'      => $dados['codigo_usuario'],
                    'codigo_cliente'  => $codigo_cliente_matriz
                ]);

                $this->getEventManager()->dispatch($evento_criacao);
            }
        } catch (Exception $e) {
            // debug($e->getMessage());exit;
            $resp['error'] = $e->getMessage();
            $this->connection->rollback();
        }

        return $resp;
    } //fim addFormResposta

    /**
     * [setVinculoSwtAcaoMelhoria metodo para gravar os dados de vinculo do swt com a acao de melhoria]
     * @param [type] $params [description]
     */
    public function setVinculoSwtAcaoMelhoria($params)
    {
        //monta o array para gravar no banco
        $dados_form_respostas = array(
            'codigo_form' => $params['form_codigo'],
            'codigo_form_respondido' => $params['codigo_form_respondido'],
            'codigo_empresa' => $params['codigo_empresa'],
            'codigo_form_questao' => $params['codigo_form_questao'],
            'codigo_criticidade' => $params['codigo_criticidade'],
            'resposta' => $params['resposta'],
            'motivo' => $params['motivo'],
            'ativo' => $params['ativo'],
            'codigo_usuario_inclusao' => $params['codigo_usuario_inclusao'],
            'data_inclusao' => $params['data_inclusao'],
        );

        //instancia para um novo registro
        $form_respostas = $this->PosSwtFormResposta->newEntity($dados_form_respostas);
        //verifica se grava os dados corretamente no banco
        if (!$this->PosSwtFormResposta->save($form_respostas)) {
            throw new Exception("Erro ao criar o dado de resposta do formulario! (" . print_r($form_respostas->errors(), 1) . ")");
        }
    } // fim setVinculoSwtAcaoMelhoria($params)

    public function queryCalculo($codigo_form, $codigo_form_respondido = null, $codigo_cliente = null, $codigo_area = null)
    {

        $select = "";
        $cond = "";
        if (!empty($codigo_form_respondido)) {
            $select .= ",resp.codigo_form_respondido";
            $cond .= " AND resp.codigo_form_respondido = {$codigo_form_respondido} \n";
        }

        if (!empty($codigo_cliente)) {
            $select .= ",f.codigo_cliente";
            $cond .= " AND f.codigo_cliente = {$codigo_cliente} \n";
        }

        if (!empty($codigo_area)) {
            $select .= ",r.codigo_setor";
            $cond .= " AND r.codigo_setor = {$codigo_area} \n";
        }

        $query = "WITH cteTotalResposta AS (
                    SELECT
                        resp.codigo_form
                        {$select}
                        ,COUNT(resp.codigo) AS total
                    FROM pos_swt_form_resposta resp
                        INNER JOIN pos_swt_form f on resp.codigo_form = f.codigo
                        INNER JOIN pos_swt_form_respondido r on r.codigo = resp.codigo_form_respondido
                    WHERE resp.resposta <> '3'
                        $cond
                        AND resp.codigo_form = {$codigo_form}
                    GROUP BY resp.codigo_form {$select}

                ),
                cteTotalRespostaSim AS (
                    SELECT
                        resp.codigo_form
                        $select
                        ,cte.total
                        ,COUNT(resp.codigo) AS total_sim
                    FROM cteTotalResposta cte
                        INNER JOIN pos_swt_form_resposta resp ON cte.codigo_form = resp.codigo_form
                        INNER JOIN pos_swt_form f on resp.codigo_form = f.codigo
                        INNER JOIN pos_swt_form_respondido r on r.codigo = resp.codigo_form_respondido
                    WHERE resp.resposta = '1'
                        $cond
                        AND resp.codigo_form = {$codigo_form}
                    GROUP BY resp.codigo_form, cte.total {$select}
                )

                SELECT
                    CAST((ROUND((CAST(total_sim AS DECIMAL) / CAST(total AS DECIMAL)),4) * 100) AS DECIMAL(18,2)) AS total_percentual
                    ,total_sim
                    ,total
                FROM cteTotalRespostaSim
                ";

        return $query;
    } //fim queryCalculo

    /**
     * [calculaResultadoForm calcula a resposta do questionario walk talk
     *
     *     -> codigo_form_respondido
     *     -> form_codigo
     *
     * ]
     * @param  [type] $return [description]
     * @return [type]         [description]
     */
    public function calculaResultadoForm($dado)
    {
        //verifica se tem os indices
        if (!isset($dado['codigo_form_respondido'])) {
            return false;
        }

        if (!isset($dado['form_codigo'])) {
            return false;
        }

        if (!isset($dado['codigo_cliente_matriz'])) {
            return false;
        }

        if (!isset($dado['codigo_setor'])) {
            return false;
        }

        //MONTA A QUERY para calcular o resultado
        $conn = ConnectionManager::get('default');
        $query_resultado = $this->queryCalculo($dado['form_codigo'], $dado['codigo_form_respondido']);
        $dados_resultado = $conn->execute($query_resultado)->fetchAll('assoc');

        $query_cliente = $this->queryCalculo($dado['form_codigo'], null, $dado['codigo_cliente_matriz']);
        $dados_cliente = $conn->execute($query_cliente)->fetchAll('assoc');

        $query_area = $this->queryCalculo($dado['form_codigo'], null, null, $dado['codigo_setor']);
        $dados_area = $conn->execute($query_area)->fetchAll('assoc');

        // debug($dados_resultado);
        // debug($dados_resultado[0]['total_percentual']);
        // exit;

        $resultado_calc['resultado'] = isset($dados_resultado[0]['total_percentual']) ? $dados_resultado[0]['total_percentual'] : null;
        $resultado_calc['media_area'] = $dados_area[0]['total_percentual'];
        $resultado_calc['media_cliente'] = $dados_cliente[0]['total_percentual'];

        ################ ATUALIZA OS DADOS ####################
        //grava o resultado na tabela de respondido
        $dados_form_respondido = $this->find()->where(['codigo' => $dado['codigo_form_respondido']])->first();

        $putData['resultado'] = isset($dados_resultado[0]['total_percentual']) ? (float) $dados_resultado[0]['total_percentual'] : null;
        $putData['media_area'] = (float) $dados_area[0]['total_percentual'];
        $putData['media_cliente'] = (float) $dados_cliente[0]['total_percentual'];
        $entity = $this->patchEntity($dados_form_respondido, $putData);
        $this->save($entity);
        ################ FIM ATUALIZA OS DADOS ####################

        return $resultado_calc;
    } // fim calculaResultadoForm($return)

    /**
     * [getSwtDados metodo para retornar o arry com a estrutura onde irá executar no método que foi chamado para ser reaproveitado]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function getSwtDadosResumo($params)
    {

        if (empty($params)) {
            throw new Exception("Necessário passar os parametros de filtros, corretamente");
        }

        $fields = [
            "codigo" => 'PosSwtFormRespondido.codigo',
            "codigo_facilitador" => "PosSwtFormFacilitadores.codigo",
            "codigo_usuario_facilitador" => "PosSwtFormFacilitadores.codigo_usuario",
            "codigo_form" => 'Form.codigo',
            "form_tipo" => 'Form.form_tipo',
            "status_codigo" => 'PosSwtFormRespondido.codigo_acoes_melhorias_status',
            "status_desc" => "AcoesMelhoriasStatus.descricao",
            "status_cor" => "AcoesMelhoriasStatus.cor",
            "codigo_cliente_localidade" => "Resumo.codigo_cliente_localidade",
            "localidade" => "CONCAT(ClienteEndereco.logradouro,', ',ClienteEndereco.numero,' - ',ClienteEndereco.cidade,', ',ClienteEndereco.estado_abreviacao)",
            "codigo_usuario" => 'Usuario.codigo',
            "nome" => 'Usuario.nome',
            "avatar" => "UsuarioDado.avatar",
            "data" => "CONVERT(VARCHAR, Resumo.data_obs, 103)",
            "hora" => "Resumo.hora_obs",
            "data_hora" => "CONCAT(CONVERT(VARCHAR, resumo.data_obs, 103),' ', resumo.hora_obs)",
            "descricao" => "Resumo.descricao",
            "desc_atividade" => "Resumo.desc_atividade",
            //            "codigo_form_respondido_swt" => "PosSwtFormRespondido.codigo_form_respondido_swt",
            "codigo_form_respondido_swt" => "(select count(psfr.codigo) from
                pos_swt_form_respondido psfr where psfr.codigo_form_respondido_swt = PosSwtFormRespondido.codigo )",
            "codigo_pos_local" => "PosSwtFormRespondido.codigo_pos_local",
            "pos_local" => "PosObsLocal.descricao",
        ];

        //verifica se existe o parametro de usuario responsavel
        if (isset($params['usuario_responsavel'])) {
            if ($params['usuario_responsavel']) {

                $subQueryCodigoFormRespondidoSwt = '
                (
                    select
                        count(psfr.codigo)
                    from
                        pos_swt_form_respondido psfr 
                    where
                        psfr.codigo_form_respondido_swt = PosSwtFormRespondido.codigo
                )                
                ';

                $subQueryAcoesPendentes = '
                (
                select 
                    count(acao.codigo)
                from pos_swt_form_respondido respondido
                    inner join pos_swt_form_acao_melhoria pAcao
                        on respondido.codigo = pAcao.codigo_form_respondido
                    inner join acoes_melhorias acao
                        on acao.codigo = pAcao.codigo_acao_melhoria
                where
                    respondido.codigo = PosSwtFormRespondido.codigo
                and acao.codigo_acoes_melhorias_status = 2
                )                
                ';

                $fields = [
                    "codigo" => 'PosSwtFormRespondido.codigo',
                    "codigo_facilitador" => "PosSwtFormFacilitadores.codigo",
                    "codigo_usuario_facilitador" => "PosSwtFormFacilitadores.codigo_usuario",
                    "codigo_form" => 'Form.codigo',
                    "form_tipo" => 'Form.form_tipo',
                    "status_codigo" => "(CASE
                                            WHEN Form.form_tipo = 1
                                            THEN
                                                CASE
                                                    WHEN
                                                        (
                                                            PosSwtFormRespondido.codigo_am_status_responsavel <> 5 AND
                                                            (" . $subQueryAcoesPendentes . ") > 0 AND
                                                            PosSwtFormFacilitadores.codigo_usuario IS NOT NULL                                                                                                                                                                                
                                                        )
                                                    THEN 
                                                        PosSwtFormRespondido.codigo_am_status_responsavel
                                                    ELSE
                                                        PosSwtFormRespondido.codigo_acoes_melhorias_status
                                                END
                                        ELSE
                                            PosSwtFormRespondido.codigo_acoes_melhorias_status
                                        END)",
                    "status_desc" => "(CASE
                                            WHEN
                                            Form.form_tipo = 1
                                            THEN
                                                CASE
                                                    WHEN
                                                    (
                                                        PosSwtFormRespondido.codigo_am_status_responsavel <> 5 AND
                                                        (" . $subQueryAcoesPendentes . ") > 0 AND
                                                        PosSwtFormFacilitadores.codigo_usuario IS NOT NULL                                                                                                                                                                                
                                                    )                                                                                                            
                                                    THEN
                                                        AcoesMelhoriasStatusResp.descricao
                                                ELSE
                                                    AcoesMelhoriasStatus.descricao
                                                END
                                        ELSE
                                            AcoesMelhoriasStatus.descricao
                                        END)",
                    "status_cor" => "(CASE
                                        WHEN Form.form_tipo = 1
                                        THEN
                                            CASE
                                                WHEN
                                                (
                                                    PosSwtFormRespondido.codigo_am_status_responsavel <> 5 AND
                                                    (" . $subQueryAcoesPendentes . ") > 0 AND
                                                    PosSwtFormFacilitadores.codigo_usuario IS NOT NULL                                                                                                                                                                                
                                                )                                                    
                                                THEN
                                                    AcoesMelhoriasStatusResp.cor
                                                ELSE
                                                    AcoesMelhoriasStatus.cor
                                            END
                                    ELSE
                                        AcoesMelhoriasStatus.cor
                                    END)",
                    "codigo_cliente_localidade" => "Resumo.codigo_cliente_localidade",
                    "localidade" => "CONCAT(ClienteEndereco.logradouro,', ',ClienteEndereco.numero,' - ',ClienteEndereco.cidade,', ',ClienteEndereco.estado_abreviacao)",
                    "codigo_usuario" => 'Usuario.codigo',
                    "nome" => 'Usuario.nome',
                    "avatar" => "UsuarioDado.avatar",
                    "data" => "CONVERT(VARCHAR, Resumo.data_obs, 103)",
                    "hora" => "Resumo.hora_obs",
                    "data_hora" => "CONCAT(CONVERT(VARCHAR, resumo.data_obs, 103),' ', resumo.hora_obs)",
                    "descricao" => "Resumo.descricao",
                    "desc_atividade" => "Resumo.desc_atividade",
                    "codigo_form_respondido_swt" => $subQueryCodigoFormRespondidoSwt,
                    "codigo_pos_local" => "PosSwtFormRespondido.codigo_pos_local",
                    "pos_local" => "PosObsLocal.descricao",
                ];
            }
        }

        $joins = [
            [
                'table' => 'pos_swt_form',
                'alias' => 'Form',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo_form =  Form.codigo',
            ],
            [
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = PosSwtFormRespondido.codigo_usuario_observador',
            ],
            [
                'table' => 'pos_swt_form_resumo',
                'alias' => 'Resumo',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo = Resumo.codigo_form_respondido',
            ],
            [
                'table' => 'cliente_endereco',
                'alias' => 'ClienteEndereco',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo_cliente_unidade = ClienteEndereco.codigo_cliente',
            ],
            [
                'table' => 'acoes_melhorias_status',
                'alias' => 'AcoesMelhoriasStatus',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo_acoes_melhorias_status = AcoesMelhoriasStatus.codigo',
            ],
            [
                'table' => 'acoes_melhorias_status',
                'alias' => 'AcoesMelhoriasStatusResp',
                'type' => 'LEFT',
                'conditions' => 'PosSwtFormRespondido.codigo_am_status_responsavel = AcoesMelhoriasStatusResp.codigo',
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuarioDado',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioDado.codigo_usuario',
            ],
            [
                'table' => 'pos_swt_form_facilitadores',
                'alias' => 'PosSwtFormFacilitadores',
                'type' => 'LEFT',
                'conditions' => 'PosSwtFormRespondido.codigo = PosSwtFormFacilitadores.codigo_form_respondido
and PosSwtFormRespondido.codigo_cliente_unidade = PosSwtFormFacilitadores.codigo_cliente',
            ],
            [
                'table' => 'pos_obs_local',
                'alias' => 'PosObsLocal',
                'type' => 'LEFT',
                'conditions' => 'PosSwtFormRespondido.codigo_pos_local = PosObsLocal.codigo',
            ]
        ];

        $conditions = [];
        if (!empty($params['codigo_usuario'])) {
            $conditions['PosSwtFormRespondido.codigo_usuario_observador'] = $params['codigo_usuario'];
        }

        if (!empty($params['codigo_form_respondido'])) {
            $conditions['PosSwtFormRespondido.codigo'] = $params['codigo_form_respondido'];
        }

        if (!empty($params['periodo_de'])) {
            $conditions[] = "Resumo.data_obs >= '" . $params['periodo_de'] . "'";
        }

        if (!empty($params['periodo_ate'])) {
            $conditions[] = "Resumo.data_obs <= '" . $params['periodo_ate'] . "'";
        }

        if (!empty($params['codigo_setor'])) {
            $conditions['PosSwtFormRespondido.codigo_setor'] = $params['codigo_setor'];
        }

        if (!empty($params['codigo_unidades'])) {
            $conditions[] = "Form.codigo_cliente IN (" . $params['codigo_unidades'] . ")"; //filtrar pelas unidades relacionadas ao usuario, seja multiclientes ou nao
        }

        // debug($params);exit;
        // debug($conditions);exit;

        $query = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);

        // echo '<pre>';
        // print_r($query->sql());
        // echo '</pre>';
        // die;

        //  debug($conditions); 
        //  debug($query->sql()); die;

        return $query;
    } //fim getSwtDados

    public function getSwtAll($param)
    {

        try {

            // debug($param);exit;
            $query = $this->getSwtDadosResumo($param);
            $dados = $query->hydrate(false)->all()->toArray();


            foreach ($dados as $key => $row) {
                $dados[$key] = $this->utf8DecodeColumns($row);
            }

            // debug($dados);exit;

        } catch (\Exception $th) {
            return ['error' => 'Erro na consulta a base de dados'];
        }

        return $dados;
    } //fim getSwtAll

    /**
     * [getRespostas metodo responsavel por pegar as respostas do formulario]
     * @param  int    $codigo_form_respondido [description]
     * @return [type]                         [description]
     */
    public function getRespostas(int $codigo_form_respondido, $codigos_questao_acao = null)
    {

        if (empty($codigo_form_respondido)) {
            throw new Exception("Necessário passar os codigo da resposta corretamente");
        }

        $fields = [
            "codigo" => 'PosSwtFormRespondido.codigo',
            "codigo_titulo" => "Titulo.codigo",
            "titulo" => "Titulo.titulo",
            "codigo_questao" => "Questao.codigo",
            "questao" => "Questao.questao",
            "codigo_resposta" => "Resposta.codigo",
            "resposta" => "(CASE
                WHEN Resposta.resposta = 1 THEN 'Sim'
                WHEN Resposta.resposta = 3 THEN 'Não se aplica'
            ELSE 'Não' END )",
            "criticidade_codigo" => "Criticidade.codigo",
            "criticidade_descricao" => "Criticidade.descricao",
            "criticidade_cor" => "Criticidade.cor",
            "motivo" => "Resposta.motivo",
        ];

        $joins = [
            [
                'table' => 'pos_swt_form_resposta',
                'alias' => 'Resposta',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo = Resposta.codigo_form_respondido',
            ],
            [
                'table' => 'pos_swt_form_questao',
                'alias' => 'Questao',
                'type' => 'INNER',
                'conditions' => 'Resposta.codigo_form_questao = Questao.codigo AND Questao.codigo_form = PosSwtFormRespondido.codigo_form',
            ],
            [
                'table' => 'pos_swt_form_titulo',
                'alias' => 'Titulo',
                'type' => 'INNER',
                'conditions' => 'PosSwtFormRespondido.codigo_form = Titulo.codigo_form AND Questao.codigo_form_titulo = Titulo.codigo',
            ],
            [
                'table' => 'pos_criticidade',
                'alias' => 'Criticidade',
                'type' => 'LEFT',
                'conditions' => 'Criticidade.codigo = Resposta.codigo_criticidade',
            ],
        ];

        $conditions['PosSwtFormRespondido.codigo'] = $codigo_form_respondido;

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->all()
            ->toArray();

        foreach ($dados as $key => $resposta) {

            $dados[$key] = $this->utf8DecodeColumnsRespostas($resposta);
        }

        // debug($dados);exit;

        $dados_formatado = [];
        //verifica se tem dados
        if (!empty($dados)) {
            foreach ($dados as $dado) {
                $codigo_titulo = intval($dado['codigo_titulo']);
                $codigo_questao = intval($dado['codigo_questao']);
                $dados_formatado[$codigo_titulo]['titulo'] = $dado['titulo'];

                $codigo_acao = (isset($codigos_questao_acao[$codigo_questao])) ? $codigos_questao_acao[$codigo_questao] : null;

                $dados_formatado[$codigo_titulo]['questao'][] = [
                    'codigo_questao' => $codigo_questao,
                    'codigo_acao' => $codigo_acao,
                    'descricao' => $dado['questao'],
                    'resposta' => $dado['resposta'],
                    'criticidade' => $dado['criticidade_descricao'],
                    'criticidade_cor' => $dado['criticidade_cor'],
                    'motivo' => trim($dado['motivo']),
                ];
            } //fim foreach

            //retira o indice para devolver como array no json
            $dados_formatado = array_values($dados_formatado);
        }

        return $dados_formatado;
    } //fim getRespostas

    /**
     * [getSwtDetalhe metodo para pegar os dados do formulário de respostas do swt e as ações de melhoria que foram abertas]
     * @param  int    $codigo_form_respondido [description]
     * @return [type]                         [description]
     */
    public function getSwtDetalhe(int $codigo_usuario, int $codigo_form_respondido)
    {

        $dados = array();

        try {

            //pega o tipo do formulario, titulos, questoes e respostas
            $param['codigo_form_respondido'] = $codigo_form_respondido;
            $resumo = $this->getSwtDadosResumo($param);
            $dados_resumo = $resumo->first()->toArray();
            $dados_resumo = $this->utf8DecodeColumns($dados_resumo);

            $dados_resumo['participantes'] = [];

            $this->PosSwtFormParticipantes = TableRegistry::getTableLocator()->get('PosSwtFormParticipantes');

            $participantes = $this->PosSwtFormParticipantes->getByCodigoFormRespondido($dados_resumo['codigo']);
            foreach ($participantes as $participante) {

                $dados_resumo['participantes'][] = $participante;
            }

            //pega as acoes de melhorias do formulario respondido
            $this->PosSwtFormAcaoMelhoria = TableRegistry::get('PosSwtFormAcaoMelhoria');
            $form_acoes_melhoria = $this->PosSwtFormAcaoMelhoria->find()->select(['codigo', 'codigo_acao_melhoria', 'codigo_form_questao'])->where(['codigo_form_respondido' => $codigo_form_respondido])->hydrate(false)->all();
            $acoes = [];
            $codigo_questao_acao = null;
            //verifica se tem dados
            if (!empty($form_acoes_melhoria)) {
                $form_acoes_melhoria = $form_acoes_melhoria->toArray();
                $arr_ids = [];
                //varre o relacionamento do form com a acao de melhoria
                foreach ($form_acoes_melhoria as $codigos_acoes) {
                    $arr_ids[] = $codigos_acoes['codigo_acao_melhoria'];
                    $codigo_questao_acao[$codigos_acoes['codigo_form_questao']][]['codigo'] = $codigos_acoes['codigo_acao_melhoria'];
                } //fim foreach

                $this->AcoesMelhorias = TableRegistry::get('AcoesMelhorias');
                $acoes = $this->AcoesMelhorias->getById($codigo_usuario, null, $arr_ids);
            } //fim verificacao se tem acoes de melhoria

            // debug($codigo_questao_acao);exit;

            $respostas = $this->getRespostas($codigo_form_respondido, $codigo_questao_acao);

            $dados['detalhes'] = [
                'resumo' => $dados_resumo,
                'respostas' => $respostas,
                'acoes_melhorias' => $acoes,
            ];
        } catch (Exception $e) {

            $dados['error'] = $e->getMessage();
        }

        return (array) $dados;
    } //fim getSwtDetalhe


    public function conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_bu IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_bu' => $codigoClienteBuConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    public function conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_opco IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_opco' => $codigoClienteOpcoConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    private function utf8DecodeColumns($row)
    {

        foreach ($row as $columnName => $columnValue) {

            if (
                in_array($columnName, self::UTF8_DECODE_COLUMNS)
            ) {
                $columnValueBefore = $columnValue;
                $row[$columnName] = utf8_decode($columnValue);

                $jsonEncodeRow = json_encode($row);

                if (empty($jsonEncodeRow)) {
                    $row[$columnName] = $columnValueBefore;
                }
            }
        }

        return $row;
    }

    private function utf8DecodeColumnsRespostas($row)
    {

        foreach ($row as $columnName => $columnValue) {

            if (
                in_array($columnName, self::UTF8_DECODE_COLUMNS_RESPOSTAS)
            ) {
                $columnValueBefore = $columnValue;
                $row[$columnName] = utf8_decode($columnValue);

                $jsonEncodeRow = json_encode($row);

                if (empty($jsonEncodeRow)) {
                    $row[$columnName] = $columnValueBefore;
                }
            }
        }

        return $row;
    }
}
