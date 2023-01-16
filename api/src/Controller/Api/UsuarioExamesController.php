<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * UsuarioExames Controller
 *
 * @property \App\Model\Table\UsuarioExamesTable $UsuarioExames
 *
 * @method \App\Model\Entity\UsuarioExame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioExamesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuarioExames = $this->paginate($this->UsuarioExames);

        $this->set(compact('usuarioExames'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario Exame id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuarioExame = $this->UsuarioExames->get($id, [
            'contain' => []
        ]);

        $this->set('usuarioExame', $usuarioExame);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuarioExame = $this->UsuarioExames->newEntity();
        if ($this->request->is('post')) {
            $usuarioExame = $this->UsuarioExames->patchEntity($usuarioExame, $this->request->getData());
            if ($this->UsuarioExames->save($usuarioExame)) {
                $this->Flash->success(__('The usuario exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario exame could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioExame'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario Exame id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuarioExame = $this->UsuarioExames->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuarioExame = $this->UsuarioExames->patchEntity($usuarioExame, $this->request->getData());
            if ($this->UsuarioExames->save($usuarioExame)) {
                $this->Flash->success(__('The usuario exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario exame could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioExame'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario Exame id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuarioExame = $this->UsuarioExames->get($id);
        if ($this->UsuarioExames->delete($usuarioExame)) {
            $this->Flash->success(__('The usuario exame has been deleted.'));
        } else {
            $this->Flash->error(__('The usuario exame could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function salvarExameUsuario(){
        $dados = $this->request->getData();

        $this->set(compact('dados'));
    }
}
