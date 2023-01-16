<?php

namespace App\Event\Callback;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Event\Shared\Roteador;
use App\Event\Contract\Notificacao;
use App\Event\Shared\Unificador;

class Participante implements Notificacao
{
    const CODIGO_FERRAMENTA                = 2;
    const CODIGO_NOTIFICACAO_PARTICIPANTES = 8;

    private $PdaTemaModel;
    private $PdaConfigRegraModel;
    private $funil;
    private $evento;

    public function __construct(Event $evento)
    {
        $this->PdaTemaModel        = TableRegistry::getTableLocator()->get('PdaTema');
        $this->PdaConfigRegraModel = TableRegistry::getTableLocator()->get('PdaConfigRegra');
        $this->funil               = new Unificador(array());
        $this->evento              = $evento;
    }

    public function notificar(): void
    {
        $codigo_cliente = (int) $this->evento->getData('codigo_cliente');

        $temaRegraAcao = $this->PdaTemaModel->buscarTemaComRelacionamentosPor(
            self::CODIGO_NOTIFICACAO_PARTICIPANTES,
            self::CODIGO_FERRAMENTA,
            $codigo_cliente
        );

        foreach ($this->evento->getData('participantes') as $participante) {
            $email_participante = $this->PdaConfigRegraModel
                ->getEmailUsuarioFuncionario($participante['codigo_usuario']);

            if (!$this->funil->inedito($email_participante)) {
                continue;
            }

            foreach ($temaRegraAcao->regras as $regra) {
                foreach ($regra->configRegraAcoes as $configRegraAcao) {
                    Roteador::direcionaNotificacao(
                        $regra,
                        $email_participante,
                        $this->evento,
                        $configRegraAcao->codigo_pda_tema_acoes
                    );
                }
            }
        }
    }
}
