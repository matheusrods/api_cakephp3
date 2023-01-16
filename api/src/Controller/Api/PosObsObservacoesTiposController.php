<?php

namespace App\Controller\Api;

use App\Controller\Api\PosApiController as ApiController;

use Exception;

class PosObsObservacoesTiposController extends ApiController
{

    public $PosObs = null;

    public function initialize()
    {
        parent::initialize();

        $this->PosObs = $this->loadModel("PosObs");

        $this->Auth->allow();
    }

    /**
     * Observador EHS - Obter lista de tipos para observação
     *
     * @param int $codigo_unidade
     *
     * @return array|Exception
     */
    public function obterLista()
    {
        $this->request->allowMethod(['GET']);

        $codigo_unidade = $this->request->getQuery('codigo_unidade', null);

        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade.', self::$POS_ERROR_CODE);
            }

            // prepara condições de pesquisa
            $filtros = [
                'codigo_cliente' => $codigo_unidade,
                'ativo' => 1,
            ];

            $tableData = $this->PosObs->obterListaObservacaoTipos($filtros);

            return $this->responseMessage($tableData);
        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }
}
