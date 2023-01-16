<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Uperfis Controller
 *
 *
 * @method \App\Model\Entity\Uperfi[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UperfisController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $uperfis = $this->paginate($this->Uperfis);

        $this->set(compact('uperfis'));
    }

    /**
     * View method
     *
     * @param string|null $id Uperfi id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $uperfi = $this->Uperfis->get($id, [
            'contain' => []
        ]);

        $this->set('uperfi', $uperfi);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $uperfi = $this->Uperfis->newEntity();
        if ($this->request->is('post')) {
            $uperfi = $this->Uperfis->patchEntity($uperfi, $this->request->getData());
            if ($this->Uperfis->save($uperfi)) {
                $this->Flash->success(__('The uperfi has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The uperfi could not be saved. Please, try again.'));
        }
        $this->set(compact('uperfi'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Uperfi id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $uperfi = $this->Uperfis->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $uperfi = $this->Uperfis->patchEntity($uperfi, $this->request->getData());
            if ($this->Uperfis->save($uperfi)) {
                $this->Flash->success(__('The uperfi has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The uperfi could not be saved. Please, try again.'));
        }
        $this->set(compact('uperfi'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Uperfi id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $uperfi = $this->Uperfis->get($id);
        if ($this->Uperfis->delete($uperfi)) {
            $this->Flash->success(__('The uperfi has been deleted.'));
        } else {
            $this->Flash->error(__('The uperfi could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
