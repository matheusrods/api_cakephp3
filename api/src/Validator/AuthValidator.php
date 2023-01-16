<?php
namespace App\Validator;

use Cake\ORM\TableRegistry;
use App\Auth\BuonnyPasswordHasher;
use App\Validator\AbstractValidator;

class AuthValidator extends AbstractValidator {

    public function validaAtualizaSenha($params){

        $error['error'] = [];

        if(!isset($params['senha_anterior'])){
            return ['error' => "Parâmetro [senha_anterior] requerido"];
        }

        if(!isset($params['senha_atual'])){
            return ['error' => "Parâmetro [senha_atual] requerido"];
        }

        if(!isset($params['senha_atual_compara'])){
            return ['error' => "Parâmetro [senha_atual_compara] requerido"];
        }

        if($params['senha_atual'] != $params['senha_atual_compara']){
            return ['error' => "Senha atual não confere"];
        }

        if(!isset($params['codigo_usuario'])){
            return ['error' => "Codigo usuário requerido"];
        }

        $registro = $this->obterUsuario($params['codigo_usuario']);

        if(isset($registro['error'])){
            return ['error' => $registro['error']];
        }

        // validar se a senha anterior enviada bate com a que esta gravada no banco
        $validar_senha_no_banco = (new BuonnyPasswordHasher)->check( $params['senha_anterior'], $registro->senha);

        if(!$validar_senha_no_banco){
            return ['error' => 'Senha não confere'];
        }

        return true;
    }

    public function validateUpdatePassword($params){

        $error['error'] = [];

        if(!isset($params['senha_atual'])){
            return ['error' => "Parâmetro [senha_atual] requerido"];
        }

        if(!isset($params['senha_atual_compara'])){
            return ['error' => "Parâmetro [senha_atual_compara] requerido"];
        }

        if($params['senha_atual'] != $params['senha_atual_compara']){
            return ['error' => "Senha atual não confere"];
        }

        if(!isset($params['codigo_usuario'])){
            return ['error' => "Codigo usuário requerido"];
        }

        $registro = $this->obterUsuario($params['codigo_usuario']);

        if(isset($registro['error'])){
            return ['error' => $registro['error']];
        }

        return true;
    }

    public function validaSolicitarRecuperarSenha($params){

        // valida usuario ativo
        // valida se usuario possui email valido
        // valida se usuario
        return true;
    }

    public function validaValidaRecuperarSenha($params){

        // valida se usuario esta ativo
        // valida se codigo de recuperar é valido
        // valida se é possivel enviar email
        return true;
    }

}
