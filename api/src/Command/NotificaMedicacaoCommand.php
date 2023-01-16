<?php
namespace App\Command;

use Aura\Intl\Exception;
use Cake\Console\Arguments;
use App\Command\AbstractCommand;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * NotificaMedicacao command.
 */
class NotificaMedicacaoCommand extends AbstractCommand
{

     public function initialize()
    {
        parent::initialize();
        $this->loadModel('UsuariosMedicamentos');
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
        $parser->setDescription('Comando para enviar notificação de lembrete para o usuário usar medicamento. ');

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

//        Define as datas atuais
        $data_hora_atual = date('Y-m-d H:i');
        $dias_semana = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
        $dia_semana_atual = date('w', time());
        $minutos_antes = strtotime('-10 minutes');
        $mensagem = '';

        $usuarios = $this->UsuariosDados->notificaUsuariosMedicamento();
        //verifica se tem usuarios para gravar/agendar os pushs
        if(!empty($usuarios)) {
            $io->out('Agendando notificações...');
//            varre os usuarios que precisa agendar/enviar push
            try {
                foreach($usuarios AS $user) {
                    //configura qual é a mensagem
                    $liberar_envio_em = null;

                    if ($user['dias_da_semana']){
                        $dias_semana_usuario = explode(',', $user['dias_da_semana']);
                    }

                    $push_opcoes = [
                        'titulo'=>'Lembrete de medicamento',
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
                        'foreign_key' => $user['usuario_medicamento_status_codigo']
                    ];

                    // Fluxo de frequência = Todos os dias
                    if ($user['frequencia_dias'] == 1){
                        // Frequência de horários = Intervalo de horários ||FEITO
                        if ($user['frequencia_horarios'] == 1) {
                            // Uso contínuo = Sim || FEITO
                            if($user['uso_continuo'] == 1) {
                                // Dia da semana bate com o dia atual || FEITO
                                if($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)){
                                    // Horario do ultimo uso do medicamento caso usado || FEITO
                                    if($user['data_hora_uso'] != null){
                                        $frequencia = (int) $user['frequencia_uso'];
                                        $liberar_envio_em  = date("Y-m-d H:i", strtotime("+" . $frequencia . "hours", true));
                                        $mensagem = self::mensagemAntecipada10Minutos($user);
                                    }
                                    // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                    else{
                                        // Horario do inicio de uso do medicamento || FEITO
                                        if($user['horario_inicio_uso'] != null){
                                            $data_envio = date('Y-m-d ' . $user['horario_inicio_uso']);
                                            $liberar_envio_em  = date("Y-m-d H:i", strtotime("-10 minutes", strtotime($data_envio)));
                                            $mensagem = self::mensagemAntecipada10Minutos($user);
                                        }
                                    }
                                }
                            }
                            // Uso contínuo = Não
                            else {
                                // Se a data termino é igual ou menor que a data atual || FEITO
                                if ($user['periodo_tratamento_termino'] <= date('Y-m-d')){
                                    if($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)){
                                        // Horario do ultimo uso do medicamento caso usado || FEITO
                                        if($user['data_hora_uso'] != null){
                                            $frequencia = (int) $user['frequencia_uso'];
                                            $liberar_envio_em  = date("Y-m-d H:i", strtotime("+" . $frequencia . "hours", true));
                                            $mensagem = self::mensagemAntecipada10Minutos($user);
                                        }
                                        // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                        else{
                                            // Horario do inicio de uso do medicamento || FEITO
                                            if($user['horario_inicio_uso'] != null){
                                                $data_envio = date('Y-m-d ' . $user['horario_inicio_uso']);
                                                $liberar_envio_em  = date("Y-m-d H:i", strtotime("-10 minutes", strtotime($data_envio)));
                                                $mensagem = self::mensagemAntecipada10Minutos($user);
                                            }
                                        }
                                    }
                                }
                            }

                        }
                        // Frequência de horários = Horário livre
                        else {
                            // Uso contínuo = Sim
                            if($user['uso_continuo'] == 1) {
                                // Dia da semana bate com o dia atual || FEITO
                                if($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)){
                                    // Horario do ultimo uso do medicamento caso usado || FEITO
                                    if($user['data_hora_uso'] != null){
                                        $liberar_envio_em  = date('Y-m-d H:i');
                                        $mensagem = self::mensagemGenerica($user);
                                    }
                                    // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                    else{
                                        $liberar_envio_em  = date("Y-m-d H:i");
                                        $mensagem = self::mensagemGenerica($user);
                                    }
                                }
                            }
                            // Uso contínuo = Não
                            else {
                                // Se a data termino é igual ou menor que a data atual || FEITO
                                if ($user['periodo_tratamento_termino'] <= date('Y-m-d')){
                                    if($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)){
                                        // Horario do ultimo uso do medicamento caso usado || FEITO
                                        if($user['data_hora_uso'] != null){
                                            $liberar_envio_em  = date('Y-m-d H:i');
                                            $mensagem = self::mensagemGenerica($user);
                                        }
                                        // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                        else{
                                            // Horario do inicio de uso do medicamento || FEITO
                                            $liberar_envio_em  = date('Y-m-d H:i');
                                            $mensagem = self::mensagemGenerica($user);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    // Frequência de dias = Dias intercalados
                    else {
                        if ($user['frequencia_horarios'] == 1) {
                            // Uso contínuo = Sim || FEITO
                            if ($user['uso_continuo'] == 1) {
                                // Dia da semana bate com o dia atual || FEITO
                                if ($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)) {
                                    // Horario do ultimo uso do medicamento caso usado || FEITO
                                    if ($user['data_hora_uso'] != null) {
                                        $liberar_envio_em  = date('Y-m-d H:i');
                                        $mensagem = self::mensagemGenerica($user);
                                    } // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                    else {
                                        // Horario do inicio de uso do medicamento || FEITO
                                        if ($user['horario_inicio_uso'] != null) {
                                            $data_envio = date('Y-m-d ' . $user['horario_inicio_uso']);
                                            $liberar_envio_em = date("Y-m-d H:i", strtotime("-10 minutes", strtotime($data_envio)));
                                            $mensagem = self::mensagemAntecipada10Minutos($user);
                                        }
                                    }
                                }
                            } // Uso contínuo = Não
                            else {
                                // Se a data termino é igual ou menor que a data atual || FEITO
                                if ($user['periodo_tratamento_termino'] <= date('Y-m-d')) {
                                    if ($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)) {
                                        // Horario do ultimo uso do medicamento caso usado || FEITO
                                        if ($user['data_hora_uso'] != null) {
                                            $liberar_envio_em  = date('Y-m-d H:i');
                                            $mensagem = self::mensagemGenerica($user);
                                        } // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                        else {
                                            // Horario do inicio de uso do medicamento || FEITO
                                            if ($user['horario_inicio_uso'] != null) {
                                                $data_envio = date('Y-m-d ' . $user['horario_inicio_uso']);
                                                $liberar_envio_em = date("Y-m-d H:i", strtotime("-10 minutes", strtotime($data_envio)));
                                                $mensagem = self::mensagemAntecipada10Minutos($user);
                                            }
                                        }
                                    }
                                }
                            }

                        } // Frequência de horários = Horário livre
                        else {
                            // Uso contínuo = Sim
                            if ($user['uso_continuo'] == 1) {
                                // Dia da semana bate com o dia atual || FEITO
                                if ($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)) {
                                    // Horario do ultimo uso do medicamento caso usado || FEITO
                                    if ($user['data_hora_uso'] != null) {
                                        $mensagem = self::mensagemGenerica($user);
                                        $liberar_envio_em = date('Y-m-d H:i');
                                    } // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                    else {
                                        $liberar_envio_em = date("Y-m-d H:i");
                                        $mensagem = self::mensagemGenerica($user);
                                    }
                                }
                            } // Uso contínuo = Não
                            else {
                                // Se a data termino é igual ou menor que a data atual || FEITO
                                if ($user['periodo_tratamento_termino'] <= date('Y-m-d')) {
                                    if ($dias_semana_usuario && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario, false)) {
                                        // Horario do ultimo uso do medicamento caso usado || FEITO
                                        if ($user['data_hora_uso'] != null) {
                                            $liberar_envio_em = null;
                                            $mensagem = self::mensagemGenerica($user);
                                        } // Horario do ultimo uso do medicamento caso ainda não tenha usado || FEITO
                                        else {
                                            // Horario do inicio de uso do medicamento || FEITO
                                            $liberar_envio_em = date("Y-m-d H:i");
                                            $mensagem = self::mensagemGenerica($user);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $push_opcoes['mensagem'] = $mensagem['mensagem'];
                    $push_opcoes['liberar_envio_em'] = $liberar_envio_em;

                    // debug($push_opcoes);
                    
                    //grava a mensagem do push
                    $push = $this->agendarPush($push_opcoes);


                }//fim foreach para gravar os pushs
                $io->out('Notificações agendadas com sucesso.');
            } catch (Exception $e){
                // debug($e->getMessage());
                $io->out('Notificações não agendadas.' . $e);
            }


        }
        else{
            $io->out('Sem usuários para agendar noficação.');
        }//fim if usuarios

    }//fim execute

    public function mensagemAntecipada10Minutos($user) {
        $mensagem = ["mensagem" => "O remédio " . $user['medicamento'] . " deve ser tomado dentro de 10 minutos, não esqueça!"];
        return $mensagem;
    }

    public function mensagemGenerica($user) {
        $mensagem = ['mensagem' => 'O remédio ' . $user['medicamento'] . ' deve ser hoje, não esqueça!'];
        return $mensagem;
    }
}
