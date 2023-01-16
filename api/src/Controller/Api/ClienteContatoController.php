<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * ClienteContato Controller
 *
 *
 * @method \App\Model\Entity\ClienteContato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClienteContatoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $clienteContato = $this->paginate($this->ClienteContato);

        $this->set(compact('clienteContato'));
    }

    /**
     * View method
     *
     * @param string|null $id Cliente Contato id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $clienteContato = $this->ClienteContato->get($id, [
            'contain' => []
        ]);

        $this->set('clienteContato', $clienteContato);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $clienteContato = $this->ClienteContato->newEntity();
        if ($this->request->is('post')) {
            $clienteContato = $this->ClienteContato->patchEntity($clienteContato, $this->request->getData());
            if ($this->ClienteContato->save($clienteContato)) {
                $this->Flash->success(__('The cliente contato has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente contato could not be saved. Please, try again.'));
        }
        $this->set(compact('clienteContato'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cliente Contato id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $clienteContato = $this->ClienteContato->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $clienteContato = $this->ClienteContato->patchEntity($clienteContato, $this->request->getData());
            if ($this->ClienteContato->save($clienteContato)) {
                $this->Flash->success(__('The cliente contato has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cliente contato could not be saved. Please, try again.'));
        }
        $this->set(compact('clienteContato'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cliente Contato id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $clienteContato = $this->ClienteContato->get($id);
        if ($this->ClienteContato->delete($clienteContato)) {
            $this->Flash->success(__('The cliente contato has been deleted.'));
        } else {
            $this->Flash->error(__('The cliente contato could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
