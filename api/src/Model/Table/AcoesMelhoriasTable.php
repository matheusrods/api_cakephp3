<?php

namespace App\Model\Table;

use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use App\Utils\Comum;

/**
 * AcoesMelhoria Model
 *
 * @method \App\Model\Entity\AcoesMelhoria get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoria newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoria[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoria|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoria saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoria patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoria[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoria findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasTable extends Table
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

        $this->connect = ConnectionManager::get('default');

        $this->setTable('acoes_melhorias');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasOne('PosCriticidade', [
            'bindingKey' => 'codigo_pos_criticidade',
            'foreignKey' => 'codigo',
            'joinTable' => 'pos_criticidade',
            'propertyName' => 'criticidade',
        ]);

        $this->hasOne('AcoesMelhoriasTipo', [
            'bindingKey' => 'codigo_acoes_melhorias_tipo',
            'foreignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_tipo',
            'propertyName' => 'tipo',
        ]);

        $this->hasOne('AcoesMelhoriasStatus', [
            'bindingKey' => 'codigo_acoes_melhorias_status',
            'foreignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_status',
            'propertyName' => 'status',
        ]);

        $this->hasOne('OrigemFerramentas', [
            'bindingKey' => 'codigo_origem_ferramenta',
            'foreignKey' => 'codigo',
            'joinTable' => 'origem_ferramentas',
            'propertyName' => 'origem_ferramenta',
        ]);

        $this->hasOne('UsuarioResponsavel', [
            'className' => 'Usuario',
            'bindingKey' => 'codigo_usuario_responsavel',
            'foreignKey' => 'codigo',
            'joinTable' => 'usuario',
            'propertyName' => 'responsavel',
        ]);

        $this->hasOne('UsuarioIdentificador', [
            'className' => 'Usuario',
            'bindingKey' => 'codigo_usuario_identificador',
            'foreignKey' => 'codigo',
            'joinTable' => 'usuario',
            'propertyName' => 'identificador',
        ]);

        $this->hasOne('Cliente', [
            'bindingKey' => 'codigo_cliente_observacao',
            'foreignKey' => 'codigo',
            'joinTable' => 'cliente',
            'propertyName' => 'localidade',
        ]);

        $this->hasMany('AcoesMelhoriasAnexos', [
            'foreignKey' => 'codigo_acao_melhoria',
            'targetForeignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_anexos',
            'propertyName' => 'anexos',
            'conditions' => ['AcoesMelhoriasAnexos.ativo' => 1],
        ]);

        $this->hasMany('UsuariosResponsaveis', [
            'className' => 'UsuariosResponsaveis',
            'foreignKey' => 'codigo_cliente',
            'bindingKey' => 'codigo_cliente_observacao',
            'joinTable' => 'usuarios_responsaveis',
            'propertyName' => 'matriz_responsabilidade',
            'conditions' => ['data_remocao IS NULL'],
        ]);

        $this->hasMany('AcoesMelhoriasSolicitacoes', [
            'foreignKey' => 'codigo_acao_melhoria',
            'targetForeignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_solicitacoes',
            'propertyName' => 'solicitacoes',
            'conditions' => ['data_remocao IS NULL'],
        ]);

        $this->hasMany('AcoesMelhoriasAssociadas', [
            'foreignKey' => 'codigo_acao_melhoria_principal',
            'targetForeignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_associadas',
            'propertyName' => 'acoes_associadas',
            'conditions' => ['data_remocao IS NULL'],
        ]);

        $this->belongsToMany('PosObsObservacao')
            ->setThrough('PosObsObservacaoAcaoMelhoria')
            ->setForeignKey('acoes_melhoria_id')
            ->setTargetForeignKey('obs_observacao_id');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria');
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
            ->integer('codigo_origem_ferramenta', 'O campo de origem da ferramenta precisa ser um número inteiro.')
            ->requirePresence('codigo_origem_ferramenta', 'create', 'O campo de origem da ferramenta é obrigatório.')
            ->notEmptyString('codigo_origem_ferramenta', 'O campo de origem da ferramenta não pode ser deixado em branco.');

        $validator
            ->scalar('formulario_resposta')
            ->requirePresence('formulario_resposta', 'create', 'O campo de formulário é obrigatório.')
            ->notEmptyString('formulario_resposta', 'O campo de formulário não pode ser deixado em branco.');

        $validator
            ->integer('codigo_cliente_observacao')
            ->requirePresence('codigo_cliente_observacao', 'create')
            ->notEmptyString('codigo_cliente_observacao');

        $validator
            ->integer('codigo_usuario_identificador', 'O campo de usuário identificador precisa ser um número inteiro.')
            ->requirePresence('codigo_usuario_identificador', 'create', 'O campo de usuário identificador é obrigatório.')
            ->notEmptyString('codigo_usuario_identificador', 'O campo de usuário identificador não pode ser deixado em branco.');

        $validator
            ->integer('codigo_usuario_responsavel', 'O campo de usuário responsável precisa ser um número inteiro.')
            ->allowEmptyString('codigo_usuario_responsavel');

        $validator
            ->integer('codigo_pos_criticidade', 'O campo de criticidade precisa ser um número inteiro.')
            ->requirePresence('codigo_pos_criticidade', 'create', 'O campo de criticidade é obrigatório.')
            ->notEmptyString('codigo_pos_criticidade', 'O campo de criticidade não pode ser deixado em branco.');

        $validator
            ->integer('codigo_acoes_melhorias_tipo', 'O campo de tipo da ação precisa ser um número inteiro.')
            ->requirePresence('codigo_acoes_melhorias_tipo', 'create', 'O campo de tipo da ação é obrigatório.')
            ->notEmptyString('codigo_acoes_melhorias_tipo', 'O campo de tipo da ação não pode ser deixado em branco.');

        $validator
            ->integer('codigo_acoes_melhorias_status', 'O campo de status da ação precisa ser um número inteiro.')
            ->requirePresence('codigo_acoes_melhorias_status', 'create', 'O campo de status da ação é obrigatório.')
            ->notEmptyString('codigo_acoes_melhorias_status', 'O campo de status da ação não pode ser deixado em branco.');

        $validator
            ->date('prazo', ['ymd'], 'O campo de prazo precisa ser uma data válida.')
            ->allowEmptyDateTime('prazo');

        $validator
            ->scalar('descricao_desvio')
            ->requirePresence('descricao_desvio', 'create', 'O campo de descrição do desvio é obrigatório.')
            ->notEmptyString('descricao_desvio', 'O campo de descrição do desvio não pode ser deixado em branco.');

        $validator
            ->scalar('descricao_acao')
            ->requirePresence('descricao_acao', 'create', 'O campo de descrição da ação é obrigatório.')
            ->notEmptyString('descricao_acao', 'O campo de descrição da ação não pode ser deixado em branco.');

        $validator
            ->scalar('descricao_local_acao')
            ->requirePresence('descricao_local_acao', 'create', 'O campo de descrição do local da ação é obrigatório.')
            ->notEmptyString('descricao_local_acao', 'O campo de descrição do local da ação não pode ser deixado em branco.');

        $validator
            ->date('data_conclusao', ['ymd'], 'O campo de data de conclusão precisa ser uma data válida.')
            ->allowEmptyDateTime('data_conclusao');

        $validator
            ->scalar('conclusao_observacao')
            ->allowEmptyString('conclusao_observacao');

        $validator
            ->boolean('analise_implementacao_valida')
            ->allowEmptyString('analise_implementacao_valida');

        $validator
            ->boolean('abrangente')
            ->allowEmptyString('abrangente');

        $validator
            ->boolean('necessario_abrangencia')
            ->allowEmptyString('necessario_abrangencia');

        $validator
            ->boolean('necessario_eficacia')
            ->allowEmptyString('necessario_eficacia');

        $validator
            ->boolean('necessario_implementacao')
            ->allowEmptyString('necessario_implementacao');

        $validator
            ->scalar('descricao_analise_implementacao')
            ->allowEmptyString('descricao_analise_implementacao');

        $validator
            ->integer('codigo_usuario_responsavel_analise_implementacao', 'O campo de usuario responsável da análise de implementação precisa ser um número inteiro.')
            ->allowEmptyString('codigo_usuario_responsavel_analise_implementacao');

        $validator
            ->date('data_analise_implementacao', ['ymd'], 'O campo de data de análise de implementação precisa ser uma data válida.')
            ->allowEmptyDateTime('data_analise_implementacao');

        $validator
            ->boolean('analise_eficacia_valida')
            ->allowEmptyString('analise_eficacia_valida');

        $validator
            ->scalar('descricao_analise_eficacia')
            ->allowEmptyString('descricao_analise_eficacia');

        $validator
            ->integer('codigo_usuario_responsavel_analise_eficacia', 'O campo de usuario responsável da análise de eficácia precisa ser um número inteiro.')
            ->allowEmptyString('codigo_usuario_responsavel_analise_eficacia');

        $validator
            ->date('data_analise_eficacia', ['ymd'], 'O campo de data de análise de eficácia precisa ser uma data válida.')
            ->allowEmptyDateTime('data_analise_eficacia');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }

    private function filter($data = [], $fields = [])
    {
        $newData = [];

        foreach ($data as $key => $value) {
            foreach ($fields as $field) {
                if ($key == $field) {
                    $newData[$key] = $value;
                }
            }
        }

        return $newData;
    }

    /**
     * [setFerramentaAcoesMelhoria description]
     */
    public function setFerramentaAcoesMelhoria($codigo_usuario, $acoes)
    {
        $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');

        $improvementActions = $acoes;

        // debug($improvementActions);exit;

        if (!is_array($improvementActions) || count($improvementActions) < 1) {
            throw new Exception('Ações de Melhoria: Não foi enviado no formato correto os dados.');
        }
        //varre os dados da acao de melhoria
        foreach ($improvementActions as $improvementAction) {

            $improvementAction['codigo_usuario_inclusao'] = $codigo_usuario;

            if (!isset($improvementAction['codigo_usuario_responsavel'])) {
                throw new Exception('Ações de Melhoria: O campo de responsável é obrigatório.');
            }

            $responsible = (int) $improvementAction['codigo_usuario_responsavel'];
            unset($improvementAction['codigo_usuario_responsavel']);

            $entityImprovementAction = $this->newEntity($improvementAction);

            // dd($entityImprovementAction);exit;

            if (!$this->save($entityImprovementAction)) {
                throw new Exception('Ações de Melhoria, erro ao inserir ações de melhorias: (' . json_encode($entityImprovementAction->getErrors()) . ')');
            }

            //seta um novo vinculo a ser feito
            $dados['vinculo_acao'][$entityImprovementAction->codigo] = ['codigo' => $entityImprovementAction->codigo, 'codigo_form_questao' => $improvementAction['codigo_form_questao']];

            // debug($dados);exit;

            $dados_solicitacao = array(
                'codigo_acao_melhoria' => $entityImprovementAction->codigo,
                'codigo_acao_melhoria_solicitacao_tipo' => 1,
                'codigo_novo_usuario_responsavel' => $responsible,
                'codigo_usuario_solicitado' => $responsible,
                'status' => 1,
                'codigo_usuario_inclusao' => $codigo_usuario,
            );

            $entitySolicitation = $this->AcoesMelhoriasSolicitacoes->newEntity($dados_solicitacao);

            // dd($entitySolicitation);exit;

            if (!$this->AcoesMelhoriasSolicitacoes->save($entitySolicitation)) {
                throw new Exception('Ações de Melhoria Solicitações, erro ao inserir ações de melhorias: (' . json_encode($entitySolicitation->getErrors()) . ')');
            }
        } //fim foreach acoes de melhorias

        return $dados;
    } //fim setFerramentaAcoesMelhoria

    public function store(array $improvementActions = [], int $userId, bool $createLink = true)
    {
        set_time_limit(300);
        ini_set('default_socket_timeout', 1000);
        ini_set('mssql.connect_timeout', 1000);
        ini_set('mssql.timeout', 3000);

        try {
            $this->connect->begin();

            $this->AcoesMelhoriasAssociadas = TableRegistry::get('AcoesMelhoriasAssociadas');
            $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');
            $this->PosSwtFormAcaoMelhoria = TableRegistry::get('PosSwtFormAcaoMelhoria');
            $this->PosObsObservacaoAcaoMelhoria = TableRegistry::get('PosObsObservacaoAcaoMelhoria');
            $this->PdaConfigRegra = TableRegistry::get('PdaConfigRegra');
            $this->Usuario = TableRegistry::get('Usuario');

            $data = [];

            foreach ($improvementActions as $improvementAction) {
                $improvementAction['codigo_usuario_inclusao'] = $userId;

                if (!isset($improvementAction['codigo_usuario_responsavel'])) {
                    $this->connect->rollback();

                    return [
                        'data' => [
                            'error' => [
                                'message' => 'O campo de responsável é obrigatório.',
                            ],
                        ],
                        'error' => null,
                    ];
                }

                $responsibleIsObserverUser = ((int) $improvementAction['codigo_usuario_identificador']) === ((int) $improvementAction['codigo_usuario_responsavel']);
                $responsible = (int) $improvementAction['codigo_usuario_responsavel'];

                // Verifica se o usuario identificador é o mesmo do responsável e se for altera o status da ação para 3 (Em andamento)
                if (!$responsibleIsObserverUser && $improvementAction['codigo_acoes_melhorias_status'] === 1) {
                    unset($improvementAction['codigo_usuario_responsavel']);
                } else if ($responsibleIsObserverUser) {
                    $improvementAction['codigo_acoes_melhorias_status'] = 3;
                }

                $associations = [];

                // Verificar se existe associações
                if (isset($improvementAction['associacoes'])) {
                    $associations = $improvementAction['associacoes'];

                    unset($improvementAction['associacoes']);
                }

                // Criar entidade da ação de melhoria
                $entityImprovementAction = $this->newEntity($this->filter($improvementAction, [
                    'codigo_origem_ferramenta',
                    'codigo_cliente_observacao',
                    'codigo_usuario_identificador',
                    'codigo_usuario_responsavel',
                    'codigo_pos_criticidade',
                    'codigo_acoes_melhorias_tipo',
                    'codigo_acoes_melhorias_status',
                    'descricao_desvio',
                    'descricao_acao',
                    'prazo',
                    'descricao_local_acao',
                    'formulario_resposta',
                    'codigo_usuario_inclusao',
                    'codigo_cliente_bu',
                    'codigo_cliente_opco',
                ]));

                // Salvar ação de melhoria e verificar se não existe erros
                if (!$this->save($entityImprovementAction)) {
                    $this->connect->rollback();

                    return [
                        'data' => [
                            'error' => [
                                'message' => 'Erro ao inserir ações de melhorias.',
                                'form_errors' => $entityImprovementAction->getErrors(),
                            ],
                        ],
                        'error' => null,
                    ];
                }

                // Vincular ações de melhorias associadas
                if (count($associations) > 0) {
                    $newAssociations = [];

                    foreach ($associations as $association) {
                        if (
                            isset($association['codigo_acao_melhoria'])
                            && isset($association['tipo_relacao'])
                            && !empty($association['codigo_acao_melhoria'])
                            && !empty($association['tipo_relacao'])
                        ) {
                            $associationsResponse = $this->getAssociations([$association['codigo_acao_melhoria']], []);

                            foreach ($associationsResponse as $familyAssociation) {
                                array_push($newAssociations, [
                                    'codigo_acao_melhoria_principal' => $entityImprovementAction->codigo,
                                    'codigo_acao_melhoria_relacionada' => $familyAssociation['codigo_acao_melhoria'],
                                    'tipo_relacao' => $familyAssociation['tipo_relacao'],
                                    'codigo_usuario_inclusao' => $userId,
                                ]);
                            }

                            array_push($newAssociations, [
                                'codigo_acao_melhoria_principal' => $entityImprovementAction->codigo,
                                'codigo_acao_melhoria_relacionada' => $association['codigo_acao_melhoria'],
                                'tipo_relacao' => $association['tipo_relacao'],
                                'codigo_usuario_inclusao' => $userId,
                            ]);
                        }
                    }

                    foreach ($newAssociations as $association) {
                        $entityAssociation = $this->AcoesMelhoriasAssociadas->newEntity($association);

                        if (!$this->AcoesMelhoriasAssociadas->save($entityAssociation)) {
                            $data = [
                                'error' => [
                                    'message' => 'Erro ao inserir ações de melhorias.',
                                    'form_errors' => $entityAssociation->getErrors(),
                                ],
                            ];

                            $this->connect->rollback();

                            $this->set(compact('data'));

                            return;
                        }
                    }
                }

                // Verificar se é necessário uma solicitação, se for cria-lá
                if (!$responsibleIsObserverUser && $improvementAction['codigo_acoes_melhorias_status'] === 1) {
                    $entitySolicitation = $this->AcoesMelhoriasSolicitacoes->newEntity([
                        'codigo_acao_melhoria' => $entityImprovementAction->codigo,
                        'codigo_acao_melhoria_solicitacao_tipo' => 1,
                        'codigo_novo_usuario_responsavel' => $responsible,
                        'codigo_usuario_solicitado' => $responsible,
                        'justificativa_solicitacao' => null,
                        'status' => 1,
                        'codigo_usuario_inclusao' => $userId,
                    ]);

                    if (!$this->AcoesMelhoriasSolicitacoes->save($entitySolicitation)) {
                        $this->connect->rollback();

                        return [
                            'data' => [
                                'error' => [
                                    'message' => 'Erro ao inserir ações de melhorias.',
                                    'form_errors' => $entitySolicitation->getErrors(),
                                ],
                            ],
                            'error' => null,
                        ];
                    }
                }

                if ($createLink) {
                    $formAnswers = json_decode($improvementAction['formulario_resposta']);

                    if (isset($formAnswers->questao_topico->id) && isset($formAnswers->codigo_walk_talk->id)) {
                        // Vincular ação de melhoria com o safety walk talk

                        $dataFormImprovementAction = array(
                            'codigo_form' => $formAnswers->codigo_walk_talk->formId,
                            'codigo_form_respondido' => $formAnswers->codigo_walk_talk->id,
                            'codigo_cliente' => $formAnswers->codigo_walk_talk->clientId,
                            'codigo_empresa' => $this->Usuario->getCodigoEmpresa($userId),
                            'codigo_acao_melhoria' => $entityImprovementAction->codigo,
                            'codigo_form_questao' => $formAnswers->questao_topico->id,
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $userId,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        $formImprovementActionEntity = $this->PosSwtFormAcaoMelhoria->newEntity($dataFormImprovementAction);

                        if (!$this->PosSwtFormAcaoMelhoria->save($formImprovementActionEntity)) {
                            $this->connect->rollback();

                            return [
                                'data' => [
                                    'error' => [
                                        'message' => 'Erro ao inserir ações de melhorias.',
                                        'form_errors' => $formImprovementActionEntity->getErrors(),
                                    ],
                                ],
                                'error' => null,
                            ];
                        }
                    } else if (isset($formAnswers->codigo_observacao->id)) {
                        // Vincular ação de melhoria com o observador

                        $dataFormImprovementAction = array(
                            'acoes_melhoria_id' => $entityImprovementAction->codigo,
                            'obs_observacao_id' => $formAnswers->codigo_observacao->id,
                            'ativo' => 1,
                            'codigo_usuario_inclusao' => $userId,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                        );

                        $formImprovementActionEntity = $this->PosObsObservacaoAcaoMelhoria->newEntity($dataFormImprovementAction);

                        if (!$this->PosObsObservacaoAcaoMelhoria->save($formImprovementActionEntity)) {
                            $this->connect->rollback();

                            return [
                                'data' => [
                                    'error' => [
                                        'message' => 'Erro ao inserir ações de melhorias.',
                                        'form_errors' => $formImprovementActionEntity->getErrors(),
                                    ],
                                ],
                                'error' => null,
                            ];
                        }
                    }
                }

                // Necessário analise de abrangência
                $coverageAnalysisNecessary = (bool) $this->PdaConfigRegra->getEmAbrangencia($entityImprovementAction->codigo);

                $implementationAnalysisNecessary = null;
                $effectivenessAnalysisNecessary = null;

                if ($entityImprovementAction->codigo_acoes_melhorias_status === 5) {
                    $necessaryToAnalyze = $this->necessaryToAnalyzeImplementationOrEffectiveness($entityImprovementAction->codigo);

                    if ($necessaryToAnalyze === 0) {
                        // Não é necessário nenhuma análise (Implementação e eficácia)
                        $implementationAnalysisNecessary = false;
                        $effectivenessAnalysisNecessary = false;
                    } else if ($necessaryToAnalyze === 1) {
                        // Necessário analise de implementação
                        $implementationAnalysisNecessary = true;
                    } else if ($necessaryToAnalyze === 2) {
                        // Necessário analise de eficacia
                        $implementationAnalysisNecessary = false;
                        $effectivenessAnalysisNecessary = true;
                    }
                }

                // Montar entidade para alteração dos dados de analise de abrangência, implementação e eficácia
                $patchImprovementAction = $this->patchEntity($entityImprovementAction, [
                    'necessario_abrangencia' => $coverageAnalysisNecessary,
                    'necessario_eficacia' => $effectivenessAnalysisNecessary,
                    'necessario_implementacao' => $implementationAnalysisNecessary,
                    'codigo_usuario_alteracao' => $userId,
                    'data_alteracao' => date('Y-m-d H:i:s'),
                ]);

                if (!$this->save($patchImprovementAction)) {
                    $this->connect->rollback();

                    return [
                        'data' => [
                            'error' => [
                                'message' => 'Erro ao inserir ações de melhorias.',
                                'form_errors' => $patchImprovementAction->getErrors(),
                            ],
                        ],
                        'error' => null,
                    ];
                }

                array_push($data, $patchImprovementAction);
            }

            // // Aplicar regras gerais da ação de melhoria, observação: Se ocorrer algum erro deve continuar execução das demais
            // foreach ($data as $improvementAction) {
            //     try {
            //         $this->PdaConfigRegra->getEmAcaoDeMelhoria($improvementAction->codigo);
            //     } catch (\Exception $exception) {
            //         continue;
            //     }
            // }

            $this->connect->commit();

            return [
                'data' => $data,
                'error' => null,
            ];
        } catch (\Exception $exception) {
            $this->connect->rollback();

            return [
                'data' => null,
                'error' => [
                    'message' => 'Erro interno no servidor.',
                ],
            ];
        }
    }

    public function update(int $improvementActionCode, $request, int $userId, array $permissions = [])
    {
        try {
            $this->connect->begin();

            $this->PdaConfigRegra = TableRegistry::get('PdaConfigRegra');
            $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');

            $improvementAction = $this->find()
                ->where([
                    'codigo' => $improvementActionCode,
                    'data_remocao IS NULL',
                ])->first();

            if (empty($improvementAction)) {
                return [
                    'data' => [
                        'error' => [
                            'message' => 'Não foi encontrado dados referente ao código informado.',
                        ],
                    ],
                    'error' => null,
                ];
            }

            $putData = $this->filter($request->getData(), [
                'codigo_usuario_responsavel',
                'codigo_acoes_melhorias_status',
                'codigo_pos_criticidade',
                'prazo',
                'abrangente',
                'data_conclusao',
                'conclusao_observacao',
                'analise_implementacao_valida',
                'descricao_analise_implementacao',
                'codigo_usuario_responsavel_analise_implementacao',
                'data_analise_implementacao',
                'analise_eficacia_valida',
                'descricao_analise_eficacia',
                'codigo_usuario_responsavel_analise_eficacia',
                'data_analise_eficacia',
            ]);

            if (
                isset($putData['codigo_acoes_melhorias_status'])
                && $putData['codigo_acoes_melhorias_status'] === 5
                && !in_array(5, $permissions)
            ) {
                $data = [
                    'error' => [
                        'message' => 'O seu usuário não tem permissão para concluir ação. Entre em contato com o seu supervisor.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            if (
                isset($putData['codigo_acoes_melhorias_status'])
                && $putData['codigo_acoes_melhorias_status'] === 5
            ) {
                $solicitation = $this->AcoesMelhoriasSolicitacoes->find()
                    ->where([
                        'codigo_acao_melhoria' => $improvementActionCode,
                        'codigo_acao_melhoria_solicitacao_tipo IN (2, 3)',
                        'status' => 1,
                        'data_remocao IS NULL',
                    ])->first();

                // Cancelando solicitação de Postergação/Cancelamento
                if (!empty($solicitation)) {
                    $solicitationEntity = $this->AcoesMelhoriasSolicitacoes->patchEntity($solicitation, [
                        'status' => 3,
                        'data_alteracao' => date('Y-m-d H:i:s'),
                        'codigo_usuario_alteracao' => $userId,
                        'alteracao_sistema' => 0,
                    ]);

                    if (!$this->AcoesMelhoriasSolicitacoes->save($solicitationEntity)) {
                        $data = [
                            'error' => [
                                'message' => 'Erro ao editar ação de melhoria.',
                                'form_errors' => $solicitationEntity->getErrors(),
                            ],
                        ];

                        $this->connect->rollback();

                        $this->set(compact('data'));

                        return;
                    }
                }
            }

            // Aplicar regras quando for realizada analise de implementação
            if (isset($putData['analise_implementacao_valida'])) {
                if ($improvementAction->necessario_implementacao === true) {
                    if (!in_array(8, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão fazer a análise de implementação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }

                    if ($putData['analise_implementacao_valida'] == 0) {
                        if (!is_null($improvementAction->data_conclusao) && !is_null($improvementAction->prazo)) {
                            $difference = strtotime($improvementAction->prazo) - strtotime($improvementAction->data_conclusao);
                            $days = floor($difference / (60 * 60 * 24));

                            if ($days > 0) {
                                $putData['prazo'] = date('Y-m-d', strtotime('+' . $days . ' days'));
                            }
                        }

                        $putData['codigo_acoes_melhorias_status'] = 3;
                    } else if ($putData['analise_implementacao_valida'] == 1) {
                        $necessaryToAnalyzeEffectiveness = $this->necessaryToAnalyzeImplementationOrEffectiveness($improvementAction->codigo, 3);

                        $putData['necessario_eficacia'] = $necessaryToAnalyzeEffectiveness === 2 ? true : false;
                        $putData['codigo_acoes_melhorias_status'] = 9;
                    }
                } else {
                    return [
                        'data' => [
                            'error' => [
                                'message' => 'Essa ação de melhoria não tem análise de implementação.',
                            ],
                        ],
                        'error' => null,
                    ];
                }
            }

            // Aplicar regras quando for realizada analise de eficacia
            if (isset($putData['analise_eficacia_valida'])) {
                if ($improvementAction->necessario_implementacao === true) {
                    if (!in_array(9, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão fazer a análise de eficácia. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }

                    if ($putData['analise_eficacia_valida'] == 1) {
                        $putData['codigo_acoes_melhorias_status'] = 7;
                    } else if ($putData['analise_eficacia_valida'] == 0) {
                        $putData['codigo_acoes_melhorias_status'] = 8;
                    }
                } else {
                    return [
                        'data' => [
                            'error' => [
                                'message' => 'Essa ação de melhoria não tem análise de eficácia.',
                            ],
                        ],
                        'error' => null,
                    ];
                }
            }

            // Verificar as analises que são necessárias quando for no fluxo de conclusão da ação
            if (
                isset($putData['codigo_acoes_melhorias_status'])
                && $putData['codigo_acoes_melhorias_status'] === 5
                && is_null($improvementAction->necessario_implementacao)
                && is_null($improvementAction->necessario_eficacia)
            ) {
                $necessaryToAnalyze = $this->necessaryToAnalyzeImplementationOrEffectiveness($improvementAction->codigo);

                if ($necessaryToAnalyze === 0) {
                    // Não é necessário nenhuma análise (Implementação e eficácia)
                    $putData['necessario_implementacao'] = false;
                    $putData['necessario_eficacia'] = false;
                } else if ($necessaryToAnalyze === 1) {
                    // Necessário analise de implementação
                    $putData['necessario_implementacao'] = true;
                } else if ($necessaryToAnalyze === 2) {
                    // Necessário analise de eficacia
                    $putData['necessario_implementacao'] = false;
                    $putData['necessario_eficacia'] = true;
                }
            }

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            //todo: fazer aqui !!!!l

            $entity = $this->patchEntity($improvementAction, $putData);

            if (!$this->save($entity)) {
                $this->connect->rollback();

                return [
                    'data' => [
                        'error' => [
                            'message' => 'Erro ao editar ação de melhoria.',
                            'form_errors' => $entity->getErrors(),
                        ],
                    ],
                    'error' => null,
                ];
            }

            // Aplicar regras gerais da ação de melhoria
            $this->PdaConfigRegra->getEmAcaoDeMelhoria($entity->codigo);

            $this->connect->commit();

            return [
                'data' => $entity,
                'error' => null,
            ];
        } catch (\Exception $exception) {
            $this->connect->rollback();

            return [
                'data' => null,
                'error' => [
                    'message' => 'Erro interno no servidor.',
                ],
            ];
        }
    }

    public function verificaAssociacoes($codigo_acao_melhoria)
    {
        $this->PdaConfigRegra = TableRegistry::get('PdaConfigRegra');
        $this->AcoesMelhoriasSolicitacoes = TableRegistry::get('AcoesMelhoriasSolicitacoes');
    }

    private function getAssociations(array $actionsCode = [], array $data = [])
    {
        $this->AcoesMelhoriasAssociadas = TableRegistry::get('AcoesMelhoriasAssociadas');

        $newActionsCode = [];

        $actionsAssociate = '';

        foreach ($actionsCode as $key => $code) {
            $actionsAssociate .= ($key === (count($actionsCode) - 1)) ? (string) $code : (string) $code . ',';
        }

        $associations = $this->AcoesMelhoriasAssociadas->find()
            ->where(['codigo_acao_melhoria_principal IN (' . $actionsAssociate . ')'])
            ->all()
            ->toArray();

        foreach ($associations as $association) {
            array_push($data, [
                'codigo_acao_melhoria' => (int) $association['codigo_acao_melhoria_relacionada'],
                'tipo_relacao' => (int) $association['tipo_relacao'],
            ]);

            array_push($newActionsCode, (int) $association['codigo_acao_melhoria_relacionada']);
        }

        if (count($associations) > 0) {
            return $this->getAssociations($newActionsCode, $data);
        } else {
            return $data;
        }
    }

    private function necessaryToAnalyzeImplementationOrEffectiveness(int $improvementActionCode, int $type = 1)
    {
        $this->PdaConfigRegra = TableRegistry::get('PdaConfigRegra');

        if ($type === 1) {
            // Verifica se é necessário analise de implementação, eficacia ou nenhuma
            if ($this->PdaConfigRegra->getEmImplementacao($improvementActionCode)) {
                return 1;
            } else if ($this->PdaConfigRegra->getEmEficacia($improvementActionCode)) {
                return 2;
            }

            return 0;
        } else if ($type === 2) {
            // Verifica se é necessário analise de implementação
            if ($this->PdaConfigRegra->getEmImplementacao($improvementActionCode)) {
                return 1;
            }

            return 0;
        } else if ($type === 3) {
            // Verifica se é necessário analise de eficacia
            if ($this->PdaConfigRegra->getEmEficacia($improvementActionCode)) {
                return 2;
            }

            return 0;
        }
    }

    public function getAll($userId, $directFilters = [], $otherFilters = [], $orderBy = null, $permissions = [], $codigo_cliente = null)
    {
        
        $conditions = [];

        if (!isset($otherFilters['autor']) && !isset($directFilters['codigo_usuario_responsavel'])) {
            array_push($conditions, "AcoesMelhorias.codigo_usuario_responsavel = $userId");
        }

        if (count($directFilters) > 0) {
            $conditions = array_merge($conditions, $directFilters);
        }

        // Filtros complexos
        if (count($otherFilters) > 0) {
            if (isset($otherFilters['autor'])) {
                $autor = (int) $otherFilters['autor'];

                $permissionsString = '';

                foreach ($permissions as $key => $value) {
                    if ($key === (count($permissions) - 1)) {
                        $permissionsString .= (string) $value;
                    } else {
                        $permissionsString .= (string) $value . ',';
                    }
                }

                switch ($autor) {
                        // Registros da área
                    case 1:
                        $this->UsuariosDados = TableRegistry::get('UsuariosDados');

                        $authenticatedUserRegistration = $this->UsuariosDados->find()
                            ->select([
                                'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                                'matricula' => 'ClienteFuncionario.matricula',
                            ])
                            ->join([
                                [
                                    'table' => 'funcionarios',
                                    'alias' => 'Funcionarios',
                                    'type' => 'INNER',
                                    'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                                ],
                                [
                                    'table' => 'cliente_funcionario',
                                    'alias' => 'ClienteFuncionario',
                                    'type' => 'INNER',
                                    'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                                ]
                            ])
                            ->where([
                                "UsuariosDados.codigo_usuario = $userId",
                                'ClienteFuncionario.matricula IS NOT NULL',
                                'ClienteFuncionario.codigo_cliente IS NOT NULL',
                                'ClienteFuncionario.data_demissao IS NULL',
                            ])
                            ->hydrate(false)
                            ->first();

                        if (
                            isset($authenticatedUserRegistration['codigo_cliente'])
                            && isset($authenticatedUserRegistration['matricula'])
                        ) {
                            $registration = (string) $authenticatedUserRegistration['matricula'];
                            $clientRegistration = (int) $authenticatedUserRegistration['codigo_cliente'];

                            array_push($conditions, "$registration IN (
                                SELECT CF.matricula_chefia_imediata
                                    FROM usuario U
                                        INNER JOIN usuarios_dados UD ON UD.codigo_usuario = U.codigo
                                        INNER JOIN funcionarios FU ON FU.cpf = UD.cpf
                                        INNER JOIN cliente_funcionario CF ON CF.codigo_funcionario = FU.codigo
                                    WHERE
                                        (
                                            U.codigo = AcoesMelhorias.codigo_usuario_responsavel
                                            OR (
                                                U.codigo IN (
                                                    SELECT UR.codigo_usuario
                                                        FROM usuarios_responsaveis UR
                                                            INNER JOIN usuario_subperfil UsuarioSubperfil ON UsuarioSubperfil.codigo_usuario = UR.codigo_usuario
                                                            INNER JOIN subperfil Subperfil ON Subperfil.codigo = UsuarioSubperfil.codigo_subperfil
                                                            INNER JOIN subperfil_acoes SubperfilAcoes ON SubperfilAcoes.codigo_subperfil = Subperfil.codigo
                                                        WHERE
                                                            UR.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao
                                                            AND UR.data_remocao IS NULL
                                                            AND Subperfil.ativo = 1
                                                            AND SubperfilAcoes.codigo_acao IN (8, 9)
                                                        GROUP BY UR.codigo_usuario
                                                )
                                                AND AcoesMelhorias.codigo_acoes_melhorias_status IN (5, 9)
                                            )
                                        )
                                        AND U.codigo <> $userId
                                        AND CF.matricula_chefia_imediata IS NOT NULL
                                        AND CF.codigo_cliente_chefia_imediata IS NOT NULL
                                        AND CF.data_demissao IS NULL
                                        AND CF.matricula_chefia_imediata = $registration
                                        AND CF.codigo_cliente_chefia_imediata = $clientRegistration
                            )");
                        } else {
                            throw new \Exception('Usuário autenticado não possuí os requisitos necessários para a consulta pela área');
                        }
                        break;
                        // Registros como identificador
                    case 2:
                        array_push($conditions, "AcoesMelhorias.codigo_usuario_identificador = $userId");
                        break;
                        // Registros como responsável
                    case 3:
                        //Retornar todas as unidades que o usuário é responsável

                        /**
                         * Verificar se o usuário é multicliente
                         */
                        $Usuario = TableRegistry::get('Usuario');
                        $dadosUsuario = $Usuario->obterDadosDoUsuarioAlocacao($userId);                   

                        $codigosClientesMatrizes = [];
                        if(!empty($dadosUsuario->cliente) && is_array($dadosUsuario->cliente) && count($dadosUsuario->cliente) > 1) {

                            /**
                             * Caso o usuário seja multicliente,
                             * vamos pesquisar em todas as unidades de código matriz,
                             * dos clientes, a ele associado
                             */

                            foreach($dadosUsuario->cliente as $arrayKey => $cliente) {

                                $codigosClientesMatrizes[] = $cliente['codigo'];
                            }
                        }       
                        else {

                            $codigosClientesMatrizes = $codigo_cliente;
                        }         
                        
                        if(empty($codigosClientesMatrizes)) {

                            $codigosClientesMatrizes = $codigo_cliente;
                        }

                        $unidades = $this->retorna_lista_de_unidades_do_grupo_economico($codigosClientesMatrizes);

                        array_push($conditions, "(AcoesMelhorias.data_remocao IS NULL
                                                    AND AcoesMelhorias.codigo_cliente_observacao IN ({$unidades}))");
                        break;
                    default:
                        array_push($conditions, "AcoesMelhorias.codigo_usuario_responsavel = $userId");
                        break;
                }
            }

            // Regras para filtro por período
            if (isset($otherFilters['data_tipo']) && isset($otherFilters['inicio_periodo']) && isset($otherFilters['fim_periodo'])) {
                $dateType = (int) $otherFilters['data_tipo'];

                switch ($dateType) {
                    case 1:
                        array_push($conditions, "AcoesMelhorias.data_inclusao BETWEEN '" . ($otherFilters['inicio_periodo'] . ' 00:00:00') . "' AND '" . ($otherFilters['fim_periodo'] . ' 23:59:59') . "'");
                        break;
                    case 2:
                        array_push($conditions, "AcoesMelhorias.prazo BETWEEN '" . ($otherFilters['inicio_periodo'] . ' 00:00:00') . "' AND '" . ($otherFilters['fim_periodo'] . ' 23:59:59') . "'");
                        break;
                    default:
                        array_push($conditions, "AcoesMelhorias.data_inclusao BETWEEN '" . ($otherFilters['inicio_periodo'] . ' 00:00:00') . "' AND '" . ($otherFilters['fim_periodo'] . ' 23:59:59') . "'");
                        break;
                }
            }
        }

        array_push($conditions, 'AcoesMelhorias.data_remocao IS NULL');

        $fields = [
            'AcoesMelhorias.codigo',
            'AcoesMelhorias.abrangente',
            'AcoesMelhorias.codigo_origem_ferramenta',
            'AcoesMelhorias.codigo_cliente_observacao',
            'AcoesMelhorias.codigo_usuario_identificador',
            'AcoesMelhorias.codigo_usuario_responsavel',
            'AcoesMelhorias.codigo_pos_criticidade',
            'AcoesMelhorias.formulario_resposta',
            'AcoesMelhorias.codigo_acoes_melhorias_tipo',
            'AcoesMelhorias.codigo_acoes_melhorias_status',
            'AcoesMelhorias.prazo',
            'AcoesMelhorias.descricao_desvio',
            'AcoesMelhorias.descricao_acao',
            'AcoesMelhorias.descricao_local_acao',
            'AcoesMelhorias.data_conclusao',
            'AcoesMelhorias.conclusao_observacao',
            'AcoesMelhorias.analise_implementacao_valida',
            'AcoesMelhorias.descricao_analise_implementacao',
            'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
            'AcoesMelhorias.data_analise_implementacao',
            'AcoesMelhorias.analise_eficacia_valida',
            'AcoesMelhorias.descricao_analise_eficacia',
            'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
            'AcoesMelhorias.data_analise_eficacia',
            'AcoesMelhorias.codigo_usuario_inclusao',
            'AcoesMelhorias.data_inclusao',
            'AcoesMelhorias.necessario_abrangencia',
            'AcoesMelhorias.necessario_eficacia',
            'AcoesMelhorias.necessario_implementacao',
            'UsuarioResponsavel.codigo',
            'UsuarioResponsavel.nome',
            'OrigemFerramentas.codigo',
            'OrigemFerramentas.codigo_cliente',
            'OrigemFerramentas.descricao',
            'PosCriticidade.codigo',
            'PosCriticidade.descricao',
            'PosCriticidade.cor',
            'AcoesMelhoriasTipo.codigo',
            'AcoesMelhoriasTipo.descricao',
            'AcoesMelhoriasStatus.codigo',
            'AcoesMelhoriasStatus.descricao',
            'AcoesMelhoriasStatus.cor',
        ];

        try {
            $data = $this->find()
                ->select($fields)
                ->where($conditions)
                ->contain([
                    'PosCriticidade',
                    'AcoesMelhoriasTipo',
                    'AcoesMelhoriasStatus',
                    'OrigemFerramentas',
                    'AcoesMelhoriasSolicitacoes' => [
                        'queryBuilder' => function ($query) {
                            return $query->select([
                                'codigo',
                                'codigo_acao_melhoria',
                                'codigo_acao_melhoria_solicitacao_tipo',
                                'codigo_novo_usuario_responsavel',
                                'codigo_usuario_solicitado',
                                'status',
                                'novo_prazo',
                                'justificativa_solicitacao',
                                'justificativa_recusa',
                                'codigo_usuario_inclusao',
                                'codigo_usuario_alteracao',
                                'data_inclusao',
                                'data_alteracao',
                                'data_remocao',
                                'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                            ])->join([
                                [
                                    'table' => 'usuario',
                                    'alias' => 'UsuarioInclusaoSolicitacao',
                                    'type' => 'LEFT',
                                    'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                ],
                                [
                                    'table' => 'usuario',
                                    'alias' => 'NovoUsuarioResponsavel',
                                    'type' => 'LEFT',
                                    'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                ],
                                [
                                    'table' => 'usuario',
                                    'alias' => 'UsuarioSolicitado',
                                    'type' => 'LEFT',
                                    'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                ],
                            ]);
                        },
                    ],
                    'Cliente' => [
                        'queryBuilder' => function ($query) {
                            return $query->select([
                                'Cliente.codigo',
                                'Cliente.razao_social',
                                'Cliente.nome_fantasia',
                                'Endereco.codigo',
                                'Endereco.cep',
                                'Endereco.logradouro',
                                'Endereco.numero',
                                'Endereco.bairro',
                                'Endereco.cidade',
                                'Endereco.estado_descricao',
                                'Endereco.complemento',
                                'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                            ])
                                ->contain(['Endereco']);
                        },
                    ],
                    'UsuarioResponsavel',
                    'UsuarioIdentificador' => [
                        'queryBuilder' => function ($query) {
                            return $query->select([
                                'UsuarioIdentificador.codigo',
                                'UsuarioIdentificador.nome',
                                'UsuariosDados.codigo',
                                'UsuariosDados.avatar',
                            ])
                                ->contain(['UsuariosDados']);
                        },
                    ],
                    'UsuariosResponsaveis' => [
                        'queryBuilder' => function ($query) {
                            return $query->select([
                                'codigo',
                                'codigo_cliente',
                                'codigo_usuario',
                            ]);
                        },
                    ],
                ]);

            switch ($orderBy) {
                case 1:
                    $data->orderAsc('AcoesMelhorias.codigo_pos_criticidade');
                    break;
                case 2:
                    $data->orderAsc('AcoesMelhorias.codigo_origem_ferramenta');
                    break;
                case 3:
                    $data->orderAsc('AcoesMelhorias.codigo_acoes_melhorias_tipo');
                    break;
                default:
                    $data->orderDesc('AcoesMelhorias.codigo');
                    break;
            }

            $dados = $data->hydrate(false)->all()->toArray();

            if (!empty($dados)) {
                // debug($dados);exit;
                foreach ($dados as $key => $val) {
                    $dados[$key]['localidade']['endereco']['cidade'] = Comum::converterEncodingPara($dados[$key]['localidade']['endereco']['cidade']);
                    $dados[$key]['origem_ferramenta']['descricao'] = Comum::converterEncodingPara($dados[$key]['origem_ferramenta']['descricao']);
                    $dados[$key]['criticidade']['descricao'] = Comum::converterEncodingPara($dados[$key]['criticidade']['descricao']);
                    $dados[$key]['tipo']['descricao'] = Comum::converterEncodingPara($dados[$key]['tipo']['descricao']);

                    if (empty($dados[$key]['responsavel']) && !empty($dados[$key]['solicitacoes'])) {
                        $dados[$key]['responsavel'] = array();
                        $dados[$key]['responsavel']['codigo'] = isset($dados[$key]['solicitacoes'][0]['codigo_usuario_solicitado']) ? $dados[$key]['solicitacoes'][0]['codigo_usuario_solicitado'] : null;
                        $dados[$key]['responsavel']['nome'] = isset($dados[$key]['solicitacoes'][0]['nome_usuario_solicitado']) ? $dados[$key]['solicitacoes'][0]['nome_usuario_solicitado'] : null;
                    }
                }
            }

            return $dados;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function getById($userId, $improvementActionCode, $arr_ids = null)
    {
        $data = [
            'error' => null,
            'registry' => null,
        ];

        $fields = [
            'AcoesMelhorias.codigo',
            'AcoesMelhorias.abrangente',
            'AcoesMelhorias.codigo_origem_ferramenta',
            'AcoesMelhorias.codigo_cliente_observacao',
            'AcoesMelhorias.codigo_usuario_identificador',
            'AcoesMelhorias.codigo_usuario_responsavel',
            'AcoesMelhorias.codigo_pos_criticidade',
            'AcoesMelhorias.codigo_acoes_melhorias_tipo',
            'AcoesMelhorias.codigo_acoes_melhorias_status',
            'AcoesMelhorias.formulario_resposta',
            'AcoesMelhorias.prazo',
            'AcoesMelhorias.descricao_desvio',
            'AcoesMelhorias.descricao_acao',
            'AcoesMelhorias.descricao_local_acao',
            'AcoesMelhorias.data_conclusao',
            'AcoesMelhorias.conclusao_observacao',
            'AcoesMelhorias.analise_implementacao_valida',
            'AcoesMelhorias.descricao_analise_implementacao',
            'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
            'AcoesMelhorias.data_analise_implementacao',
            'AcoesMelhorias.analise_eficacia_valida',
            'AcoesMelhorias.descricao_analise_eficacia',
            'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
            'AcoesMelhorias.data_analise_eficacia',
            'AcoesMelhorias.codigo_usuario_inclusao',
            'AcoesMelhorias.data_inclusao',
            'AcoesMelhorias.necessario_abrangencia',
            'AcoesMelhorias.necessario_eficacia',
            'AcoesMelhorias.necessario_implementacao',
            'UsuarioIdentificador.codigo',
            'UsuarioIdentificador.nome',
            'UsuarioResponsavel.codigo',
            'UsuarioResponsavel.nome',
            'OrigemFerramentas.codigo',
            'OrigemFerramentas.codigo_cliente',
            'OrigemFerramentas.descricao',
            'PosCriticidade.codigo',
            'PosCriticidade.descricao',
            'PosCriticidade.cor',
            'AcoesMelhoriasTipo.codigo',
            'AcoesMelhoriasTipo.descricao',
            'AcoesMelhoriasStatus.codigo',
            'AcoesMelhoriasStatus.descricao',
            'AcoesMelhoriasStatus.cor',
        ];

        try {
            if (is_null($arr_ids)) {
                $conditions = [
                    "AcoesMelhorias.codigo = $improvementActionCode",
                    "AcoesMelhorias.data_remocao IS NULL",
                ];

                $improvementAction = $this->getRelationDetalhes($fields, $conditions)->first();

                if (!$improvementAction) {
                    $data['error'] = [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ];

                    return $data;
                }

                if (count($improvementAction['acoes_associadas']) > 0) {
                    foreach ($improvementAction['acoes_associadas'] as $key => $association) {
                        $improvementAction['acoes_associadas'][$key]->acao = $this->getSimplifiedRelationship([
                            'AcoesMelhorias.codigo' => $association->codigo_acao_melhoria_relacionada,
                            'AcoesMelhorias.data_remocao IS NULL',
                        ]);
                    }
                }
            } else {
                if (count($arr_ids) === 0) {
                    $data['registry'] = [];

                    return $data;
                }

                $conditions = [
                    "AcoesMelhorias.codigo IN (" . implode(',', $arr_ids) . ")",
                    "AcoesMelhorias.data_remocao IS NULL",
                ];

                $improvementAction = $this->getRelationDetalhes($fields, $conditions)->all();

                if (!$improvementAction) {
                    $data['error'] = [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ];

                    return $data;
                }
            }


            if (isset($improvementAction->localidade)) {
                //corrigi o utf8
                $improvementAction->localidade->endereco->cidade = Comum::converterEncodingPara($improvementAction->localidade->endereco->cidade);
                // $improvementAction->origem_ferramenta->descricao = Comum::converterEncodingPara($improvementAction->origem_ferramenta->descricao);
                // $improvementAction->criticidade->descricao = Comum::converterEncodingPara($improvementAction->criticidade->descricao);
                $improvementAction->tipo->descricao = Comum::converterEncodingPara($improvementAction->tipo->descricao);
            } else {

                foreach ($improvementAction as $key => $val) {
                    //corrigi o utf8
                    $val->localidade->endereco->cidade = Comum::converterEncodingPara($val->localidade->endereco->cidade);
                    $val->tipo->descricao = Comum::converterEncodingPara($val->tipo->descricao);
                }
            }


            $data['registry'] = $improvementAction;

            return $data;
        } catch (\Exception $exception) {
            $data = [
                'error' => [
                    'message' => $exception->getMessage(),
                ],
                'registry' => null,
            ];

            return $data;
        }
    }

    public function getRelationDetalhes($fields, $conditions)
    {
        $improvementAction = $this->find()
            ->select($fields)
            ->where($conditions)
            ->contain([
                'PosCriticidade',
                'AcoesMelhoriasTipo',
                'AcoesMelhoriasStatus',
                'AcoesMelhoriasAnexos' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'codigo',
                            'codigo_acao_melhoria',
                            'arquivo_nome',
                            'arquivo_tamanho',
                            'arquivo_url',
                            'arquivo_tipo',
                            'codigo_usuario_inclusao',
                            'data_inclusao',
                        ]);
                    },
                ],
                'UsuariosResponsaveis' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'codigo',
                            'codigo_cliente',
                            'codigo_usuario',
                        ]);
                    },
                ],
                'AcoesMelhoriasSolicitacoes' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'codigo',
                            'codigo_acao_melhoria',
                            'codigo_acao_melhoria_solicitacao_tipo',
                            'codigo_novo_usuario_responsavel',
                            'codigo_usuario_solicitado',
                            'status',
                            'novo_prazo',
                            'justificativa_solicitacao',
                            'justificativa_recusa',
                            'codigo_usuario_inclusao',
                            'codigo_usuario_alteracao',
                            'data_inclusao',
                            'data_alteracao',
                            'data_remocao',
                            'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                            'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                            'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                        ])->join([
                            [
                                'table' => 'usuario',
                                'alias' => 'UsuarioInclusaoSolicitacao',
                                'type' => 'LEFT',
                                'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                            ],
                            [
                                'table' => 'usuario',
                                'alias' => 'NovoUsuarioResponsavel',
                                'type' => 'LEFT',
                                'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                            ],
                            [
                                'table' => 'usuario',
                                'alias' => 'UsuarioSolicitado',
                                'type' => 'LEFT',
                                'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                            ],
                        ]);
                    },
                ],
                'AcoesMelhoriasAssociadas' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'codigo',
                            'codigo_acao_melhoria_principal',
                            'codigo_acao_melhoria_relacionada',
                            'tipo_relacao',
                            'abrangente',
                            'codigo_usuario_inclusao',
                            'data_inclusao',
                        ]);
                    },
                ],
                'Cliente' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'Cliente.codigo',
                            'Cliente.razao_social',
                            'Cliente.nome_fantasia',
                            'Endereco.codigo',
                            'Endereco.cep',
                            'Endereco.logradouro',
                            'Endereco.numero',
                            'Endereco.bairro',
                            'Endereco.cidade',
                            'Endereco.estado_descricao',
                            'Endereco.complemento',
                            'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                        ])->contain(['Endereco']);
                    },
                ],
                'OrigemFerramentas',
                'UsuarioResponsavel',
                'UsuarioIdentificador',
            ]);

        return $improvementAction;
    }

    private function getSimplifiedRelationship($conditions)
    {
        $fields = [
            'AcoesMelhorias.codigo',
            'AcoesMelhorias.abrangente',
            'AcoesMelhorias.codigo_origem_ferramenta',
            'AcoesMelhorias.codigo_cliente_observacao',
            'AcoesMelhorias.codigo_usuario_identificador',
            'AcoesMelhorias.codigo_usuario_responsavel',
            'AcoesMelhorias.codigo_pos_criticidade',
            'AcoesMelhorias.codigo_acoes_melhorias_tipo',
            'AcoesMelhorias.codigo_acoes_melhorias_status',
            'AcoesMelhorias.descricao_acao',
            'AcoesMelhorias.codigo_usuario_inclusao',
            'AcoesMelhorias.data_inclusao',
            'AcoesMelhoriasTipo.codigo',
            'AcoesMelhoriasTipo.descricao',
            'OrigemFerramentas.codigo',
            'OrigemFerramentas.codigo_cliente',
            'OrigemFerramentas.descricao',
        ];

        $improvementAction = $this->find()
            ->select($fields)
            ->where($conditions)
            ->contain([
                'AcoesMelhoriasTipo',
                'OrigemFerramentas',
                'UsuarioIdentificador' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'UsuarioIdentificador.codigo',
                            'UsuarioIdentificador.nome',
                            'UsuariosDados.codigo',
                            'UsuariosDados.avatar',
                        ])
                            ->contain(['UsuariosDados']);
                    },
                ],
            ])
            ->first();

        return $improvementAction;
    }

    public function getPendencies(int $userId = null, int $status = null, array $permissions = [])
    {
        try {
            switch ($status) {
                    // Aceite ou transferência
                case 1:
                    // Postergação
                case 2:
                    // Cancelamento
                case 3:
                    if ($status === 1 && (!in_array(2, $permissions) && !in_array(3, $permissions) && !in_array(4, $permissions))) {
                        return [];
                    } else if ($status === 2 && !in_array(11, $permissions)) {
                        return [];
                    } else if ($status === 3 && !in_array(12, $permissions)) {
                        return [];
                    }

                    $conditions = [
                        "(AcoesMelhoriasSolicitacoes.usuario_solicitado_tipo = 2 AND $userId IN (
                            SELECT UsuariosResponsaveis.codigo_usuario FROM usuarios_responsaveis UsuariosResponsaveis
                            WHERE UsuariosResponsaveis.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao AND UsuariosResponsaveis.data_remocao IS NULL
                        ) OR AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado = $userId)",
                        'AcoesMelhoriasSolicitacoes.status' => 1,
                        'AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo' => $status,
                        'AcoesMelhorias.data_remocao IS NULL',
                    ];

                    $fields = [
                        'AcoesMelhorias.codigo',
                        'AcoesMelhorias.abrangente',
                        'AcoesMelhorias.codigo_origem_ferramenta',
                        'AcoesMelhorias.codigo_cliente_observacao',
                        'AcoesMelhorias.codigo_usuario_identificador',
                        'AcoesMelhorias.codigo_usuario_responsavel',
                        'AcoesMelhorias.codigo_pos_criticidade',
                        'AcoesMelhorias.formulario_resposta',
                        'AcoesMelhorias.codigo_acoes_melhorias_tipo',
                        'AcoesMelhorias.codigo_acoes_melhorias_status',
                        'AcoesMelhorias.prazo',
                        'AcoesMelhorias.descricao_desvio',
                        'AcoesMelhorias.descricao_acao',
                        'AcoesMelhorias.descricao_local_acao',
                        'AcoesMelhorias.data_conclusao',
                        'AcoesMelhorias.conclusao_observacao',
                        'AcoesMelhorias.analise_implementacao_valida',
                        'AcoesMelhorias.descricao_analise_implementacao',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
                        'AcoesMelhorias.data_analise_implementacao',
                        'AcoesMelhorias.analise_eficacia_valida',
                        'AcoesMelhorias.descricao_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
                        'AcoesMelhorias.data_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_inclusao',
                        'AcoesMelhorias.data_inclusao',
                        'UsuarioResponsavel.codigo',
                        'UsuarioResponsavel.nome',
                        'OrigemFerramentas.codigo',
                        'OrigemFerramentas.codigo_cliente',
                        'OrigemFerramentas.descricao',
                        'PosCriticidade.codigo',
                        'PosCriticidade.descricao',
                        'PosCriticidade.cor',
                        'AcoesMelhoriasTipo.codigo',
                        'AcoesMelhoriasTipo.descricao',
                        'AcoesMelhoriasStatus.codigo',
                        'AcoesMelhoriasStatus.descricao',
                        'AcoesMelhoriasStatus.cor',
                    ];

                    $data = $this->find()
                        ->select($fields)
                        ->where($conditions)
                        ->join([
                            [
                                'table' => 'acoes_melhorias_solicitacoes',
                                'alias' => 'AcoesMelhoriasSolicitacoes',
                                'type' => 'INNER',
                                'conditions' => 'AcoesMelhoriasSolicitacoes.codigo_acao_melhoria = AcoesMelhorias.codigo',
                            ],
                        ])
                        ->contain([
                            'PosCriticidade',
                            'AcoesMelhoriasTipo',
                            'AcoesMelhoriasStatus',
                            'OrigemFerramentas',
                            'AcoesMelhoriasSolicitacoes' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'codigo',
                                        'codigo_acao_melhoria',
                                        'codigo_acao_melhoria_solicitacao_tipo',
                                        'codigo_novo_usuario_responsavel',
                                        'codigo_usuario_solicitado',
                                        'status',
                                        'novo_prazo',
                                        'justificativa_solicitacao',
                                        'justificativa_recusa',
                                        'codigo_usuario_inclusao',
                                        'codigo_usuario_alteracao',
                                        'data_inclusao',
                                        'data_alteracao',
                                        'data_remocao',
                                        'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                        'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                        'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                                    ])->join([
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioInclusaoSolicitacao',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'NovoUsuarioResponsavel',
                                            'type' => 'LEFT',
                                            'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioSolicitado',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                        ],
                                    ]);
                                },
                            ],
                            'Cliente' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'Cliente.codigo',
                                        'Cliente.razao_social',
                                        'Cliente.nome_fantasia',
                                        'Endereco.codigo',
                                        'Endereco.cep',
                                        'Endereco.logradouro',
                                        'Endereco.numero',
                                        'Endereco.bairro',
                                        'Endereco.cidade',
                                        'Endereco.estado_descricao',
                                        'Endereco.complemento',
                                        'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                                    ])
                                        ->contain(['Endereco']);
                                },
                            ],
                            'UsuarioResponsavel',
                            'UsuarioIdentificador' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'UsuarioIdentificador.codigo',
                                        'UsuarioIdentificador.nome',
                                        'UsuariosDados.codigo',
                                        'UsuariosDados.avatar',
                                    ])
                                        ->contain(['UsuariosDados']);
                                },
                            ],
                        ]);

                    $dados = $data->orderAsc('AcoesMelhorias.codigo')->hydrate(false)->all()->toArray();

                    if (!empty($dados)) {
                        // debug($dados);exit;
                        foreach ($dados as $key => $val) {
                            $dados[$key]['localidade']['endereco']['cidade'] = Comum::converterEncodingPara($dados[$key]['localidade']['endereco']['cidade']);
                            $dados[$key]['origem_ferramenta']['descricao'] = Comum::converterEncodingPara($dados[$key]['origem_ferramenta']['descricao']);
                            $dados[$key]['criticidade']['descricao'] = Comum::converterEncodingPara($dados[$key]['criticidade']['descricao']);
                            $dados[$key]['tipo']['descricao'] = Comum::converterEncodingPara($dados[$key]['tipo']['descricao']);
                        }
                    }

                    return $dados;

                    break;
                    // Abrangencia
                case 4:
                    if (!in_array(10, $permissions)) {
                        return [];
                    }

                    $conditions = [
                        'AcoesMelhorias.data_remocao IS NULL',
                        'AcoesMelhorias.codigo_acoes_melhorias_status <> 6',
                        '(
                            (
                                (SELECT COUNT(ama.codigo) FROM acoes_melhorias_associadas ama WHERE ama.abrangente IS NULL AND ama.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) > 0
                                AND (SELECT COUNT(amat.codigo) FROM acoes_melhorias_associadas amat WHERE amat.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) > 0
                            ) OR (
                                (SELECT COUNT(amas.codigo) FROM acoes_melhorias_associadas amas WHERE amas.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) = 0 AND AcoesMelhorias.abrangente IS NULL
                            )
                        )',
                        "$userId IN (
                            SELECT UsuariosResponsaveis.codigo_usuario FROM usuarios_responsaveis UsuariosResponsaveis
                            WHERE UsuariosResponsaveis.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao AND UsuariosResponsaveis.data_remocao IS NULL
                        )",
                        'AcoesMelhorias.necessario_abrangencia = 1',
                    ];

                    $fields = [
                        'AcoesMelhorias.codigo',
                        'AcoesMelhorias.codigo_origem_ferramenta',
                        'AcoesMelhorias.codigo_cliente_observacao',
                        'AcoesMelhorias.codigo_usuario_identificador',
                        'AcoesMelhorias.codigo_usuario_responsavel',
                        'AcoesMelhorias.codigo_pos_criticidade',
                        'AcoesMelhorias.codigo_acoes_melhorias_tipo',
                        'AcoesMelhorias.codigo_acoes_melhorias_status',
                        'AcoesMelhorias.formulario_resposta',
                        'AcoesMelhorias.prazo',
                        'AcoesMelhorias.abrangente',
                        'AcoesMelhorias.descricao_desvio',
                        'AcoesMelhorias.descricao_acao',
                        'AcoesMelhorias.descricao_local_acao',
                        'AcoesMelhorias.data_conclusao',
                        'AcoesMelhorias.conclusao_observacao',
                        'AcoesMelhorias.analise_implementacao_valida',
                        'AcoesMelhorias.descricao_analise_implementacao',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
                        'AcoesMelhorias.data_analise_implementacao',
                        'AcoesMelhorias.analise_eficacia_valida',
                        'AcoesMelhorias.descricao_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
                        'AcoesMelhorias.data_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_inclusao',
                        'AcoesMelhorias.data_inclusao',
                        'UsuarioResponsavel.codigo',
                        'UsuarioResponsavel.nome',
                        'OrigemFerramentas.codigo',
                        'OrigemFerramentas.codigo_cliente',
                        'OrigemFerramentas.descricao',
                        'PosCriticidade.codigo',
                        'PosCriticidade.descricao',
                        'PosCriticidade.cor',
                        'AcoesMelhoriasTipo.codigo',
                        'AcoesMelhoriasTipo.descricao',
                        'AcoesMelhoriasStatus.codigo',
                        'AcoesMelhoriasStatus.descricao',
                        'AcoesMelhoriasStatus.cor',
                    ];

                    $data = $this->find()
                        ->select($fields)
                        ->where($conditions)
                        ->contain([
                            'PosCriticidade',
                            'AcoesMelhoriasTipo',
                            'AcoesMelhoriasStatus',
                            'OrigemFerramentas',
                            'Cliente' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'Cliente.codigo',
                                        'Cliente.razao_social',
                                        'Cliente.nome_fantasia',
                                        'Endereco.codigo',
                                        'Endereco.cep',
                                        'Endereco.logradouro',
                                        'Endereco.numero',
                                        'Endereco.bairro',
                                        'Endereco.cidade',
                                        'Endereco.estado_descricao',
                                        'Endereco.complemento',
                                        'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                                    ])
                                        ->contain(['Endereco']);
                                },
                            ],
                            'AcoesMelhoriasAssociadas' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'codigo',
                                        'codigo_acao_melhoria_principal',
                                        'codigo_acao_melhoria_relacionada',
                                        'tipo_relacao',
                                        'abrangente',
                                        'codigo_usuario_inclusao',
                                        'data_inclusao',
                                    ]);
                                },
                            ],
                            'AcoesMelhoriasSolicitacoes' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'codigo',
                                        'codigo_acao_melhoria',
                                        'codigo_acao_melhoria_solicitacao_tipo',
                                        'codigo_novo_usuario_responsavel',
                                        'codigo_usuario_solicitado',
                                        'status',
                                        'novo_prazo',
                                        'justificativa_solicitacao',
                                        'justificativa_recusa',
                                        'codigo_usuario_inclusao',
                                        'codigo_usuario_alteracao',
                                        'data_inclusao',
                                        'data_alteracao',
                                        'data_remocao',
                                        'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                        'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                        'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                                    ])->join([
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioInclusaoSolicitacao',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'NovoUsuarioResponsavel',
                                            'type' => 'LEFT',
                                            'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioSolicitado',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                        ],
                                    ]);
                                },
                            ],
                            'UsuarioResponsavel',
                            'UsuarioIdentificador' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'UsuarioIdentificador.codigo',
                                        'UsuarioIdentificador.nome',
                                        'UsuariosDados.codigo',
                                        'UsuariosDados.avatar',
                                    ])
                                        ->contain(['UsuariosDados']);
                                },
                            ],
                        ]);

                    $dados = $data->orderAsc('AcoesMelhorias.codigo')->hydrate(false)->all()->toArray();

                    if (!empty($dados)) {
                        // debug($dados);exit;
                        foreach ($dados as $key => $val) {
                            $dados[$key]['localidade']['endereco']['cidade'] = Comum::converterEncodingPara($dados[$key]['localidade']['endereco']['cidade']);
                            $dados[$key]['origem_ferramenta']['descricao'] = Comum::converterEncodingPara($dados[$key]['origem_ferramenta']['descricao']);
                            $dados[$key]['criticidade']['descricao'] = Comum::converterEncodingPara($dados[$key]['criticidade']['descricao']);
                            $dados[$key]['tipo']['descricao'] = Comum::converterEncodingPara($dados[$key]['tipo']['descricao']);
                        }
                    }

                    return $dados;

                    break;
                    // A vencer
                case 5:
                    if (!in_array(13, $permissions)) {
                        return [];
                    }

                    $conditions = [
                        'AcoesMelhorias.codigo_usuario_responsavel' => $userId,
                        'AcoesMelhorias.codigo_acoes_melhorias_status IN (1, 2, 3, 4)',
                        'AcoesMelhorias.data_remocao IS NULL',
                        'AcoesMelhorias.prazo IS NOT NULL',
                        'DATEDIFF(DAY, GETDATE(), AcoesMelhorias.prazo) <= RegraAcao.dias_a_vencer',
                        'DATEDIFF(DAY, GETDATE(), AcoesMelhorias.prazo) > 0',
                    ];

                    $joins = [
                        [
                            'table' => 'regra_acao',
                            'alias' => 'RegraAcao',
                            'type' => 'LEFT',
                            'conditions' => 'RegraAcao.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao',
                        ],
                    ];

                    $fields = [
                        'AcoesMelhorias.codigo',
                        'AcoesMelhorias.abrangente',
                        'AcoesMelhorias.codigo_origem_ferramenta',
                        'AcoesMelhorias.codigo_cliente_observacao',
                        'AcoesMelhorias.codigo_usuario_identificador',
                        'AcoesMelhorias.codigo_usuario_responsavel',
                        'AcoesMelhorias.codigo_pos_criticidade',
                        'AcoesMelhorias.codigo_acoes_melhorias_tipo',
                        'AcoesMelhorias.codigo_acoes_melhorias_status',
                        'AcoesMelhorias.formulario_resposta',
                        'AcoesMelhorias.prazo',
                        'AcoesMelhorias.descricao_desvio',
                        'AcoesMelhorias.descricao_acao',
                        'AcoesMelhorias.descricao_local_acao',
                        'AcoesMelhorias.data_conclusao',
                        'AcoesMelhorias.conclusao_observacao',
                        'AcoesMelhorias.analise_implementacao_valida',
                        'AcoesMelhorias.descricao_analise_implementacao',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
                        'AcoesMelhorias.data_analise_implementacao',
                        'AcoesMelhorias.analise_eficacia_valida',
                        'AcoesMelhorias.descricao_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
                        'AcoesMelhorias.data_analise_eficacia',
                        'AcoesMelhorias.codigo_usuario_inclusao',
                        'AcoesMelhorias.data_inclusao',
                        'UsuarioResponsavel.codigo',
                        'UsuarioResponsavel.nome',
                        'OrigemFerramentas.codigo',
                        'OrigemFerramentas.codigo_cliente',
                        'OrigemFerramentas.descricao',
                        'PosCriticidade.codigo',
                        'PosCriticidade.descricao',
                        'PosCriticidade.cor',
                        'AcoesMelhoriasTipo.codigo',
                        'AcoesMelhoriasTipo.descricao',
                        'AcoesMelhoriasStatus.codigo',
                        'AcoesMelhoriasStatus.descricao',
                        'AcoesMelhoriasStatus.cor',
                        'dias_a_vencer' => 'DATEDIFF(DAY, GETDATE(), AcoesMelhorias.prazo)',
                    ];

                    $data = $this->find()
                        ->select($fields)
                        ->where($conditions)
                        ->join($joins)
                        ->contain([
                            'PosCriticidade',
                            'AcoesMelhoriasTipo',
                            'AcoesMelhoriasStatus',
                            'OrigemFerramentas',
                            'AcoesMelhoriasSolicitacoes' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'codigo',
                                        'codigo_acao_melhoria',
                                        'codigo_acao_melhoria_solicitacao_tipo',
                                        'codigo_novo_usuario_responsavel',
                                        'codigo_usuario_solicitado',
                                        'status',
                                        'novo_prazo',
                                        'justificativa_solicitacao',
                                        'justificativa_recusa',
                                        'codigo_usuario_inclusao',
                                        'codigo_usuario_alteracao',
                                        'data_inclusao',
                                        'data_alteracao',
                                        'data_remocao',
                                        'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                        'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                        'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                                    ])->join([
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioInclusaoSolicitacao',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'NovoUsuarioResponsavel',
                                            'type' => 'LEFT',
                                            'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioSolicitado',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                        ],
                                    ]);
                                },
                            ],
                            'Cliente' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'Cliente.codigo',
                                        'Cliente.razao_social',
                                        'Cliente.nome_fantasia',
                                        'Endereco.codigo',
                                        'Endereco.cep',
                                        'Endereco.logradouro',
                                        'Endereco.numero',
                                        'Endereco.bairro',
                                        'Endereco.cidade',
                                        'Endereco.estado_descricao',
                                        'Endereco.complemento',
                                        'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                                    ])
                                        ->contain(['Endereco']);
                                },
                            ],
                            'UsuarioResponsavel',
                            'UsuarioIdentificador' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'UsuarioIdentificador.codigo',
                                        'UsuarioIdentificador.nome',
                                        'UsuariosDados.codigo',
                                        'UsuariosDados.avatar',
                                    ])
                                        ->contain(['UsuariosDados']);
                                },
                            ],
                        ]);

                    $dados = $data->hydrate(false)->all()->toArray();

                    if (!empty($dados)) {
                        // debug($dados);exit;
                        foreach ($dados as $key => $val) {
                            $dados[$key]['localidade']['endereco']['cidade'] = Comum::converterEncodingPara($dados[$key]['localidade']['endereco']['cidade']);
                            $dados[$key]['origem_ferramenta']['descricao'] = Comum::converterEncodingPara($dados[$key]['origem_ferramenta']['descricao']);
                            $dados[$key]['criticidade']['descricao'] = Comum::converterEncodingPara($dados[$key]['criticidade']['descricao']);
                            $dados[$key]['tipo']['descricao'] = Comum::converterEncodingPara($dados[$key]['tipo']['descricao']);
                        }
                    }

                    return $dados;
                    break;
                    // Vencidas do meu time
                case 6:
                    if (!in_array(14, $permissions)) {
                        return [];
                    }

                    $this->UsuariosDados = TableRegistry::get('UsuariosDados');

                    $authenticatedUserRegistration = $this->UsuariosDados->find()
                        ->select([
                            'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                            'matricula' => 'ClienteFuncionario.matricula',
                        ])
                        ->join([
                            [
                                'table' => 'funcionarios',
                                'alias' => 'Funcionarios',
                                'type' => 'INNER',
                                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                            ],
                            [
                                'table' => 'cliente_funcionario',
                                'alias' => 'ClienteFuncionario',
                                'type' => 'INNER',
                                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                            ]
                        ])
                        ->where([
                            "UsuariosDados.codigo_usuario = $userId",
                            'ClienteFuncionario.matricula IS NOT NULL',
                            'ClienteFuncionario.codigo_cliente IS NOT NULL',
                            'ClienteFuncionario.data_demissao IS NULL',
                        ])
                        ->hydrate(false)
                        ->first();

                    if (
                        isset($authenticatedUserRegistration['codigo_cliente'])
                        && isset($authenticatedUserRegistration['matricula'])
                    ) {
                        $registration = (string) $authenticatedUserRegistration['matricula'];
                        $clientRegistration = (int) $authenticatedUserRegistration['codigo_cliente'];

                        $conditions = [
                            'AcoesMelhorias.codigo_acoes_melhorias_status = 4',
                            'AcoesMelhorias.data_remocao IS NULL',
                            "$registration IN (
                                SELECT CF.matricula_chefia_imediata
                                    FROM usuario U
                                        INNER JOIN usuarios_dados UD ON UD.codigo_usuario = U.codigo
                                        INNER JOIN funcionarios FU ON FU.cpf = UD.cpf
                                        INNER JOIN cliente_funcionario CF ON CF.codigo_funcionario = FU.codigo
                                    WHERE
                                        (
                                            U.codigo = AcoesMelhorias.codigo_usuario_responsavel
                                            OR (
                                                U.codigo IN (
                                                    SELECT UR.codigo_usuario
                                                        FROM usuarios_responsaveis UR
                                                        WHERE
                                                            UR.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao
                                                            AND UR.data_remocao IS NULL
                                                )
                                            )
                                        )
                                        AND U.codigo <> $userId
                                        AND CF.matricula_chefia_imediata IS NOT NULL
                                        AND CF.codigo_cliente_chefia_imediata IS NOT NULL
                                        AND CF.data_demissao IS NULL
                                        AND CF.matricula_chefia_imediata = $registration
                                        AND CF.codigo_cliente_chefia_imediata = $clientRegistration
                            )"
                        ];

                        $fields = [
                            'AcoesMelhorias.codigo',
                            'AcoesMelhorias.abrangente',
                            'AcoesMelhorias.codigo_origem_ferramenta',
                            'AcoesMelhorias.codigo_cliente_observacao',
                            'AcoesMelhorias.codigo_usuario_identificador',
                            'AcoesMelhorias.codigo_usuario_responsavel',
                            'AcoesMelhorias.codigo_pos_criticidade',
                            'AcoesMelhorias.codigo_acoes_melhorias_tipo',
                            'AcoesMelhorias.codigo_acoes_melhorias_status',
                            'AcoesMelhorias.formulario_resposta',
                            'AcoesMelhorias.prazo',
                            'AcoesMelhorias.descricao_desvio',
                            'AcoesMelhorias.descricao_acao',
                            'AcoesMelhorias.descricao_local_acao',
                            'AcoesMelhorias.data_conclusao',
                            'AcoesMelhorias.conclusao_observacao',
                            'AcoesMelhorias.analise_implementacao_valida',
                            'AcoesMelhorias.descricao_analise_implementacao',
                            'AcoesMelhorias.codigo_usuario_responsavel_analise_implementacao',
                            'AcoesMelhorias.data_analise_implementacao',
                            'AcoesMelhorias.analise_eficacia_valida',
                            'AcoesMelhorias.descricao_analise_eficacia',
                            'AcoesMelhorias.codigo_usuario_responsavel_analise_eficacia',
                            'AcoesMelhorias.data_analise_eficacia',
                            'AcoesMelhorias.codigo_usuario_inclusao',
                            'AcoesMelhorias.data_inclusao',
                            'UsuarioResponsavel.codigo',
                            'UsuarioResponsavel.nome',
                            'OrigemFerramentas.codigo',
                            'OrigemFerramentas.codigo_cliente',
                            'OrigemFerramentas.descricao',
                            'PosCriticidade.codigo',
                            'PosCriticidade.descricao',
                            'PosCriticidade.cor',
                            'AcoesMelhoriasTipo.codigo',
                            'AcoesMelhoriasTipo.descricao',
                            'AcoesMelhoriasStatus.codigo',
                            'AcoesMelhoriasStatus.descricao',
                            'AcoesMelhoriasStatus.cor',
                        ];

                        $data = $this->find()
                            ->select($fields)
                            ->where($conditions)
                            ->contain([
                                'PosCriticidade',
                                'AcoesMelhoriasTipo',
                                'AcoesMelhoriasStatus',
                                'OrigemFerramentas',
                                'AcoesMelhoriasSolicitacoes' => [
                                    'queryBuilder' => function ($query) {
                                        return $query->select([
                                            'codigo',
                                            'codigo_acao_melhoria',
                                            'codigo_acao_melhoria_solicitacao_tipo',
                                            'codigo_novo_usuario_responsavel',
                                            'codigo_usuario_solicitado',
                                            'status',
                                            'novo_prazo',
                                            'justificativa_solicitacao',
                                            'justificativa_recusa',
                                            'codigo_usuario_inclusao',
                                            'codigo_usuario_alteracao',
                                            'data_inclusao',
                                            'data_alteracao',
                                            'data_remocao',
                                            'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                            'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                            'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                                        ])->join([
                                            [
                                                'table' => 'usuario',
                                                'alias' => 'UsuarioInclusaoSolicitacao',
                                                'type' => 'LEFT',
                                                'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                            ],
                                            [
                                                'table' => 'usuario',
                                                'alias' => 'NovoUsuarioResponsavel',
                                                'type' => 'LEFT',
                                                'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                            ],
                                            [
                                                'table' => 'usuario',
                                                'alias' => 'UsuarioSolicitado',
                                                'type' => 'LEFT',
                                                'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                            ],
                                        ]);
                                    },
                                ],
                                'Cliente' => [
                                    'queryBuilder' => function ($query) {
                                        return $query->select([
                                            'Cliente.codigo',
                                            'Cliente.razao_social',
                                            'Cliente.nome_fantasia',
                                            'Endereco.codigo',
                                            'Endereco.cep',
                                            'Endereco.logradouro',
                                            'Endereco.numero',
                                            'Endereco.bairro',
                                            'Endereco.cidade',
                                            'Endereco.estado_descricao',
                                            'Endereco.complemento',
                                            'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                                        ])
                                            ->contain(['Endereco']);
                                    },
                                ],
                                'UsuarioResponsavel',
                                'UsuarioIdentificador' => [
                                    'queryBuilder' => function ($query) {
                                        return $query->select([
                                            'UsuarioIdentificador.codigo',
                                            'UsuarioIdentificador.nome',
                                            'UsuariosDados.codigo',
                                            'UsuariosDados.avatar',
                                        ])
                                            ->contain(['UsuariosDados']);
                                    },
                                ],
                            ]);

                        return $data->all()->toArray();
                    } else {
                        return [];
                    }
                    break;
                default:
                    return [];
                    break;
            }
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function countPendencies($userId, $status = null, $closeToMaturity = false, $permissions = [])
    {
        $conditions = ['data_remocao IS NULL'];

        $joins = [];

        if ($closeToMaturity) {
            if (!in_array(13, $permissions)) {
                return 0;
            }

            $conditions[] = 'AcoesMelhorias.prazo IS NOT NULL';
            $conditions[] = 'AcoesMelhorias.codigo_acoes_melhorias_status IN (1, 2, 3, 4)';
            $conditions[] = 'DATEDIFF(DAY, GETDATE(), AcoesMelhorias.prazo) <= RegraAcao.dias_a_vencer';
            $conditions[] = 'DATEDIFF(DAY, GETDATE(), AcoesMelhorias.prazo) > 0';

            $joins[] = [
                'table' => 'regra_acao',
                'alias' => 'RegraAcao',
                'type' => 'LEFT',
                'conditions' => 'RegraAcao.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao',
            ];
        }

        switch ($status) {
            case 3:
                $conditions['codigo_acoes_melhorias_status'] = $status;
                $conditions['codigo_usuario_responsavel'] = $userId;
                break;
            case 4:
                if (!in_array(10, $permissions)) {
                    return 0;
                }

                $conditions[] = 'AcoesMelhorias.codigo_acoes_melhorias_status <> 6';
                $conditions[] = '(
                    (
                        (SELECT COUNT(ama.codigo) FROM acoes_melhorias_associadas ama WHERE ama.abrangente IS NULL AND ama.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) > 0
                        AND (SELECT COUNT(amat.codigo) FROM acoes_melhorias_associadas amat WHERE amat.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) > 0
                    ) OR (
                        (SELECT COUNT(amas.codigo) FROM acoes_melhorias_associadas amas WHERE amas.codigo_acao_melhoria_principal = AcoesMelhorias.codigo) = 0 AND AcoesMelhorias.abrangente IS NULL
                    )
                )';
                $conditions[] = "$userId IN (
                    SELECT UsuariosResponsaveis.codigo_usuario FROM usuarios_responsaveis UsuariosResponsaveis
                    WHERE UsuariosResponsaveis.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao AND UsuariosResponsaveis.data_remocao IS NULL
                )";
                $conditions[] = 'AcoesMelhorias.necessario_abrangencia = 1';
                break;
            case 5:
                $permissionsString = '';

                foreach ($permissions as $key => $value) {
                    if ($key === (count($permissions) - 1)) {
                        $permissionsString .= (string) $value;
                    } else {
                        $permissionsString .= (string) $value . ',';
                    }
                }

                $conditions[] = "(
                    AcoesMelhorias.codigo_acoes_melhorias_status IN (1, 2, 3, 4)
                    OR (AcoesMelhorias.codigo_acoes_melhorias_status = 5 AND (
                        (
                            AcoesMelhorias.necessario_implementacao = 1 AND 8 IN ($permissionsString)
                        ) OR (
                            AcoesMelhorias.necessario_eficacia = 1 AND 8 IN ($permissionsString)
                        )
                    ))
                    OR (AcoesMelhorias.codigo_acoes_melhorias_status = 9 AND AcoesMelhorias.necessario_eficacia = 1)
                )";
                $conditions[] = "(($userId IN (
                    SELECT UsuariosResponsaveis.codigo_usuario FROM usuarios_responsaveis UsuariosResponsaveis
                    WHERE UsuariosResponsaveis.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao AND UsuariosResponsaveis.data_remocao IS NULL
                ) AND AcoesMelhorias.codigo_acoes_melhorias_status IN (5, 9)) OR codigo_usuario_responsavel = $userId)";
                break;
            case 6:
                if (!in_array(14, $permissions)) {
                    return 0;
                }

                $this->UsuariosDados = TableRegistry::get('UsuariosDados');

                $authenticatedUserRegistration = $this->UsuariosDados->find()
                    ->select([
                        'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                        'matricula' => 'ClienteFuncionario.matricula',
                    ])
                    ->join([
                        [
                            'table' => 'funcionarios',
                            'alias' => 'Funcionarios',
                            'type' => 'INNER',
                            'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                        ],
                        [
                            'table' => 'cliente_funcionario',
                            'alias' => 'ClienteFuncionario',
                            'type' => 'INNER',
                            'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                        ]
                    ])
                    ->where([
                        "UsuariosDados.codigo_usuario = $userId",
                        'ClienteFuncionario.matricula IS NOT NULL',
                        'ClienteFuncionario.codigo_cliente IS NOT NULL',
                        'ClienteFuncionario.data_demissao IS NULL',
                    ])
                    ->hydrate(false)
                    ->first();

                if (
                    !empty($authenticatedUserRegistration['codigo_cliente'])
                    && empty($authenticatedUserRegistration['matricula'])
                ) {

                    $registration = (string) $authenticatedUserRegistration['matricula'];
                    $clientRegistration = (int) $authenticatedUserRegistration['codigo_cliente'];

                    $conditions[] = 'AcoesMelhorias.codigo_acoes_melhorias_status = 4';
                    $conditions[] = "$registration IN (
                        SELECT CF.matricula_chefia_imediata
                            FROM usuario U
                                INNER JOIN usuarios_dados UD ON UD.codigo_usuario = U.codigo
                                INNER JOIN funcionarios FU ON FU.cpf = UD.cpf
                                INNER JOIN cliente_funcionario CF ON CF.codigo_funcionario = FU.codigo
                            WHERE
                                (
                                    U.codigo = AcoesMelhorias.codigo_usuario_responsavel
                                    OR (
                                        U.codigo IN (
                                            SELECT UR.codigo_usuario
                                                FROM usuarios_responsaveis UR
                                                WHERE
                                                    UR.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao
                                                    AND UR.data_remocao IS NULL
                                        )
                                    )
                                )
                                AND U.codigo <> $userId
                                AND CF.matricula_chefia_imediata IS NOT NULL
                                AND CF.codigo_cliente_chefia_imediata IS NOT NULL
                                AND CF.data_demissao IS NULL
                                AND CF.matricula_chefia_imediata = $registration
                                AND CF.codigo_cliente_chefia_imediata = $clientRegistration
                    )";
                }
                break;
            default:
                $conditions['codigo_usuario_responsavel'] = $userId;
                break;
        }

        try {
            $data = $this->find()
                ->where($conditions);

            if (count($joins) > 0) {
                $data->join($joins);
            }

            return $data->all()->count();
        } catch (\Exception $exception) {
            return 0;
        }
    }

    /**
     * Retorna a lista de unidades do grupo economico
     * @param $codigo_cliente
     * @return array
     */
    public function retorna_lista_de_unidades_do_grupo_economico($codigo_cliente)
    {
        $this->GruposEconomicosClientes = TableRegistry::get('GruposEconomicosClientes');

        $query = $this->GruposEconomicosClientes->find()
        ->select([
            'Cliente.codigo'
        ])
        ->join([
            [
                'table' => 'grupos_economicos',
                'alias' => 'GrupoEconomico',
                'type' => 'INNER',
                'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
            ],
            [
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = GruposEconomicosClientes.codigo_cliente',
            ]
        ])
        ->where([
            'GrupoEconomico.codigo_cliente IN' => $codigo_cliente,
            'Cliente.ativo' => 1,
            'Cliente.e_tomador' => 0
        ])
        ->order(['Cliente.nome_fantasia ASC']);

        $unidades = $query->hydrate(false)
                        ->toArray();

        $arr = array();

        foreach ($unidades as $unidade) {
            $arr[] = $unidade['Cliente']['codigo'];
        };

        $arr = implode(",", $arr);

        return $arr;
    }

    public function conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrCodigosDuplicatas)
    {
        try {

            $this->addBehavior('Loggable');


            $this->find()
                ->where(['codigo_cliente_bu IN' => $arrCodigosDuplicatas])
                ->update()
                ->set(['codigo_cliente_bu' => $codigoClienteBuConciliador])
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
                ->set(['codigo_cliente_opco' => $codigoClienteOpcoConciliador])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
