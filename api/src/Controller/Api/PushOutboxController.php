<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * PushOutbox Controller
 *
 * @property \App\Model\Table\PushOutboxTable $PushOutbox
 *
 * @method \App\Model\Entity\PushOutbox[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PushOutboxController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $pushOutbox = $this->paginate($this->PushOutbox);

        $this->set(compact('pushOutbox'));
    }

    /**
     * View method
     *
     * @param string|null $id Push Outbox id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_usuario = null)
    {
        //$data = $this->PushOutbox->find()->select(['codigo', 'titulo', 'mensagem','msg_lida','funcionalidade' => 'link'])->where(['codigo_usuario' => $codigo_usuario,'data_envio IS NOT NULL','msg_lida' => '0'])->limit(20)->all();
        $data = $this->PushOutbox->find()->select(['codigo'=>'codigo',
            'titulo'=>'RHHealth.dbo.ufn_decode_utf8_string(titulo)',
            'mensagem'=>'RHHealth.dbo.ufn_decode_utf8_string(mensagem)',
            'msg_lida',
            'funcionalidade' => 'link'])->where(['codigo_usuario' => $codigo_usuario])->limit(20)->all();


        $this->set(compact('data'));
    }

    /**
     * [setMsgLida description]
     *
     * metodo para setar que a mensagem foi lida
     *
     * @param [type] $codigo_push [description]
     */
    public function setMsgLida($codigo_push)
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        $params = $this->request->getData();

        // se não tem parametros válidos termina a requisição
        if(!isset($params['msg_lida'])){
            $error[] = "Parâmetros não encontrado no body";
            $this->set(compact('error'));
            return;
        }
        // regras de aceite, apenas se valor de msg_lida for igual a 1
        if($params['msg_lida'] != 1){
            $error[] = "Parâmetros incorretos";
            $this->set(compact('error'));
            return;
        }

        $pushOutbox = $this->PushOutbox->get($codigo_push);

        if(!$pushOutbox){
            $error[] = "Mensagem não encontrada";
            $this->set(compact('error'));
            $abc = "a";

            return;
        }

        $pushOutbox = $this->PushOutbox->patchEntity($pushOutbox, $this->request->getData());

        if ($this->PushOutbox->save($pushOutbox)) {

            $data = "Mensagem atualizada com sucesso!";
            $this->set(compact('data'));
        }
        else {
            $error[] = "Erro ao atualizar leitura da mensagem";
            $this->set(compact('error'));
        }


    }//fim setMsgLida
}
