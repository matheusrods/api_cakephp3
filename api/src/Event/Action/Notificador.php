<?php

namespace App\Event\Action;

use App\Event\Contract\Notificacao;

class Notificador
{
    private $estrategia;

    public function estrategia(Notificacao $estrategia)
    {
        $this->estrategia = $estrategia;

        return $this;
    }

    public function disparar(): void
    {
        if (empty($this->estrategia)) {
            return;
        }

        $this->estrategia->notificar();
    }
}
