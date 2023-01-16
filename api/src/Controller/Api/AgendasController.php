<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * Agendas Controller
 *
 * @property \App\Model\Table\AgendasTable $Agendas
 *
 * @method \App\Model\Entity\Mensagem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AgendasController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        
        $this->loadModel("Agendas");
        $this->loadModel("UsuariosDados");
        $this->loadModel("AgendasUsuario");
    }

    public function index($codigo_usuario)
    {
        $usuario = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        if (empty($usuario)) {
            $error = 'Usuário não encontrado!';
            $this->set(compact('error'));
            return;
        }

        $where = ['AgendasUsuario.codigo_usuario' => $usuario['codigo_usuario'], 'Agendas.ativo' => 1];

        $data = $this->Agendas->find()
            ->select([
                'codigo'                    => 'Agendas.codigo',
                'titulo'                    => 'Agendas.titulo',
                'descricao'                 => 'Agendas.descricao',
                'data_inicio'               => 'Agendas.data_inicio',
                'data_fim'                  => 'Agendas.data_fim',
                'link'                      => 'Agendas.link',
                'data_inclusao'             => 'Agendas.data_inclusao',
            ])
            ->join([
                'AgendasUsuario' => [
                    'table' => 'agendas_usuario',
                    'alias' => 'AgendasUsuario',
                    'type' => 'INNER',
                    'conditions' => ['AgendasUsuario.codigo_agenda = Agendas.codigo']
                ],
            ])
            ->where($where)
            ->order(['codigo ASC']);

        $this->set(compact('data'));
    }

    public function view($codigo)
    {
        $data = $this->Agendas->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();
        $this->set(compact('data'));
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $this->connect->begin();

        try {
            $request = $this->request->getData();

            $codigo_usuario_inclusao = $this->getAuthUser();
            $data_agora = date('Y-m-d H:i:s');

            $request['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;
            $request['data_inclusao'] = $data_agora;

            $entityAgenda = $this->Agendas->newEntity($request);

            if (!$this->Agendas->save($entityAgenda)) {
                $data['message'] = 'Erro ao inserir na agenda';
                $data['error'] = $entityAgenda->getErrors();
                $this->set(compact('data'));
                return;
            }

            if (!empty($request['usuarios'])) {
                foreach ($request['usuarios'] as $usuario) {
                    $u = array();

                    $existeUsuario = $this->UsuariosDados->find()->where(['codigo_usuario' => $usuario['codigo_usuario']])->first();
                    if (empty($existeUsuario)) {
                        $error = 'Usuário não encontrado!';
                        $this->set(compact('error'));
                        return;
                    }

                    $u['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;
                    $u['data_inclusao'] = $data_agora;
                    $u['codigo_agenda'] = $entityAgenda['codigo'];
                    $u['codigo_usuario'] = $usuario['codigo_usuario'];

                    $entityAgendasUsuarios = $this->AgendasUsuario->newEntity($u);

                    if (!$this->AgendasUsuario->save($entityAgendasUsuarios)) {
                        $data['message'] = 'Erro ao inserir o usuario do evento da agenda';
                        $data['error'] = $entityAgendasUsuarios->errors();
                        $this->set(compact('data'));
                        return;
                    }
                }
            }

            $data = $entityAgenda;

            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function edit()
    {
        $this->request->allowMethod(['put']);

        $this->connect->begin();

        try {
        
            $request = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();
            $data_agora = date('Y-m-d H:i:s');

            $request['codigo_usuario_alteracao'] = $codigo_usuario_logado;
            $request['data_alteracao'] = $data_agora;
                
            $agenda = $this->Agendas->find()->where(['codigo' => $request['codigo'], 'ativo' => 1])->first();

            if (empty($agenda)) {
                $error = 'Não foi encontrado o evento na agenda';
                $this->set(compact('error'));
                return;
            }

            $entityAgenda = $this->Agendas->patchEntity($agenda, $request);

            if (!$this->Agendas->save($entityAgenda)) {
                $data['message'] = 'Erro ao editar o evento na agenda';
                $data['error'] = $entityAgenda->getErrors();
                $this->set(compact('data'));
                return;
            }

            //Remove da tabela agendas_usuario
            $total = $this->AgendasUsuario->deleteAll(['codigo_agenda' => $request['codigo']], false);

            if (!empty($request['usuarios'])) {
                foreach ($request['usuarios'] as $usuario) {
                    $u = array();

                    $existeUsuario = $this->UsuariosDados->find()->where(['codigo_usuario' => $usuario['codigo_usuario']])->first();
                    if (empty($existeUsuario)) {
                        $error = 'Usuário não encontrado!';
                        $this->set(compact('error'));
                        return;
                    }

                    $u['codigo_usuario_inclusao'] = $codigo_usuario_logado;
                    $u['data_inclusao'] = $data_agora;
                    $u['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                    $u['data_alteracao'] = $data_agora;
                    $u['codigo_agenda'] = $entityAgenda['codigo'];
                    $u['codigo_usuario'] = $usuario['codigo_usuario'];

                    $entityAgendasUsuarios = $this->AgendasUsuario->newEntity($u);

                    if (!$this->AgendasUsuario->save($entityAgendasUsuarios)) {
                        $data['message'] = 'Erro ao atualizar o usuario do evento da agenda';
                        $data['error'] = $entityAgendasUsuarios->errors();
                        $this->set(compact('data'));
                        return;
                    }
                }
            }

            $data = $entityAgenda;
            
            $this->connect->commit();
            
            $this->set(compact('data'));

        } catch (Exception $e) {
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function delete($codigo)
    {
        $this->request->allowMethod(['delete']);

        $codigo_usuario_logado = $this->getAuthUser();
        $dataAgora = date('Y-m-d H:i:s');
            
        $mensagem = $this->Agendas->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();

        if (empty($mensagem)) {
            $error = 'Não foi encontrado o evento na agenda';
            $this->set(compact('error'));
            return;
        }

        $dados = [
            "codigo_usuario_alteracao" => $codigo_usuario_logado,
            "data_alteracao" => $dataAgora,
            "data_remocao" => $dataAgora,
            "ativo" => 0
        ];

        $entityAgenda = $this->Agendas->patchEntity($mensagem, $dados);

        if (!$this->Agendas->save($entityAgenda)) {
            $data['message'] = 'Erro ao remover o evento na agenda';
            $data['error'] = $entityAgenda->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data['message'] = "Evento removido com sucesso";
        $this->set(compact('data'));
    }

    public function getAuthUser()
    {
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
            $error = 'Logar novamente o usuario';
            $this->set(compact('error'));
            return;
        }

        return $codigo_usuario;
    }
}
