<?php

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;

/**
 * Hello command.
 */
class NotificaEmAcaoMelhoriaCommand extends AbstractCommand
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('AcoesMelhorias');
        $this->loadModel('PdaConfigRegra');
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {

        $io->out("Inciando busca por acoes de melhorias.");

        //metodo para pegar as que estao em andamento
        $em_andamento = $this->getEmAndamento();

        //pega as acoes que estao atrasadas com a regra
        $em_atraso = $this->getEmAtraso();

        $io->out("Fim da Operação.");
    }

    /**
     * [getEmAndamento busca os dados de em andamento de acoes de melhorias]
     * @return [type] [description]
     */
    public function getEmAndamento()
    {

        //busca as acoes de melhorias que estão com o status em andamento
        $dados_acoes = $this->AcoesMelhorias->find()
            ->select(['codigo'])
            ->where([
                'codigo_acoes_melhorias_status' => 3
            ])
            ->enableHydration(false)
            ->all()
            ->toArray();

        if (!empty($dados_acoes)) {

            foreach ($dados_acoes as $val) {
                // echo 'codigo_acao: '.$val['codigo']."\n";
                $this->PdaConfigRegra->getEmAcaoDeMelhoria($val['codigo']);
            } //fim varrendo dados acoes

        } //fim dados_acoes

    } //fim getEmAndamento

    public function getEmAtraso()
    {

        //pega os dados de acoes de melhoria que devem ser enviados hoje a acao
        $dados_acoes = $this->PdaConfigRegra->getAcaoesMelhoriasEmAtraso();
        // debug($dados_acoes);exit;

        if (!empty($dados_acoes)) {
            //varre os dados de acoes
            foreach ($dados_acoes as $acao) {
                $this->PdaConfigRegra->getEmAtraso($acao['codigo_acao_melhoria'], $acao['valor_dias']);
            } //fim das acoes

        } //fim dados acoes

    } //fim getEmAtraso


}
