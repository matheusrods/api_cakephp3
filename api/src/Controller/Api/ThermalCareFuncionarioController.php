<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\Comum;

class ThermalCareFuncionarioController extends ThermalCareApiController
{
    
    /**
     * Buscar dados do funcionário pelo CPF
     * 
     * URL: api/thermal-care/funcionario/:codigo_usuario?q=888.888.888-88
     * URL: api/thermal-care/funcionario/:codigo_usuario?q=88888888888
     * URL: api/thermal-care/funcionario/:codigo_usuario?q=Nome%20do%20funcionário
     * 
     * @return JSON Datetime
     */
    public function buscarFuncionarioPorCpfOuNome( $codigo_usuario )
    {
        $data = [];

        if(!empty($this->validateRequestQuery($this->request->query(), ['q']))){
            return $this->responseError($this->validateRequestQuery($this->request->query(), ['q']));
        }

        $queryData = $this->request->query('q');

        $codigo_cliente_matriz = $this->obterCodigoClienteMatriz($codigo_usuario);

        if(empty($codigo_cliente_matriz)){
            return $this->responseJson('Relacionamento não encontrado com usuário autenticado');
        }

        $this->loadModel('ThermalFuncionarios');

        // valida se é uma pesquisa por nome
        if (!is_numeric(Comum::soNumero($queryData))) {
            $queryData = urldecode($queryData);
            $data = $this->ThermalFuncionarios->obterFuncionarioPorNome($queryData, $codigo_cliente_matriz, $likeCondition = true);
            return $this->responseJson($data);
        } 
        
        // deve ser pesquisa por cpf
        $data = $this->ThermalFuncionarios->obterFuncionarioPorCpf($queryData, $codigo_cliente_matriz, $likeCondition = true);
        
        return $this->responseJson($data);
    }

}