<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

/**
 * FornecedoresContato Controller
 *
 * @property \App\Model\Table\FornecedoresContatoTable $FornecedoresContato
 *
 * @method \App\Model\Entity\FornecedoresContato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedoresContatoController extends AppController
{

    public function listaGet(int $codigo_fornecedor)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel('FornecedoresContato');
        $data = $this->FornecedoresContato->getContatos($codigo_fornecedor);
        $this->set(compact('data'));
    }

    public function contatoGet(int $codigo_fornecedor, int $codigo_contato)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel('FornecedoresContato');
        $data = $this->FornecedoresContato->getContato($codigo_fornecedor, $codigo_contato);
        $this->set(compact('data'));
    }

    public function contatoPost()
    {
        $this->request->allowMethod(['post']);

        $this->loadModel('FornecedoresContato');
        $this->loadModel('TipoRetorno');

        try {

            $dados = [
                'codigo_fornecedor'   => $this->request->getData('codigo_fornecedor'),
                'nome'                => $this->request->getData('nome'),
                'codigo_tipo_contato' => $this->request->getData('codigo_tipo_contato'),
                'codigo_tipo_retorno' => $this->request->getData('codigo_tipo_retorno'),
                'descricao'           => $this->request->getData('descricao'),
            ];


            $dados = $this->formataData($dados);
            $error = $dados['error'];
            if (count($error) > 0) {
                $this->set(compact('error'));
                return;
            }

            $getFornecedorContato = $this->Fornecedores->get($dados['codigo_fornecedor']);
            $fornecedorContato = $this->Fornecedores->patchEntity($getFornecedorContato, $dados);
            if ($this->Fornecedores->save($fornecedorContato)) {

                $data = array(
                    "success" => true,
                    "message" => "Contato salvos!"
                );

            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar o contato!"
                );

            }
            $this->set(compact('data'));
        } catch (Exception $e) {

            $error[] = $e->getMessage();
            $this->set(compact('error'));

        }
    }

    public function contatoPut()
    {
        $this->request->allowMethod(['put']);

        $this->loadModel('FornecedoresContato');
        
        try {
            $dados = [
                'codigo'              => $this->request->getData('codigo_contato'),
                'codigo_fornecedor'   => $this->request->getData('codigo_fornecedor'),
                'nome'                => $this->request->getData('nome'),
                'codigo_tipo_contato' => $this->request->getData('codigo_tipo_contato'),
                'codigo_tipo_retorno' => $this->request->getData('codigo_tipo_retorno'),
                'descricao'           => $this->request->getData('descricao'),
            ];

            $dados = $this->formataData($dados);
            $error = $dados['error'];
            if (count($error) > 0) {
                $this->set(compact('error'));
                return;
            }
            
            $dados = $dados['response'];
            $getFornecedorContato = $this->FornecedoresContato->get($dados['codigo']);
            $fornecedorContato = $this->FornecedoresContato->patchEntity($getFornecedorContato, $dados);
            if ($this->FornecedoresContato->save($fornecedorContato)) {

                $data = array(
                    "success" => true,
                    "message" => "Contato salvo!"
                );

            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar o contato!"
                );

            }
            $this->set(compact('data'));
        } catch (Exception $e) {

            $error[] = $e->getMessage();
            $this->set(compact('error'));

        }
    }

    public function getTiposContatoRetorno()
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        try {

            $this->loadModel('TipoRetorno');

            $tipoRetorno = $this->TipoRetorno
                                ->find()
                                ->select(['codigo', 'descricao'])
                                ->order(['descricao ASC']);

            $this->set(compact('tipoRetorno'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }


    /**
     * @param array $dados
     * @param string|string $http
     * @return array
     */
    public function formataData(array $dados)
    {
        $error = [];
        if (empty($dados['codigo']) && $this->request->getMethod() == 'PUT') {
            $error[] = 'Informe o código do contato';
        }

        if(empty($dados['nome'])){
            $error[] = 'Informe o Representante (campo nome)';
        }

        if(empty($dados['codigo_tipo_contato'])){
            $error[] = 'Informe o código tipo de contato';
        }

        if (empty($dados['codigo_tipo_retorno'])) {
            $erro[] = "Informe o tipo de retorno";
        } else {

            $this->loadModel('TipoRetorno');

            $getTiposContatoRetorno = $this->TipoRetorno
                ->find()
                ->select(['codigo', 'descricao'])
                ->order(['descricao ASC']);

            foreach ($getTiposContatoRetorno as $item) {
                if ( $item['codigo'] == $dados['codigo_tipo_retorno'] && $dados['descricao'] == null) {
                    $error[] = "Informe o {$item['descricao']} na descrição";
                }
            }
        }
        //$dados = $this->formataTelefone($dados);

        return [
            'response' => $dados,
            'error' => $error
            ];
    }

    /*private function formataTelefone($dados)
    {
        if (in_array($dados['codigo_tipo_retorno'], array(1,3,5))) {
            $fone = preg_replace( '/[^0-9]/', '', $dados['descricao'] );
            $dados['ddd'] = substr($fone,0,2);
            $dados['descricao'] = substr($fone,2);
        }

        return $dados;
    }*/

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fornecedoresContato = $this->paginate($this->FornecedoresContato);

        $this->set(compact('fornecedoresContato'));
    }

    /**
     * View method
     *
     * @param string|null $id Fornecedores Contato id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $fornecedoresContato = $this->FornecedoresContato->get($id, [
            'contain' => [],
        ]);

        $this->set('fornecedoresContato', $fornecedoresContato);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $fornecedoresContato = $this->FornecedoresContato->newEntity();
        if ($this->request->is('post')) {
            $fornecedoresContato = $this->FornecedoresContato->patchEntity($fornecedoresContato, $this->request->getData());
            if ($this->FornecedoresContato->save($fornecedoresContato)) {
                $this->Flash->success(__('The fornecedores contato has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fornecedores contato could not be saved. Please, try again.'));
        }
        $this->set(compact('fornecedoresContato'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fornecedores Contato id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fornecedoresContato = $this->FornecedoresContato->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fornecedoresContato = $this->FornecedoresContato->patchEntity($fornecedoresContato, $this->request->getData());
            if ($this->FornecedoresContato->save($fornecedoresContato)) {
                $this->Flash->success(__('The fornecedores contato has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fornecedores contato could not be saved. Please, try again.'));
        }
        $this->set(compact('fornecedoresContato'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fornecedores Contato id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fornecedoresContato = $this->FornecedoresContato->get($id);
        if ($this->FornecedoresContato->delete($fornecedoresContato)) {
            $this->Flash->success(__('The fornecedores contato has been deleted.'));
        } else {
            $this->Flash->error(__('The fornecedores contato could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
