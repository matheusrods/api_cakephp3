<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * Cid Controller
 *
 * @property \App\Model\Table\CidTable $Cid
 *
 * @method \App\Model\Entity\Cid[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CidController extends ApiController
{

    public function obterCid()
    {
        // obrigatório passar um nome        
        if(empty($this->request->query('nome'))){
            $error = 'Parâmetro nome não encontrado';
            $this->set('error', $error);    
            return;
        }

        if(strlen($this->request->query('nome')) <= 2){
            $error = 'Parâmetro nome deve conter mais que 2 caracteres';
            $this->set('error', $error);    
            return;
        }

        $query_params['descricao'] = urldecode($this->request->query('nome'));

        $data = $this->Cid->obterCidAutoComplete($query_params);

        $this->set('data', $data);
    }

}
