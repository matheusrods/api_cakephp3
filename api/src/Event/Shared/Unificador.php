<?php

namespace App\Event\Shared;

class Unificador {

    private $lista;

    public function __construct(array $lista)
    {
        $this->lista = $lista;
    }

    public function devolve()
    {
        return $this->lista;
    }

    public function inedito($item)
    {
        if (in_array($item, $this->lista)) {
            return false;
        }

        array_push($this->lista, $item);

        return true;
    }

    public function limpar()
    {
        $this->lista = array();
    }
}
