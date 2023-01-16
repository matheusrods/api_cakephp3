<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\Comum;
use Cake\Datasource\ConnectionManager;

/**
 * AcoesMelhorias Controller
 *
 * @property \App\Model\Table\AcoesMelhoriasTable $AcoesMelhorias
 *
 * @method \App\Model\Entity\AcoesMelhoria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AcoesMelhoriasController extends ApiController
{
    private $connect;

    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');

        $this->loadModel('AcoesMelhoriasAnexos');
        $this->loadModel('AcoesMelhoriasSolicitacoes');
        $this->loadModel('AcoesMelhoriasStatus');
        $this->loadModel('AcoesMelhoriasTipo');
        $this->loadModel('AcoesMelhoriasAssociadas');
        $this->loadModel('PosCriticidade');
        $this->loadModel('RegraAcao');
        $this->loadModel('Usuario');
        $this->loadModel('Subperfil');
        $this->loadModel('PdaConfigRegra');
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

    public function getAllActions()
    {
        $this->request->allowMethod(['GET']);

        $data = [];
        $data['acoes_melhorias'] = [];

        $userId = $this->getAuthenticatedUser();

        $codigo_cliente = $this->getCodigoCliente($userId);

        $permissions = $this->getPermissions($userId);

        if (in_array(15, $permissions)) {
            $queryParams = $this->request->getQueryParams();

            $directFilters = $this->filter($queryParams, [
                // 'codigo_acoes_melhorias_status',
                'codigo_origem_ferramenta',
                'codigo_usuario_responsavel',
            ]);

            $otherFilters = $this->filter($queryParams, [
                'autor',
                'inicio_periodo',
                'fim_periodo',
                'data_tipo',
            ]);

            $orderBy = isset($queryParams['ordenar_por']) ? $queryParams['ordenar_por'] : null;

            $data['acoes_melhorias'] = $this->AcoesMelhorias->getAll($userId, $directFilters, $otherFilters, $orderBy, $permissions, $codigo_cliente);
        }

        // debug($data);exit;

        $pendingRequestsData = $this->AcoesMelhoriasSolicitacoes->pendencies($userId);

        function getPendingRequest($data = [], $type)
        {
            $quantity = 0;

            if (count($data) > 0) {
                foreach ($data as $pendingRequest) {
                    if ((int) $pendingRequest->codigo_acao_melhoria_solicitacao_tipo === $type) {
                        $quantity += (int) $pendingRequest->quantidade;
                    }
                }
            }

            return $quantity;
        }

        $data['pendencias'] = [
            # Aceite / Recusa / Transferência
            [
                'tipo' => 1,
                'quantidade' => (in_array(2, $permissions)
                    || in_array(3, $permissions)
                    || in_array(4, $permissions)
                ) ? getPendingRequest($pendingRequestsData, 1) : 0,
            ],
            # Postergação
            [
                'tipo' => 2,
                'quantidade' => in_array(11, $permissions) ? getPendingRequest($pendingRequestsData, 2) : 0,
            ],
            # Cancelamento
            [
                'tipo' => 3,
                'quantidade' => in_array(12, $permissions) ? getPendingRequest($pendingRequestsData, 3) : 0,
            ],
            # Análise (Em andamento)
            [
                'tipo' => 4,
                'quantidade' => $this->AcoesMelhorias->countPendencies($userId, 4, false, $permissions),
            ],
            # Vencer em breve
            [
                'tipo' => 5,
                'quantidade' => $this->AcoesMelhorias->countPendencies($userId, null, true, $permissions),
            ],
            # Atrasados do meu time
            [
                'tipo' => 6,
                'quantidade' => $this->AcoesMelhorias->countPendencies($userId, 6, false, $permissions),
            ],
        ];

        $this->set(compact('data'));
    }

    public function getPendingImprovementActions($status)
    {
        $this->request->allowMethod(['GET']);

        $userId = $this->getAuthenticatedUser();

        $permissions = $this->getPermissions($userId);

        $data = [
            'regra_acao' => (in_array(2, $permissions)
                || in_array(3, $permissions)
                || in_array(4, $permissions)
            ) ? $this->RegraAcao->getUserClientActionRule($userId) : null,
            'acoes_melhorias' => $this->AcoesMelhorias->getPendencies($userId, (int) $status, $permissions),
        ];

        $this->set(compact('data'));
    }

    public function getActionById($improvementActionCode)
    {
        $this->request->allowMethod(['GET']);

        $userId = $this->getAuthenticatedUser();

        $permissions = $this->getPermissions($userId);

        if (in_array(15, $permissions)) {
            $requestDatabase = $this->AcoesMelhorias->getById($userId, $improvementActionCode);

            if ($requestDatabase['error']) {
                $data = [
                    'error' => [
                        'message' => $requestDatabase['error']['message'],
                    ],
                ];

                $this->set(compact('data'));
            } else {
                $data = $requestDatabase['registry'];

                $this->set(compact('data'));
            }
        } else {
            $data = [
                'error' => [
                    'message' => 'O seu usuário não tem permissão para ver ações de melhoria. Entre em contato com o seu supervisor.',
                ],
            ];

            $this->set(compact('data'));
        }
    }

    public function postImprovementActions()
    {
        $this->request->allowMethod(['POST']);

        try {
            $userId = $this->getAuthenticatedUser();

            $permissions = $this->getPermissions($userId);

            if (!in_array(1, $permissions)) {
                $data = [
                    'error' => [
                        'message' => 'O seu usuário não tem permissão criar ações de melhoria.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $improvementActions = $this->request->getData();

            // Valida se os dados informados estão vindo em uma estrutura de array e se veio pelo menos 1 registro
            if (!is_array($improvementActions) || count($improvementActions) < 1) {
                $data = [
                    'error' => [
                        'message' => 'Não foi enviado no formato correto os dados.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $store = $this->AcoesMelhorias->store($improvementActions, $userId, true);

            if (isset($store['error']) && !is_null($store['error'])) {
                $error = $store['error'];

                $this->set(compact('error'));
            } else {
                $data = $store['data'];

                $this->set(compact('data'));
            }
        } catch (\Exception $exception) {
            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function regrasGeraisAcoes()
    {
        $this->request->allowMethod(['POST']);

        $data = $this->request->getData();
        // debug($data);exit;
        $error = 0;
        // Aplicar regras gerais da ação de melhoria, observação: Se ocorrer algum erro deve continuar execução das demais
        foreach ($data as $improvementAction) {
            try {
                //debug($improvementAction['codigo']);
                $this->PdaConfigRegra->getEmAcaoDeMelhoria($improvementAction['codigo']);
            } catch (\Exception $exception) {
                $error++;
                continue;
            }
        }

        $err = array(
            'qtd_error' => $error
        );
        echo json_encode($err);
        exit;
    }

    public function regrasGeraisAcoesTimer()
    {
        $this->request->allowMethod(['POST']);

        $data = $this->request->getData();
        // debug($data);exit;
        $error = 0;
        // Aplicar regras gerais da ação de melhoria, observação: Se ocorrer algum erro deve continuar execução das demais
        foreach ($data as $improvementAction) {
            try {
                //debug($improvementAction['codigo']);
                $timers = $this->PdaConfigRegra->getEmAcaoDeMelhoriaTimer($improvementAction['codigo']);
            } catch (\Exception $exception) {
                $error++;
                continue;
            }
        }

        $err = array(
            'qtd_error' => $error
        );

        $retorno = array(
            'timers' => $timers,
            'qtd_error' => $error
        );

        echo json_encode($retorno);
        exit;
    }

    public function putImprovementAction($improvementActionCode)
    {
        $this->request->allowMethod(['PUT']);

        try {
            $userId = $this->getAuthenticatedUser();

            $permissions = $this->getPermissions($userId);

            $update = $this->AcoesMelhorias->update((int) $improvementActionCode, $this->request, $userId, $permissions);

            if (isset($update['error']) && !is_null($update['error'])) {
                $error = $update['error'];

                $this->set(compact('error'));
            } else {
                $data = $update['data'];

                $this->set(compact('data'));
            }
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function deleteImprovementAction($improvementActionCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $improvementAction = $this->AcoesMelhorias->find()->where([
                'codigo' => $improvementActionCode,
                'data_remocao IS NULL',
            ])->first();

            if (empty($improvementAction)) {
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
            ];

            $entity = $this->AcoesMelhorias->patchEntity($improvementAction, $putData);

            if (!$this->AcoesMelhorias->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar ação de melhoria.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Ação de melhoria removida com sucesso.',
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

    public function getAssociationById($associationCode)
    {
        $this->request->allowMethod(['GET']);

        try {
            $userId = $this->getAuthenticatedUser();

            $data = $this->AcoesMelhoriasAssociadas->find()->where([
                'codigo' => $associationCode,
                'data_remocao IS NULL',
            ])->first();

            if ($data && !empty($data)) {
                $this->set(compact('data'));
            } else {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));
            }
        } catch (\Exception $exception) {
            $data = [
                'error' => [
                    'message' => 'Não foi encontrado dados referente ao código informado.',
                ],
            ];

            $this->set(compact('data'));
        }
    }

    public function putActionAssociation($associationCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $association = $this->AcoesMelhoriasAssociadas->find()->where([
                'codigo' => $associationCode,
                'data_remocao IS NULL',
            ])->first();

            if (empty($association)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $putData = $this->filter($this->request->getData(), [
                'abrangente',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->AcoesMelhoriasAssociadas->patchEntity($association, $putData);

            if (!$this->AcoesMelhoriasAssociadas->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao editar ação.',
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

    public function getFilesByActionId($improvementActionCode)
    {
        $requestDatabase = $this->AcoesMelhoriasAnexos->getByActionId($improvementActionCode);

        if ($requestDatabase['error']) {
            $data = [
                'error' => [
                    'message' => $requestDatabase['error']['message'],
                ],
            ];

            $this->set(compact('data'));
        } else {
            $data = $requestDatabase['registry'];

            $this->set(compact('data'));
        }
    }

    public function postFile()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $postData = $this->request->getData();

            if (!isset($postData['arquivo']) || empty($postData['arquivo'])) {
                $data = [
                    'error' => [
                        'message' => 'O campo de arquivo é obrigatório.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $fileData = [
                'file' => $postData['arquivo'],
                'prefix' => 'planodeacao',
                'type' => 'base64',
            ];

            $sendFile = Comum::sendFileToServer($fileData);

            $path = $sendFile->{'response'}->{'path'};

            if (empty($path)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao fazer upload do arquivo.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $fileUrl = FILE_SERVER . $path;

            $dataToInsert = $this->filter($this->request->getData(), [
                'codigo_acao_melhoria',
                'arquivo',
                'arquivo_tipo',
                'arquivo_nome',
                'arquivo_tamanho',
            ]);

            $dataToInsert['codigo_usuario_inclusao'] = $userId;
            $dataToInsert['arquivo_url'] = $fileUrl;

            $entity = $this->AcoesMelhoriasAnexos->newEntity($dataToInsert);

            if (!$this->AcoesMelhoriasAnexos->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao fazer upload do arquivo.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $data = $entity;

            $this->connect->commit();

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    public function putFile($fileCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $file = $this->AcoesMelhoriasAnexos->find()->where(['codigo' => $fileCode, 'ativo' => 1])->first();

            if (empty($file)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi encontrado dados referente ao código informado.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $putData = $this->request->getData();

            if (!isset($putData['arquivo']) || empty($putData['arquivo'])) {
                $data = [
                    'error' => [
                        'message' => 'O campo de arquivo é obrigatório.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $fileData = [
                'file' => $putData['arquivo'],
                'prefix' => 'planodeacao',
                'type' => 'base64',
            ];

            $sendFile = Comum::sendFileToServer($fileData);

            $path = $sendFile->{'response'}->{'path'};

            if (empty($path)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao fazer upload do arquivo.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $fileUrl = FILE_SERVER . $path;

            $dataToUpdate = $this->filter($this->request->getData(), [
                'arquivo_tipo',
                'arquivo',
                'arquivo_nome',
                'arquivo_tamanho',
            ]);

            $dataToUpdate['arquivo_url'] = $fileUrl;
            $dataToUpdate['codigo_usuario_alteracao'] = $userId;
            $dataToUpdate['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->AcoesMelhoriasAnexos->patchEntity($file, $dataToUpdate);

            if (!$this->AcoesMelhoriasAnexos->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao fazer upload do arquivo.',
                        'form_errors' => $entity->getErrors(),
                    ],
                ];

                $this->connect->commit();

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

    public function deleteFile($fileCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $file = $this->AcoesMelhoriasAnexos->find()->where([
                'codigo' => $fileCode,
                'ativo' => 1,
            ])->first();

            if (empty($file)) {
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
                'ativo' => 0,
                'codigo_usuario_alteracao' => $userId,
                'data_alteracao' => $requisitionDate,
                'data_remocao' => $requisitionDate,
            ];

            $entity = $this->AcoesMelhoriasAnexos->patchEntity($file, $putData);

            if (!$this->AcoesMelhoriasAnexos->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar anexo.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Anexo deletado com sucesso.',
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

    public function getAllStatus()
    {
        $this->request->allowMethod(['GET']);

        $data = $this->AcoesMelhoriasStatus->getAll();

        $this->set(compact('data'));
    }

    public function postStatus()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $status = $this->request->getData();

            $status['codigo_usuario_inclusao'] = $userId;

            $entity = $this->AcoesMelhoriasStatus->newEntity($status);

            if (!$this->AcoesMelhoriasStatus->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao inserir status.',
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

    public function putStatus($statusCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $status = $this->AcoesMelhoriasStatus->find()->where([
                'codigo' => $statusCode,
                'ativo' => 1,
            ])->first();

            if (empty($status)) {
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
                'cor',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->AcoesMelhoriasStatus->patchEntity($status, $putData);

            if (!$this->AcoesMelhoriasStatus->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao editar status.',
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

    public function deleteStatus($statusCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $status = $this->AcoesMelhoriasStatus->find()->where([
                'codigo' => $statusCode,
                'ativo' => 1,
            ])->first();

            if (empty($status)) {
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

            $entity = $this->AcoesMelhoriasStatus->patchEntity($status, $putData);

            if (!$this->AcoesMelhoriasStatus->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar status.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Status deletado com sucesso.',
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

    public function getAllTypes($clientCode = null)
    {
        $this->request->allowMethod(['GET']);

        //tratamento para nao cair no erro de quando não passar o codigo cliente
        if (is_null($clientCode)) {
            $userId = $this->getAuthenticatedUser();

            //busca o cliente que o usuario esta conectado
            $usuario = $this->Usuario->obterDadosDoUsuarioAlocacao($userId);
            $clientCode = $usuario->cliente['0']['codigo'];
        }

        $data = $this->AcoesMelhoriasTipo->getAll($clientCode);

        $this->set(compact('data'));
    }

    public function postType()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->request->getData();

            $type['codigo_usuario_inclusao'] = $userId;

            $entity = $this->AcoesMelhoriasTipo->newEntity($type);

            if (!$this->AcoesMelhoriasTipo->save($entity)) {
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

    public function putType($typeCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->AcoesMelhoriasTipo->find()->where([
                'codigo' => $typeCode,
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
                'codigo_cliente',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->AcoesMelhoriasTipo->patchEntity($type, $putData);

            if (!$this->AcoesMelhoriasTipo->save($entity)) {
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

    public function deleteType($typeCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $type = $this->AcoesMelhoriasTipo->find()->where([
                'codigo' => $typeCode,
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

            $entity = $this->AcoesMelhoriasTipo->patchEntity($type, $putData);

            if (!$this->AcoesMelhoriasTipo->save($entity)) {
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

    public function getAllCriticisms($codigo_cliente, $codigo_pos_ferramenta)
    {
        $this->request->allowMethod(['GET']);

        $query = "select top 1 ge.codigo_cliente from grupos_economicos_clientes gec
        inner join grupos_economicos ge on ge.codigo = gec.codigo_grupo_economico
        where gec.codigo_cliente = '" . $codigo_cliente . "'";

        $conn = ConnectionManager::get('default');
        $codigo_cliente =  $conn->execute($query)->fetchAll('assoc');

        $codigo_cliente = $codigo_cliente[0]['codigo_cliente'];

        $data = $this->PosCriticidade->getAll($codigo_cliente, $codigo_pos_ferramenta);

        $this->set(compact('data'));
    }

    public function postCriticality()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $criticality = $this->request->getData();

            $criticality['codigo_usuario_inclusao'] = $userId;
            $criticality['ativo'] = 1;

            $entity = $this->PosCriticidade->newEntity($criticality);

            if (!$this->PosCriticidade->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao inserir criticidade.',
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

    public function putCriticality($criticalityCode)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $criticality = $this->PosCriticidade->find()->where([
                'codigo' => $criticalityCode,
                'ativo' => 1,
            ])->first();

            if (empty($criticality)) {
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
                'cor',
                'observacao',
                'valor_inicio',
                'valor_fim',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->PosCriticidade->patchEntity($criticality, $putData);

            if (!$this->PosCriticidade->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao editar criticidade.',
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

    public function deleteCriticality($criticalityCode)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $criticality = $this->PosCriticidade->find()->where([
                'codigo' => $criticalityCode,
                'ativo' => 1,
            ])->first();

            if (empty($criticality)) {
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

            $entity = $this->PosCriticidade->patchEntity($criticality, $putData);

            if (!$this->PosCriticidade->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao deletar criticidade.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Criticidade deletada com sucesso.',
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

    public function getCodigoCliente($userId)
    {
        $fields = [
            'codigo_cliente' => 'ge.codigo_cliente',
        ];

        $joins = [
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'gec',
                'type' => 'INNER',
                'conditions' => [
                    'gec.codigo_cliente = Usuario.codigo_cliente',
                ],
            ],
            [
                'table' => 'grupos_economicos',
                'alias' => 'ge',
                'type' => 'INNER',
                'conditions' => [
                    'ge.codigo = gec.codigo_grupo_economico',
                ],
            ],
        ];

        $usuario = $this->Usuario->find()
            ->select($fields)
            ->join($joins)
            ->hydrate(false)
            ->where([
                'Usuario.codigo' => $userId
            ])
            ->first();

        return isset($usuario['codigo_cliente']) ? $usuario['codigo_cliente'] : null;
    }
}
