<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use App\Model\Entity\Paciente;

/**
 * Empresa Controller
 *
 * @property \App\Model\Table\GruposEconomicosTable $GruposEconomicos
 *
 * @method \App\Model\Entity\GruposEconomico[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EmpresaController extends ApiController
{
    public $connection;

    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow(['index', 'getSetores', 'getCargos', 'getCategorias']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
    }//fim

    //Retorna todos os setores referente a empresa
    public function getSetores($codigoCliente)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $loadModel = $this->loadModel('Setores');
            $dados = $loadModel->getSetoresPorEmpresas($codigoCliente);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim

    //Retorna o setor referente a empresa
    public function getSetor($codigoSetor, $codigoCliente)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $setoresModel = $this->loadModel('Setores');
            $dados = $setoresModel->getSetor($codigoSetor, $codigoCliente);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim

    //Retorna todos os setores referente a empresa
    public function getCargos($codigoCliente)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $loadModel = $this->loadModel('Cargos');
            $dados = $loadModel->getCargosPorEmpresas($codigoCliente);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    //Retorna todos o cargo referente a empresa
    public function getCargo($codigoCargo, $codigoCliente)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $cargosModel = $this->loadModel('Cargos');
            $dados = $cargosModel->getCargo($codigoCargo, $codigoCliente);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function getCargosPorEmpresaSetor($codigoCliente, $codigoSetor)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $loadModel = $this->loadModel('Cargos');
            $dados = $loadModel->getCargosPorEmpresaSetor($codigoCliente, $codigoSetor);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    //Retorna todos os categorias referente a empresa
    public function getCategorias($codigoEmpresa)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $loadModel = $this->loadModel('PacientesCategoria');
            $dados = $loadModel->getPacientesCategoria($codigoEmpresa);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim
    /**
     * View method
     *
     * @param string|null $codigo_fornecedor codigo_fornecedor id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_fornecedor = null)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET
        try {
            $loadModel = $this->loadModel('ClientesFornecedores');
            $dados = $loadModel->getEmpresasPorFornecedor($codigo_fornecedor);

            $this->set(compact('dados'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    }
    /**
     * Edit method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
    }
    /**
     * Delete method
     *
     * @param string|null $id Medico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
    }
}
