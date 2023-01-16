<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\I18n\Time;

/**
 * Hello command.
 */
class NotificaExamesAgendadosCommand extends AbstractCommand
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addOption('data', [
            'help' => 'Informe qual a data para notificar \'vespera\' ou \'agendamento\'',
            'required' => true,
            'choices' => ['agendamento', 'vespera']
        ]);

        return $parser;
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

        $opcaoSelecionada = $args->getOption('data');
        $momento = $this->buscarAgendamentos('momento');

        $vespera = $this->buscarAgendamentos('vespera');
        $io->out("Operação realizada com sucesso.");
    }

    public function buscarAgendamentos($opcaoSelecionada){
        $this->loadModel('ItensPedidosExames');
        $agendamentos = $this->ItensPedidosExames->notificarExamesAgendados($opcaoSelecionada);
        
        $push_data = [];

        foreach ($agendamentos->toArray() as $agendamento) {

            $primeiroNome = !empty($agendamento->Usuario["nome"]) ? ucfirst(strtolower(explode(" ", $agendamento->Usuario["nome"])[0])) : '';

            $push_opcoes = [
                'fone_para' => $agendamento->UsuariosDados['telefone'],
                'token'=> $agendamento->UsuarioSistema['token_push'],
                'sistema_origem'=>'Lyn',
                'modulo_origem'=>__CLASS__,
                'platform'=> $agendamento->UsuarioSistema['platform'],
                'codigo_key'=> 4,
                'extra_data'=> ' ',
                'model' =>'NotificaExamesAgendados'.$opcaoSelecionada,
                'foreign_key'=>$agendamento->codigo,
                'codigo_usuario_inclusao' => 1, // operacao
                'codigo_usuario' => $agendamento->UsuariosDados['codigo_usuario'],
                'data_inclusao' => date('Y-m-d H:i:s')
            ];

            if($opcaoSelecionada == 'vespera') {
                $push_opcoes['titulo'] = 'Aviso de exame agendado';
                $push_opcoes['mensagem'] = 'Olá '.$primeiroNome.', você tem um exame agendado para amanhã.';
            } else {
                $time = new Time($agendamento["data_agendamento"]);
                $dataLegivel = $time->format("d/m/Y");
                $push_opcoes['titulo'] = 'Novo exame agendado';
                $push_opcoes['mensagem'] = 'Olá '.$primeiroNome.', você tem um exame agendado para o dia '.$dataLegivel.'.';
            }

            $push = $this->agendarPush($push_opcoes);

            if(isset($push['codigo'])){
                $push_data[] = $push['codigo'];
            }
        }
        return $push_data;

    }
}
