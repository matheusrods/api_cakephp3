<?php
namespace App\Command;

use Cake\Console\Arguments;
use App\Command\AbstractCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * NotificaExamesAVencer command.
 */
class NotificaExamesAVencerCommand extends AbstractCommand
{

     public function initialize()
    {
        parent::initialize();
        $this->loadModel('PedidosExames');
    }

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

        $parser->addOption('dias', ['help' => 'Digite um codigo']);

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

        //verifica se tem argumento para quantidade de dias
        $dias_a_vencer = $args->getOption('dias');

        //verifica se tem o a vencer
        if(is_null($dias_a_vencer)) {
            $dias_a_vencer = 30;
        }//fim a dias a vencer

        //pega os usuarios de exames a vencer
        $usuarios = $this->PedidosExames->notificacaoExamesAVencer($dias_a_vencer);

        //verifica se tem usuarios para gravar/agendar os pushs
        if(!empty($usuarios)) {
            //varre os usuarios que precisa agendar/enviar push
            foreach($usuarios AS $user) {

                //configura qual é a mensagem
                $push_opcoes = [
                   'titulo'=>'Exames a vencer',
                   'mensagem'=>'Olá ' . $user['usuario_nome'] . ', o seu exame periódico irá vencer em 30 dias. Agende agora.',
                   'fone_para'=>$user['celular'],
                   'token'=> $user['token_push'],
                   'sistema_origem'=>'Lyn',
                   'modulo_origem'=>'SHELL_'.__CLASS__,
                   'platform'=>$user['platform'],
                   'codigo_key'=>4,
                   'extra_data'=>' ',
                   'codigo_usuario_inclusao' => $user['codigo_usuario'], // operacao
                   'codigo_usuario' => $user['codigo_usuario'], // operacao
                   'data_inclusao' => date('Y-m-d H:i:s')
               ];
               //grava a mensagem do push
               $push = $this->agendarPush($push_opcoes);

           }//fim foreach para gravar os pushs
            $io->out("Operação realizada com sucesso.");

        }//fim if usuarios

    }//fim execute
}
