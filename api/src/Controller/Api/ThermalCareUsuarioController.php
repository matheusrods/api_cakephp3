<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\DatetimeUtil;

class ThermalCareUsuarioController extends ThermalCareApiController
{

    /**
     * Obter dados pertinentes ao usuario por código
     * 
     * ** api/thermal-care/usuario/:codigo
     *
     * @param int $codigo_usuario
     * @return json
     */
    public function obterUsuario($codigo_usuario = null)
    {
        $data = [
            'onboarding'=> null,
            'produtos'=> null
        ];

        $onboardingData = [];

        // Para usuários multi-cliente exibir a configuração do onboarding de acordo com a matriz/grupo econômico a qual ele foi cadastrado.
        $codigo_cliente = $this->obterCodigoClienteMatriz($codigo_usuario);
        
        // PRODUTOS - obter lista de produtos permitidos ao usuário
        $data['produtos'] = $this->obterProdutos($codigo_usuario);

        // ONBOARDING - obter lista de onboarding
        $data['onboarding'] = $this->obterOnboarding($codigo_cliente);

        return $this->responseJson($data);

    }


    // METODOS PRIVADOS

    /**
     * Obter Lista de onboarding por sistema e cliente
     *
     * @param integer $codigo_sistema
     * @param integer $codigo_cliente
     * @param boolean $inativos
     * @return array
     */
    private function obterOnboarding(int $codigo_cliente, bool $inativos = false){

        $data = [];
        
        // se não existe um código cliente associado, vai retornar Onboarding padrão
        if(empty($codigo_cliente) || $codigo_cliente === 0){
            $this->loadModel('Onboarding');
            $OnboardingData = $this->Onboarding->obterLista(self::$CODIGO_SISTEMA, $inativos);
        } else {
            // se existir um codigo cliente associado verifica a existência de imagens associadas ao cliente
            $this->loadModel('OnboardingCliente');
            $OnboardingData = $this->OnboardingCliente->avaliarListaPorCliente(self::$CODIGO_SISTEMA, $codigo_cliente, $inativos);
        }
        
        foreach ($OnboardingData->toArray() as $entity) {
            
            $tmp = [];
            
            if(isset($entity->codigo)){
                
                // tratar endereço da imagem
                $imagemData = $this->avaliaUrlImagem($entity->imagem);

                $tmp['codigo'] = $entity->codigo;
                $tmp['titulo'] = $entity->titulo;
                $tmp['descricao'] = $entity->texto;
                $tmp['imagem'] = $imagemData;
                $tmp['ativo'] = $entity->ativo;
                
                array_push( $data, $tmp );
            }
        }
        
        return $data;
    }


    /**
     * Obter Lista de produtos permitidos ao usuario
     *
     * @param integer $codigo_usuario
     * @return array
     */
    private function obterProdutos(int $codigo_usuario){

        $data = [];
     
        return $data;
    }

}