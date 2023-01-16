<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * UsuariosMedicamentos Controller
 *
 * @property \App\Model\Table\UsuariosMedicamentosTable $UsuariosMedicamentos
 *
 * @method \App\Model\Entity\UsuariosMedicamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosMedicamentosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuariosMedicamentos = $this->paginate($this->UsuariosMedicamentos);

        $this->set(compact('usuariosMedicamentos'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuarios Medicamento id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuariosMedicamento = $this->UsuariosMedicamentos->get($id, [
            'contain' => []
        ]);

        $this->set('usuariosMedicamento', $usuariosMedicamento);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuariosMedicamento = $this->UsuariosMedicamentos->newEntity();
        if ($this->request->is('post')) {
            $usuariosMedicamento = $this->UsuariosMedicamentos->patchEntity($usuariosMedicamento, $this->request->getData());
            if ($this->UsuariosMedicamentos->save($usuariosMedicamento)) {
                $this->Flash->success(__('The usuarios medicamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios medicamento could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosMedicamento'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuarios Medicamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuariosMedicamento = $this->UsuariosMedicamentos->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuariosMedicamento = $this->UsuariosMedicamentos->patchEntity($usuariosMedicamento, $this->request->getData());
            if ($this->UsuariosMedicamentos->save($usuariosMedicamento)) {
                $this->Flash->success(__('The usuarios medicamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios medicamento could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosMedicamento'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuarios Medicamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuariosMedicamento = $this->UsuariosMedicamentos->get($id);
        if ($this->UsuariosMedicamentos->delete($usuariosMedicamento)) {
            $this->Flash->success(__('The usuarios medicamento has been deleted.'));
        } else {
            $this->Flash->error(__('The usuarios medicamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
