<?php
namespace App\Command;

use Cake\Console\Arguments;
use App\Command\AbstractCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

use App\Utils\ArrayUtil;


/**
 * NotificaAtestados command.
 *
 *
 * ex. chamadas
 *
 *  .\bin\cake NotificaAtestados
 *
 *  ou com argumentos
 *
 *  .\bin\cake NotificaAtestados --data_retorno_periodo=2019-11-20 --codigo_cliente=10011 --codigo_usuario=1111
 *
 */
class NotificaAtestadosCommand extends AbstractCommand
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Atestados');
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

        $parser
        ->addOption('codigo_cliente', [
            'help' => 'codigo_cliente'
        ])
        ->addOption('codigo_usuario', [
            'help' => 'codigo_usuario'
        ])
        ->addOption('data_retorno_periodo', [
            'help' => 'data_retorno_periodo'
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

        $codigo_cliente = $args->getOption('codigo_cliente');
        $codigo_usuario = $args->getOption('codigo_usuario');
        $data_retorno_periodo = $args->getOption('data_retorno_periodo');

        $conditions = [];

        if(isset($codigo_cliente) && !empty($codigo_cliente)){
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['codigo_cliente'=>(string)$codigo_cliente]);
        }

        if(isset($codigo_usuario) && !empty($codigo_usuario)){
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['codigo_usuario'=>(string)$codigo_usuario]);
        }

        if(isset($data_retorno_periodo) && !empty($data_retorno_periodo)){
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['data_retorno_periodo'=>(string)$data_retorno_periodo]);
        } else {
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['data_retorno_periodo'=>date('Y-m-d')]);
        }

        $usuarios_e_atestados = $this->Atestados->obterAtestadosPorVencimento($conditions);

        if(isset($usuarios_e_atestados['error'])){
            $io->out($usuarios_e_atestados['error']);
            return;
        }

        $push_agendados = [];

        foreach ($usuarios_e_atestados as $key => $value) {

            if(isset($value['notificacao']) && $value['notificacao'] != '1'){
                continue;
            }

            /**
             * 3. Notificação Atestados (quando de dias, na data do término).
             * Enviar mensagem: Olá @@@@, a validade do seu atestado se encerra hoje.
             */
            $push_opcoes = [
                'titulo' => 'Validade de atestado',
                'mensagem' => "Olá {$value['nome']}, a validade do seu atestado se encerra hoje.",
                'fone_para' => $value['telefone'],
                'token'=> $value['token_push'],
                'sistema_origem'=>'Lyn',
                'modulo_origem'=>__CLASS__,
                'model'=>__CLASS__,
                'platform'=> $value['platform'],
                'codigo_key'=> 4,
                'foreign_key'=> $value['codigo_atestado'],
                'codigo_usuario'=> $value['codigo_usuario'],
                'extra_data'=> ' ',
                'codigo_usuario_inclusao' => 1, // operacao
                'data_inclusao' => date('Y-m-d H:i:s')
            ];

            $push = $this->agendarPush($push_opcoes);

            if(isset($push['codigo'])){
                $push_agendados[] = $push['codigo'];
            }
        }

        $io->out("Operação realizada com sucesso.");
    }

}
