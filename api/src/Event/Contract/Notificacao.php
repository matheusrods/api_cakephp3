<?php

namespace App\Event\Contract;

/**
 * Essa interface declara operações comuns
 * a todas as versões suportadas de notificação.
 *
 * O Notificador usa esta interface para chamar o notificar
 * definido por estratégias concretas.
 */
interface Notificacao
{
    public function notificar(): void;
}
