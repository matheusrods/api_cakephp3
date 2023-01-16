<?php
namespace App\Controller\API;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;

/**
 * OrigemFerramentas Controller
 *
 * @property \App\Model\Table\OrigemFerramentasTable $OrigemFerramentas
 *
 * @method \App\Model\Entity\OrigemFerramenta[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrigemFerramentasController extends ApiController
{
    private $connect;

    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');
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

    public function getAllOriginToolsByClient($clientId = null)
    {
        $data = $this->OrigemFerramentas->getAll($clientId);

        $this->set(compact('data'));
    }

    public function getOriginToolById($originToolId)
    {
        $this->request->allowMethod(['GET']);

        $requestDatabase = $this->OrigemFerramentas->getById($originToolId);

        if ($requestDatabase['error']) {
            $error = $requestDatabase['error'];

            $this->set(compact('error'));
        } else {
            $data = $requestDatabase['registry'];

            $this->set(compact('data'));
        }
    }

    public function postOriginTool()
    {
        $this->request->allowMethod(['POST']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $originTool = $this->request->getData();

            $originTool['codigo_usuario_inclusao'] = $userId;

            $entity = $this->OrigemFerramentas->newEntity($originTool);

            if (!$this->OrigemFerramentas->save($entity)) {
                $error = [
                    'message' => 'Erro ao inserir origem de ferramenta.',
                    'form_errors' => $entity->getErrors(),
                ];

                $this->connect->rollback();

                $this->set(compact('error'));

                return;
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => $exception->getMessage(),
            ];

            $this->set(compact('error'));
        }
    }

    public function putOriginTool($originToolId)
    {
        $this->request->allowMethod(['PUT']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $originTool = $this->OrigemFerramentas->find()->where([
                'codigo' => $originToolId,
                'ativo' => 1,
            ])->first();

            if (empty($originTool)) {
                $error = [
                    'message' => 'Não foi encontrado dados referente ao código informado.',
                ];

                $this->set(compact('error'));

                return;
            }

            $putData = $this->filter($this->request->getData(), [
                'codigo_cliente',
                'descricao',
                'formulario',
            ]);

            $putData['codigo_usuario_alteracao'] = $userId;
            $putData['data_alteracao'] = date('Y-m-d H:i:s');

            $entity = $this->OrigemFerramentas->patchEntity($originTool, $putData);

            if (!$this->OrigemFerramentas->save($entity)) {
                $error = [
                    'message' => 'Erro ao editar origem de ferramenta.',
                    'form_errors' => $entity->getErrors(),
                ];

                $this->connect->rollback();

                $this->set(compact('error'));

                return;
            }

            $this->connect->commit();

            $data = $entity;

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => $exception->getMessage(),
            ];

            $this->set(compact('error'));
        }
    }

    public function deleteOriginTool($originToolId)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            $userId = $this->getAuthenticatedUser();

            $originTool = $this->OrigemFerramentas->find()->where([
                'codigo' => $originToolId,
                'ativo' => 1,
            ])->first();

            if (empty($originTool)) {
                $error = [
                    'message' => 'Não foi encontrado dados referente ao código informado.',
                ];

                $this->set(compact('error'));

                return;
            }

            $requisitionDate = date('Y-m-d H:i:s');

            $putData = [
                'ativo' => 0,
                'codigo_usuario_alteracao' => $userId,
                'data_alteracao' => $requisitionDate,
                'data_remocao' => $requisitionDate,
            ];

            $entity = $this->OrigemFerramentas->patchEntity($originTool, $putData);

            if (!$this->OrigemFerramentas->save($entity)) {
                $error = [
                    'message' => 'Erro ao deletar origem de ferramenta.',
                ];

                $this->connect->rollback();

                $this->set(compact('error'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Origem de ferramenta deletada com sucesso.',
            ];

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => $exception->getMessage(),
            ];

            $this->set(compact('error'));
        }
    }
}
