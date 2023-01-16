<?php
namespace App\Utils;

class FilterUtil
{
    /**
     * usado para limpar dados que não sejam alfa
     *
     * @param string $string 
     * @return string | null
     */
    public function toAlpha( $string ) {
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    /**
     * usado para limpar dados que não sejam numericos
     *
     * @param string $string 
     * @return string | null
     */
    public function toNum( $string ) {
        return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * usado para limpar dados que não sejam numericos
     *
     * @param string $string 
     * @return string | null
     */
    public function toInt( $string ) {
        return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
    }
}