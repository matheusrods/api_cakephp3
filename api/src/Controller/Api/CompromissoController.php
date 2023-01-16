<?php
namespace App\Controller\Api;

//use App\Controller\AppController;
use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;

/**
 * Compromisso Controller
 *
 *
 * @method \App\Model\Entity\Compromisso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompromissoController extends ApiController
{
    
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['setCompromisso']);
    
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $compromisso = $this->paginate($this->Compromisso);

        $this->set(compact('compromisso'));
    }

    /**
     * View method
     *
     * @param string|null $id Compromisso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $compromisso = $this->Compromisso->get($id, [
            'contain' => []
        ]);

        $this->set('compromisso', $compromisso);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function setCompromisso()
    {  

        //verifica se é post
        if ($this->request->is(['post', 'put'])) {            

            $dados = $this->request->getData();

            if(empty($dados['codigo_usuario'])){
                $error = 'Código do usuário está vazio.';
                $this->set(compact('error'));
                return;
            }
            if(empty($dados['codigo_medico'])){
                $error = 'Código do médico está vazio.';
                $this->set(compact('error'));
                return;
            }
            if(empty($dados['data'])){
                $error = 'Data está vazio.';
                $this->set(compact('error'));
                return;
            }
            if(empty($dados['titulo'])){
                $error = 'Título está vazio.';
                $this->set(compact('error'));
                return;
            }
            if(empty($dados['hora_inicio'])){
                $error = 'Hora início está vazio.';
                $this->set(compact('error'));
                return;
            }
            

            if($this->request->is('post')){//inserção

                $atualizando = false;
                $dados['data_inclusao']= date("Y-m-d H:i:s");
                $dados['codigo_usuario_inclusao'] = $dados['codigo_usuario'];
                $compromisso = $this->Compromisso->newEntity();

            } elseif ($this->request->is('put') && isset($dados['codigo']) && !empty($dados['codigo'])) {//alteração

                $atualizando = true;
                $dados['data_alteracao']= date("Y-m-d H:i:s");
                $dados['codigo_usuario_alteracao'] = $dados['codigo_usuario'];
                $compromisso = $this->Compromisso->get(['codigo' => $dados['codigo']]);

            }  

            $compromisso = $this->Compromisso->patchEntity($compromisso, $dados);
            
            try{          
                //echo $this->Compromisso->save($compromisso); 
                if ($this->Compromisso->save($compromisso)) { 

                    if(!$atualizando){
                        $this->set('data','Compromisso criado com sucesso!');
                    }else{
                        $this->set('data','Compromisso alterado com sucesso!');
                    }

                }else{

                    //debug($compromisso->errors());
                    //die();

                    $error = 'Falha ao salvar';
                    $this->set(compact('error'));
                    return;
                }
                

            } catch (\Exception $e) {

                //rollback da transacao
                //$conn->rollback();

                $error[] = $e->getMessage();
                $this->set(compact('error'));
                return;
            }

        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Compromisso id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $compromisso = $this->Compromisso->get($id, ['contain' => []]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $compromisso = $this->Compromisso->patchEntity($compromisso, $this->request->getData());
            if ($this->Compromisso->save($compromisso)) {
                $this->Flash->success(__('The compromisso has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The compromisso could not be saved. Please, try again.'));
        }
        $this->set(compact('compromisso'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Compromisso id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $compromisso = $this->Compromisso->get($id);
        if ($this->Compromisso->delete($compromisso)) {
            $this->Flash->success(__('The compromisso has been deleted.'));
        } else {
            $this->Flash->error(__('The compromisso could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
