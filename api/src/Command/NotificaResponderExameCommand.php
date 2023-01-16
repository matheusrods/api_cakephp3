<?php
namespace App\Command;

use Aura\Intl\Exception;
use Cake\Console\Arguments;
use App\Command\AbstractCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * NotificaResponderExame command.
 */
class NotificaResponderExameCommand extends AbstractCommand
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('PedidosExames');
        $this->loadModel('PushOutbox');
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

        $usuarios = $this->PedidosExames->getUsuariosResponderExame(null, true);

        if (!empty($usuarios)) {
            $io->out('Agendando notificações...');

            try {
                foreach ($usuarios as $user) {
                    //configura qual é a mensagem
                    $push_opcoes = [
                        'titulo' => 'Responder Exame',
                        'mensagem' => 'Olá ' . $user['usuario_nome'] . ', você tem o exame ' . $user['exame'] . ' para responder através do Lyn.',
                        'fone_para' => $user['celular'],
                        'token' => $user['token_push'],
                        'sistema_origem' => 'Lyn',
                        'modulo_origem' => 'SHELL_'.__CLASS__,
                        'platform' => $user['platform'],
                        'codigo_key' => 4,
                        'extra_data' => ' ',
                        'foreign_key' => $user['codigo_pedidos_exames'],
                        'model' => 'PedidoExame',
                        'codigo_usuario_inclusao' => $user['codigo_usuario'],
                        'codigo_usuario' => $user['codigo_usuario'],
                        'data_inclusao' => date('Y-m-d H:i:s')
                    ];

                    $total = $this->PushOutbox->find()
                        ->where([
                            'foreign_key' => $user['codigo_pedidos_exames'], 
                            'model' => 'PedidoExame',
                            "data_inclusao between '" . date('Y-m-d') . " 00:00:00' AND '" . date('Y-m-d') . " 23:59:59'"
                        ])
                        ->count();

                    if(!$total){
                        $this->agendarPush($push_opcoes);
                    }
                }

                $io->out(count($usuarios) . ' Notificações agendadas com sucesso.');

            } catch (Exception $e) {
                $io->out('Notificações não agendadas.' . $e);
            }
        } else {
            $io->out('Sem usuários para agendar noficação.');
        }
    }
}
