<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Validation\Validator;

/**
 * UsuariosMedicamentosStatus Controller
 *
 * @property \App\Model\Table\UsuariosMedicamentosStatusTable $UsuariosMedicamentosStatus
 *
 * @method \App\Model\Entity\UsuariosMedicamentosStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuariosMedicamentosStatusController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuariosMedicamentosStatus = $this->paginate($this->UsuariosMedicamentosStatus);

        $this->set(compact('usuariosMedicamentosStatus'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuarios Medicamentos Status id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->get($id, [
            'contain' => []
        ]);

        $this->set('usuariosMedicamentosStatus', $usuariosMedicamentosStatus);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->newEntity();
        if ($this->request->is('post')) {
            $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->patchEntity($usuariosMedicamentosStatus, $this->request->getData());
            if ($this->UsuariosMedicamentosStatus->save($usuariosMedicamentosStatus)) {
                $this->Flash->success(__('The usuarios medicamentos status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios medicamentos status could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosMedicamentosStatus'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Usuarios Medicamentos Status id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->patchEntity($usuariosMedicamentosStatus, $this->request->getData());
            if ($this->UsuariosMedicamentosStatus->save($usuariosMedicamentosStatus)) {
                $this->Flash->success(__('The usuarios medicamentos status has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuarios medicamentos status could not be saved. Please, try again.'));
        }
        $this->set(compact('usuariosMedicamentosStatus'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuarios Medicamentos Status id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuariosMedicamentosStatus = $this->UsuariosMedicamentosStatus->get($id);
        if ($this->UsuariosMedicamentosStatus->delete($usuariosMedicamentosStatus)) {
            $this->Flash->success(__('The usuarios medicamentos status has been deleted.'));
        } else {
            $this->Flash->error(__('The usuarios medicamentos status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function addMedicamecaoTomada() {

        if($this->request->is(['POST'])){
            //Valida????o dos campos necess??rios
            $validator = new Validator();

            //Campos necess??rios e modifica????o da mensagem de erro
            $field_need = array(
                'codigo_usuario_medicamento' => array(
                    'message' => 'Este campo ?? necess??rio.'
                ),
            );

            //Campo necess??rios
            $validator->requirePresence($field_need);

            //Verifica????o dos dados recebidos
            $errors = $validator->errors($this->request->getData());
            if (!empty($errors)) {
                $this->set('error', $errors);
                return;
            };

            $codigo_usuario_medicamento = (int) $this->request->getData('codigo_usuario_medicamento');
            $user_codigo = $this->Auth->user('codigo');

            $this->loadModel('UsuariosMedicamentos');
            $hasUsuariosMedicamento = $this->UsuariosMedicamentos->find()->where(['codigo'=>$codigo_usuario_medicamento])->first();

            if(empty($hasUsuariosMedicamento)){
                $error[] = "Usuario com esse medicamento n??o foi econtrado";
                $this->set(compact('error'));
                return null;
            }

            $usuario_medicamento_status = $this->UsuariosMedicamentosStatus->newEntity();
            $usuario_medicamento_status->codigo_usuario_medicamento = $codigo_usuario_medicamento;
            $usuario_medicamento_status->data_hora_uso = date('Y-m-d H:i:s');
            $usuario_medicamento_status->data_inclusao = date('Y-m-d H:i:s');
            $usuario_medicamento_status->codigo_usuario_inclusao = $user_codigo;

            if ($this->UsuariosMedicamentosStatus->save($usuario_medicamento_status)) {
                $success = "Medica????o tomada salva com sucesso.";
                $this->set(compact('success'));
//                return $this->redirect(['action' => 'index']);
            } else {
                $fail = "Medica????o tomada n??o pode ser salva. Por favor tente novamente.";
                $this->set(compact('fail'));

            }

        } else {
            $error[] = "Favor passar o metodo corretamente!";
        }

        // sa??da
        if(!empty($usuario_medicamento_status)) {
            $this->set(compact('programacao_medicamento'));
        }
        else {
            $this->set(compact('error'));
        }
    }
}
