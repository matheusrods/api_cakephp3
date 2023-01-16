<?php

namespace App\Event;

use Cake\Event\Event;
use App\Event\Action\Notificador;
use Cake\Event\EventListenerInterface;
use App\Event\Callback\Criticidade;
use App\Event\Callback\FollowUpMetaNaoAtingida;
use App\Event\Callback\Participante;

class PosSwtFormRespondidoListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Model.PosSwtFormRespondido.criacao' => 'informaCriacao',
            'Model.PosSwtFormRespondido.followUpMetaNaoAtingida' => 'followUpMetaNaoAtingida'
        ];
    }

    public function informaCriacao(Event $evento)
    {
        /** @var array*/
        $participantes = $evento->getData('participantes');
        $notificador   = new Notificador();

        if ($this->existemParticipantes($participantes)) {
            $notificador->estrategia(new Participante($evento))->disparar();
        }

        $notificador->estrategia(new Criticidade($evento))->disparar();
    }

    public function followUpMetaNaoAtingida(Event $evento)
    {
        $notificador   = new Notificador();

        $notificador->estrategia(new FollowUpMetaNaoAtingida($evento))->disparar();
    }

    private function existemParticipantes(array $participantes)
    {
        return isset($participantes[0]['codigo_usuario']);
    }
}
