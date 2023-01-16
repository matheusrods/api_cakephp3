<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * TermoUso Controller
 *
 * @property \App\Model\Table\TermoUsoTable $TermoUso
 *
 * @method \App\Model\Entity\TermoUso[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TermoUsoController extends AppController
{

    /**
     * [initialize description]
     *
     * metodo de liberação dos metodos que não precisam de autenticação
     *
     * @return [type] [description]
     */
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['view']);
    }

    /**
     * View method
     *
     * @param string|null $id Termo Uso id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_termo = null)
    {
        /*
        * 1 = Politiva de privacidade Lyn
        * 2 = Termos de uso Lyn
        * 3 = Politiva de privacidade Gestão Riscos
        * 4 = Termos de uso Gestão Riscos
        */
        $data = array();
        if ($codigo_termo) {
            $data = $this->TermoUso->get($codigo_termo);
        }
        $this->set(compact('data'));
    }
}
