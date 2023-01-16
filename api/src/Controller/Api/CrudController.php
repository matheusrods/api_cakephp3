<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

class CrudController extends ApiController
{
    private $modelLoaded;

    public function initialize()
    {
        parent::initialize();
        
        if(!isset($this->model) && empty($this->model)){
            throw new Exception("Erros na definição da Model para o crud", 1);
        }

        if(!isset($this->searchAllowedSFields) && empty($this->searchAllowedSFields)){
            throw new Exception("Erros na definição de parâmetros aceitos para o crud", 1);
        }
        
        $this->modelLoaded = $this->loadModel($this->model);
        
    }

    
    /**
     * Crud GET List
     * Listagem de registros, pesquisas atravém do metodo GET
     *
     * @return null|array
     */
    public function index()
    {
        $this->request->allowMethod(['get']);

        $queryParams = $this->request->getQueryParams();

        $data = [];

        try{

            $args = $this->sanitizeParams($queryParams, $this->searchAllowedSFields);

            $find = $this->modelLoaded->search($args, true);
    
            $data = $this->paginate($find);
                
        } catch (\Exception $e) {
            $this->debug($e->getCode(), $e->getMessage());
        }
    
        $this->set(compact('data'));

    }


    /**
     * Registro
     * Pesquisa de registros atravém do método POST
     *
     * @return null|array
     */
    public function search(){

        $this->request->allowMethod(['post', 'get']);
        
        $formParams = $this->request->is('post') ? $this->request->getData() : $this->request->getQueryParams();

        $data = [];

        try{
            
            $args = $this->sanitizeParams($formParams, $this->searchAllowedSFields);

            if(is_array($args)){
                $args = $this->removeNullParams($args); // remover campos vazios e nulos
            }

            $find = $this->modelLoaded->search($args, true);

            $data = $this->paginate($find);

        } catch (\Exception $e) {
            $this->debug($e->getCode(), $e->getMessage());
        }

        $this->set(compact('data'));
        
    }


    /**
     * Registro
     * Detalhe de um registro
     *
     * @param string|null $id ex. Registro id.
     * @return null|array
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);

        $data = [];
        
        try {

            $data = $this->modelLoaded->search(['codigo' => $id]);

        } catch (\Exception $e) { 
            $this->debug($e->getCode(), $e->getMessage());
        }

        $this->set('data', $data);
    }

    
    /**
     * Registro
     * Adiciona um registro
     *
     * @return null|array Id do registro criado
     */
    public function add()
    {
        $this->request->allowMethod(['post']);

        $registro = $this->modelLoaded->newEntity($this->request->getData());
        $registro->set(['codigo_usuario_inclusao'=> 1]);
        if (!$this->modelLoaded->save($registro)) {
            $message[] = $registro->getValidationErrors();
            $this->set(compact('message'));
            return;
        }
        
        $data['codigo'] = isset($registro->codigo) ? $registro->codigo : $registro->id;
        $this->set(compact('data'));

    }

    /**
     * Registro
     * Altera um registro
     *
     * @param string|null $id Registro id.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['put']);

        try {

            $registro = $this->modelLoaded->get(['codigo' => $id]);

        } catch (\Exception $e) { 

            $this->debug($e->getCode(), $e->getMessage());
            $message[] = 'Registro não encontrado.';
            $this->set(compact('message'));
            return;
        }
        
        $registroEntity = $this->modelLoaded->patchEntity($registro, $this->request->getData());

        if (!$this->modelLoaded->save($registroEntity)) {
            $message[] = $registro->getValidationErrors();
            $this->set(compact('message'));
            return;
        }

        $message[] = "Registro alterado com sucesso!";
        $this->set(compact('message'));

    }

    /**
     * Apaga um registro
     *
     * @param string|null $id Registro id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);

        try {

            $registro = $this->modelLoaded->get(['codigo' => $id]);

        } catch (\Exception $e) { 
            $this->debug($e->getCode(), $e->getMessage());
            $message[] = 'Registro não encontrado.';
            $this->set(compact('message'));
            return;
        }

        if (!$this->modelLoaded->delete($registro)) {
            $message[] = $registro->getValidationErrors();
            $this->set(compact('message'));
            return;
        }

        $message[] = "Registro removido com sucesso!";
        $this->set(compact('message'));
    }
}