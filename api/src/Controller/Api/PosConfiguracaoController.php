<?php

namespace App\Controller\Api;

use App\Controller\Api\PosApiController as ApiController;
use Cake\Log\Log;

use Exception;

class PosConfiguracaoController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('PosConfiguracoes');
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
        $this->loadModel('GruposEconomicos');

        $codigo_ferramenta = $this->request->getQuery('codigo_ferramenta');
        $codigo_cliente    = $this->request->getQuery('codigo_cliente');
        $chave             = $this->request->getQuery('chave');

        try {
            if (empty($codigo_cliente)) {
                throw new Exception('É obrigatório informar um código de cliente.', self::$POS_ERROR_CODE);
            }
            if (empty($codigo_ferramenta)) {
                throw new Exception('É obrigatório informar um código de ferramenta.', self::$POS_ERROR_CODE);
            }
            if (empty($chave)) {
                throw new Exception('É obrigatório informar a chave.', self::$POS_ERROR_CODE);
            }
            /*
             */
            $data = array(
                "codigo" => '',
                "codigo_ferramenta" => '',
                "codigo_cliente" => '',
                "chave" => '',
                "descricao" => '',
                "valor" => '',
                "observacao" => '',
                "PosSwtRegras" => array(
                    "dias_registro_retroativo" => ''
                )                
            );

            //Pegar codigo_cliente do grupo economico
            $joins = array(
                array (
                    'table' => 'grupos_economicos_clientes',
                    'alias' => 'GrupoEconomicoCliente',
                    'type' => 'INNER',
                    'conditions' => array (
                        'GrupoEconomicoCliente.codigo_grupo_economico = GruposEconomicos.codigo',
                    ),
                ),
            );
          
            $conditions = array('GrupoEconomicoCliente.codigo_cliente' => $codigo_cliente);
    
            $grupo_economico = $this->GruposEconomicos->find()->select("GruposEconomicos.codigo_cliente")->join($joins)->where($conditions)->first()->toArray();
    
            if (!empty($grupo_economico)) {
                $codigo_cliente = $grupo_economico['codigo_cliente'];
            }
            //FIM Pegar codigo_cliente do grupo economico

            if($codigo_ferramenta == 3) { //observador
                $dados = $this->PosConfiguracoes
                    ->buscarConfig($codigo_ferramenta, $codigo_cliente, $chave);

                if(!empty($dados)) {
                    $data = $dados;
                }
            }
            else if($codigo_ferramenta == 2) { //swt
                $this->PosSwtRegras = $this->loadModel('PosSwtRegras');
                $dados_swt = $this->PosSwtRegras->find()
                    ->select(['dias_registro_retroativo'])
                    ->where(['codigo_cliente' => $codigo_cliente])
                    ->first();
                if(!empty($dados_swt)) {
                    $data['PosSwtRegras']['dias_registro_retroativo'] = $dados_swt->dias_registro_retroativo;
                }
            }


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
