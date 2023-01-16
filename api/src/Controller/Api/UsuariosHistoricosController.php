<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * UsuariosHistoricos Controller
 *
 *
 * @method \App\Model\Entity\UsuariosHistorico[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosHistoricosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuariosHistoricos = $this->paginate($this->UsuariosHistoricos);

        $this->set(compact('usuariosHistoricos'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuarios Historico id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuariosHistorico = $this->UsuariosHistoricos->get($id, [
            'contain' => [],
        ]);

        $this->set('usuariosHistorico', $usuariosHistorico);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuariosHistorico = $this->UsuariosHistoricos->newEntity();
        if ($this->request->is('post')) {
            $usuariosHistorico = $this->UsuariosHistoricos->patchEntity($usuariosHistorico, $this->request->getData());
            if ($this->UsuariosHistoricos->save($usuariosHistorico)) {
                $this->Flash->success(__('The usuarios historico has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios historico could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosHistorico'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuarios Historico id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuariosHistorico = $this->UsuariosHistoricos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuariosHistorico = $this->UsuariosHistoricos->patchEntity($usuariosHistorico, $this->request->getData());
            if ($this->UsuariosHistoricos->save($usuariosHistorico)) {
                $this->Flash->success(__('The usuarios historico has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios historico could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosHistorico'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuarios Historico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuariosHistorico = $this->UsuariosHistoricos->get($id);
        if ($this->UsuariosHistoricos->delete($usuariosHistorico)) {
            $this->Flash->success(__('The usuarios historico has been deleted.'));
        } else {
            $this->Flash->error(__('The usuarios historico could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
