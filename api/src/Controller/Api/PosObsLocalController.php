<?php

namespace App\Controller\Api;

use App\Controller\Api\PosApiController as ApiController;

use Exception;

class PosObsLocalController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('PosObsLocal');
        $this->Auth->allow();
    }

    /**
     * Configurações - Obter parâmetros de configuração
     *
     * @return array|Exception
     */
    public function obter()
    {
        $this->request->allowMethod(['GET']);

        $codigo_cliente = $this->request->getQuery('codigo_cliente');

        try {

            if (empty($codigo_cliente)) {
                throw new Exception('É obrigatório informar um código de cliente.', self::$POS_ERROR_CODE);
            }

            $this->loadModel('PosGruposEconomicos');

            $grupoEconomico = $this->PosGruposEconomicos
                ->obterCodigoMatrizPeloCodigoFilial($codigo_cliente)
                ->first();

            if (empty($grupoEconomico)) {
                throw new Exception('Não foi encontrado nenhum grupo econômico para o cliente informado.', self::$POS_ERROR_CODE);
            }

            if (empty($grupoEconomico['codigo_cliente_matriz'])) {
                throw new Exception('Não foi encontrada matriz para o grupo econômico.', self::$POS_ERROR_CODE);
            }

            $data = $this->PosObsLocal->buscarLocaisPeloCodigoCliente($grupoEconomico['codigo_cliente_matriz']);

            $this->set(compact('data'));
        } catch (Exception $e) {
            $data = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            $this->set(compact('data'));
        }
    }
}
