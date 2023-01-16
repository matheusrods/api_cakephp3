<?php

namespace App\Event\Callback;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Event\Contract\Notificacao;
use App\Event\Shared\Unificador;
use DateTime;

class FollowUpMetaNaoAtingida implements Notificacao
{
    const CODIGO_FERRAMENTA = 2;
    const CODIGO_TEMA = 10;

    private $PdaTemaModel;
    private $PosMetasModel;
    private $UsuarioModel;
    private $PdaConfigRegraModel;
    private $codigoUsuariosEmail;
    private $codigoUsuariosPush;
    private $evento;

    public function __construct(Event $evento)
    {
        $this->PdaTemaModel = TableRegistry::getTableLocator()->get("PdaTema");
        $this->PosMetasModel = TableRegistry::getTableLocator()->get("PosMetas");
        $this->UsuarioModel = TableRegistry::getTableLocator()->get("Usuario");
        $this->PdaConfigRegraModel = TableRegistry::getTableLocator()->get("PdaConfigRegra");

        $this->codigoUsuariosEmail = new Unificador(array());
        $this->codigoUsuariosPush = new Unificador(array());
        $this->evento = $evento;
    }

    public function notificar(): void
    {
        $configuracoes = $this->PdaTemaModel->buscarTemaComRelacionamentosPor(
            self::CODIGO_TEMA,
            self::CODIGO_FERRAMENTA
        );

        foreach ($configuracoes->regras as $configuracao) {
            foreach ($configuracao->configRegraCodicoes as $condicao) {
                if (is_null($condicao->qtd_dias)) {
                    continue;
                }

                $filtros = array(
                    "codigo_cliente" => $condicao->codigo_cliente,
                    "codigo_unidade" => $condicao->codigo_cliente_unidade,
                    "codigo_setor" => $condicao->codigo_setor,
                    "codigo_cliente_opco" => $condicao->codigo_cliente_opco,
                    "codigo_cliente_bu" => $condicao->codigo_cliente_bu
                );

                $metas = $this->PosMetasModel->consultGoals($filtros);

                foreach ($metas as $meta) {
                    $periodoAvaliado = $this->consultarPeriodo(date("Y-m-d"), (int) $meta["periodicidade"], $meta["data_configuracao"], 1);

                    $enviarEmailPush = $this->verificarSeEncaminhaEmailPush($periodoAvaliado["data_fim_periodo"], (int) $condicao->qtd_dias);

                    if (!$enviarEmailPush) {
                        continue;
                    }

                    $usuarios = $this->UsuarioModel->getUsersByGoal($meta);

                    foreach ($usuarios as $usuario) {
                        $quantidadeRegistros = 0;

                        foreach ($usuario["formularios_respondidos"] as $formulario) {
                            $tempoDataRegistro = strtotime($formulario["data_registro"]);

                            if (
                                $tempoDataRegistro >= strtotime($periodoAvaliado["data_inicio_periodo"])
                                && $tempoDataRegistro <= strtotime($periodoAvaliado["data_fim_periodo"])
                            ) {
                                $quantidadeRegistros += (int) $formulario["registros_criados"];
                            }
                        }

                        if ($quantidadeRegistros >= $meta["valor_meta"]) {
                            continue;
                        }

                        $this->determinaQuemNotificar($configuracao, $usuario["codigo"]);
                    }
                }
            }
        }
    }

    private function determinaQuemNotificar($regra, $codigoUsuario)
    {
        $usuarios = array();
        $emails = array();

        /**
         * 3: Gestor direto
         * 6: Gestor direto 1
         * 7: Gestor direto 2
         * 8: Gestor direto 3
         * 9: Gestor direto 4
         * 10: Gestor direto 5
         */
        $acaoTipoGestores = array(3, 6, 7, 8, 9, 10);

        foreach ($regra->configRegraAcoes as $configRegraAcao) {
            $tipoAcao = (int) $configRegraAcao->tipo_acao;

            # Verificar se foi configurado como gestor direto e pegar o nível do mesmo
            $nivelGestor = array_search($tipoAcao, $acaoTipoGestores);

            /**
             * 1  => Notificação por Email
             * 2  => Notificação por Push
             */
            if ($configRegraAcao->codigo_pda_tema_acoes === 1) {
                switch ($tipoAcao) {
                    # Usuário que cadastrou
                    case 1:
                        $email = $this->PdaConfigRegraModel->getEmailUsuarioFuncionario($codigoUsuario);

                        if ($this->codigoUsuariosEmail->inedito($email)) {
                            array_push($emails, $email);
                        }
                        break;
                    # E-mail cadastrado
                    case 4:
                        if (
                            !empty($configRegraAcao->email)
                            && filter_var($configRegraAcao->email, FILTER_VALIDATE_EMAIL)
                        ) {
                            if ($this->codigoUsuariosEmail->inedito($configRegraAcao->email)) {
                                array_push($emails, $configRegraAcao->email);
                            }
                        }
                        break;
                    # Gestor
                    case 3:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    case 10:
                        $emailGestores = $this->PdaConfigRegraModel->getEmailGestorDireto(
                            null,
                            $codigoUsuario,
                            $nivelGestor
                        );

                        if (isset($emailGestores[0]) && !empty($emailGestores[0])) {
                            if ($this->codigoUsuariosEmail->inedito($emailGestores[0])) {
                                array_push($emails, $emailGestores[0]);
                            }
                        }
                        break;
                }
            } else if ($configRegraAcao->codigo_pda_tema_acoes === 2) {
                switch ($tipoAcao) {
                    # Usuário que cadastrou
                    case 1:
                        $usuario = $this->UsuarioModel->getUserToPushNotification($codigoUsuario);

                        if (!empty($usuario)) {
                            if ($this->codigoUsuariosPush->inedito($usuario["codigo_usuario"])) {
                                array_push($usuarios, $usuario);
                            }
                        }
                        break;
                    # Gestor
                    case 3:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    case 10:
                        $usuario = $this->PdaConfigRegraModel->getTokenPushGestorDireto(
                            null,
                            $codigoUsuario,
                            $nivelGestor
                        );

                        if (!empty($usuario)) {
                            # Remover dados desnecessários
                            unset($usuario['codigo_cliente_chefia_imediata']);
                            unset($usuario['matricula_chefia_imediata']);
                            unset($usuario['matricula']);
                            unset($usuario['codigo_cliente']);

                            if ($this->codigoUsuariosPush->inedito($usuario["codigo_usuario"])) {
                                array_push($usuarios, $usuario);
                            }
                        }
                        break;
                }
            }
        }

        # Verificar se existe e-mails para serem enviados e enviá-los
        if (!empty($emails)) {
            $emails = array_values(array_unique($emails, SORT_STRING));

            foreach ($emails as $email) {
                $this->PdaConfigRegraModel->enviaEmail($email, $regra->assunto, $regra->mensagem);
            }
        }

        # Verificar se existe usuários para o envio das notificações e cadastrar as notificações
        if (!empty($usuarios)) {
            foreach ($usuarios as $usuario) {
                $this->PdaConfigRegraModel->cadastrarNotificacaoGenerica($usuario, $regra->assunto, $regra->mensagem, null);
            }
        }
    }

    /*
        Função verifica se o tempo de dias passados é igual ao da regra
        para assim poder estar enviando os e-mails e/ou push
    */
    private function verificarSeEncaminhaEmailPush(string $data, int $quantidadeDias)
    {
        $dataInicio = new DateTime($data);
        $dataFim = new DateTime(date("Y-m-d"));

        $intervalo = $dataFim->diff($dataInicio);

        return $intervalo->d === $quantidadeDias;
    }

    /*
        Função para identificar os períodos de uma configuração
    */
    private function consultarPeriodo(string $data, int $periodicidade, $dataConfiguracao, int $quantidadePeriodosAnteriores = 0)
    {
        $dataInicio = new DateTime($dataConfiguracao);
        $dataFim = new DateTime($data);

        $intervalo = $dataFim->diff($dataInicio);

        $meses = ($intervalo->y * 12) + $intervalo->m;

        $quantidadeMesesInicioPeriodo = $meses - floor($meses / $periodicidade);
        $quantidadeMesesFimPeriodo = $quantidadeMesesInicioPeriodo + $periodicidade;

        $periodo = array(
            "data_inicio_periodo" => date("Y-m-d", strtotime("+{$quantidadeMesesInicioPeriodo} months", strtotime($dataConfiguracao))),
            "data_fim_periodo" => date("Y-m-d", strtotime("+{$quantidadeMesesFimPeriodo} months", strtotime($dataConfiguracao)))
        );

        if ($quantidadePeriodosAnteriores > 0) {
            $quantidadeMesesAnteriores = $periodicidade * $quantidadePeriodosAnteriores;

            $periodo = array(
                "data_inicio_periodo" => date("Y-m-d", strtotime("-{$quantidadeMesesAnteriores} months", strtotime($periodo["data_inicio_periodo"]))),
                "data_fim_periodo" => date("Y-m-d", strtotime("-{$quantidadeMesesAnteriores} months", strtotime($periodo["data_fim_periodo"])))
            );
        }

        return $periodo;
    }
}
