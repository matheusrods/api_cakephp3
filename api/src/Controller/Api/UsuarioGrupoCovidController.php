<?php
namespace App\Controller\Api;

//use App\Controller\AppController;
use App\Controller\Api\ApiController;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * UsuarioGrupoCovid Controller
 *
 * @property \App\Model\Table\UsuarioGrupoCovidTable $UsuarioGrupoCovid
 *
 * @method \App\Model\Entity\UsuarioGrupoCovid[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioGrupoCovidController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuarioGrupoCovid = $this->paginate($this->UsuarioGrupoCovid);

        $this->set(compact('usuarioGrupoCovid'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario Grupo Covid id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuarioGrupoCovid = $this->UsuarioGrupoCovid->get($id, [
            'contain' => []
        ]);

        $this->set('usuarioGrupoCovid', $usuarioGrupoCovid);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuarioGrupoCovid = $this->UsuarioGrupoCovid->newEntity();
        if ($this->request->is('post')) {
            $usuarioGrupoCovid = $this->UsuarioGrupoCovid->patchEntity($usuarioGrupoCovid, $this->request->getData());
            if ($this->UsuarioGrupoCovid->save($usuarioGrupoCovid)) {
                $this->Flash->success(__('The usuario grupo covid has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario grupo covid could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioGrupoCovid'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuario Grupo Covid id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuarioGrupoCovid = $this->UsuarioGrupoCovid->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuarioGrupoCovid = $this->UsuarioGrupoCovid->patchEntity($usuarioGrupoCovid, $this->request->getData());
            if ($this->UsuarioGrupoCovid->save($usuarioGrupoCovid)) {
                $this->Flash->success(__('The usuario grupo covid has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario grupo covid could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioGrupoCovid'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario Grupo Covid id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuarioGrupoCovid = $this->UsuarioGrupoCovid->get($id);
        if ($this->UsuarioGrupoCovid->delete($usuarioGrupoCovid)) {
            $this->Flash->success(__('The usuario grupo covid has been deleted.'));
        } else {
            $this->Flash->error(__('The usuario grupo covid could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
}
