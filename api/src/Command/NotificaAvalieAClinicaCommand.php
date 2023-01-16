<?php
namespace App\Command;

use Aura\Intl\Exception;
use Cake\Console\Arguments;
use App\Command\AbstractCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * NotificaAvalieAClinica command.
 */
class NotificaAvalieAClinicaCommand extends AbstractCommand
{

     public function initialize()
    {
        parent::initialize();
        $this->loadModel('ItensPedidosExameBaixa');
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
        $parser->setDescription('Comando para enviar notificação ao usuário após o término da consulta. ');

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
        $io->out('Efetuando busca de usuários...');
        $this->loadModel('UsuariosDados');

        $usuarios = $this->UsuariosDados->notificacaoUsuariosAvalieAClinica();
        //verifica se tem usuarios para gravar/agendar os pushs
        if(!empty($usuarios)) {
            $io->out('Agendando notificações...');
            //varre os usuarios que precisa agendar/enviar push
            try {
                foreach($usuarios AS $user) {

                    //configura qual é a mensagem
                    $push_opcoes = [
                        'titulo'=>'Avalie a clínica',
                        'mensagem'=>'Olá ' . $user['usuario_nome'] . ', avalie a clínica ' . $user['nome_credenciado'] . '.',
                        'fone_para'=>$user['celular'],
                        'token'=> $user['token_push'],
                        'sistema_origem'=>'Lyn',
                        'modulo_origem'=>'SHELL_'.__CLASS__,
                        'platform'=>$user['platform'],
                        'codigo_key'=>4,
                        'extra_data'=>' ',
                        'codigo_usuario_inclusao' => $user['codigo_usuario'], // operacao
                        'codigo_usuario' => $user['codigo_usuario'], // operacao
                        'data_inclusao' => date('Y-m-d H:i:s'),
                        'foreign_key' => $user['codigo_pedido_exame']
                    ];
                        //grava a mensagem do push
                        $push = $this->agendarPush($push_opcoes);

                }//fim foreach para gravar os pushs
                $io->out(count($usuarios) . ' Notificações agendadas com sucesso.');
            } catch (Exception $e){
                $io->out('Notificações não agendadas.' . $e);
            }


        }
        else{
            $io->out('Sem usuários para agendar noficação.');
        }//fim if usuarios

    }//fim execute
}
