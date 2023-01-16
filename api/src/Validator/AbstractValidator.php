<?php
namespace App\Validator;

use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Log\LogTrait;

/**
 * Classe abstrata para centralizar validações comuns à toda a aplicação
 * 
 * Esta classe se propõe a padronizar validações, por exemplo se algum dia 
 * for necessário alterar tipo inteiro de codigo_cliente para array não precisará 
 * alterar no sistema todo desde que exista uma validarCodigoCliente. 
 * 
 * - premissas
 * métodos iniciam com validar e publicos
 * métodos que necessitam consumir uma model devem ser privados e de forma nenhuma ser 
 * reutilizadas em controllers, são apenas para validar uma informação se correta
 * 
 */
abstract class AbstractValidator extends Validator {
    
    use LogTrait;


    public function validaSeValorExisteEmArray( $valor, array $arrayValores, string $parametro = 'codigo', $FORCE_INT_VALIDATION = false){
        
        if(!isset($valor)){
            return ['error'=> 'valor não definido'];
        }

        try {

            $iterator = new \RecursiveArrayIterator($arrayValores);
            $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
            $return = false;

            foreach ($recursive as $key => $value) {

                if($FORCE_INT_VALIDATION){
                    if(empty($return) && isset($value[$parametro]) && (int)$value[$parametro] == (int)$valor){
                        $return = true;
                    } 
                } else {

                    if(empty($return) && isset($value[$parametro]) && $value[$parametro] == $valor){
                        $return = true;
                    }
    
                }
            
            } 

        } catch (\Exception $e) {
            return ['error', 'não foi possivel verificar'];
        }

        return $return;
    }

    /**
     * Verifica se codigo cliente é válido
     *
     * @param array $codigo_cliente
     * @return boolean
     */
    public function validaCodigoCliente($codigo_cliente){

        if(empty($codigo_cliente)
            || (!is_array($codigo_cliente)
                && count($codigo_cliente) == 0)){
                return false;
        }
        return true;

    }

    /**
     * Obter usuario do sistema fornecendo um codigo
     *
     * @param integer $codigo_usuario
     * @return object|array
     */
    public function obterUsuario(int $codigo_usuario){
        return $this->_obterUsuario($codigo_usuario);
    }

    private function _obterUsuario(int $codigo_usuario){
        
        if(empty($codigo_usuario)){
            return ['error' => 'Codigo não fornecido.'];
        }

        if(!is_int($codigo_usuario)){
            return ['error' => 'Codigo fornecido inválido.'];
        }

        $this->Usuario = TableRegistry::get('Usuario');
        
        try{
            $registro = $this->Usuario->get(['codigo' => $codigo_usuario]);
        } catch (\Exception $e) { 
            return ['error' => 'Registro não encontrado.'];
        }

        if(is_object($registro) && isset($registro->codigo)){
            return $registro;
        }
        
        return ['error' => 'Ocorreu algum erro não identificado.'];

    }    

    public function obterDadosDoUsuario(int $codigo_usuario){

        $dados = [];
        try{
            $UsuarioTable = TableRegistry::get('Usuario');
            $dados = $UsuarioTable->obterDadosDoUsuario($codigo_usuario);
        } catch (\Exception $e) { 
            $dados = ['error' => 'Registro não encontrado.'];
        }

        return $dados;
    }

}