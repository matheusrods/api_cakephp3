<?php

namespace App\Event\Callback\Observador;

use App\Event\Contract\Notificacao;
use App\Event\Shared\Unificador;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class Criticidade implements Notificacao
{
    const CODIGO_FERRAMENTA = 3;
    const CODIGO_TEMA = 12;

    const URL_PROD = "https://pos.ithealth.com.br/";
    const URL_TST  = "https://tstpda.ithealth.com.br/";

    private $PdaTemaModel;
    private $UsuarioModel;
    private $PdaConfigRegraModel;
    private $PosObsObservacaoModel;

    private $evento;

    private $emailsSelecionados;
    private $usuariosSelecionados;

    public function __construct(Event $evento)
    {
        $this->PdaTemaModel = TableRegistry::getTableLocator()->get('PdaTema');
        $this->UsuarioModel = TableRegistry::getTableLocator()->get('Usuario');
        $this->PdaConfigRegraModel = TableRegistry::getTableLocator()->get('PdaConfigRegra');
        $this->PosObsObservacaoModel = TableRegistry::getTableLocator()->get('PosObsObservacao');

        $this->evento = $evento;

        $this->emailsSelecionados = new Unificador(array());
        $this->usuariosSelecionados = new Unificador(array());
    }

    public function notificar(): void
    {
        $codigo = $this->evento->getData('codigo');

        $observacao = $this->PosObsObservacaoModel->getObservationById($codigo);

        if (!empty($observacao) && $observacao['status'] === 2) {
            $tema = $this->PdaTemaModel->buscarTemaComRelacionamentosPor(
                self::CODIGO_TEMA,
                self::CODIGO_FERRAMENTA,
                $observacao['codigo_cliente']
            );

            # Percorre todas as regras do tema
            foreach ($tema->regras as $regra) {
                foreach ($regra->configRegraCodicoes as $condicao) {
                    if ($condicao->codigo_pos_criticidade === $observacao['observacao_criticidade']) {
                        # Percorrendo ações da regra (Validando para quem deve ser enviado e como deve ser enviado)
                        foreach ($regra->configRegraAcoes as $acao) {
                            $this->enviarEmailPush(
                                $acao->codigo_pda_tema_acoes,
                                $acao->tipo_acao,
                                $observacao,
                                $regra->assunto,
                                $regra->mensagem,
                                $acao
                            );
                        }
                    }
                }
            }
        }
    }

    private function enviarEmailPush(
        int $tipoAcao = null,
        int $tipoUsuario,
        array $observacao,
        string $assunto,
        string $mensagem,
        object $acao
    ) {
        if (
            empty($tipoUsuario)
            || empty($assunto)
            || empty($mensagem)
        ) {
            return;
        }

        $usuarios = [];
        $emails = [];

        /*
         * 3: Gestor direto
         * 6: Gestor direto 1
         * 7: Gestor direto 2
         * 8: Gestor direto 3
         * 9: Gestor direto 4
         * 10: Gestor direto 5
         */
        $acaoTipoGestores = array(3, 6, 7, 8, 9, 10);

        # Verificar se foi configurado como gestor direto e pegar o nível do mesmo
        $nivelGestor = array_search($tipoUsuario, $acaoTipoGestores);

        /**
         * 1 => E-mail
         * 2 => Push notification
         */
        if ($tipoAcao === 1) {
            switch ($tipoUsuario) {
                # Usuário que identificou
                case 1:
                    $email = $this->PdaConfigRegraModel->getEmailUsuarioFuncionario($observacao['codigo_usuario_inclusao']);

                    if (!empty($email) && $this->emailsSelecionados->inedito($email)) {
                        array_push($emails, $email);
                    }
                    break;
                # Gestor direto
                case 3:
                    $dados = $this->PdaConfigRegraModel->getEmailGestorDireto(
                        null,
                        $observacao['codigo_usuario_inclusao'],
                        $nivelGestor
                    );

                    if (!empty($dados)) {
                        foreach ($dados as $email) {
                            if ($this->emailsSelecionados->inedito($email)) {
                                array_push($emails, $email);
                            }
                        }
                    }
                    break;
                # E-mail custom
                case 4:
                    if (
                        !empty($acao->email)
                        && filter_var($acao->email, FILTER_VALIDATE_EMAIL)
                        && $this->emailsSelecionados->inedito($acao->email)
                    ) {
                        array_push($emails, $acao->email);
                    }
                    break;
                # Usuário responsável pela análise de qualidade
                # Responsável da área
                case 2:
                case 5:
                    $dados = $this->PdaConfigRegraModel->getEmailUsuarioResponsavelArea($observacao['codigo_cliente']);

                    if (!empty($dados)) {
                        foreach ($dados as $email) {
                            if ($this->emailsSelecionados->inedito($email)) {
                                array_push($emails, $email);
                            }
                        }
                    }
                    break;
            }
        } else if ($tipoAcao === 2) {
            switch ($tipoUsuario) {
                # Usuário que identificou
                case 1:
                    $usuario = $this->UsuarioModel->getUserToPushNotification($observacao['codigo_usuario_inclusao']);

                    if (!empty($usuario) && $this->usuariosSelecionados->inedito($usuario['codigo_usuario'])) {
                        array_push($usuarios, $usuario);
                    }
                    break;
                # Gestor direto
                case 3:
                    $usuario = $this->PdaConfigRegraModel->getTokenPushGestorDireto(
                        null,
                        $observacao['codigo_usuario_inclusao'],
                        $nivelGestor
                    );

                    if (!empty($usuario) && $this->usuariosSelecionados->inedito($usuario['codigo_usuario'])) {
                        # Remover dados desnecessários
                        unset($usuario['codigo_cliente_chefia_imediata']);
                        unset($usuario['matricula_chefia_imediata']);
                        unset($usuario['matricula']);
                        unset($usuario['codigo_cliente']);

                        array_push($usuarios, $usuario);
                    }
                    break;
                # Usuário responsável pela análise de qualidade
                # Responsável da área
                case 2:
                case 5:
                    $usuariosResponsaveis = $this->PdaConfigRegraModel->getTokenPushResponsaveisArea($observacao['codigo_cliente']);

                    if (!empty($usuariosResponsaveis)) {
                        foreach ($usuariosResponsaveis as $usuario) {
                            if (!empty($usuario) && $this->usuariosSelecionados->inedito($usuario['codigo_usuario'])) {
                                array_push($usuarios, $usuario);
                            }
                        }
                    }
                    break;
            }
        }

        foreach ($emails as $email) {
            $this->PdaConfigRegraModel->enviaEmail(
                $email,
                $assunto,
                $this->tratarMensagem($mensagem, $observacao['codigo'])
            );
        }

        foreach ($usuarios as $usuario) {
            $this->PdaConfigRegraModel->cadastrarNotificacaoGenerica(
                $usuario,
                $assunto,
                $mensagem,
                $this->criaUrlDetalhes($observacao['codigo'])
            );
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
