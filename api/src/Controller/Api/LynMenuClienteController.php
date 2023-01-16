<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * LynMenuCliente Controller
 *
 * @property \App\Model\Table\LynMenuClienteTable $LynMenuCliente
 *
 * @method \App\Model\Entity\LynMenuCliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LynMenuClienteController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $lynMenuCliente = $this->paginate($this->LynMenuCliente);

        $this->set(compact('lynMenuCliente'));
    }

    /**
     * View method
     *
     * @param string|null $id Lyn Menu Cliente id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lynMenuCliente = $this->LynMenuCliente->get($id, [
            'contain' => [],
        ]);

        $this->set('lynMenuCliente', $lynMenuCliente);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lynMenuCliente = $this->LynMenuCliente->newEntity();
        if ($this->request->is('post')) {
            $lynMenuCliente = $this->LynMenuCliente->patchEntity($lynMenuCliente, $this->request->getData());
            if ($this->LynMenuCliente->save($lynMenuCliente)) {
                $this->Flash->success(__('The lyn menu cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lyn menu cliente could not be saved. Please, try again.'));
        }
        $this->set(compact('lynMenuCliente'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lyn Menu Cliente id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lynMenuCliente = $this->LynMenuCliente->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lynMenuCliente = $this->LynMenuCliente->patchEntity($lynMenuCliente, $this->request->getData());
            if ($this->LynMenuCliente->save($lynMenuCliente)) {
                $this->Flash->success(__('The lyn menu cliente has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lyn menu cliente could not be saved. Please, try again.'));
        }
        $this->set(compact('lynMenuCliente'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lyn Menu Cliente id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lynMenuCliente = $this->LynMenuCliente->get($id);
        if ($this->LynMenuCliente->delete($lynMenuCliente)) {
            $this->Flash->success(__('The lyn menu cliente has been deleted.'));
        } else {
            $this->Flash->error(__('The lyn menu cliente could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
