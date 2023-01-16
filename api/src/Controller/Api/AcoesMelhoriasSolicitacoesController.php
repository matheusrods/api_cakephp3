<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;

/**
 * AcoesMelhoriasSolicitacoes Controller
 *
 * @property \App\Model\Table\AcoesMelhoriasSolicitacoesTable $AcoesMelhoriasSolicitacoes
 *
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AcoesMelhoriasSolicitacoesController extends ApiController
{
    private $connect;

    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');

        $this->loadModel('AcoesMelhorias');
        $this->loadModel('AcoesMelhoriasSolicitacoesTipo');
        $this->loadModel('PdaConfigRegra');
        $this->loadModel('Subperfil');
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

    private function getAuthenticatedUser()
    {
        $authenticatedUser = $this->getDadosToken();

        if (empty($authenticatedUser)) {
            $error = 'Não foi possível encontrar os dados no Token!';

            $this->set(compact('error'));

            return;
        } else {
            $userId = isset($authenticatedUser->codigo_usuario) ? $authenticatedUser->codigo_usuario : '';

            if (empty($userId)) {
                $error = 'Logar novamente o usuário';

                $this->set(compact('error'));

                return;
            } else {
                return $userId;
            }
        }
    }

    private function getPermissions(int $userId)
    {
        $permissionsData = $this->Subperfil->getPermissoesUsuario($userId);

        $permissions = [];

        foreach ($permissionsData as $permission) {
            array_push($permissions, (int) $permission['codigo_acao']);
        }

        return $permissions;
    }

    public function getRequestsByActionId($actionCode)
    {
        $this->request->allowMethod(['GET']);

        $data = $this->AcoesMelhoriasSolicitacoes->getByActionId($actionCode);

        $this->set(compact('data'));
    }

    public function postRequest()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $permissions = $this->getPermissions($userId);

            $request = $this->request->getData();

            if (!isset($request['codigo_acao_melhoria'])) {
                $data = [
                    'error' => [
                        'message' => 'É obrigatório informar a ação de melhoria.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $improvementAction = $this->AcoesMelhorias->find()
                ->where([
                    'codigo' => (int) $request['codigo_acao_melhoria'],
                    'data_remocao IS NULL',
                ])
                ->contain([
                    'AcoesMelhoriasSolicitacoes' => [
                        'queryBuilder' => function ($query) {
                            return $query->where(['status' => 1]);
                        },
                    ],
                ])
                ->first();

            if (empty($improvementAction)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrada a ação de melhoria informada.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            if ($improvementAction->codigo_acoes_melhorias_status === 6) {
                $data = [
                    'error' => [
                        'message' => 'Essa ação de melhoria já foi cancelada, não é possível fazer mais nenhuma solicitação direcionada a ela.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            if (count($improvementAction->solicitacoes) > 0) {
                $data = [
                    'error' => [
                        'message' => 'Já existe uma solicitação para essa ação de melhoria em andamento.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            if (
                ((int) $request['codigo_acao_melhoria_solicitacao_tipo']) === 1
                && isset($request['codigo_novo_usuario_responsavel'])
                && ((int) $request['codigo_novo_usuario_responsavel']) === $improvementAction->codigo_usuario_responsavel
            ) {
                $data = [
                    'error' => [
                        'message' => 'Esse usuário já é o responsável dessa ação de melhoria.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            if (
                ((int) $request['codigo_acao_melhoria_solicitacao_tipo']) === 2
                && isset($request['novo_prazo'])
                && !is_null($improvementAction->prazo)
                && strtotime($request['novo_prazo']) <= strtotime($improvementAction->prazo)
            ) {
                $data = [
                    'error' => [
                        'message' => 'O novo prazo deve ser uma data maior que o prazo atual.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $request['status'] = 1;
            $request['codigo_usuario_inclusao'] = $userId;
            $request['codigo_usuario_solicitado'] = isset($request['codigo_usuario_solicitado'])
            ? $request['codigo_usuario_solicitado']
            : $improvementAction->codigo_usuario_responsavel;

            switch ((int) $request['codigo_acao_melhoria_solicitacao_tipo']) {
                case 1:
                    if (!in_array(4, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para criar uma solicitação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }

                    $request['usuario_solicitado_tipo'] = 1;
                    break;
                case 2:
                    if (!in_array(6, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para criar uma solicitação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }

                    $request['codigo_usuario_solicitado'] = null;

                    $response = $this->PdaConfigRegra->getAprovacaoPostergacao((int) $request['codigo_acao_melhoria']);

                    // Verificar se precisa aceitar a solicitação de postergação, se não precisar, postergar automaticamente
                    if ($response['status'] === true && !is_null($response['tipo_solicitacao'])) {
                        switch ($response['tipo_solicitacao']) {
                            case 1:
                                $request['status'] = 2;
                                $request['usuario_solicitado_tipo'] = 1;

                                $action = $this->AcoesMelhorias->find()
                                    ->where(['codigo' => (int) $request['codigo_acao_melhoria']])
                                    ->first();

                                $entityAction = $this->AcoesMelhorias->patchEntity($action, [
                                    'prazo' => $request['novo_prazo'],
                                    'codigo_usuario_alteracao' => $userId,
                                    'data_alteracao' => date('Y-m-d H:i:s'),
                                ]);

                                if (!$this->AcoesMelhorias->save($entityAction)) {
                                    $data = [
                                        'error' => [
                                            'message' => 'Erro ao criar solicitação.',
                                            'form_errors' => $entityAction->getErrors(),
                                        ],
                                    ];

                                    $this->connect->rollback();

                                    $this->set(compact('data'));

                                    return;
                                } else {
                                    $this->PdaConfigRegra->getEmAcaoDeMelhoria($entityAction->codigo);
                                }
                                break;
                            case 2:
                                // Necessário o aceite do responsável da matriz de responsabilidade
                                $request['usuario_solicitado_tipo'] = 2;
                                break;
                            case 3:
                                // Necessário o aceite do gestor
                                $request['usuario_solicitado_tipo'] = 3;
                                $request['codigo_usuario_solicitado'] = $response['codigo_gestor'];
                                break;
                        }
                    }
                    break;
                case 3:
                    if (!in_array(7, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para criar uma solicitação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }

                    $request['codigo_usuario_solicitado'] = null;

                    $response = $this->PdaConfigRegra->getAprovacaoCancelamento((int) $request['codigo_acao_melhoria']);

                    // Verificar se precisa aceitar a solicitação de cancelamento, se não precisar, cancelar automaticamente
                    if ($response['status'] === true && !is_null($response['tipo_solicitacao'])) {
                        switch ($response['tipo_solicitacao']) {
                            case 1:
                                $request['status'] = 2;
                                $request['usuario_solicitado_tipo'] = 1;

                                $action = $this->AcoesMelhorias->find()
                                    ->where(['codigo' => (int) $request['codigo_acao_melhoria']])
                                    ->first();

                                $entityAction = $this->AcoesMelhorias->patchEntity($action, [
                                    'codigo_acoes_melhorias_status' => 6,
                                    'codigo_usuario_alteracao' => $userId,
                                    'data_alteracao' => date('Y-m-d H:i:s'),
                                ]);

                                if (!$this->AcoesMelhorias->save($entityAction)) {
                                    $data = [
                                        'error' => [
                                            'message' => 'Erro ao criar solicitação.',
                                            'form_errors' => $entityAction->getErrors(),
                                        ],
                                    ];

                                    $this->connect->rollback();

                                    $this->set(compact('data'));

                                    return;
                                } else {
                                    $this->PdaConfigRegra->getEmAcaoDeMelhoria($entityAction->codigo);
                                }
                                break;
                            case 2:
                                // Necessário o aceite do responsável da matriz de responsabilidade
                                $request['usuario_solicitado_tipo'] = 2;
                                break;
                            case 3:
                                // Necessário o aceite do gestor
                                $request['usuario_solicitado_tipo'] = 3;
                                $request['codigo_usuario_solicitado'] = $response['codigo_gestor'];
                                break;
                        }
                    }
                    break;
                default:
                    $request['usuario_solicitado_tipo'] = 1;
                    break;
            }

            $entity = $this->AcoesMelhoriasSolicitacoes->newEntity($request);

            if (!$this->AcoesMelhoriasSolicitacoes->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao criar solicitação.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function putRequest($requestCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $permissions = $this->getPermissions($userId);

            $requestData = $this->request->getData();

            $generateNewRequest = (isset($requestData['gerar_nova_solicitacao']) && $requestData['gerar_nova_solicitacao'] == 1) ? true : false;

            $request = $this->AcoesMelhoriasSolicitacoes
                ->find()
                ->where([
                    'codigo' => $requestCode,
                    'data_remocao IS NULL',
                ])->first();

            // Verificar se solicitação existe
            if (empty($request)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            // Bloquear a criação de uma nova solicitação, sendo que uma já está em andamento
            if ($request->status !== 1) {
                $data = [
                    'error' => [
                        'message' => 'Essa solicitação já foi finalizada e não pode ser mais alterada.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            // Filtrar dados para alteração
            $putData = $this->filter($requestData, [
                'status',
                'justificativa_recusa',
            ]);

            // Verificar permissões do usuário logado
            switch ($request->codigo_acao_melhoria_solicitacao_tipo) {
                case 1:
                    if (((int) $putData['status']) === 2 && !in_array(2, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para aceitar uma solicitação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    } else if (
                        ((int) $putData['status']) === 3
                        && $request->codigo_acao_melhoria_solicitacao_tipo === 1
                        && !is_null($request->codigo_acao_melhoria_solicitacao_antecedente)
                        && $generateNewRequest
                        && !in_array(4, $permissions)
                    ) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para transferir uma ação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    } else if (((int) $putData['status']) === 3 && !in_array(3, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para recusar uma solicitação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }
                    break;
                case 2:
                    if (!in_array(11, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para aprovar ou recusar uma postergação. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }
                    break;
                case 3:
                    if (!in_array(12, $permissions)) {
                        $data = [
                            'error' => [
                                'message' => 'O seu usuário não tem permissão para aprovar ou recusar um cancelamento. Entre em contato com o seu supervisor.',
                            ],
                        ];

                        $this->set(compact('data'));

                        return;
                    }
                    break;
            }

            $dateToUpdate = date('Y-m-d H:i:s');

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = $dateToUpdate;
            $putData['alteracao_sistema'] = 0;

            $entity = $this->AcoesMelhoriasSolicitacoes->patchEntity($request, $putData);

            if (!$this->AcoesMelhoriasSolicitacoes->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao editar solicitação.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            if (((int) $putData['status']) === 2) {
                $improvementAction = $this->AcoesMelhorias->find()
                    ->where([
                        'codigo' => $request->codigo_acao_melhoria,
                        'data_remocao IS NULL',
                    ])->first();

                if (empty($improvementAction)) {
                    $data = [
                        'error' => [
                            'message' => 'Erro ao editar solicitação.',
                        ],
                    ];

                    $this->set(compact('data'));

                    return;
                }

                switch ($request->codigo_acao_melhoria_solicitacao_tipo) {
                    case 1:
                        $putDataImprovementAction = [
                            'codigo_usuario_responsavel' => $request->codigo_novo_usuario_responsavel,
                            'codigo_usuario_alteracao' => $userId,
                            'data_alteracao' => date('Y-m-d H:i:s'),
                            'codigo_acoes_melhorias_status' => 3,
                        ];

                        if ($improvementAction->codigo_acoes_melhorias_status !== 1 && $improvementAction->codigo_acoes_melhorias_status !== 2) {
                            unset($putDataImprovementAction['codigo_acoes_melhorias_status']);
                        }

                        $entityImprovementAction = $this->AcoesMelhorias->patchEntity($improvementAction, $putDataImprovementAction);

                        if (!$this->AcoesMelhorias->save($entityImprovementAction)) {
                            $data = [
                                'error' => [
                                    'message' => 'Erro ao editar solicitação.',
                                    'form_errors' => $entityImprovementAction->getErrors(),
                                ],
                            ];

                            $this->connect->rollback();

                            $this->set(compact('data'));

                            return;
                        } else {
                            $this->PdaConfigRegra->getEmAcaoDeMelhoria($entityImprovementAction->codigo);
                        }
                        break;
                    case 2:
                        $putDataImprovementAction = [
                            'prazo' => $request->novo_prazo,
                            'codigo_usuario_alteracao' => $userId,
                            'data_alteracao' => date('Y-m-d H:i:s'),
                        ];

                        $entityImprovementAction = $this->AcoesMelhorias->patchEntity($improvementAction, $putDataImprovementAction);

                        if (!$this->AcoesMelhorias->save($entityImprovementAction)) {
                            $data = [
                                'error' => [
                                    'message' => 'Erro ao editar solicitação.',
                                    'form_errors' => $entityImprovementAction->getErrors(),
                                ],
                            ];

                            $this->connect->rollback();

                            $this->set(compact('data'));

                            return;
                        } else {
                            $this->PdaConfigRegra->getEmAcaoDeMelhoria($entityImprovementAction->codigo);
                        }
                        break;
                    case 3:
                        $putDataImprovementAction = [
                            'codigo_acoes_melhorias_status' => 6,
                            'codigo_usuario_alteracao' => $userId,
                            'data_alteracao' => date('Y-m-d H:i:s'),
                        ];

                        $entityImprovementAction = $this->AcoesMelhorias->patchEntity($improvementAction, $putDataImprovementAction);

                        if (!$this->AcoesMelhorias->save($entityImprovementAction)) {
                            $data = [
                                'error' => [
                                    'message' => 'Erro ao editar solicitação.',
                                    'form_errors' => $entityImprovementAction->getErrors(),
                                ],
                            ];

                            $this->connect->rollback();

                            $this->set(compact('data'));

                            return;
                        } else {
                            $this->PdaConfigRegra->getEmAcaoDeMelhoria($entityImprovementAction->codigo);
                        }
                        break;
                }
            } else if (
                ((int) $putData['status']) === 3
                && $request->codigo_acao_melhoria_solicitacao_tipo === 1
                && !is_null($request->codigo_acao_melhoria_solicitacao_antecedente)
                && $generateNewRequest
            ) {
                $requestToGenerate = $this->AcoesMelhoriasSolicitacoes
                    ->find()
                    ->where([
                        'codigo' => $request->codigo_acao_melhoria_solicitacao_antecedente,
                        'data_remocao IS NULL',
                    ])->first();

                if (!empty($requestToGenerate) && !is_null($requestToGenerate->codigo_acao_melhoria_solicitacao_antecedente)) {
                    $newRequest = [
                        'codigo_acao_melhoria' => $requestToGenerate->codigo_acao_melhoria,
                        'codigo_acao_melhoria_solicitacao_antecedente' => $requestToGenerate->codigo_acao_melhoria_solicitacao_antecedente,
                        'codigo_acao_melhoria_solicitacao_tipo' => 1,
                        'status' => 1,
                        'codigo_novo_usuario_responsavel' => $requestToGenerate->codigo_novo_usuario_responsavel,
                        'codigo_usuario_solicitado' => $requestToGenerate->codigo_usuario_solicitado,
                        'codigo_usuario_inclusao' => $userId,
                    ];

                    $newEntity = $this->AcoesMelhoriasSolicitacoes->newEntity($newRequest);

                    if (!$this->AcoesMelhoriasSolicitacoes->save($newEntity)) {
                        $data = [
                            'error' => [
                                'message' => 'Erro ao editar solicitação.',
                                'form_errors' => $newEntity->getErrors(),
                            ],
                        ];

                        $this->connect->rollback();

                        $this->set(compact('data'));

                        return;
                    }
                }
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function deleteRequest($requestCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $request = $this->AcoesMelhoriasSolicitacoes->find()->where([
                'codigo' => $requestCode,
                'data_remocao IS NULL',
            ])->first();

            if (empty($request)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $requisitionDate = date('Y-m-d H:i:s');

            $putData = [
                'codigo_usuario_alteracao' => $userId,
                'data_alteracao' => $requisitionDate,
                'data_remocao' => $requisitionDate,
                'alteracao_sistema' => 0,
            ];

            $entity = $this->AcoesMelhoriasSolicitacoes->patchEntity($request, $putData);

            if (!$this->AcoesMelhoriasSolicitacoes->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar solicitação.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Solicitação deletada com sucesso.',
            ];

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function geAllRequestTypes()
    {
        $this->request->allowMethod(['GET']);

        $data = $this->AcoesMelhoriasSolicitacoesTipo->getAll();

        $this->set(compact('data'));
    }

    public function postRequestType()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->request->getData();

            $type['codigo_usuario_inclusao'] = $userId;

            $entity = $this->AcoesMelhoriasSolicitacoesTipo->newEntity($type);

            if (!$this->AcoesMelhoriasSolicitacoesTipo->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao inserir tipo.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function putRequestType($requestTypeCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->AcoesMelhoriasSolicitacoesTipo->find()->where([
                'codigo' => $requestTypeCode,
                'ativo' => 1,
            ])->first();

            if (empty($type)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $putData = $this->filter($this->request->getData(), [
                'descricao',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->AcoesMelhoriasSolicitacoesTipo->patchEntity($type, $putData);

            if (!$this->AcoesMelhoriasSolicitacoesTipo->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao editar tipo.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function deleteRequestType($requestTypeCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->AcoesMelhoriasSolicitacoesTipo->find()->where([
                'codigo' => $requestTypeCode,
                'ativo' => 1,
            ])->first();

            if (empty($type)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $putData = [
                'ativo' => 0,
                'codigo_usuario_alteracao' => $userId,
                'data_alteracao' => date('Y-m-d H:i:s'),
            ];

            $entity = $this->AcoesMelhoriasSolicitacoesTipo->patchEntity($type, $putData);

            if (!$this->AcoesMelhoriasSolicitacoesTipo->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar tipo.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Tipo deletado com sucesso.',
            ];

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }
}
