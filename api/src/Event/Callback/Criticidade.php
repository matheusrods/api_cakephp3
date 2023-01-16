<?php

namespace App\Event\Callback;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Event\Shared\Roteador;
use App\Event\Contract\Notificacao;
use App\Event\Shared\Unificador;

class Criticidade implements Notificacao
{
    const CODIGO_FERRAMENTA              = 2;
    const CODIGO_NOTIFICACAO_CRITICIDADE = 9;

    private $PdaTemaModel;
    private $PdaConfigRegraModel;
    private $UsuariosResponsaveis;
    private $funil;
    private $evento;

    public function __construct(Event $evento)
    {
        $this->PdaTemaModel         = TableRegistry::getTableLocator()->get('PdaTema');
        $this->PdaConfigRegraModel  = TableRegistry::getTableLocator()->get('PdaConfigRegra');
        $this->UsuariosResponsaveis = TableRegistry::getTableLocator()->get('UsuariosResponsaveis');
        $this->funil                = new Unificador(array());
        $this->evento               = $evento;
    }

    public function notificar(): void
    {
        /** @var int */
        $codigo_cliente = $this->evento->getData('codigo_cliente');

        $temaRegraAcao = $this->PdaTemaModel->buscarTemaComRelacionamentosPor(
            self::CODIGO_NOTIFICACAO_CRITICIDADE,
            self::CODIGO_FERRAMENTA,
            $codigo_cliente
        );

        foreach ($temaRegraAcao->regras as $regra) {
            foreach ($regra->configRegraCodicoes as $configRegra) {
                $nivelCriticidade = (int) $configRegra->codigo_pos_criticidade;
                $respostas = $this->encontraRespostasQueAtendaRegra($nivelCriticidade);

                if (empty($respostas)) {
                    continue;
                }

                $this->cumpreRequisitos($configRegra, $respostas, $regra);
            }
        }
    }

    private function cumpreRequisitos($configRegra, array $respostas, $regra)
    {
        $titulo  = (int) $configRegra->codigo_pos_swt_form_titulo;
        $questao = (int) $configRegra->codigo_pos_swt_form_questao;

        if (empty($titulo) && empty($questao)) {
            /**
             * RN1.6.8 Se o usuário preencher apenas a criticidade, deixando em branco os campos Título e Pergunta,
             * o sistema deve entender que todos os títulos e questionários quando forem respondidos com a criticidade
             * configurada, deve gerar a notificação.
             */

            $this->determinaQuemNotificar($regra);
        }

        if (!empty($titulo) && empty($questao)) {
            /**
             * RN1.6.9 Se o usuário preencher a criticidade e o campo título, deixando em branco apenas as perguntas,
             * o sistema deve entender que todos as perguntas daquele(s) título(s) quando forem respondidos com a criticidade X,
             * título Y configuradas, deve gerar a notificação.
             */
            $respostasFiltradasPorTitulo = $this->filtraPorCodigoTitulo($titulo, $respostas);

            if (empty($respostasFiltradasPorTitulo)) {
                return;
            }

            $this->determinaQuemNotificar($regra);
        }

        if (!empty($titulo) && !empty($questao)) {
            /**
             * RN1.6.10 Se o usuário preencher a criticidade, o título e as perguntas, o sistema deve entender que
             * somente aquela criticidade X, título Y, questão Z configuradas, deve gerar a notificação.
             */
            $respostasFiltradasPorTituloQuestao = array_filter(
                array_map(
                    function ($resposta) use ($questao) {
                        $questoes = $this->filtraPorCodigoQuestao($questao, $resposta['questao']);

                        if (empty($questoes)) {
                            return [];
                        }

                        return [
                            'codigo_titulo' => $resposta['codigo_titulo'],
                            'questao'       => $questoes
                        ];
                    },
                    $respostas,
                )
            );

            $respostasFiltradasPorTituloQuestao = array_values($respostasFiltradasPorTituloQuestao);

            if (empty($respostasFiltradasPorTituloQuestao)) {
                return;
            }

            $this->determinaQuemNotificar($regra);
        }
    }

    private function encontraRespostasQueAtendaRegra(int $criticidade)
    {
        $respostas = array_filter(
            array_map(
                function ($resposta) use ($criticidade) {
                    $questoes = $this->filtraPorNivelCriticidadeAtingida($criticidade, $resposta['questao']);

                    if (empty($questoes)) {
                        return [];
                    }

                    return [
                        'codigo_titulo' => $resposta['codigo_titulo'],
                        'questao'       => $questoes
                    ];
                },
                $this->evento->getData('respostas'),
            )
        );

        return array_values($respostas);
    }

    private function filtraPorNivelCriticidadeAtingida(int $criticidade, array $questoes)
    {
        return array_filter(
            $questoes,
            function ($questao) use ($criticidade) {
                return $questao['criticidade'] >= $criticidade;
            }
        );
    }

    private function filtraPorCodigoTitulo(int $codigoTitulo, array $respostas)
    {
        return array_filter(
            $respostas,
            function ($resposta) use ($codigoTitulo) {
                return $resposta['codigo_titulo'] === $codigoTitulo;
            }
        );
    }

    private function filtraPorCodigoQuestao(int $codigoQuestao, array $questoes)
    {
        return array_filter(
            $questoes,
            function ($questao) use ($codigoQuestao) {
                return $questao['codigo'] == $codigoQuestao;
            }
        );
    }

    private function determinaQuemNotificar($regra)
    {
        foreach ($regra->configRegraAcoes as $configRegraAcao) {
            /**
             * 1  => Usuário observador,
             * 2  => Usuário responsável do Walk Talk / Análise de qualidade,
             * 3  => Gestor Direto,
             * 4  => Email Preenchido no campo regra
             */
            switch ($configRegraAcao->tipo_acao) {
                case 1:
                    $email = $this->PdaConfigRegraModel->getEmailUsuarioFuncionario(
                        $this->evento->getData('observador')
                    );

                    if ($this->funil->inedito($email)) {
                        Roteador::direcionaNotificacao(
                            $regra,
                            $email,
                            $this->evento,
                            $configRegraAcao->codigo_pda_tema_acoes
                        );
                    }

                    break;

                case 2:
                    $emails = $this->PdaConfigRegraModel->getEmailUsuarioResponsavelArea(
                        $this->evento->getData('codigo_cliente')
                    );

                    foreach ($emails as $email) {
                        if ($this->funil->inedito($email)) {
                            Roteador::direcionaNotificacao(
                                $regra,
                                $email,
                                $this->evento,
                                $configRegraAcao->codigo_pda_tema_acoes
                            );
                        }
                    }

                    break;

                case 3:
                    $emails = $this->buscaEmailGestores($this->evento);

                    foreach ($emails as $email) {
                        if ($this->funil->inedito($email)) {
                            Roteador::direcionaNotificacao(
                                $regra,
                                $email,
                                $this->evento,
                                $configRegraAcao->codigo_pda_tema_acoes
                            );
                        }
                    }

                    break;

                case 4:
                    $email = $configRegraAcao->email;

                    if ($this->funil->inedito($email)) {
                        Roteador::direcionaNotificacao(
                            $regra,
                            $email,
                            $this->evento,
                            $configRegraAcao->codigo_pda_tema_acoes
                        );
                    }

                    break;

                default:
                    break;
            }
        }
    }

    private function buscaEmailGestores()
    {
        $codigo_cliente = $this->evento->getData('codigo_cliente');
        $email_gestores = [];

        $usuariosResponsaveis = $this->UsuariosResponsaveis
            ->find()
            ->select(['codigo_usuario'])
            ->where(['codigo_cliente' => $codigo_cliente, 'data_remocao IS NULL'])
            ->enableHydration(false)
            ->all()
            ->toArray();

        foreach ($usuariosResponsaveis as $usuarioResponsavel) {
            $email = $this->PdaConfigRegraModel->getEmailGestorDireto(
                $usuarioResponsavel['codigo_usuario']
            );

            if (empty($email)) {
                continue;
            }

            array_push($email_gestores, $email);
        }

        return $email_gestores;
    }
}
