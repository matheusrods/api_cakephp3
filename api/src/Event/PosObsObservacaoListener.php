<?php

namespace App\Event;

use Cake\Event\Event;
use App\Event\Action\Notificador;
use App\Event\Callback\Observador\AtrasoTratativa;
use App\Event\Callback\Observador\Criticidade;
use App\Event\Callback\Observador\FeedbackObservador;
use Cake\Event\EventListenerInterface;

class PosObsObservacaoListener implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'Model.PosObsObservacao.feedbackObservador' => 'feedbackObservador',
            'Model.PosObsObservacao.notificarPelaCriticidade' => 'notificarPelaCriticidade',
            'Model.PosObsObservacao.atrasoTratativa' => 'atrasoTratativa'
        ];
    }

    public function feedbackObservador(Event $evento)
    {
        $notificador = new Notificador();

        $notificador->estrategia(new FeedbackObservador($evento))->disparar();
    }

    public function notificarPelaCriticidade(Event $evento)
    {
        $notificador = new Notificador();

        $notificador->estrategia(new Criticidade($evento))->disparar();
    }

    public function atrasoTratativa(Event $evento)
    {
        $notificador = new Notificador();

        $notificador->estrategia(new AtrasoTratativa($evento))->disparar();
    }
}
