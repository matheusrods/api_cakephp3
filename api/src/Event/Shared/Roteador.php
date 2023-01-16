<?php

namespace App\Event\Shared;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class Roteador
{
    const URL_PROD = "https://pos.ithealth.com.br/safety-walk-talk/details/";
    const URL_TST  = "https://tstpda.ithealth.com.br/safety-walk-talk/details/";

    public static function direcionaNotificacao($regra, $email, Event $evento, $temaAcao)
    {
        $PdaConfigRegraModel = TableRegistry::getTableLocator()->get('PdaConfigRegra');

        if (empty($email)) {
            return;
        }

        /**
         * 1  => Notificação por Email,
         * 2  => Notificação por Push
         */
        switch ($temaAcao) {
            case 1:
                $PdaConfigRegraModel->enviaEmail(
                    $email,
                    $regra->assunto,
                    self::transformaMensagem($regra->mensagem, $evento->getData('form_respondido'))
                );
                break;

            case 2:
                //TODO: implementação do push notification.
                break;

            default:
                break;
        }
    }

    public static function criaUrlDetalhes($codigo_swt)
    {
        return BASE_URL === "https://api.rhhealth.com.br"
            ? self::URL_PROD . $codigo_swt
            : self::URL_TST . $codigo_swt;
    }

    public static function transformaMensagem($mensagem, $form_respondido)
    {
        $url  = self::criaUrlDetalhes($form_respondido->codigo);
        $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

        $mensagemTransformada = str_replace("[aqui]", $aqui, nl2br($mensagem));

        return $mensagemTransformada;
    }
}
