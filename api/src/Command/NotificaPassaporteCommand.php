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
class NotificaPassaporteCommand extends AbstractCommand
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    /*public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
        $parser->addOption('data', [
            'help' => 'Informe qual a data para notificar \'vespera\' ou \'agendamento\'',
            'required' => true,
            'choices' => ['agendamento', 'vespera']
        ]);

        return $parser;
    }*/

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {

        $io->out("Inciando operação de busca para geração dos passaportes.");
        
        $resultado = $this->getUsuarioSemPassaporte();
        
        $io->out("Operação realizada com sucesso.");
    }

    /**
     * [getUsuarioSemPassaporte metodo para buscar os usuarios que precisam gerar o passaporte no dia de hoje com itervalos de 3 em 3 horas iniciando as 07:00 hrs e finalizando as 22:00]
     * @return [type] [description]
     */
    public function getUsuarioSemPassaporte()
    {
        $this->loadModel('ResultadoCovid');
        $data = date('Y-m-d 00:00:00');
        $dados = $this->ResultadoCovid->getUsuariosSemResultado($data);

        $push_data = [];

        if(!empty($dados)) {

            //varre os dados para agendar o push
            foreach ($dados as $d) {

                $nome = !empty($d["nome"]) ? $d["nome"] : '';

                $push_opcoes = [
                    'fone_para' => $d['telefone'],
                    'token'=> $d['token_push'],
                    'sistema_origem'=>'Lyn',
                    'modulo_origem'=>__CLASS__,
                    'platform'=> $d['platform'],
                    'codigo_key'=> 4,
                    'extra_data'=> ' ',
                    'model' =>'NotificaPassaporte',
                    'foreign_key'=>$d['codigo'],
                    'codigo_usuario_inclusao' => 1, // operacao
                    'codigo_usuario' => $d['codigo'],
                    'data_inclusao' => date('Y-m-d H:i:s'),
                    'titulo' => 'Passaporte',
                    'mensagem' => 'Olá '.$nome.', não se esqueça de preencher o seu questionário para a geração do passaporte'
                ];

                if($d['codigo_grupo_covid'] == '3') {
                    $push_opcoes['mensagem'] = 'Olá '.$nome.', não se esqueça de entrar no aplicativo Lyn para atualizar o seu passaporte';
                }

                $push = $this->agendarPush($push_opcoes);

                if(isset($push['codigo'])){
                    $push_data[] = $push['codigo'];
                }
            
            }//fim foreach

        }//fim verificacao dados vazio

        return $push_data;

    }//fim getUsuarioSemPassaporte
}
