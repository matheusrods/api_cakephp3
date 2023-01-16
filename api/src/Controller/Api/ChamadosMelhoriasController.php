<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * ChamadosMelhorias Controller
 *
 * @property \App\Model\Table\ChamadosMelhoriasTable $ChamadosMelhorias
 *
 * @method \App\Model\Entity\ChamadosMelhorias[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChamadosMelhoriasController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        
        $this->loadModel("ChamadosMelhorias");
        $this->loadModel("UsuariosDados");
    }

    public function index()
    {
        $data = $this->ChamadosMelhorias->find()->where(['ativo' => 1])->order(['codigo DESC']);
        $this->set(compact('data'));
    }

    public function view($codigo)
    {
        $data = $this->ChamadosMelhorias->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();
        $this->set(compact('data'));
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $request = $this->request->getData();

        $request['codigo_usuario_inclusao'] = $this->getAuthUser();
        $request['data_inclusao'] = date('Y-m-d H:i:s');

        $entityChamadoMelhoria = $this->ChamadosMelhorias->newEntity($request);

        if (!$this->ChamadosMelhorias->save($entityChamadoMelhoria)) {
            $data['message'] = 'Erro ao inserir a ações de melhoria';
            $data['error'] = $entityChamadoMelhoria->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data = $entityChamadoMelhoria;
        $this->set(compact('data'));
    }

    public function edit()
    {
        $this->request->allowMethod(['put']);
        
        $request = $this->request->getData();

        $request['codigo_usuario_alteracao'] = $this->getAuthUser();
        $request['data_alteracao'] = date('Y-m-d H:i:s');
            
        $chamadoMelhoria = $this->ChamadosMelhorias->find()->where(['codigo' => $request['codigo'], 'ativo' => 1])->first();

        if (empty($chamadoMelhoria)) {
            $error = 'Não foi encontrado a ações de melhoria';
            $this->set(compact('error'));
            return;
        }

        $entityChamadoMelhoria = $this->ChamadosMelhorias->patchEntity($chamadoMelhoria, $request);

        if (!$this->ChamadosMelhorias->save($entityChamadoMelhoria)) {
            $data['message'] = 'Erro ao editar a ações de melhoria';
            $data['error'] = $entityChamadoMelhoria->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data = $entityChamadoMelhoria;
        $this->set(compact('data'));
    }

    public function delete($codigo)
    {
        $this->request->allowMethod(['delete']);

        $codigo_usuario_logado = $this->getAuthUser();
        $dataAgora = date('Y-m-d H:i:s');
            
        $chamadoMelhoria = $this->ChamadosMelhorias->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();

        if (empty($chamadoMelhoria)) {
            $error = 'Não foi encontrado a ações de melhoria';
            $this->set(compact('error'));
            return;
        }

        $dados = [
            "codigo_usuario_alteracao" => $codigo_usuario_logado,
            "data_alteracao" => $dataAgora,
            "ativo" => 0
        ];

        $entityChamadoMelhoria = $this->ChamadosMelhorias->patchEntity($chamadoMelhoria, $dados);

        if (!$this->ChamadosMelhorias->save($entityChamadoMelhoria)) {
            $data['message'] = 'Erro ao remover a ações de melhoria';
            $data['error'] = $entityChamadoMelhoria->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data['message'] = "Ações de melhoria removida com sucesso";
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
