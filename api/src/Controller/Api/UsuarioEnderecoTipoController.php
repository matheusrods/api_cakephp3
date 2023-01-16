<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * UsuarioEnderecoTipo Controller
 *
 * @property \App\Model\Table\UsuarioEnderecoTipoTable $UsuarioEnderecoTipo
 *
 * @method \App\Model\Entity\UsuarioEnderecoTipo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioEnderecoTipoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuarioEnderecoTipo = $this->paginate($this->UsuarioEnderecoTipo);

        $this->set(compact('usuarioEnderecoTipo'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario Endereco Tipo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->get($id, [
            'contain' => []
        ]);

        $this->set('usuarioEnderecoTipo', $usuarioEnderecoTipo);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->newEntity();
        if ($this->request->is('post')) {
            $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->patchEntity($usuarioEnderecoTipo, $this->request->getData());
            if ($this->UsuarioEnderecoTipo->save($usuarioEnderecoTipo)) {
                $this->Flash->success(__('The usuario endereco tipo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario endereco tipo could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioEnderecoTipo'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario Endereco Tipo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->patchEntity($usuarioEnderecoTipo, $this->request->getData());
            if ($this->UsuarioEnderecoTipo->save($usuarioEnderecoTipo)) {
                $this->Flash->success(__('The usuario endereco tipo has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario endereco tipo could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioEnderecoTipo'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario Endereco Tipo id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuarioEnderecoTipo = $this->UsuarioEnderecoTipo->get($id);
        if ($this->UsuarioEnderecoTipo->delete($usuarioEnderecoTipo)) {
            $this->Flash->success(__('The usuario endereco tipo has been deleted.'));
        } else {
            $this->Flash->error(__('The usuario endereco tipo could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getAllTipoEndereco(){
        $result = $this->UsuarioEnderecoTipo->query();

        $this->set(compact('result'));
    }
}
