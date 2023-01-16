<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\BadRequestException;

class ClienteOpcoConciliacaoController extends ApiController
{
    public $connect;


    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');

        $this->loadModel('ClienteOpco');
        $this->loadModel('AcoesMelhorias');
        $this->loadModel('ClienteFuncionario');
        $this->loadModel('CentroResultado');
        $this->loadModel('PdaConfigRegraCondicao');
        $this->loadModel('PosMetas');
        $this->loadModel('PosObsLocais');
        $this->loadModel('PosSwtFormRespondido');
        $this->loadModel('PosSwtFormResumo');
    }

    public function conciliacaoDuplicatas()
    {

        try {

            $this->connect->begin();

            $data = [];

            $data['conciliacoes'] = $this->ClienteOpco->obterArrayConciliacoes();

            $duplicatasConciliadas = [];
            foreach ($data['conciliacoes'] as $codigoCliente => $arrCodigoClienteExternoCodigo) {
                foreach ($arrCodigoClienteExternoCodigo as $codigoClienteExterno => $arrDuplicatas) {

                    $codigoClienteOpcoConciliador = $arrDuplicatas[0];
                    unset($arrDuplicatas[0]);

                    if (count($arrDuplicatas)) {

                        $this->AcoesMelhorias->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->CentroResultado->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->ClienteFuncionario->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->PdaConfigRegraCondicao->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->PosMetas->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->PosObsLocais->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->PosSwtFormRespondido->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);
                        $this->PosSwtFormResumo->conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrDuplicatas);

                        $this->ClienteOpco->conciliarDuplicatas($arrDuplicatas);

                        $duplicatasConciliadas[$codigoClienteOpcoConciliador] = $arrDuplicatas;
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
                        'cliente_opco/conciliacao_duplicatas' => $e->getMessage()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            $dadosResponse = [
                "status" => 500,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_opco/conciliacao_duplicatas' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'stacktrace' => $e->getTraceAsString()
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
