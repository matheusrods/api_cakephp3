<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * PosQtdParticipantes Controller
 *
 * @property \App\Model\Table\PosQtdParticipantesTable $PosQtdParticipantes
 *
 * @method \App\Model\Entity\PosQtdParticipante[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PosQtdParticipantesController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        
        $this->Auth->allow();        
    }

    /**
     * View method
     *
     * @param string|null $id Pos Qtd Participante id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $codigo_unidade)
    {

        $data = array();

        //valida se tem os parametros corretamente
        if(empty($codigo_unidade)){
            $error = "Parametro vazio (unidade)";
            $this->set(compact('error'));
            return;
        }

        $data = $this->PosQtdParticipantes->getQtdParticipante($codigo_unidade);

         //verifica se econtrou algum erro
        if(isset($data['error'])) {
            $error = $date['error'];
            $this->set(compact('error'));
            return;
        }

        $this->set(compact('data'));

        
    }//fim view

   
}
