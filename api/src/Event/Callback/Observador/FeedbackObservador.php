<?php

namespace App\Event\Callback\Observador;

use App\Event\Contract\Notificacao;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class FeedbackObservador implements Notificacao
{
    const CODIGO_FERRAMENTA = 3;
    const CODIGO_TEMA = 11;

    const URL_PROD = "https://pos.ithealth.com.br/";
    const URL_TST  = "https://tstpda.ithealth.com.br/";

    private $PdaTemaModel;
    private $UsuarioModel;
    private $PdaConfigRegraModel;
    private $PosObsObservacaoModel;

    private $evento;

    public function __construct(Event $evento)
    {
        $this->PdaTemaModel = TableRegistry::getTableLocator()->get('PdaTema');
        $this->UsuarioModel = TableRegistry::getTableLocator()->get('Usuario');
        $this->PdaConfigRegraModel = TableRegistry::getTableLocator()->get('PdaConfigRegra');
        $this->PosObsObservacaoModel = TableRegistry::getTableLocator()->get('PosObsObservacao');

        $this->evento = $evento;
    }

    public function notificar(): void
    {
        $codigo = $this->evento->getData('codigo');

        $observacao = $this->PosObsObservacaoModel->getObservationById($codigo);

        if (!empty($observacao) && in_array($observacao['status'], [1, 2])) {
            $tema = $this->PdaTemaModel->buscarTemaComRelacionamentosPor(
                self::CODIGO_TEMA,
                self::CODIGO_FERRAMENTA,
                $observacao['codigo_cliente']
            );

            # Percorre todas as regras do tema
            foreach ($tema->regras as $regra) {

                # Percorrendo ações da regra (Validando para quem deve ser enviado e como deve ser enviado)
                foreach ($regra->configRegraAcoes as $acao) {
                    $this->enviarEmailPush(
                        $acao->codigo_pda_tema_acoes,
                        $observacao['codigo_usuario_inclusao'],
                        $regra->assunto,
                        $regra->mensagem,
                        $observacao['codigo']
                    );
                }
            }
        }
    }

    private function enviarEmailPush(
        int $tipoAcao = null,
        int $codigoUsuario,
        string $assunto,
        string $mensagem,
        int $codigoObservacao
    ) {
        if (
            empty($codigoUsuario)
            || empty($assunto)
            || empty($mensagem)
            || empty($codigoObservacao)
        ) {
            return;
        }

        /**
         * 1 => E-mail
         * 2 => Push notification
         */
        if ($tipoAcao === 1) {
            $email = $this->PdaConfigRegraModel->getEmailUsuarioFuncionario($codigoUsuario);

            if (!empty($email)) {
                $this->PdaConfigRegraModel->enviaEmail(
                    $email,
                    $assunto,
                    $this->tratarMensagem($mensagem, $codigoObservacao)
                );
            }
        } else if ($tipoAcao === 2) {
            $usuario = $this->UsuarioModel->getUserToPushNotification($codigoUsuario);

            if (!empty($usuario)) {
                $this->PdaConfigRegraModel->cadastrarNotificacaoGenerica(
                    $usuario,
                    $assunto,
                    $mensagem,
                    $this->criaUrlDetalhes($codigoObservacao)
                );
            }
        }
    }

    private function criaUrlDetalhes(int $codigoObservacao)
    {
        return (BASE_URL === "https://api.rhhealth.com.br" ? self::URL_PROD : self::URL_TST)
            . "observer-ehs/details/"
            . $codigoObservacao;
    }

    private function tratarMensagem(string $mensagem, int $codigoObservacao)
    {
        $url = $this->criaUrlDetalhes($codigoObservacao);

        $aqui = "<a href=\"{$url}\" target=\"blank\">aqui</a>";

        $mensagemTransformada = str_replace("[aqui]", $aqui, nl2br($mensagem));

        return $mensagemTransformada;
    }
}
