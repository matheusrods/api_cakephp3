<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * Mensagens Controller
 *
 * @property \App\Model\Table\MensagensTable $Mensagens
 *
 * @method \App\Model\Entity\Mensagem[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MensagensController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        
        $this->loadModel("Mensagens");
        $this->loadModel("UsuariosDados");
    }

    public function index($codigo_usuario)
    {
        $usuario = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        if (empty($usuario)) {
            $error = 'Usuário não encontrado!';
            $this->set(compact('error'));
            return;
        }

        $where = ['codigo_usuario' => $usuario['codigo_usuario'], 'ativo' => 1];
        if( $this->request->getQuery('leitura') !== null ){
            $leitura = $this->request->getQuery('leitura');
            if($leitura === "true"){
                $where[] = 'data_leitura IS NOT NULL';
            }else{
                $where[] = 'data_leitura IS NULL';
            }
        }

        $data = $this->Mensagens->find()
            ->where($where)
            ->order(['codigo DESC']);

        $this->set(compact('data'));
    }

    public function view($codigo)
    {
        $data = $this->Mensagens->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();
        $this->set(compact('data'));
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $request = $this->request->getData();

        $request['codigo_usuario_inclusao'] = $this->getAuthUser();
        $request['data_inclusao'] = date('Y-m-d H:i:s');

        $entityMensagem = $this->Mensagens->newEntity($request);

        if (!$this->Mensagens->save($entityMensagem)) {
            $data['message'] = 'Erro ao inserir a mensagem';
            $data['error'] = $entityMensagem->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data = $entityMensagem;
        $this->set(compact('data'));
    }

    public function edit()
    {
        $this->request->allowMethod(['put']);
        
        $request = $this->request->getData();

        $request['codigo_usuario_alteracao'] = $this->getAuthUser();
        $request['data_alteracao'] = date('Y-m-d H:i:s');
            
        $mensagem = $this->Mensagens->find()->where(['codigo' => $request['codigo'], 'ativo' => 1])->first();

        if (empty($mensagem)) {
            $error = 'Não foi encontrado a mensagem';
            $this->set(compact('error'));
            return;
        }

        $entityMensagem = $this->Mensagens->patchEntity($mensagem, $request);

        if (!$this->Mensagens->save($entityMensagem)) {
            $data['message'] = 'Erro ao editar a mensagem';
            $data['error'] = $entityMensagem->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data = $entityMensagem;
        $this->set(compact('data'));
    }

    public function delete($codigo)
    {
        $this->request->allowMethod(['delete']);

        $codigo_usuario_logado = $this->getAuthUser();
        $dataAgora = date('Y-m-d H:i:s');
            
        $mensagem = $this->Mensagens->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();

        if (empty($mensagem)) {
            $error = 'Não foi encontrado a mensagem';
            $this->set(compact('error'));
            return;
        }

        $dados = [
            "codigo_usuario_alteracao" => $codigo_usuario_logado,
            "data_alteracao" => $dataAgora,
            "data_remocao" => $dataAgora,
            "ativo" => 0
        ];

        $entityMensagem = $this->Mensagens->patchEntity($mensagem, $dados);

        if (!$this->Mensagens->save($entityMensagem)) {
            $data['message'] = 'Erro ao remover a mensagem';
            $data['error'] = $entityMensagem->getErrors();
            $this->set(compact('data'));
            return;
        }

        $data['message'] = "Mensagem removida com sucesso";
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
