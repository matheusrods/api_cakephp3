<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * MotivosAfastamento Controller
 *
 * @property \App\Model\Table\MotivosAfastamentoTable $MotivosAfastamento
 *
 * @method \App\Model\Entity\MotivosAfastamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MotivosAfastamentoController extends ApiController
{
    
    public function lista($codigo_motivo_afastamento=null)
    {
        $data = $this->MotivosAfastamento->obterLista($codigo_motivo_afastamento);

        $this->set(compact('data'));
    }

    public function listaESocial()
    {
        $this->loadModel('Esocial');
        // // obrigatório passar um nome        
        // if(empty($this->request->query('nome'))){
        //     $error = 'Parâmetro nome não encontrado';
        //     $this->set('error', $error);    
        //     return;
        // }

        // if(strlen($this->request->query('nome')) <= 2){
        //     $error = 'Parâmetro nome deve conter mais que 2 caracteres';
        //     $this->set('error', $error);    
        //     return;
        // }

        $query_params['descricao'] = urldecode($this->request->query('nome'));

        $data = $this->Esocial->obterLista($query_params);

        $this->set(compact('data'));
    }

}
