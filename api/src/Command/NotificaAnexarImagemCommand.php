<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use App\Command\AbstractCommand;
use Cake\I18n\Time;
use Cake\ORM\Query;

/**
 * NotificaAnexarImagem command.
 */
class NotificaAnexarImagemCommand extends AbstractCommand
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
        // Buscando notificações de 3 horas atrás
        $notifica3hrs = $this->buscarNotificacoes("NotificacaoAnexoExame3hrs", new Time('3 hours ago'));

        // Buscando notificações de 24 horas atrás
        $notifica24hrs = $this->buscarNotificacoes("NotificacaoAnexoExame24hrs", new Time('24 hours ago'));

        $io->out("Operação realizada com sucesso.");

    }

    /**
     * Grava as notificações
     *
     * @param string $model nome da model que esta notificação pertence
     * @param Time $time horários que buscará os pedidos de exame [Ex: new Time('3 hours ago')]
     * @return array Lista de IDs dos itens notificados
     */
    public function buscarNotificacoes(string $model, Time $time){
        $this->loadModel('ItensPedidosExames');
        $agendamentos = $this->ItensPedidosExames->getUsuarioNotificarAnexoExame($model, $time);
        $push_data = [];

        foreach ($agendamentos->toArray() as $agendamento) {

            /**
             * Olá @@@@ , você realizou seu exame?
             * Se sim, pedimos que nos envie uma foto do seu ASO.
             */
            $push_opcoes = [
                'titulo' => 'Anexar foto do exame',
                'mensagem' => "Olá {$agendamento->Usuario['nome']}, você realizou seu exame? Se sim, pedimos que nos envie uma foto do seu ASO.",
                'fone_para' => $agendamento->UsuariosDados['telefone'],
                'token'=> $agendamento->UsuarioSistema['token_push'],
                'sistema_origem'=>'Lyn',
                'modulo_origem'=>__CLASS__,
                'platform'=> $agendamento->UsuarioSistema['platform'],
                'codigo_key'=> 4,
                'extra_data'=> ' ',
                'model' =>$model,
                'foreign_key'=>$agendamento->codigo,
                'codigo_usuario_inclusao' => 1, // operacao
                'codigo_usuario' => $agendamento->UsuariosDados['codigo_usuario'], // operacao
                'data_inclusao' => date('Y-m-d H:i:s')
            ];

            $push = $this->agendarPush($push_opcoes);

            if(isset($push['codigo'])){
                $push_data[] = $push['codigo'];
            }
        }
        return $push_data;

    }
}
