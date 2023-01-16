<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
use App\Utils\Comum;

/**
 * Credenciamento Controller
 *
 *
 * @method \App\Model\Entity\Credenciamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CredenciamentoController extends ApiController
{
    public function initialize()
    {
        parent::initialize();
         $this->Auth->allow(['getDadosBancarios', 'dadosBancarios', 'getBancos']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $credenciamento = $this->paginate($this->Credenciamento);

        $this->set(compact('credenciamento'));
    }

    /**
     * View method
     *
     * @param string|null $id credenciamento id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $credenciamento = $this->Credenciamento->get($id, [
            'contain' => [],
        ]);

        $this->set('credenciamento', $credenciamento);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $credenciamento = $this->Credenciamento->newEntity();
        if ($this->request->is('post')) {
            $credenciamento = $this->Credenciamento->patchEntity($credenciamento, $this->request->getData());
            if ($this->Credenciamento->save($credenciamento)) {
                $this->Flash->success(__('The credenciamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The credenciamento could not be saved. Please, try again.'));
        }
        $this->set(compact('credenciamento'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Credenciamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $credenciamento = $this->Credenciamento->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $credenciamento = $this->Credenciamento->patchEntity($credenciamento, $this->request->getData());
            if ($this->Credenciamento->save($credenciamento)) {
                $this->Flash->success(__('The credenciamento has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The credenciamento could not be saved. Please, try again.'));
        }
        $this->set(compact('credenciamento'));
    }

    /**
     * Delete method
     *
     * @param string|null $id credenciamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $credenciamento = $this->Credenciamento->get($id);
        if ($this->Credenciamento->delete($credenciamento)) {
            $this->Flash->success(__('The credenciamento has been deleted.'));
        } else {
            $this->Flash->error(__('The credenciamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getDadosBancarios($codigo_fornecedor)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        try {

            $this->loadModel('Fornecedores');

            $data = $this->Fornecedores->find()
                ->select(['codigo', 'numero_banco', 'agencia', 'numero_conta', 'tipo_conta', 'favorecido', 'modalidade_pagamento'])
                ->where(['codigo' => $codigo_fornecedor])
                ->first();

            $this->set(compact('data'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function dadosBancarios()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $this->loadModel('Fornecedores');

            if (!isset($dados['codigo_fornecedor'])) {
                $data = array(
                    "success" => false,
                    "message" => "Campo codigo_fornecedor é necessário!"
                );

                $this->set(compact('data'));
                return;
            }

            $getFornecedor = $this->Fornecedores->get($dados['codigo_fornecedor']);
            $fornecedor = $this->Fornecedores->patchEntity($getFornecedor, $dados);

            if ($this->Fornecedores->save($fornecedor)) {

                $data = array(
                    "success" => true,
                    "message" => "Dados bancarios inseridos com sucesso!"
                );
            } else {
                $data = array(
                    "success" => false,
                    "message" => "Não foi possível inserir os dados bancarios!"
                );
            }

            $this->set(compact('data'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function getBancos()
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        try {

            $this->loadModel('Bancos');

            $bancos = $this->Bancos->find();

            $this->set(compact('bancos'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
}
