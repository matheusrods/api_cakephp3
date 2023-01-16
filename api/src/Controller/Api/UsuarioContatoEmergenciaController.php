<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * UsuarioContatoEmergencia Controller
 *
 * @property \App\Model\Table\UsuarioContatoEmergenciaTable $UsuarioContatoEmergencia
 *
 * @method \App\Model\Entity\UsuarioContatoEmergencium[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioContatoEmergenciaController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuarioContatoEmergencia = $this->paginate($this->UsuarioContatoEmergencia);

        $this->set(compact('usuarioContatoEmergencia'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario Contato Emergencium id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->get($id, [
            'contain' => []
        ]);

        $this->set('usuarioContatoEmergencium', $usuarioContatoEmergencium);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($codigo_usuario)
    {
        //variavel para add o contato
        $data = '';
        if ($this->request->is(['post','put'])) {
            $this->loadModel('UsuarioContatoEmergencia');
            $dados = $this->request->getData();

            $dados['codigo_usuario'] = $codigo_usuario;
            $dados['ativo'] = 1;

            // dd($dados);

            //verifica se existe contato de emergencia
            $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->find()->where(['codigo_usuario' => $codigo_usuario])->first();
            // dd($usuarioContatoEmergencium);
            if(empty($usuarioContatoEmergencium)) {
                
                $dados['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['data_inclusao'] = date('Y-m-d H:i:s');

                $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->newEntity($dados);
            }
            else {
                $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->patchEntity($usuarioContatoEmergencium, $dados);
            }


            // dd($usuarioContatoEmergencium);

            if ($this->UsuarioContatoEmergencia->save($usuarioContatoEmergencium)) {
               $data = "Registro registrado com Sucesso!";
            }
            else {
                $error = "Erro ao cadastrar contato de emergencia";
            }
        }

        //verifica o retorno para quem chamou a api
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario Contato Emergencium id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($codigo_usuario)
    {
        $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->find()->where(['codigo_usuario' => $codigo_usuario])->first();
        $data = '';
        if ($this->request->is(['put'])) {

            $dados = $this->request->getData();
            $dados['codigo_usuario'] = $codigo_usuario;
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');

            $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->patchEntity($usuarioContatoEmergencium, $dados);

            if ($this->UsuarioContatoEmergencia->save($usuarioContatoEmergencium)) {
                $data = "Contato de Emergencia atualizado com Sucesso!";
            }
            else {
                $error = "Erro ao atualizar contato de emergencia";
            }
        }

        //verifica o retorno para quem chamou a api
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }
    }//fim edit

    // /**
    //  * Delete method
    //  *
    //  * @param string|null $id Usuario Contato Emergencium id.
    //  * @return \Cake\Http\Response|null Redirects to index.
    //  * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    //  */
    // public function delete($id = null)
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $usuarioContatoEmergencium = $this->UsuarioContatoEmergencia->get($id);
    //     if ($this->UsuarioContatoEmergencia->delete($usuarioContatoEmergencium)) {
    //         $this->Flash->success(__('The usuario contato emergencium has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The usuario contato emergencium could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }
}
