<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * PdaConfigRegra Model
 *
 * @method \App\Model\Entity\PdaConfigRegra get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaConfigRegra newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegra[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegra|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegra saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegra patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegra[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegra findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaConfigRegraTable extends AppTable
{

    const OPERADORES_CONDICAO = [
        '=',
        '!=',
        '>',
        '>=',
        '<',
        '<='
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pda_config_regra');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
        $this->hasMany('PdaConfigRegraCondicao', [
            'foreignKey'       => 'codigo_pda_config_regra',
            'bindingKey'       => 'codigo',
            'joinTable'        => 'pda_config_regra_condicao',
            'propertyName'     => 'configRegraCodicoes',
            'conditions'       => [
                'PdaConfigRegraCondicao.ativo' => 1,
            ],
        ]);
        $this->hasMany('PdaConfigRegraAcao', [
            'foreignKey'       => 'codigo_pda_config_regra',
            'bindingKey'       => 'codigo',
            'joinTable'        => 'pda_config_regra_acao',
            'propertyName'     => 'configRegraAcoes',
            'conditions'       => [
                'PdaConfigRegraAcao.ativo' => 1,
            ],
        ]);
        $this->AcoesMelhorias = TableRegistry::get('AcoesMelhorias');
        $this->AcoesMelhoriasLog = TableRegistry::get('AcoesMelhoriasLog');
        $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');
        $this->GruposEconomicosClientes = TableRegistry::get('GruposEconomicosClientes');
        $this->PdaConfigRegraAcao = TableRegistry::get('PdaConfigRegraAcao');
        $this->Usuario = TableRegistry::get('Usuario');
        $this->FuncionariosContatos = TableRegistry::get('FuncionariosContatos');
        $this->UsuariosResponsaveis = TableRegistry::get('UsuariosResponsaveis');
        $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');
        $this->PushOutbox = TableRegistry::get('PushOutbox');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pda_tema')
            ->requirePresence('codigo_pda_tema', 'create')
            ->notEmptyString('codigo_pda_tema');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->allowEmptyString('codigo_acoes_melhorias_status');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('assunto')
            ->maxLength('assunto', 255)
            ->allowEmptyString('assunto');

        $validator
            ->scalar('mensagem')
            ->maxLength('mensagem', 255)
            ->allowEmptyString('mensagem');

        $validator
            ->allowEmptyString('ativo');

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

        return $validator;
    }

    public function getDadosRegra($codigo_tema, $params, $valor_dias = null)
    {

        $relacionamento = 'LEFT';
        if (isset($params['tipo_relacionamento'])) {
            if (!empty($params['tipo_relacionamento'])) {
                $relacionamento = $params['tipo_relacionamento'];
            }
        }

        $joins = [
            [
                'table' => 'pda_config_regra_condicao',
                'alias' => 'PdaConfigRegraCondicao',
                'type' => $relacionamento,
                'conditions' => 'PdaConfigRegraCondicao.codigo_pda_config_regra = PdaConfigRegra.codigo AND PdaConfigRegraCondicao.ativo = 1',
            ],
        ];

        $fields = [
            "PdaConfigRegra.codigo",
            "PdaConfigRegra.codigo_cliente",
            "PdaConfigRegra.descricao",
            "PdaConfigRegra.codigo_pda_tema",
            "PdaConfigRegra.assunto",
            "PdaConfigRegra.mensagem",
            "PdaConfigRegra.codigo_acoes_melhorias_status",
            "PdaConfigRegraCondicao.codigo",
            "PdaConfigRegraCondicao.codigo_acoes_melhorias_status",
            "PdaConfigRegraCondicao.codigo_origem_ferramentas",
            "PdaConfigRegraCondicao.codigo_pos_criticidade",
            "PdaConfigRegraCondicao.qtd_dias",
            "PdaConfigRegraCondicao.condicao",
            "PdaConfigRegraCondicao.codigo_cliente_unidade",
        ];

        $conditions['PdaConfigRegra.codigo_pda_tema'] = $codigo_tema;
        $conditions['PdaConfigRegra.ativo'] = 1;

        if (!empty($params['codigo_cliente_matriz']) && !empty($params['codigo_cliente_unidade'])) {
            $conditions[] = "(PdaConfigRegra.codigo_cliente = " . $params['codigo_cliente_matriz'] . " OR PdaConfigRegraCondicao.codigo_cliente = " . $params['codigo_cliente_unidade'] . ")";
        } else if (!empty($params['codigo_cliente_matriz'])) {
            $conditions[] = "PdaConfigRegra.codigo_cliente = " . $params['codigo_cliente_matriz'];
        }

        if ($codigo_tema == 1) {
            $conditions[] = "PdaConfigRegra.codigo_acoes_melhorias_status = " . $params['codigo_acoes_melhorias_status'];
        }
        // else {
        //     if(!empty($params['codigo_acoes_melhorias_status'])) {
        //         $conditions[] = "(PdaConfigRegraCondicao.codigo_acoes_melhorias_status = " . $params['codigo_acoes_melhorias_status'].")" ;
        //     }
        // }

        if (isset($valor_dias) && !empty($valor_dias)) {
            $conditions['PdaConfigRegraCondicao.qtd_dias'] = $valor_dias;
        }

        //pega as configuracoes do tema em acao de melhoria
        $dados_regra = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);
        // ->sql();

        return $dados_regra;
    } //fim getDadostema

    public function getDadosRegraTimer($codigo_tema, $params, $valor_dias = null)
    {

        $relacionamento = 'LEFT';
        if (isset($params['tipo_relacionamento'])) {
            if (!empty($params['tipo_relacionamento'])) {
                $relacionamento = $params['tipo_relacionamento'];
            }
        }

        $joins = [
            [
                'table' => 'pda_config_regra_condicao',
                'alias' => 'PdaConfigRegraCondicao',
                'type' => $relacionamento,
                'conditions' => 'PdaConfigRegraCondicao.codigo_pda_config_regra = PdaConfigRegra.codigo AND PdaConfigRegraCondicao.ativo = 1',
            ],
        ];

        $fields = [
            "PdaConfigRegra.codigo",
            "PdaConfigRegra.codigo_cliente",
            "PdaConfigRegra.descricao",
            "PdaConfigRegra.codigo_pda_tema",
            "PdaConfigRegra.assunto",
            "PdaConfigRegra.mensagem",
            "PdaConfigRegra.codigo_acoes_melhorias_status",
            "PdaConfigRegraCondicao.codigo",
            "PdaConfigRegraCondicao.codigo_acoes_melhorias_status",
            "PdaConfigRegraCondicao.codigo_origem_ferramentas",
            "PdaConfigRegraCondicao.codigo_pos_criticidade",
            "PdaConfigRegraCondicao.qtd_dias",
            "PdaConfigRegraCondicao.condicao",
            "PdaConfigRegraCondicao.codigo_cliente_unidade",
        ];

        $conditions['PdaConfigRegra.codigo_pda_tema'] = $codigo_tema;
        $conditions['PdaConfigRegra.ativo'] = 1;

        if (!empty($params['codigo_cliente_matriz']) && !empty($params['codigo_cliente_unidade'])) {
            $conditions[] = "(PdaConfigRegra.codigo_cliente = " . $params['codigo_cliente_matriz'] . " OR PdaConfigRegraCondicao.codigo_cliente = " . $params['codigo_cliente_unidade'] . ")";
        } else if (!empty($params['codigo_cliente_matriz'])) {
            $conditions[] = "PdaConfigRegra.codigo_cliente = " . $params['codigo_cliente_matriz'];
        }

        if ($codigo_tema == 1) {
            $conditions[] = "PdaConfigRegra.codigo_acoes_melhorias_status = " . $params['codigo_acoes_melhorias_status'];
        }
        // else {
        //     if(!empty($params['codigo_acoes_melhorias_status'])) {
        //         $conditions[] = "(PdaConfigRegraCondicao.codigo_acoes_melhorias_status = " . $params['codigo_acoes_melhorias_status'].")" ;
        //     }
        // }

        if (isset($valor_dias) && !empty($valor_dias)) {
            $conditions['PdaConfigRegraCondicao.qtd_dias'] = $valor_dias;
        }

        //pega as configuracoes do tema em acao de melhoria
        $dados_regra = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);
        // ->sql();

        return $dados_regra;
    } //fim getDadostema    

    /**
     * [getEmAcaoDeMelhoria description]
     * @param  [type] $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmAcaoDeMelhoria(int $codigo_acao_melhoria)
    {
        $dados = false;

        try {
            //verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            //pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()->where(['codigo' => $codigo_acao_melhoria])->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];

                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;

                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade, 'codigo_acoes_melhorias_status' => $dados_acoes['codigo_acoes_melhorias_status']];

                //pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $dados_regra = $this->getDadosRegra(1, $param)->enableHydration(false)->all()->toArray();

                //varre as regras
                if (!empty($dados_regra)) {
                    if (BASE_URL == 'https://api.rhhealth.com.br') {
                        $url = "https://pos.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    } else {
                        $url = "https://tstpda.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    }

                    $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

                    //varre as regras
                    foreach ($dados_regra as $regra) {

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        //assunto da mensagem
                        $assunto = $regra['assunto'];
                        //troca a variavel caso exista
                        $mensagem = str_replace("[aqui]", $aqui, nl2br($regra['mensagem']));
                        //verifica se é email e push
                        $arr_params['assunto'] = $assunto;
                        $arr_params['mensagem'] = $mensagem;
                        $arr_params['codigo_cliente'] = (!empty($codigo_cliente_unidade)) ? $codigo_cliente_unidade : $codigo_cliente_matriz;
                        $arr_params['acao_melhoria'] = $dados_acoes;

                        //verifica se tem condicao cadastrada
                        if (!empty($regra['PdaConfigRegraCondicao']['codigo'])) {

                            //verifica se o status é igual a 3 em andamento
                            if ($dados_acoes['codigo_acoes_melhorias_status'] == 3 && $regra['codigo_acoes_melhorias_status'] == 3) {

                                //verificar os outros campos
                                if ($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'] == 3) { //verifica se é em andamento
                                    //nao pode estar vazio
                                    if (empty($regra['PdaConfigRegraCondicao']['condicao'])) {
                                        continue;
                                    }
                                    if ($regra['PdaConfigRegraCondicao']['qtd_dias'] == "") {
                                        continue;
                                    }

                                    if ($regra['PdaConfigRegraCondicao']['codigo_pos_criticidade'] > $dados_acoes['codigo_pos_criticidade']) {
                                        continue;
                                    }
                                    // echo "em andamento \n";

                                    $parametros = $regra['PdaConfigRegraCondicao'];
                                    $aplicaCondicao = $this->aplicaCondicao($parametros, $dados_acoes);

                                    // echo 'para enviar email ou nao: '.$aplicaCondicao."\n";

                                    //verifica se aplicaCondicoes é verdadeiro para enviar a acao
                                    if ($aplicaCondicao) {
                                        //busca os responsaveis pelo email/push                                        
                                        $this->acoesConfiguradas($regra['codigo'], $arr_params);
                                    } //fim aplicacaCondicao
                                } else if ($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'] == 13) { //verifica se é a vecer
                                    //nao pode estar vazio
                                    if (empty($regra['PdaConfigRegraCondicao']['qtd_dias'])) {
                                        continue;
                                    }

                                    //calcula as acoes de melhorias que estão a vencer daqui a 10 dias pela data de prazo
                                    $parametros = $regra['PdaConfigRegraCondicao'];
                                    $aplica_a_vencer = $this->aplicaAVencer($parametros, $dados_acoes);

                                    // echo "condicao a vencer {$aplica_a_vencer} \n\n";

                                    //verifica se aplicaCondicoes é verdadeiro para enviar a acao
                                    if ($aplica_a_vencer) {
                                        //busca os responsaveis pelo email/push                                        
                                        $this->acoesConfiguradas($regra['codigo'], $arr_params);
                                    } //fim aplicacaCondicao

                                } //fim validacao do status da regra

                            } //fim status em andamento
                            else {
                                //verifica se tem criticidade igual a acao de melhoria
                                if ($regra['PdaConfigRegraCondicao']['codigo_pos_criticidade'] == $dados_acoes['codigo_pos_criticidade']) {

                                    // echo $mensagem."<br><br><br><br>";

                                    //busca os responsaveis pelo email/push                                    
                                    $this->acoesConfiguradas($regra['codigo'], $arr_params);
                                } //fim criticidade
                            } //fim status em andamento
                        } //verificacao condicao
                    } //fim foreach regras
                } // fim foreach
            } //fim dados acoes

            $dados = true;
        } catch (Exception $e) {
            $dados = $e->getMessage();
            // $dados = false;
        }

        return $dados;
    } //fim getEmAcaoDeMelhoria()

    /**
     * [getEmAcaoDeMelhoria description]
     * @param  [type] $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmAcaoDeMelhoriaTimer(int $codigo_acao_melhoria)
    {
        $dados = false;

        try {
            //verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            //pega os dados de acao de melhoria
            $startTimerFetchAcoes = microtime(true);
            $dados_acoes = $this->AcoesMelhorias->find()->where(['codigo' => $codigo_acao_melhoria])->first();
            $endTimerFetchAcoes = microtime(true);

            $timerFetchAcoes = $endTimerFetchAcoes - $startTimerFetchAcoes;

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];

                $startTimerGetCodigoMatriz = microtime(true);
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;
                $endTimerGetCodigoMatriz = microtime(true);

                $timerGetCodigoMatriz = $endTimerGetCodigoMatriz - $startTimerGetCodigoMatriz;

                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade, 'codigo_acoes_melhorias_status' => $dados_acoes['codigo_acoes_melhorias_status']];

                //pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $startTimerFetchRegras = microtime(true);
                $dados_regra = $this->getDadosRegraTimer(1, $param)->enableHydration(false)->all()->toArray();
                $endTimerFetchRegras = microtime(true);

                $timerFetchRegras = $endTimerFetchRegras - $startTimerFetchRegras;

                //varre as regras
                if (!empty($dados_regra)) {
                    if (BASE_URL == 'https://api.rhhealth.com.br') {
                        $url = "https://pos.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    } else {
                        $url = "https://tstpda.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    }

                    $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

                    //varre as regras
                    $startTimerLoopRegras = microtime(true);
                    foreach ($dados_regra as $regra) {

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        //assunto da mensagem
                        $assunto = $regra['assunto'];
                        //troca a variavel caso exista
                        $mensagem = str_replace("[aqui]", $aqui, nl2br($regra['mensagem']));
                        //verifica se é email e push
                        $arr_params['assunto'] = $assunto;
                        $arr_params['mensagem'] = $mensagem;
                        $arr_params['codigo_cliente'] = (!empty($codigo_cliente_unidade)) ? $codigo_cliente_unidade : $codigo_cliente_matriz;
                        $arr_params['acao_melhoria'] = $dados_acoes;

                        //verifica se tem condicao cadastrada
                        if (!empty($regra['PdaConfigRegraCondicao']['codigo'])) {

                            //verifica se o status é igual a 3 em andamento
                            if ($dados_acoes['codigo_acoes_melhorias_status'] == 3 && $regra['codigo_acoes_melhorias_status'] == 3) {

                                //verificar os outros campos
                                if ($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'] == 3) { //verifica se é em andamento
                                    //nao pode estar vazio
                                    if (empty($regra['PdaConfigRegraCondicao']['condicao'])) {
                                        continue;
                                    }
                                    if ($regra['PdaConfigRegraCondicao']['qtd_dias'] == "") {
                                        continue;
                                    }

                                    if ($regra['PdaConfigRegraCondicao']['codigo_pos_criticidade'] > $dados_acoes['codigo_pos_criticidade']) {
                                        continue;
                                    }
                                    // echo "em andamento \n";

                                    $parametros = $regra['PdaConfigRegraCondicao'];

                                    $startTimerAplicaCondicao = microtime(true);
                                    $aplicaCondicao = $this->aplicaCondicao($parametros, $dados_acoes);
                                    $endTimerAplicaCondicao = microtime(true);

                                    $timerAplicaCondicao = $endTimerAplicaCondicao - $startTimerAplicaCondicao;

                                    // echo 'para enviar email ou nao: '.$aplicaCondicao."\n";

                                    //verifica se aplicaCondicoes é verdadeiro para enviar a acao
                                    if ($aplicaCondicao) {
                                        //busca os responsaveis pelo email/push
                                        $startTimerAcoesConfiguradas = microtime(true);
                                        $this->acoesConfiguradas($regra['codigo'], $arr_params);
                                        $endTimerAcoesConfiguradas = microtime(true);

                                        $timerAcoesConfiguradas = $endTimerAcoesConfiguradas - $startTimerAcoesConfiguradas;
                                    } //fim aplicacaCondicao
                                } else if ($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'] == 13) { //verifica se é a vecer
                                    //nao pode estar vazio
                                    if (empty($regra['PdaConfigRegraCondicao']['qtd_dias'])) {
                                        continue;
                                    }

                                    //calcula as acoes de melhorias que estão a vencer daqui a 10 dias pela data de prazo
                                    $parametros = $regra['PdaConfigRegraCondicao'];
                                    $startAplicaAVencer = microtime(true);
                                    $aplica_a_vencer = $this->aplicaAVencer($parametros, $dados_acoes);
                                    $endAplicaAVencer = microtime(true);

                                    $timerAplicaAVencer = $endAplicaAVencer - $startAplicaAVencer;

                                    // echo "condicao a vencer {$aplica_a_vencer} \n\n";

                                    //verifica se aplicaCondicoes é verdadeiro para enviar a acao
                                    if ($aplica_a_vencer) {
                                        //busca os responsaveis pelo email/push   
                                        $startTimerAcoesConfiguradasAVencer = microtime(true);
                                        $timersAcoesConfiguradas = $this->acoesConfiguradasTimer($regra['codigo'], $arr_params);
                                        $endTimerAcoesConfiguradasAVencer = microtime(true);

                                        $timerAcoesConfiguradasAVencer = $endTimerAcoesConfiguradasAVencer - $startTimerAcoesConfiguradasAVencer;
                                    } //fim aplicacaCondicao

                                } //fim validacao do status da regra

                            } //fim status em andamento
                            else {
                                //verifica se tem criticidade igual a acao de melhoria
                                if ($regra['PdaConfigRegraCondicao']['codigo_pos_criticidade'] == $dados_acoes['codigo_pos_criticidade']) {

                                    // echo $mensagem."<br><br><br><br>";

                                    //busca os responsaveis pelo email/push  
                                    $startTimerAcoesConfiguradas = microtime(true);
                                    $timersAcoesConfiguradas = $this->acoesConfiguradasTimer($regra['codigo'], $arr_params);
                                    $endTimerAcoesConfiguradas = microtime(true);

                                    $timerAcoesConfiguradas = $endTimerAcoesConfiguradas - $startTimerAcoesConfiguradas;
                                } //fim criticidade
                            } //fim status em andamento
                        } //verificacao condicao
                    } //fim foreach regras
                    $endTimerLoopRegras = microtime(true);

                    $timerLoopRegras = $endTimerLoopRegras - $startTimerLoopRegras;
                } // fim foreach
            } //fim dados acoes

        } catch (Exception $e) {
            $dados = $e->getMessage();
            // $dados = false;
        }

        $dados = array(
            // 'timerLoopRegras' => $timerLoopRegras,
            // 'timerAcoesConfiguradas' => $timerAcoesConfiguradas,
            // 'timerAcoesConfiguradasAVencer' => $timerAcoesConfiguradasAVencer,
            // 'timerAplicaCondicao' => $timerAplicaCondicao,
            // 'timerAplicaAVencer' => $timerAplicaAVencer,
            // 'timerAplicaCondicao' => $timerAplicaCondicao,
        );

        if (isset($timerFetchAcoes)) {
            $dados['timerFetchAcoes'] = $timerFetchAcoes;
        }

        if ($timerGetCodigoMatriz) {
            $dados['timerGetCodigoMatriz'] = $timerGetCodigoMatriz;
        }

        if ($timerFetchRegras) {
            $dados['timerFetchRegras'] = $timerFetchRegras;
        }

        if (isset($timerLoopRegras)) {
            $dados['timerLoopRegras'] = $timerLoopRegras;
        }

        if (isset($timerAcoesConfiguradas)) {
            $dados['timerAcoesConfiguradas'] = $timerAcoesConfiguradas;
        }

        if (isset($timerAcoesConfiguradasAVencer)) {
            $dados['timerAcoesConfiguradasAVencer'] = $timerAcoesConfiguradasAVencer;
        }

        if (isset($timerAplicaCondicao)) {
            $dados['timerAplicaCondicao'] = $timerAplicaCondicao;
        }

        if (isset($timerAplicaAVencer)) {
            $dados['timerAplicaAVencer'] = $timerAplicaAVencer;
        }

        if (isset($timersAcoesConfiguradas)) {
            $dados['timersAcoesConfiguradas'] = $timersAcoesConfiguradas;
        }

        return $dados;
    } //fim getEmAcaoDeMelhoria()    

    /**
     * [getEmailUsuarioFuncionario pega o email do usuario ou funcionario]
     * @return [type] [description]
     */
    public function getEmailUsuarioFuncionario($codigo_usuario = null)
    {

        $email = null;

        if (is_null($codigo_usuario)) {
            return $email;
        }

        //pega os dados do usuario
        $usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);
        //verifica se tem dados
        if (!empty($usuario)) {
            //verifica se tem email do usuario
            if (!empty($usuario->email)) {
                $email = $usuario->email;
            } //fim usuario email
            else {
                //verfica se tem usuario
                if (!empty($usuario->codigo_funcionario)) {
                    //pega o codigo_funcionario
                    $codigo_funcionario = $usuario->codigo_funcionario;

                    $email_funcionario = $this->FuncionariosContatos->find()->select(['descricao'])->where(['codigo_funcionario' => $codigo_funcionario, 'codigo_tipo_retorno' => 2])->first();
                    $email = $email_funcionario->descricao;
                } //fim codigo funcionario
            } //fim else
        } //verifica se tem dados do usuario

        return $email;
    } //fim getEmailUsuarioFuncionario

    /**
     * [Metodo para consultar os gestores diretos pelo código da ação de melhoria e código do usuário responsável]
     * @param  [int] $codigo_acao_melhoria          [Código da ação de melhoria]
     * @param  [int] $codigo_usuario_responsavel    [Código do usuário responsável]
     * @param  [int] $nivel_gestor                  [Nível do gestor]
     * @return [array]                              [Retorna os usuário encontrado com seus respectivos dados]
     */
    public function getEmailGestorDireto(
        int $codigo_acao_melhoria = null,
        int $codigo_usuario_responsavel = null,
        int $nivel_gestor = null
    ) {
        try {
            if (is_null($codigo_usuario_responsavel) || $codigo_usuario_responsavel === 0 || is_null($nivel_gestor)) {
                if (!is_null($codigo_acao_melhoria) && !is_null($nivel_gestor)) {
                    /*
                    Se o código de usuário responsável for nulo,
                    verificar se existe uma solicitação aberta com o código do provável responsável
                     */
                    $solicitacao = $this->AcoesMelhoriasSolicitacoes->find()
                        ->select(['codigo_novo_usuario_responsavel'])
                        ->where([
                            "codigo_acao_melhoria = $codigo_acao_melhoria",
                            'data_remocao IS NULL',
                            'status = 1',
                            'codigo_acao_melhoria_solicitacao_tipo = 1',
                            'codigo_novo_usuario_responsavel IS NOT NULL',
                        ])
                        ->hydrate(false)
                        ->first();

                    if (isset($solicitacao['codigo_novo_usuario_responsavel'])) {
                        $codigo_usuario_responsavel = (int) $solicitacao['codigo_novo_usuario_responsavel'];
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }

            $email_gestores = array();

            # Consultar a matricula e codigo cliente do gestor direto
            $gestor_usuario = $this->Usuario->getManagerByUserId($codigo_usuario_responsavel);

            if (is_null($gestor_usuario)) {
                return null;
            }

            # Consultar gestores
            $data = $this->getGestores(
                6,
                1,
                (int) $gestor_usuario['codigo_cliente'],
                (string) $gestor_usuario['matricula']
            );

            if (isset($data[$nivel_gestor])) {
                $email_gestores[] = $data[$nivel_gestor]['email'];
            }

            return count($email_gestores) > 0 ? $email_gestores : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * [Metodo para consultar os gestores diretos pelo código da ação de melhoria e código do usuário responsável]
     * @param  [int] $codigo_acao_melhoria          [Código da ação de melhoria]
     * @param  [int] $codigo_usuario_responsavel    [Código do usuário responsável]
     * @param  [int] $nivel_gestor                  [Nível do gestor]
     * @return [array]                              [Retorna os usuário encontrado com seus respectivos dados]
     */
    public function getTokenPushGestorDireto(
        int $codigo_acao_melhoria = null,
        int $codigo_usuario_responsavel = null,
        int $nivel_gestor = null
    ) {
        try {
            if (is_null($codigo_usuario_responsavel) || $codigo_usuario_responsavel === 0 || is_null($nivel_gestor)) {
                if (!is_null($codigo_acao_melhoria) && !is_null($nivel_gestor)) {
                    /*
                    Se o código de usuário responsável for nulo,
                    verificar se existe uma solicitação aberta com o código do provável responsável
                     */
                    $solicitacao = $this->AcoesMelhoriasSolicitacoes->find()
                        ->select(['codigo_novo_usuario_responsavel'])
                        ->where([
                            "codigo_acao_melhoria = $codigo_acao_melhoria",
                            'data_remocao IS NULL',
                            'status = 1',
                            'codigo_acao_melhoria_solicitacao_tipo = 1',
                            'codigo_novo_usuario_responsavel IS NOT NULL',
                        ])
                        ->hydrate(false)
                        ->first();

                    if (isset($solicitacao['codigo_novo_usuario_responsavel'])) {
                        $codigo_usuario_responsavel = (int) $solicitacao['codigo_novo_usuario_responsavel'];
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            }

            $usuario = null;

            # Consultar a matricula e codigo cliente do gestor direto
            $gestor_usuario = $this->Usuario->getManagerByUserId($codigo_usuario_responsavel);

            if (is_null($gestor_usuario)) {
                return null;
            }

            # Consultar gestores
            $data = $this->getGestores(6, 1, (int) $gestor_usuario['codigo_cliente'], (string) $gestor_usuario['matricula'], [], true);

            if (isset($data[$nivel_gestor])) {
                $usuario = $data[$nivel_gestor];
            }

            return !empty($usuario) ? $usuario : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * [Metodo para consultar os responsáveis da área de um determinado cliente que possuem dados de notificação PUSH]
     * @param  [int] $codigo_cliente    [Código do cliente]
     * @return [array]                  [Retorna os usuários encontrados com seus respectivos dados]
     */
    public function getTokenPushResponsaveisArea(int $codigo_cliente)
    {
        $usuarios = $this->UsuariosResponsaveis->find()
            ->select([
                'codigo_usuario' => 'UsuariosResponsaveis.codigo_usuario',
                'telefone' => 'UsuarioSistema.celular',
                'token' => 'UsuarioSistema.token_push',
                'plataforma' => 'UsuarioSistema.platform',
            ])
            ->join([
                [
                    'table' => 'usuario_sistema',
                    'alias' => 'UsuarioSistema',
                    'type' => 'INNER',
                    'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosResponsaveis.codigo_usuario',
                ],
            ])
            ->where([
                'UsuariosResponsaveis.codigo_cliente' => $codigo_cliente,
                'UsuariosResponsaveis.data_remocao IS NULL',
                'UsuarioSistema.token_push IS NOT NULL',
                'UsuarioSistema.platform IS NOT NULL',
                'UsuarioSistema.ativo = 1',
                'UsuarioSistema.codigo_sistema = 8',
            ])
            ->enableHydration(false)
            ->all()
            ->toArray();

        return count($usuarios) > 0 ? $usuarios : null;
    }

    /**
     * [Metodo para consultar os gestores de um determinado usuário por meio da matricula e código do cliente]
     * @param  [int] $nivel_maximo      [Nivel maximo de busca dos gestores]
     * @param  [int] $nivel_atual       [Utilizado para contagem]
     * @param  [int] $codigo_cliente    [Código do cliente vinculado ao usuário]
     * @param  [string] $matricula      [Matricula do usuário]
     * @param  [array] $gestores        [Gestores que foram encontrados em execução]
     * @param  [bool] $notificacao_push [Utilizado para trazer os campos para a notificação PUSH]
     * @return [array]                  [Retorna os usuários encontrados com seus respectivos dados]
     */
    private function getGestores(
        int $nivel_maximo = 6,
        int $nivel_atual = 1,
        int $codigo_cliente = null,
        string $matricula = null,
        array $gestores = [],
        bool $notificacao_push = false
    ) {
        $registro = $this->Usuario->getEmployee($matricula, $codigo_cliente, $notificacao_push);

        if (is_null($registro)) {
            array_push($gestores, null);
        } else {
            array_push($gestores, $registro);
        }

        if ($nivel_maximo === $nivel_atual) {
            return $gestores;
        }

        $nivel_atual += 1;

        return $this->getGestores(
            $nivel_maximo,
            $nivel_atual,
            isset($registro['codigo_cliente_chefia_imediata'])
                ? (int) $registro['codigo_cliente_chefia_imediata']
                : null,
            isset($registro['matricula_chefia_imediata'])
                ? $registro['matricula_chefia_imediata']
                : null,
            $gestores,
            $notificacao_push
        );
    }

    /**
     * [getEmailUsuarioInclusao metodo para pegar o email do usuario inclusao ou do funcionario relacionado ao usuario]
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getEmailUsuarioInclusao($codigo_usuario)
    {
        $email[] = $this->getEmailUsuarioFuncionario($codigo_usuario);
        return $email;
    } //fim getEmailUsuarioInclusao($codigo_usuario)

    /**
     * [getEmailUsuarioResponsavel metodo para pegar o email do usuario responsavel pela acao ou do funcionario relacionado ao usuario
     * seguindo a regra de buscar inicialmente na tabela acoes_melhorias_solicitacoes caso não tenha buscar o usuario_reponsavel da acoes de melhoria
     * ]
     * @param  [type] $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmailUsuarioResponsavel($acao_melhoria)
    {
        $email = array();
        //pegar da tabela acoes_melhorias_solicitacoes
        //para o responsavel olhar na acoes_melhorias_solicitacoes primeiro e depois se não tiver na acoes_melhorias campo usuario_reposnsavel
        $usuario_solicitacao = $this->AcoesMelhoriasSolicitacoes->find()
            ->select(['codigo_novo_usuario_responsavel'])
            ->where([
                'codigo_acao_melhoria' => $acao_melhoria['codigo'],
                'status' => 1,
                'codigo_acao_melhoria_solicitacao_tipo' => 1,
                'data_remocao IS NULL',
            ])
            ->first();

        //verifica se tem dados da solicitacao
        if (!empty($usuario_solicitacao)) {
            $email[] = $this->getEmailUsuarioFuncionario($usuario_solicitacao->codigo_novo_usuario_responsavel);
        } else {
            if (!empty($acao_melhoria['codigo_usuario_responsavel'])) {
                $email[] = $this->getEmailUsuarioFuncionario($acao_melhoria['codigo_usuario_responsavel']);
            }
        }

        return $email;
    } //getEmailUsuarioResponsavel($codigo_acao_melhoria)

    /**
     * [getEmailUsuarioResponsavelArea pegar o usuario responsavel da area pelo codigo do cliente, onde o usuario não tiver o email buscar do email do funcionario caso consiga relacionar]
     * @param  [type] $codigo_cliente [description]
     * @return [type]                 [description]
     */
    public function getEmailUsuarioResponsavelArea($codigo_cliente)
    {
        $email = array();

        $dados_ur = $this->UsuariosResponsaveis->find()->select(['codigo_usuario'])->where(['codigo_cliente' => $codigo_cliente, 'data_remocao IS NULL'])->enableHydration(false)->all();

        if (!empty($dados_ur)) {

            foreach ($dados_ur->toArray() as $val) {
                $email[] = $this->getEmailUsuarioFuncionario($val['codigo_usuario']);
            }
        }

        return $email;
    } // fim getEmailUsuarioResponsavelArea($codigo_cliente)

    /**
     * [acoesConfiguradas description]
     * @param  [type] $codigo_pda_config_regra [description]
     * @param  [type] $assunto                 [description]
     * @param  [type] $mensagem                [description]
     * @return [type]                          [description]
     */
    public function acoesConfiguradasTimer(int $codigo_pda_config_regra = null, array $parametros = [])
    {
        if (empty($parametros['assunto']) || empty($parametros['mensagem']) || empty($codigo_pda_config_regra)) {
            return;
        }

        # Consultar configurações
        $startTimerFetchConfigRegraAcao = microtime(true);
        $configuracoes = $this->PdaConfigRegraAcao->find()
            ->where([
                'codigo_pda_config_regra' => $codigo_pda_config_regra,
                'ativo' => 1,
            ])
            ->enableHydration(false)
            ->all()
            ->toArray();

        $endTimerFetchConfigRegraAcao = microtime(true);

        $timerFetchConfigRegraAcao = $endTimerFetchConfigRegraAcao - $startTimerFetchConfigRegraAcao;

        $emails = [];
        $usuarios = [];

        /*
         * 3: Gestor direto
         * 6: Gestor direto 1
         * 7: Gestor direto 2
         * 8: Gestor direto 3
         * 9: Gestor direto 4
         * 10: Gestor direto 5
         */
        $acao_tipo_gestores = array(3, 6, 7, 8, 9, 10);

        if (!empty($configuracoes)) {
            foreach ($configuracoes as $configuracao) {
                $tipo_acao = (int) $configuracao['tipo_acao'];

                # Verificar se foi configurado como gestor direto e pegar o nivel do mesmo
                $nivel_gestor = array_search($tipo_acao, $acao_tipo_gestores);

                /*
                 * 1: E-mail
                 * 2: Notificação via Push
                 */
                if ($configuracao['codigo_pda_tema_acoes'] === 1) {
                    switch ($tipo_acao) {
                            # Usuário que cadastrou
                        case 1:
                            $startTimerGetEmailUsuario = microtime(true);
                            $email = $this->getEmailUsuarioInclusao($parametros['acao_melhoria']['codigo_usuario_inclusao']);
                            $endTimerGetEmailUsuario = microtime(true);

                            $timerGetEmailUsuario = $endTimerGetEmailUsuario - $startTimerGetEmailUsuario;

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # Responsável
                        case 2:
                            $startTimerGetEmailUsuario = microtime(true);
                            $email = $this->getEmailUsuarioResponsavel($parametros['acao_melhoria']);
                            $endTimerGetEmailUsuario = microtime(true);

                            $timerGetEmailUsuario = $endTimerGetEmailUsuario - $startTimerGetEmailUsuario;

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # E-mail cadastrado
                        case 4:
                            if (
                                !empty($configuracao['email'])
                                && filter_var($configuracao['email'], FILTER_VALIDATE_EMAIL)
                            ) {
                                array_push($emails, $configuracao['email']);
                            }
                            break;
                            # Responsáveis da área
                        case 5:
                            $startTimerGetEmailUsuario = microtime(true);
                            $email = $this->getEmailUsuarioResponsavelArea($parametros['codigo_cliente']);
                            $endTimerGetEmailUsuario = microtime(true);

                            $timerGetEmailUsuario = $endTimerGetEmailUsuario - $startTimerGetEmailUsuario;

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # Gestor
                        case 3:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        case 10:
                            $startTimerGetEmailUsuario = microtime(true);
                            $email = $this->getEmailGestorDireto(
                                (int) $parametros['acao_melhoria']['codigo'],
                                (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'],
                                $nivel_gestor
                            );
                            $endTimerGetEmailUsuario = microtime(true);

                            $timerGetEmailUsuario = $endTimerGetEmailUsuario - $startTimerGetEmailUsuario;

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                    }
                } else if ($configuracao['codigo_pda_tema_acoes'] === 2) {
                    switch ($tipo_acao) {
                            # Usuário que cadastrou
                        case 1:
                            $startTimerGetUsuario = microtime(true);
                            $usuario = $this->Usuario->getUserToPushNotification((int) $parametros['acao_melhoria']['codigo_usuario_inclusao']);
                            $endTimerGetUsuario = microtime(true);

                            $timerGetUsuario = $endTimerGetUsuario - $startTimerGetUsuario;

                            if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                $usuarios[$usuario['codigo_usuario']] = $usuario;
                            }
                            break;
                            # Responsável
                        case 2:
                            $codigo_usuario_responsavel = null;

                            if (!empty($parametros['acao_melhoria']['codigo_usuario_responsavel'])) {
                                $codigo_usuario_responsavel = (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'];
                            } else {
                                $startTimerGetUsuario = microtime(true);
                                $usuario_solicitacao = $this->AcoesMelhoriasSolicitacoes->find()
                                    ->select(['codigo_novo_usuario_responsavel'])
                                    ->where([
                                        'codigo_acao_melhoria' => (int) $parametros['acao_melhoria']['codigo'],
                                        'status' => 1,
                                        'codigo_acao_melhoria_solicitacao_tipo' => 1,
                                        'data_remocao IS NULL',
                                    ])
                                    ->first();

                                $endTimerGetUsuario = microtime(true);

                                $timerGetUsuario = $endTimerGetUsuario - $startTimerGetUsuario;

                                if (!empty($usuario_solicitacao)) {
                                    $codigo_usuario_responsavel = (int) $usuario_solicitacao->codigo_novo_usuario_responsavel;
                                }
                            }

                            $usuario = $this->Usuario->getUserToPushNotification($codigo_usuario_responsavel);

                            if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                $usuarios[$usuario['codigo_usuario']] = $usuario;
                            }
                            break;
                            # Responsáveis da área
                        case 5:
                            $startTimerGetTokenResponsaveisArea = microtime(true);
                            $usuariosResponsaveis = $this->getTokenPushResponsaveisArea($parametros['codigo_cliente']);
                            $endTimerGetTokenResponsaveisArea = microtime(true);

                            $timerGetTokenResponsaveisArea = $endTimerGetTokenResponsaveisArea - $startTimerGetTokenResponsaveisArea;

                            if (!empty($usuariosResponsaveis)) {
                                foreach ($usuariosResponsaveis as $usuario) {
                                    if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                        $usuarios[$usuario['codigo_usuario']] = $usuario;
                                    }
                                }
                            }
                            break;
                            # Gestor
                        case 3:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        case 10:
                            $startTimerGetTokenGestor = microtime(true);
                            $usuario = $this->getTokenPushGestorDireto(
                                (int) $parametros['acao_melhoria']['codigo'],
                                (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'],
                                $nivel_gestor
                            );
                            $endTimerGetTokenGestor = microtime(true);

                            $timerGetTokenGestor = $endTimerGetTokenGestor - $startTimerGetTokenGestor;

                            if (!empty($usuario)) {
                                # Remover dados desnecessários
                                unset($usuario['codigo_cliente_chefia_imediata']);
                                unset($usuario['matricula_chefia_imediata']);
                                unset($usuario['matricula']);
                                unset($usuario['codigo_cliente']);

                                if (!array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                    $usuarios[$usuario['codigo_usuario']] = $usuario;
                                }
                            }
                            break;
                    }
                }
            }


            # Verificar se existe e-mails para serem enviados e enviá-los
            if (!empty($emails)) {
                $emails = array_values(array_unique($emails, SORT_STRING));

                foreach ($emails as $email) {
                    $startTimerEnviarEmail = microtime(true);
                    $this->enviaEmail('vinicius.dias.ext@ithealth.com.br', $parametros['assunto'], $parametros['mensagem']);
                    $endTimerEnviarEmail = microtime(true);

                    $timerEnviarEmail = $endTimerEnviarEmail - $startTimerEnviarEmail;
                }
            }

            # Verificar se existe usuários para o envio das notificações e cadastrar as notificações
            if (!empty($usuarios)) {
                foreach ($usuarios as $usuario) {
                    $startTimerCadastrarNotificacao = microtime(true);
                    $usuario['codigo_usuario'] = 82117;
                    $usuario['token'] = 'xxxxxxxxxxxxxx';
                    $usuario['telefone'] = null;
                    $this->cadastrarNotificacao($usuario, $parametros['assunto'], $parametros['mensagem'], (int) $parametros['acao_melhoria']['codigo']);
                    $endTimerCadastrarNotificacao = microtime(true);

                    $timerCadastrarNotificacao = $endTimerCadastrarNotificacao - $startTimerCadastrarNotificacao;
                }
            }
        }

        $timers = array();

        if (isset($timerFetchConfigRegraAcao)) {
            $timers['timerFetchConfigRegraAcao'] = $timerFetchConfigRegraAcao;
        }

        if (isset($timerGetUsuario)) {
            $timers['getUsuario'] = $timerGetUsuario;
        }

        if (isset($timerGetEmailUsuario)) {
            $timers['getEmailUsuario'] = $timerGetEmailUsuario;
        }


        if (isset($timerGetTokenResponsaveisArea)) {
            $timers['getTokenResponsaveisArea'] = $timerGetTokenResponsaveisArea;
        }

        if (isset($timerGetTokenGestor)) {
            $timers['getTokenGestor'] = $timerGetTokenGestor;
        }

        if (isset($timerEnviarEmail)) {
            $timers['enviarEmail'] = $timerEnviarEmail;
        }

        return $timers;
    }

    /**
     * [acoesConfiguradas description]
     * @param  [type] $codigo_pda_config_regra [description]
     * @param  [type] $assunto                 [description]
     * @param  [type] $mensagem                [description]
     * @return [type]                          [description]
     */
    public function acoesConfiguradas(int $codigo_pda_config_regra = null, array $parametros = [])
    {
        if (empty($parametros['assunto']) || empty($parametros['mensagem']) || empty($codigo_pda_config_regra)) {
            return;
        }

        # Consultar configurações
        $configuracoes = $this->PdaConfigRegraAcao->find()
            ->where([
                'codigo_pda_config_regra' => $codigo_pda_config_regra,
                'ativo' => 1,
            ])
            ->enableHydration(false)
            ->all()
            ->toArray();

        $emails = [];
        $usuarios = [];

        /*
         * 3: Gestor direto
         * 6: Gestor direto 1
         * 7: Gestor direto 2
         * 8: Gestor direto 3
         * 9: Gestor direto 4
         * 10: Gestor direto 5
         */
        $acao_tipo_gestores = array(3, 6, 7, 8, 9, 10);

        if (!empty($configuracoes)) {
            foreach ($configuracoes as $configuracao) {
                $tipo_acao = (int) $configuracao['tipo_acao'];

                # Verificar se foi configurado como gestor direto e pegar o nivel do mesmo
                $nivel_gestor = array_search($tipo_acao, $acao_tipo_gestores);

                /*
                 * 1: E-mail
                 * 2: Notificação via Push
                 */
                if ($configuracao['codigo_pda_tema_acoes'] === 1) {
                    switch ($tipo_acao) {
                            # Usuário que cadastrou
                        case 1:
                            $email = $this->getEmailUsuarioInclusao($parametros['acao_melhoria']['codigo_usuario_inclusao']);

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # Responsável
                        case 2:
                            $email = $this->getEmailUsuarioResponsavel($parametros['acao_melhoria']);

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # E-mail cadastrado
                        case 4:
                            if (
                                !empty($configuracao['email'])
                                && filter_var($configuracao['email'], FILTER_VALIDATE_EMAIL)
                            ) {
                                array_push($emails, $configuracao['email']);
                            }
                            break;
                            # Responsáveis da área
                        case 5:
                            $email = $this->getEmailUsuarioResponsavelArea($parametros['codigo_cliente']);

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                            # Gestor
                        case 3:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        case 10:
                            $email = $this->getEmailGestorDireto(
                                (int) $parametros['acao_melhoria']['codigo'],
                                (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'],
                                $nivel_gestor
                            );

                            if (!empty($email)) {
                                $emails = array_merge($emails, $email);
                            }
                            break;
                    }
                } else if ($configuracao['codigo_pda_tema_acoes'] === 2) {
                    switch ($tipo_acao) {
                            # Usuário que cadastrou
                        case 1:
                            $usuario = $this->Usuario->getUserToPushNotification((int) $parametros['acao_melhoria']['codigo_usuario_inclusao']);

                            if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                $usuarios[$usuario['codigo_usuario']] = $usuario;
                            }
                            break;
                            # Responsável
                        case 2:
                            $codigo_usuario_responsavel = null;

                            if (!empty($parametros['acao_melhoria']['codigo_usuario_responsavel'])) {
                                $codigo_usuario_responsavel = (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'];
                            } else {
                                $usuario_solicitacao = $this->AcoesMelhoriasSolicitacoes->find()
                                    ->select(['codigo_novo_usuario_responsavel'])
                                    ->where([
                                        'codigo_acao_melhoria' => (int) $parametros['acao_melhoria']['codigo'],
                                        'status' => 1,
                                        'codigo_acao_melhoria_solicitacao_tipo' => 1,
                                        'data_remocao IS NULL',
                                    ])
                                    ->first();

                                if (!empty($usuario_solicitacao)) {
                                    $codigo_usuario_responsavel = (int) $usuario_solicitacao->codigo_novo_usuario_responsavel;
                                }
                            }

                            $usuario = $this->Usuario->getUserToPushNotification($codigo_usuario_responsavel);

                            if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                $usuarios[$usuario['codigo_usuario']] = $usuario;
                            }
                            break;
                            # Responsáveis da área
                        case 5:
                            $usuariosResponsaveis = $this->getTokenPushResponsaveisArea($parametros['codigo_cliente']);

                            if (!empty($usuariosResponsaveis)) {
                                foreach ($usuariosResponsaveis as $usuario) {
                                    if (!empty($usuario) && !array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                        $usuarios[$usuario['codigo_usuario']] = $usuario;
                                    }
                                }
                            }
                            break;
                            # Gestor
                        case 3:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                        case 10:
                            $usuario = $this->getTokenPushGestorDireto(
                                (int) $parametros['acao_melhoria']['codigo'],
                                (int) $parametros['acao_melhoria']['codigo_usuario_responsavel'],
                                $nivel_gestor
                            );

                            if (!empty($usuario)) {
                                # Remover dados desnecessários
                                unset($usuario['codigo_cliente_chefia_imediata']);
                                unset($usuario['matricula_chefia_imediata']);
                                unset($usuario['matricula']);
                                unset($usuario['codigo_cliente']);

                                if (!array_key_exists($usuario['codigo_usuario'], $usuarios)) {
                                    $usuarios[$usuario['codigo_usuario']] = $usuario;
                                }
                            }
                            break;
                    }
                }
            }


            # Verificar se existe e-mails para serem enviados e enviá-los
            if (!empty($emails)) {
                $emails = array_values(array_unique($emails, SORT_STRING));

                foreach ($emails as $email) {
                    $this->enviaEmail($email, $parametros['assunto'], $parametros['mensagem']);
                }
            }

            # Verificar se existe usuários para o envio das notificações e cadastrar as notificações
            if (!empty($usuarios)) {
                foreach ($usuarios as $usuario) {
                    $this->cadastrarNotificacao($usuario, $parametros['assunto'], $parametros['mensagem'], (int) $parametros['acao_melhoria']['codigo']);
                }
            }
        }
    }


    /**
     * [enviaEmail metdo para disparar o email com o assunto configurado e a mensagem]
     * @param  [type] $email    [description]
     * @param  [type] $assunto  [description]
     * @param  [type] $mensagem [description]
     * @return [type]           [description]
     */
    public function enviaEmail($email, $assunto, $mensagem)
    {

        $default_charset = 'UTF-8';
        ini_set('default_charset', $default_charset);

        $assunto_html = $assunto;

        $mensagem = $this->convertAcentuacaoParaHtml($mensagem);

        $mensagem_html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' .
            '<html xmlns="http://www.w3.org/1999/xhtml">' .
            '<head>' .
            '<meta http-equiv="Content-Type" content="text/html; charset=' . $default_charset . '" />' .
            '<meta charset="' . $default_charset . '">' .
            '</head>' .
            '<body>' .
            '<div style="clear: both;padding-top: 50px;padding-left: 50px;width: 98.4%;min-height: 300px;">' .
            $mensagem .
            '<br />' .
            '<br />' .
            '<br />' .
            'Atenciosamente<br />' .
            '<b>Equipe RH Health</b><br />' .
            'Tel. 0800-014-2659<br />' .
            '<a href="https://rhhealth.com.br/" target="_blank">www.rhhealth.com.br</a><br />' .

            '</div>' .
            '<div><br /><br /></div>' .
            '</body>' .
            '</html>';

        // Esta dando erro ao tentar usar a MailerOutboxTable e por isso foi feito o insert em sql
        $conn = ConnectionManager::get('default');
        $insert = "INSERT INTO mailer_outbox ([to],[subject],[content],[from],[created],[modified]) VALUES ('$email','$assunto_html','$mensagem_html','portal@rhhealth.com.br','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "')";
        return $conn->execute($insert);
    } //fim enviaEmail

    /**
     * [Metodo para cadastrar uma notificação a um determinado usuário]
     * @param  [array] $usuario             [Código da ação de melhoria]
     * @param  [int] $titulo                [Código do usuário responsável]
     * @param  [int] $descricao             [Nível do gestor]
     * @param  [int] $codigo_acao_melhoria  [Código da ação de melhoria]
     * @return [array]                      [Retorna os usuário encontrado com seus respectivos dados]
     */
    public function cadastrarNotificacao(
        array $usuario = [],
        string $titulo = null,
        string $descricao = null,
        int $codigo_acao_melhoria = null
    ) {

        try {
            if (empty($usuario)) {
                throw new Exception('Dados do usuário não foram informados');
            }

            if (empty($titulo) || empty($descricao) || empty($codigo_acao_melhoria)) {
                throw new Exception('Não foi informado os dados da mensagem');
            }

            $descricao_push = str_replace(' .', '.', str_replace('aqui', '', strip_tags($descricao)));

            $url = null;

            if (BASE_URL == 'https://api.rhhealth.com.br') {
                $url = "https://pos.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
            } else {
                $url = "https://tstpda.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
            }

            $dados = [
                'codigo_key' => 4, // Alterar posteriomente para o codigo do projeto POS
                'token' => $usuario['token'], // Installation id
                'fone_para' => is_null($usuario['telefone']) ? 'POS' : $usuario['telefone'],
                'titulo' => $titulo,
                'mensagem' => $descricao_push,
                'extra_data' => json_encode([
                    'url' => $url,
                    'description' => $descricao,
                ]),
                'codigo_usuario_inclusao' => 1, // operacao
                'data_inclusao' => date('Y-m-d H:i:s'),
                'sistema_origem' => 'POS',
                // 'modulo_origem' => __CLASS__,
                'platform' => $usuario['plataforma'],
                // 'foreign_key' => '',
                // 'model' => __CLASS__,
                'codigo_usuario' => $usuario['codigo_usuario'],
            ];

            $notificacao = $this->PushOutbox->newEntity($dados);

            if (!$this->PushOutbox->save($notificacao)) {
                return false;
            }

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * [Metodo para cadastrar uma notificação a um determinado usuário]
     * @param  [array] $usuario             [Código da ação de melhoria]
     * @param  [int] $titulo                [Código do usuário responsável]
     * @param  [int] $descricao             [Nível do gestor]
     * @param  [string] $link               [Link PWA]
     * @return [array]                      [Retorna os usuário encontrado com seus respectivos dados]
     */
    public function cadastrarNotificacaoGenerica(
        array $usuario = [],
        string $titulo = null,
        string $descricao = null,
        string $link = null
    ) {
        try {
            if (empty($usuario)) {
                throw new Exception('Dados do usuário não foram informados');
            }

            if (empty($titulo) || empty($descricao)) {
                throw new Exception('Não foi informado os dados da mensagem');
            }

            $descricao_push = str_replace(' .', '.', str_replace('aqui', '', strip_tags($descricao)));

            $dados = [
                'codigo_key' => 4, // Alterar posteriomente para o codigo do projeto POS
                'token' => $usuario['token'], // Installation id
                'fone_para' => is_null($usuario['telefone']) ? 'POS' : $usuario['telefone'],
                'titulo' => $titulo,
                'mensagem' => $descricao_push,
                'extra_data' => json_encode([
                    'url' => $link,
                    'description' => $descricao,
                ]),
                'codigo_usuario_inclusao' => 1, // operacao
                'data_inclusao' => date('Y-m-d H:i:s'),
                'sistema_origem' => 'POS',
                // 'modulo_origem' => __CLASS__,
                'platform' => $usuario['plataforma'],
                // 'foreign_key' => '',
                // 'model' => __CLASS__,
                'codigo_usuario' => $usuario['codigo_usuario'],
            ];

            $notificacao = $this->PushOutbox->newEntity($dados);

            if (!$this->PushOutbox->save($notificacao)) {
                return false;
            }

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function convertAcentuacaoParaHtml($frase)
    {
        $tabela_acentuacao = ['Á' => "&Aacute;", 'á' => "&aacute;", 'Â' => "&Acirc;", 'â' => "&acirc;", 'À' => "&Agrave;", 'à' => "&agrave;", 'Å' => "&Aring;", 'å' => "&aring;", 'Ã' => "&Atilde;", 'ã' => "&atilde;", 'Ä' => "&Auml;", 'ä' => "&auml;", 'Æ' => "&AElig;", 'æ' => "&aelig;", 'É' => "&Eacute;", 'é' => "&eacute;", 'Ê' => "&Ecirc;", 'ê' => "&ecirc;", 'È' => "&Egrave;", 'è' => "&egrave;", 'Ë' => "&Euml;", 'ë' => "&euml;", 'Ð' => "&ETH;", 'ð' => "&eth;", 'Í' => "&Iacute;", 'í' => "&iacute;", 'Î' => "&Icirc;", 'î' => "&icirc;", 'Ì' => "&Igrave;", 'ì' => "&igrave;", 'Ï' => "&Iuml;", 'ï' => "&iuml;", 'Ó' => "&Oacute;", 'ó' => "&oacute;", 'Ô' => "&Ocirc;", 'ô' => "&ocirc;", 'Ò' => "&Ograve;", 'ò' => "&ograve;", 'Ø' => "&Oslash;", 'ø' => "&oslash;", 'Õ' => "&Otilde;", 'õ' => "&otilde;", 'Ö' => "&Ouml;", 'ö' => "&ouml;", 'Ú' => "&Uacute;", 'ú' => "&uacute;", 'Û' => "&Ucirc;", 'û' => "&ucirc;", 'Ù' => "&Ugrave;", 'ù' => "&ugrave;", 'Ü' => "&Uuml;", 'ü' => "&uuml;", 'Ç' => "&Ccedil;", 'ç' => "&ccedil;", 'Ñ' => "&Ntilde;", 'ñ' => "&ntilde;", 'Ý' => "&Yacute;", 'ý' => "&yacute;"];

        foreach ($tabela_acentuacao as $key => $val) {
            $frase = str_replace($key, $val, $frase);
        }

        return $frase;
    } //fim convertAcentuacaoParaHtml

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
    public function converterEncodingPara($strText, $strConvertEncoding = 'ISO-8859-1')
    {

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
        if ($encoding == 'ISO-8859-1') {

            $strText = mb_convert_encoding($strText, 'Windows-1252', 'ISO-8859-1');

            // mas se esta forçando converter iso-8859 para UTF-8
            if ($strConvertEncoding == "UTF-8") {
                $strText = mb_convert_encoding($strText, 'ISO-8859-1', "UTF-8");
            }
        } else {

            // se estiver em encoding utf-8 ou outro apenas converta do sql-server para corrigir registros com acentuação irregular
            $strText = mb_convert_encoding($strText, 'Windows-1252', "UTF-8");
        }

        return $strText;
    }

    /**
     * [calculaQtdDiasStatus calcula quantos dias ficou no status a acao de melhoria]
     * @return [type] [description]
     */
    public function calculaQtdDiasAcao($codigo_acao_melhoria, $codigo_acoes_melhorias_status = null)
    {
        $qtd_dias_aberta = 0;

        $fields = [
            'codigo',
            'qtd_dias' => "(CASE WHEN data_alteracao IS NULL THEN DATEDIFF(DAY, data_inclusao, GETDATE()) ELSE DATEDIFF(DAY, data_alteracao, GETDATE()) END)",
        ];

        $conditions['codigo_acao_melhoria'] = $codigo_acao_melhoria;

        if (!is_null($codigo_acoes_melhorias_status)) {
            $conditions['codigo_acoes_melhorias_status'] = $codigo_acoes_melhorias_status;
        }

        //pega os dados do log para comparar as datas pelo codigo do status passado
        $dadosAcao = $this->AcoesMelhoriasLog->find()
            ->select($fields)
            ->where($conditions)
            ->order(['codigo DESC'])
            ->first();

        //verifica se existe registros no log
        if (!empty($dadosAcao)) {
            $qtd_dias_aberta = $dadosAcao->qtd_dias;
        }

        return $qtd_dias_aberta;
    } //fim calculaQtdDiasStatus($codigo_acao_melhoria, $codigo_status)

    /**
     * [aplicaCondicao metodo para aplicar as condicoes]
     * @param  [type] $condicao [description]
     * @return [type]        [description]
     */
    public function aplicaCondicao($condicao, $acao)
    {

        if (empty($condicao['qtd_dias']) && $condicao['qtd_dias'] != 0) {
            return false;
        }


        //verifica se tem valor na quantidade de dias

        if (empty($condicao['condicao']) || !in_array($condicao['condicao'], self::OPERADORES_CONDICAO)) {
            return false;
        }


        $qtd_total_dias = $this->calculaQtdDiasAcao($acao['codigo'], $condicao['codigo_acoes_melhorias_status']);
        $qtd_dias = $condicao['qtd_dias'];

        //pega a condicao em string para tratar
        switch ($condicao['condicao']) {
            case ">":
                if (!$qtd_total_dias > $qtd_dias) {
                    return false;
                }
                break;
            case "<":
                if (!$qtd_total_dias < $qtd_dias) {
                    return false;
                }
                break;
            case ">=":
                if (!$qtd_total_dias >= $qtd_dias) {
                    return false;
                }
                break;
            case "<=":
                if (!$qtd_total_dias <= $qtd_dias) {
                    return false;
                }
                break;
            case "=":
                if (!$qtd_total_dias == $qtd_dias) {
                    return false;
                }
                break;
            default:
                return false;
                break;
        } //fim switch de condicao


        //verifica as outras condicoes codigo_origem_ferramenta,codigo_pos_criticidade,codigo_cliente_unidade
        if (
            !empty($condicao['codigo_origem_ferramenta']) &&
            $condicao['codigo_origem_ferramenta'] != $acao['codigo_origem_ferramenta']
        ) {
            return false;
        } //fim codigo origem ferramenta

        if (
            !empty($condicao['codigo_pos_criticidade']) &&
            $condicao['codigo_pos_criticidade'] != $acao['codigo_pos_criticidade']
        ) {
            return false;
        } //fim codigo criticidade

        if (
            !empty($condicao['codigo_cliente_unidade']) &&
            $condicao['codigo_cliente_unidade'] != $acao['codigo_cliente_observacao']
        ) {
            return false;
        } //fim codigo_cliente unidade

        return true;
    } //fim aplicaCondicao

    /**
     * [aplicaAVencer metodo para saber se uma acao esta para vencer dentro do prazo estipulado]
     * @param  [type] $parametros  [description]
     * @param  [type] $dados_acoes [description]
     * @return [type]              [description]
     */
    public function aplicaAVencer($parametros, $acoes)
    {
        # Variavel auxiliar para saber se pode entrar para continuar a verificacao dos outros criterios
        $var_aux = '0';

        # Verifica se a data do prazo existe
        if (!empty($acoes['prazo'])) {
            $time = new Time($acoes['prazo']);
            $prazo = $time->i18nFormat('yyyy-MM-dd');

            # Calcula os dias a vencer
            $qtd_dias = $parametros['qtd_dias'];

            # Soma os dias
            $data_a_vencer = date('Y-m-d', strtotime('+' . $qtd_dias . ' days'));

            # Verifica o prazo com a data calculada
            if ($prazo == $data_a_vencer) {
                $var_aux = '1';

                # Verifica as outras condicoes codigo_origem_ferramenta,codigo_pos_criticidade,codigo_cliente_unidade
                if (!empty($parametros['codigo_origem_ferramenta'])) {
                    $var_aux = '0';

                    if ($parametros['codigo_origem_ferramenta'] == $acoes['codigo_origem_ferramenta']) {
                        $var_aux = '1';
                    }
                }

                if (!empty($parametros['codigo_pos_criticidade'])) {
                    $var_aux = '0';

                    if ($parametros['codigo_pos_criticidade'] == $acoes['codigo_pos_criticidade']) {
                        $var_aux = '1';
                    }
                }

                if (!empty($parametros['codigo_cliente_unidade'])) {
                    $var_aux = '0';

                    if ($parametros['codigo_cliente_unidade'] == $acoes['codigo_cliente_observacao']) {
                        $var_aux = '1';
                    }
                }
            }
        }

        return $var_aux;
    }

    /**
     * [getEmImplementacao metodo para trabalhar no tema em implantação da ação]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmImplementacao(int $codigo_acao_melhoria)
    {
        # Retorna o valor que vai ter implementacao
        $implantacao = true;

        try {
            # Verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()
                ->where(['codigo' => $codigo_acao_melhoria])
                ->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;

                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade, 'tipo_relacionamento' => 'INNER'];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_implantacao
                $dados_regra = $this->getDadosRegra(3, $param)
                    ->enableHydration(false)
                    ->all()
                    ->toArray();

                if (!empty($dados_regra)) {
                    # Varre as regras
                    foreach ($dados_regra as $regra) {
                        $implantacao = false;

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Verifica se tem condicao cadastrada
                        if (!empty($regra['PdaConfigRegraCondicao']['codigo'])) {
                            # Verificar os outros campos
                            if (!empty($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'])) { # Verifica se tem status para aplicar a regra de condicao
                                # Não pode estar vazio
                                if (empty($regra['PdaConfigRegraCondicao']['condicao'])) {
                                    continue;
                                }

                                if (empty($regra['PdaConfigRegraCondicao']['qtd_dias'])) {
                                    continue;
                                }
                            }

                            $parametros = $regra['PdaConfigRegraCondicao'];
                            $aplicaCondicao = $this->aplicaCondicao($parametros, $dados_acoes);

                            # Verifica se aplicaCondicoes é verdadeiro para enviar a ação
                            if ($aplicaCondicao) {
                                # Seta que deve ter implantacao
                                $implantacao = true;
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // $implantacao = $e->getMessage();
            $implantacao = false;
        }

        return $implantacao;
    }

    /**
     * [getEmEficacia metodo para saber se vai poder fazer eficacia ou nao]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmEficacia(int $codigo_acao_melhoria)
    {
        # Retorna o valor que vai ter eficacia
        $eficacia = true;

        try {
            # Verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()
                ->where(['codigo' => $codigo_acao_melhoria])
                ->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;
                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade, 'tipo_relacionamento' => 'INNER'];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_implantacao
                $dados_regra = $this->getDadosRegra(4, $param)
                    ->enableHydration(false)
                    ->all()
                    ->toArray();

                if (!empty($dados_regra)) {
                    # Varre as regras
                    foreach ($dados_regra as $regra) {
                        $eficacia = false;

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Verifica se tem condicao cadastrada
                        if (!empty($regra['PdaConfigRegraCondicao']['codigo'])) {
                            # Verificar os outros campos
                            if (!empty($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'])) { # Verifica se tem status para aplicar a regra de condicao
                                # Não pode estar vazio
                                if (empty($regra['PdaConfigRegraCondicao']['condicao'])) {
                                    continue;
                                }

                                if (empty($regra['PdaConfigRegraCondicao']['qtd_dias'])) {
                                    continue;
                                }
                            }

                            $parametros = $regra['PdaConfigRegraCondicao'];
                            $aplicaCondicao = $this->aplicaCondicao($parametros, $dados_acoes);

                            # Verifica se aplicaCondicoes é verdadeiro para enviar a acao
                            if ($aplicaCondicao) {
                                # Seta que deve ter implantacao
                                $eficacia = true;
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // $eficacia = $e->getMessage();
            $eficacia = false;
        }

        return $eficacia;
    }

    /**
     * [getEmAbrangencia metodo para verificar se pode realizar a abrangencia e enviar a acao como email ou push da configuracao]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmAbrangencia(int $codigo_acao_melhoria)
    {
        $dados = false;

        try {
            # Verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()
                ->where(['codigo' => $codigo_acao_melhoria])
                ->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;
                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $dados_regra = $this->getDadosRegra(5, $param)
                    ->enableHydration(false)
                    ->all()
                    ->toArray();


                if (!empty($dados_regra)) {
                    if (BASE_URL == 'https://api.rhhealth.com.br') {
                        $url = "https://pos.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    } else {
                        $url = "https://tstpda.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    }

                    $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

                    # Varre as regras
                    foreach ($dados_regra as $regra) {

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Assunto da mensagem
                        $assunto = $regra['assunto'];

                        # Troca a variavel caso exista
                        $mensagem = str_replace("[aqui]", $aqui, nl2br($regra['mensagem']));

                        $arr_params['assunto'] = $assunto;
                        $arr_params['mensagem'] = $mensagem;
                        $arr_params['codigo_cliente'] = (!empty($codigo_cliente_unidade)) ? $codigo_cliente_unidade : $codigo_cliente_matriz;
                        $arr_params['acao_melhoria'] = $dados_acoes;

                        # Verifica se tem condicao cadastrada
                        if (!empty($regra['PdaConfigRegraCondicao']['codigo'])) {
                            # Verifica se a acao tem o mesmo status da condicao
                            if ($regra['PdaConfigRegraCondicao']['codigo_acoes_melhorias_status'] == $dados_acoes['codigo_acoes_melhorias_status']) {
                                # Não pode estar vazio
                                if (empty($regra['PdaConfigRegraCondicao']['condicao'])) {
                                    continue;
                                }

                                if ($regra['PdaConfigRegraCondicao']['qtd_dias'] == "") {
                                    continue;
                                }
                            }

                            $parametros = $regra['PdaConfigRegraCondicao'];
                            $aplicaCondicao = $this->aplicaCondicao($parametros, $dados_acoes);

                            # Verifica se aplicaCondicoes é verdadeiro para enviar a acao
                            if ($aplicaCondicao) {
                                # Busca os responsaveis pelo email/push
                                $this->acoesConfiguradas($regra['codigo'], $arr_params);
                            }
                        }
                    }
                }
            }

            $dados = true;
        } catch (Exception $e) {
            // $dados = $e->getMessage();
            $dados = false;
        }

        return $dados;
    }

    /**
     * [getAprovacaoCancelamento verifica as regras para cancelamento ou retorna os ids dos usuarios que precisam aprovar]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getAprovacaoCancelamento(int $codigo_acao_melhoria)
    {
        $dados = null;

        try {
            # Verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()
                ->where(['codigo' => $codigo_acao_melhoria])
                ->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;
                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $dados_regra = $this->getDadosRegra(6, $param)
                    ->enableHydration(false)
                    ->all()
                    ->toArray();

                if (!empty($dados_regra)) {
                    # Varre as regras
                    foreach ($dados_regra as $regra) {
                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Pega as acoes configuradas
                        $acoes = $this->PdaConfigRegraAcao->find()
                            ->where(['codigo_pda_config_regra' => $regra['codigo']])
                            ->enableHydration(false)
                            ->all()
                            ->toArray();

                        # Verifica se tem acoes
                        if (!empty($acoes)) {
                            # Varre as acoes
                            foreach ($acoes as $acao) {
                                switch ($acao['codigo_pda_tema_acoes']) {
                                        # Gestor direto
                                    case '4':
                                        if (!is_null($dados_acoes['codigo_usuario_responsavel'])) {
                                            # Consultar a matricula e codigo cliente do gestor direto
                                            $gestor_usuario = $this->Usuario->getManagerByUserId($dados_acoes['codigo_usuario_responsavel']);

                                            if (!is_null($gestor_usuario)) {
                                                $dados_gestor = $this->Usuario->getEmployee(
                                                    (string) $gestor_usuario['matricula'],
                                                    (int) $gestor_usuario['codigo_cliente']
                                                );

                                                if (!is_null($dados_gestor)) {
                                                    $dados = [
                                                        'status' => true,
                                                        'tipo_solicitacao' => 3,
                                                        'codigo_gestor' => $dados_gestor['codigo_usuario'],
                                                    ];
                                                }
                                            }
                                        }
                                        break;
                                        # Responsavel area
                                    case '5':
                                        $responsaveis = $this->getUsuarioResponsavelArea($codigo_cliente_unidade);

                                        if (is_array($responsaveis) && count($responsaveis) > 0) {
                                            $dados = [
                                                'status' => true,
                                                'tipo_solicitacao' => 2,
                                            ];
                                        }
                                        break;
                                }
                            }
                        }
                    }
                }
            }

            if (is_null($dados)) {
                $dados = [
                    'status' => true,
                    'tipo_solicitacao' => 1,
                ];
            }
        } catch (Exception $e) {
            $dados = [
                'status' => false,
                'tipo_solicitacao' => null,
            ];
        }

        return $dados;
    }

    /**
     * [getUsuarioResponsavelArea pegar os codigos dos usuarios responsaveis]
     * @param  [type] $codigo_cliente [description]
     * @return [type]                 [description]
     */
    public function getUsuarioResponsavelArea($codigo_cliente)
    {
        $codigos = array();

        $dados_ur = $this->UsuariosResponsaveis->find()
            ->select(['codigo_usuario'])
            ->where(['codigo_cliente' => $codigo_cliente, 'data_remocao IS NULL'])
            ->enableHydration(false)
            ->all();

        if (!empty($dados_ur)) {
            foreach ($dados_ur->toArray() as $val) {
                $codigos[] = $val['codigo_usuario'];
            }
        }

        return $codigos;
    }

    /**
     * [getAprovacaoPostergacao metodo para saber se tem alguem que deve aprovar a postergacao]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getAprovacaoPostergacao(int $codigo_acao_melhoria)
    {
        $dados = null;

        try {
            # Verifica se o codigo de ação de melhoria não está vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()
                ->where(['codigo' => $codigo_acao_melhoria])
                ->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;
                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $dados_regra = $this->getDadosRegra(7, $param)
                    ->enableHydration(false)
                    ->all()
                    ->toArray();

                # Verifica se existe as regras
                if (!empty($dados_regra)) {
                    # Varre as regras
                    foreach ($dados_regra as $regra) {

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Pega as ações configuradas
                        $acoes = $this->PdaConfigRegraAcao->find()
                            ->where(['codigo_pda_config_regra' => $regra['codigo']])
                            ->enableHydration(false)
                            ->all()
                            ->toArray();

                        # Verifica se tem ações
                        if (!empty($acoes)) {
                            # Varre as ações
                            foreach ($acoes as $acao) {
                                switch ($acao['codigo_pda_tema_acoes']) {
                                        # Gestor direto
                                    case '4':
                                        if (!is_null($dados_acoes['codigo_usuario_responsavel'])) {
                                            # Consultar a matricula e codigo cliente do gestor direto
                                            $gestor_usuario = $this->Usuario->getManagerByUserId($dados_acoes['codigo_usuario_responsavel']);

                                            if (!is_null($gestor_usuario)) {
                                                $dados_gestor = $this->Usuario->getEmployee(
                                                    (string) $gestor_usuario['matricula'],
                                                    (int) $gestor_usuario['codigo_cliente']
                                                );

                                                if (!is_null($dados_gestor)) {
                                                    $dados = [
                                                        'status' => true,
                                                        'tipo_solicitacao' => 3,
                                                        'codigo_gestor' => $dados_gestor['codigo_usuario'],
                                                    ];
                                                }
                                            }
                                        }
                                        break;
                                        # Responsavel area
                                    case '5':
                                        $responsaveis = $this->getUsuarioResponsavelArea($codigo_cliente_unidade);

                                        if (is_array($responsaveis) && count($responsaveis) > 0) {
                                            $dados = [
                                                'status' => true,
                                                'tipo_solicitacao' => 2,
                                            ];
                                        }
                                        break;
                                }
                            }
                        }
                    }
                }
            }

            if (is_null($dados)) {
                $dados = [
                    'status' => true,
                    'tipo_solicitacao' => 1,
                ];
            }
        } catch (Exception $e) {
            $dados = [
                'status' => false,
                'tipo_solicitacao' => null,
            ];
        }

        return $dados;
    }

    /**
     * [getAcaoesMelhoriasEmAtraso metodo para pegar os codigos das acoes de melhorias que estao em atraso]
     * @return [type] [description]
     */
    public function getAcaoesMelhoriasEmAtraso()
    {
        $query = "
                WITH cteQtdDias AS (
                    SELECT
                        PdaConfigRegraCondicao.qtd_dias
                        ,CONVERT(DATE,getdate(),103) as hoje
                        ,PdaConfigRegra.codigo_cliente
                        ,PdaConfigRegra.codigo as codigo_regra
                    FROM pda_config_regra PdaConfigRegra
                        INNER JOIN pda_config_regra_condicao PdaConfigRegraCondicao ON PdaConfigRegraCondicao.codigo_pda_config_regra = PdaConfigRegra.codigo
                            AND PdaConfigRegraCondicao.ativo = 1
                    WHERE PdaConfigRegra.codigo_pda_tema = 2
                           AND PdaConfigRegra.ativo = 1
                )
                SELECT (CASE WHEN DATEADD(day,d.qtd_dias,am.prazo) = d.hoje THEN am.codigo END) AS codigo_acao_melhoria
                    ,d.qtd_dias AS valor_dias
                    ,d.codigo_regra
                FROM acoes_melhorias am
                    LEFT JOIN cteQtdDias d on d.codigo_cliente=am.codigo_cliente_observacao
                WHERE am.prazo IS NOT NULL
                    AND (CASE WHEN DATEADD(day,d.qtd_dias,am.prazo) = d.hoje THEN am.codigo END) IS NOT NULL and am.codigo_acoes_melhorias_status not in (5,6);";

        $conn = ConnectionManager::get('default');
        $dados = $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }

    /**
     * [getEmAtraso pega a configuracao de quem esta atrasado para aplicar na data do prazo e calcular se deve enviar a ação ou não]
     * @param  int    $codigo_acao_melhoria [description]
     * @return [type]                       [description]
     */
    public function getEmAtraso(int $codigo_acao_melhoria, $valor_dias)
    {
        $dados = false;

        try {
            # Verifica se o codigo de acao de melhoria nao esta vazio
            if (empty($codigo_acao_melhoria)) {
                throw new Exception("Necessário passar um valor de acao de melhoria");
            }

            # Pega os dados de acao de melhoria
            $dados_acoes = $this->AcoesMelhorias->find()->where(['codigo' => $codigo_acao_melhoria])->first();

            if (!empty($dados_acoes)) {
                $dados_acoes = $dados_acoes->toArray();

                $codigo_cliente_unidade = $dados_acoes['codigo_cliente_observacao'];
                $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente_unidade)->codigo_cliente_matriz;

                $param = ['codigo_cliente_matriz' => $codigo_cliente_matriz, 'codigo_cliente_unidade' => $codigo_cliente_unidade];

                # Pega as regras com a matriz ou unidade e pelo status do tema em_acao_melhoria
                $dados_regra = $this->getDadosRegra(2, $param, $valor_dias)->enableHydration(false)->all()->toArray();

                if (!empty($dados_regra)) {
                    if (BASE_URL == 'https://api.rhhealth.com.br') {
                        $url = "https://pos.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    } else {
                        $url = "https://tstpda.ithealth.com.br/action/details/" . $codigo_acao_melhoria;
                    }

                    $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

                    # Varre as regras
                    foreach ($dados_regra as $regra) {

                        $regra['mensagem'] = utf8_decode($regra['mensagem']);
                        $regra['assunto'] = utf8_decode($regra['assunto']);

                        # Assunto da mensagem
                        $assunto = $regra['assunto'];

                        # Troca a variavel caso exista
                        $mensagem = str_replace("[aqui]", $aqui, nl2br($regra['mensagem']));

                        # Verifica se é email e push
                        $arr_params['assunto'] = $assunto;
                        $arr_params['mensagem'] = $mensagem;
                        $arr_params['codigo_cliente'] = (!empty($codigo_cliente_unidade)) ? $codigo_cliente_unidade : $codigo_cliente_matriz;
                        $arr_params['acao_melhoria'] = $dados_acoes;

                        # Zera os dias porque já foi calculado pela cron
                        $regra['PdaConfigRegraCondicao']['qtd_dias'] = '';

                        # Calcula as acoes de melhorias que estão a vencer daqui a 10 dias pela data de prazo
                        $parametros = $regra['PdaConfigRegraCondicao'];
                        $aplica_em_atraso = $this->aplicaCondicao($parametros, $dados_acoes);

                        # Verifica se aplicaCondicoes é verdadeiro para enviar a acao
                        if ($aplica_em_atraso) {
                            # Busca os responsáveis pelo e-mail/push
                            $this->acoesConfiguradas($regra['codigo'], $arr_params);
                        }
                    }
                }
            }

            $dados = true;
        } catch (Exception $e) {
            // $dados = $e->getMessage();
            $dados = false;
        }

        return $dados;
    }
}
