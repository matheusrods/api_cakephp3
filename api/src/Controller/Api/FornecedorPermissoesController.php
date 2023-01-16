<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Http\Client;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use InvalidArgumentException;

/**
 * FornecedorPermissoes Controller
 *
 * @property \App\Model\Table\FornecedorPermissoesTable $FornecedorPermissoes
 *
 * @method \App\Model\Entity\FornecedorPermisso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedorPermissoesController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fornecedorPermissoes = $this->paginate($this->FornecedorPermissoes);

        $this->set(compact('fornecedorPermissoes'));
    }

    /**
     * View method
     *
     * @param string|null $id Fornecedor Permisso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $fornecedorPermisso = $this->FornecedorPermissoes->get($id, [
            'contain' => ['Usuario']
        ]);

        $this->set('fornecedorPermisso', $fornecedorPermisso);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fornecedorPermisso = $this->FornecedorPermissoes->newEntity();
        if ($this->request->is('post')) {
            $fornecedorPermisso = $this->FornecedorPermissoes->patchEntity($fornecedorPermisso, $this->request->getData());
            if ($this->FornecedorPermissoes->save($fornecedorPermisso)) {
                $this->Flash->success(__('The fornecedor permisso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fornecedor permisso could not be saved. Please, try again.'));
        }
        $usuario = $this->FornecedorPermissoes->Usuario->find('list', ['limit' => 200]);
        $this->set(compact('fornecedorPermisso', 'usuario'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fornecedor Permisso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fornecedorPermisso = $this->FornecedorPermissoes->get($id, [
            'contain' => ['Usuario']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fornecedorPermisso = $this->FornecedorPermissoes->patchEntity($fornecedorPermisso, $this->request->getData());
            if ($this->FornecedorPermissoes->save($fornecedorPermisso)) {
                $this->Flash->success(__('The fornecedor permisso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fornecedor permisso could not be saved. Please, try again.'));
        }
        $usuario = $this->FornecedorPermissoes->Usuario->find('list', ['limit' => 200]);
        $this->set(compact('fornecedorPermisso', 'usuario'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fornecedor Permisso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fornecedorPermisso = $this->FornecedorPermissoes->get($id);
        if ($this->FornecedorPermissoes->delete($fornecedorPermisso)) {
            $this->Flash->success(__('The fornecedor permisso has been deleted.'));
        } else {
            $this->Flash->error(__('The fornecedor permisso could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * [getFornecedorPermissoes para montar o combo box para a selecao na tela de usuarios fornecedores]
     * @return [type] [description]
     */
    public function getFornecedorPermissoes()
    {

        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if(empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        $data = $this->FornecedorPermissoes->find('all', ['fields' => ['codigo', 'descricao']])->hydrate(false)->toArray();

        $this->set(compact('data'));
        

    }//fim getFornecedorPermissoes
}
