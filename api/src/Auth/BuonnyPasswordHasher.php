<?php
namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;
use App\Utils\Encriptacao;

class BuonnyPasswordHasher extends AbstractPasswordHasher
{

    public function hash($password)
    {
    	$Encriptador = new Encriptacao();
        return $Encriptador->encriptar($password);
    }

    public function check($password, $hashedPassword)
    {
    	//instancia para criptografar a senha
        $Encriptador = new Encriptacao();
        $hashedPassword = $Encriptador->desencriptar($hashedPassword);

        return $password == $hashedPassword;
    }

    public function unhash($password)
    {
    	$Encriptador = new Encriptacao();
        return $Encriptador->desencriptar($password);
    }
}
