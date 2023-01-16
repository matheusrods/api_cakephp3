<?php
namespace App\Model\Entity;

use App\Utils\DatetimeUtil;
use App\Utils\EncodingUtil;

use Cake\ORM\Entity;

class AppEntity extends Entity {

    public function getValidationErrors() {
        $message = [];
        foreach ($this->errors() as $campo => $regra) {
            foreach ($regra as $nome => $msg) {
                $nomeSoLetras = preg_replace('/[^a-zA-Z0-9]+/', '', $nome);
                $message[$campo][$nomeSoLetras] = $msg;
            }
        }
        return $message;
    }
    
    public function getValidationErrorsString() {
        $errors = $this->getValidationErrors();
        $msg = [];
        foreach ($errors as $campo => $regra) {
            foreach ($regra as $message) {
                $msg[] = strtoupper($campo) . ': ' . nl2br($message);
            }
        }
        return join('<br>', $msg);
    }
    /**
     * Recupera campo ativo (boolean) como bit/string
     *
     * @param [boolean] $ativo
     * @return string
     */
    protected function _getAtivo($ativo)
    {
        return boolval($ativo) ? '1' : '0';
    }

    protected function _getDataInclusao($datetime)
    {
        // se for object pode ser o FrozenDatetime do cake
        if(gettype($datetime) =='object'){
            return $datetime->format('Y-m-d H:i:s');
        }

        return $datetime;
    }

    protected function _getDataAlteracao($datetime)
    {
        // se for object pode ser o FrozenDatetime do cake
        if(gettype($datetime) =='object'){
            return $datetime->format('Y-m-d H:i:s');
        }

        return $datetime;
    }
    
    /**
     * converte uma string para uma codificação específica
     *
     * @param string $string
     * @return void
     */
    protected function iconv(string $string){

        $iconv = new EncodingUtil();
        return $iconv->convert($string);

    }

}