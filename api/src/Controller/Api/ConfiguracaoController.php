<?php
namespace App\Controller\Api;

use App\Controller\AppController;

use App\Controller\Api\PosApiController as ApiController;
use Cake\Log\Log;

use Exception;


/**
 * Configuracao Controller
 *
 * @property \App\Model\Table\ConfiguracaoTable $Configuracao
 *
 * @method \App\Model\Entity\Configuracao[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfiguracaoController extends ApiController
{
    
    public function getConfiguracaoChave($chave = null)
    {

        $this->request->allowMethod(['GET']);
        try {
            if (empty($chave)) {
                throw new Exception('É obrigatório informar uma chave.');
            }
            
            $data['valor'] = $this->Configuracao->getChave($chave);
            
            $this->set(compact('data'));
        } catch (Exception $e) {
            $data = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            $this->set(compact('data'));
        }

    }//fim getConfiguracao 


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $configuracao = $this->paginate($this->Configuracao);

        $this->set(compact('configuracao'));
    }


    /**
     * View method
     *
     * @param string|null $id Configuracao id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configuracao = $this->Configuracao->get($id, [
            'contain' => [],
        ]);

        $this->set('configuracao', $configuracao);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configuracao = $this->Configuracao->newEntity();
        if ($this->request->is('post')) {
            $configuracao = $this->Configuracao->patchEntity($configuracao, $this->request->getData());
            if ($this->Configuracao->save($configuracao)) {
                $this->Flash->success(__('The configuracao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The configuracao could not be saved. Please, try again.'));
        }
        $this->set(compact('configuracao'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Configuracao id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configuracao = $this->Configuracao->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configuracao = $this->Configuracao->patchEntity($configuracao, $this->request->getData());
            if ($this->Configuracao->save($configuracao)) {
                $this->Flash->success(__('The configuracao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The configuracao could not be saved. Please, try again.'));
        }
        $this->set(compact('configuracao'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Configuracao id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configuracao = $this->Configuracao->get($id);
        if ($this->Configuracao->delete($configuracao)) {
            $this->Flash->success(__('The configuracao has been deleted.'));
        } else {
            $this->Flash->error(__('The configuracao could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
