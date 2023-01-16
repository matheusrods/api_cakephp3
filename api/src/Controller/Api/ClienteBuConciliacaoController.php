<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\BadRequestException;

class ClienteBuConciliacaoController extends ApiController
{
    public $connect;


    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');

        $this->loadModel('ClienteBu');
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

            $data['conciliacoes'] = $this->ClienteBu->obterArrayConciliacoes();

            $duplicatasConciliadas = [];
            foreach ($data['conciliacoes'] as $codigoCliente => $arrCodigoClienteExternoCodigo) {
                foreach ($arrCodigoClienteExternoCodigo as $codigoClienteExterno => $arrDuplicatas) {

                    $codigoClienteBuConciliador = $arrDuplicatas[0];
                    unset($arrDuplicatas[0]);

                    if (count($arrDuplicatas)) {

                        $this->AcoesMelhorias->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->CentroResultado->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->ClienteFuncionario->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->PdaConfigRegraCondicao->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->PosMetas->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->PosObsLocais->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->PosSwtFormRespondido->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);
                        $this->PosSwtFormResumo->conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrDuplicatas);

                        $this->ClienteBu->conciliarDuplicatas($arrDuplicatas);

                        $duplicatasConciliadas[$codigoClienteBuConciliador] = $arrDuplicatas;
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
                        'cliente_bu/conciliacao_duplicatas' => $e->getMessage()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            $dadosResponse = [
                "status" => 500,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_bu/conciliacao_duplicatas' => $e->getMessage(),
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
