<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\BadRequestException;

class ClienteDsConciliacaoController extends ApiController
{
    public $connect;


    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');

        $this->loadModel('ClienteDs');
        $this->loadModel('CentroResultado');
        $this->loadModel('ClienteFuncionario');
    }

    public function conciliacaoDuplicatas()
    {

        try {

            $this->connect->begin();

            $data = [];

            $data['conciliacoes'] = $this->ClienteDs->obterArrayConciliacoes();

            $duplicatasConciliadas = [];
            foreach ($data['conciliacoes'] as $codigoCliente => $arrCodigoClienteExternoCodigo) {
                foreach ($arrCodigoClienteExternoCodigo as $codigoClienteExterno => $arrDuplicatas) {

                    $codigoClienteDsConciliador = $arrDuplicatas[0];
                    unset($arrDuplicatas[0]);

                    if (count($arrDuplicatas)) {
                        $this->CentroResultado->conciliarDuplicatasClienteDs($codigoClienteDsConciliador, $arrDuplicatas);
                        $this->ClienteFuncionario->conciliarDuplicatasClienteDs($codigoClienteDsConciliador, $arrDuplicatas);

                        $this->ClienteDs->conciliarDuplicatas($arrDuplicatas);

                        $duplicatasConciliadas[$codigoClienteDsConciliador] = $arrDuplicatas;
                    }
                }
            }

            $this->connect->commit();

            $data['duplicatasConciliadas'] = $duplicatasConciliadas;

            $dadosResponse = [
                'status' => 200,
                'result' => [
                    'data' => $data,
                    'message' => 'sucesso',
                ]
            ];
        } catch (BadRequestException $e) {

            $this->connect->rollback();

            $dadosResponse = [
                "status" => 400,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_ds/conciliacao_duplicatas' => $e->getMessage()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            $dadosResponse = [
                "status" => 500,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_ds/conciliacao_duplicatas' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]
                ]
            ];
        } finally {

            return $this->response->withStatus($dadosResponse['status'])
                ->withType('application/json')
                ->withStringBody(json_encode($dadosResponse));
        }
    }
}
