<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Aura\Intl\Exception;
use Cake\I18n\Time;
use App\Utils\Comum;
use DateTime;

/**
 * Medicamentos Controller
 *
 *
 * @method \App\Model\Entity\Medicamento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MedicamentosController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $medicamentos = $this->paginate($this->Medicamentos);

        $this->set(compact('medicamentos'));
    }

    /**
     * Busca os medicamentos por nome
     */
    public function getMedicamentos($medicamento=null)
    {

        $param = "";
        if(is_null($medicamento)){
            $parameters = $this->request->getAttribute('params');          
            
            if (isset($parameters['?']['medicamento'])) {
                $param = $parameters['?']['medicamento'];
            }
        }else{
            $param = $medicamento;
        }

        $query = $this->Medicamentos->getMedicamentosNome($param);

        if (count($query->all()) > 0) {
            $this->set('data', $query);
        } else {
            $error = 'Não existe medicamento com esse nome.';
            $this->set(compact('error'));
            return;
        }

    }

    /***
     * Busca as apresentações dos medicamentos por Descrição e posologia do medicamento
     */

    public function getMedicamentosApresentacao()
    {
        $parameters = $this->request->getAttribute('params');

        if (!empty($parameters['?']['descricao'])) {
            $descricao = $parameters['?']['descricao'];
        }

        if (!empty($parameters['?']['posologia']) && isset($parameters['?']['posologia'])) {
            $posologia = $parameters['?']['posologia'];
        }

        if (empty($descricao)) {
            $error = "A descrição não pode estar vazia.";
            $this->set(compact('error'));
            return;
        } else {
            try {
                if (isset($posologia) && isset($descricao)) {
                    $codigos_apresentacao = $this->Medicamentos->getApresentacoesId($descricao, $posologia);
                }
                
                if (isset($descricao) && !isset($posologia)) {
                    $codigos_apresentacao = $this->Medicamentos->getApresentacoesId($descricao);
                }

                if (count($codigos_apresentacao->all()) > 0) {

                    $this->loadModel('Apresentacoes');

                    try {
                        $codigos = $this->Apresentacoes->getApresentacao($codigos_apresentacao);

                        // debug($codigos->sql());exit;

                        if (count($codigos->all()) > 0) {
                            $this->set('data', $codigos);
                        } else {
                            $error = 'Apresentação não encontrada.';
                            $this->set(compact('error'));
                            return;
                        }

                    } catch (Exception $e) {
                        throwException($e);
                    }

                } else {
                    $error = "Não foi possivel encontrar uma apresentação.";
                    $this->set(compact('error'));
                }

            } catch (Exception $e) {
                throwException($e);
            }
        }

    }

    /***
     * Cria a programacao de medicamentos do usuario
     * @return |null
     */

    public function addProgramacaoMedicamentos()
    {
        //verifica se é post

        if ($this->request->is(['post'])) {
            $usuario = $this->Auth->user('codigo');

            // debug($this->request->getData());exit;

            $codigo_medicamentos = (int)$this->request->getData('codigo_medicamentos');
            $codigo_apresentacao = (int)$this->request->getData('codigo_apresentacao');
            $frequencia_dias = (int)$this->request->getData('frequencia_dias');
            $frequencia_dias_intercalados = (int)$this->request->getData('frequencia_dias_intercalados');
            $frequencia_horarios = (int)$this->request->getData('frequencia_horarios');
            $uso_continuo = (int)$this->request->getData('uso_continuo');
            $frequencia_uso = (int)$this->request->getData('frequencia_uso');
            $periodo_tratamento_inicio = date($this->request->getData('periodo_tratamento_inicio'));
            $periodo_tratamento_termino = date($this->request->getData('periodo_tratamento_termino'));
            $dias_da_semana = $this->request->getData('dias_da_semana');
            $horario_inicio_uso = $this->request->getData('horario_inicio_uso');
            $quantidade = (int)$this->request->getData('quantidade');
            $recomendacao_medica = $this->request->getData('recomendacao_medica');
            $foto_receita = $this->request->getData('foto_receita');
            $quantos_em_quantos_dias = (int)$this->request->getData('quantos_em_quantos_dias');

            $hasMedicamento = $this->Medicamentos->find()->where(['codigo' => $codigo_medicamentos])->first();

            if (empty($hasMedicamento)) {
                $error[] = "Medicamento não econtrado";
                $this->set(compact('error'));
                return null;
            }

            $this->loadModel('Apresentacoes');
            $hasApresentacao = $this->Apresentacoes->find()->where(['codigo' => $codigo_apresentacao])->first();
            if (empty($hasApresentacao)) {
                $error[] = "Apresentação não econtrada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($frequencia_dias)) {
                $error[] = "Frequência de dias não informada.";
                $this->set(compact('error'));
                return null;
            }

            if ($frequencia_dias == 2) {
                if (empty($frequencia_dias_intercalados)) {
                    $error[] = "Frequência de dias intercalados não informado";
                    $this->set(compact('error'));
                    return null;

                }
            }

            if (empty($frequencia_horarios)) {
                $error[] = "Frequência de horarios não informada.";
                $this->set(compact('error'));
                return null;
            }

            if (empty($frequencia_uso)) {
                $error[] = "Frequência de uso não informada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($uso_continuo)) {
                $error[] = "Uso continuo não informado";
                $this->set(compact('error'));
                return null;
            }

            if ($uso_continuo == 2) {
                if (empty($periodo_tratamento_inicio)) {
                    $error[] = "Inicio do periodo de tratamento não informado";
                    $this->set(compact('error'));
                    return null;
                }

                if (empty($periodo_tratamento_termino)) {
                    $error[] = "Termino do periodo de tratamento não informado";
                    $this->set(compact('error'));
                    return null;
                }
            }

            if (empty($dias_da_semana)) {
                $error[] = "Dias da semana não informados";
                $this->set(compact('error'));
                return null;
            } else {
                $dias_da_semana = implode(",", $dias_da_semana);
            }

            if (empty($horario_inicio_uso)) {
                $error[] = "Horario de inicio do uso não informado";
                $this->set(compact('error'));
                return null;
            }

            if (empty($quantidade)) {
                $error[] = "Quantidade não informada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($recomendacao_medica)) {
                $error[] = "Recomendação médica não informada";
                $this->set(compact('error'));
                return null;
            }

           /* if (empty($quantos_em_quantos_dias)) {
                $error[] = "Quantos em quantos dias não informado";
                $this->set(compact('error'));
                return null;

            }*/

            if (empty($foto_receita)) {
                $error[] = "Foto da receita não informada";
                $this->set(compact('error'));
                return null;

            }

            // configura a pasta de upload dos arquivos
            $dados = array(
                'file' => $foto_receita,
                'prefix' => 'nina',
                'type' => 'base64'
            );


            // envia a foto para o systemstorage
            $url_imagem = Comum::sendFileToServer($dados);
            $caminho_image = @array("path" => $url_imagem->{'response'}->{'path'});

            $this->loadModel('UsuariosMedicamentos');

            $fields = [
                'codigo_medicamentos' => $codigo_medicamentos,
                'codigo_apresentacao' => $codigo_apresentacao,
                'codigo_usuario' => $usuario,
                'frequencia_dias' => $frequencia_dias,
                'frequencia_dias_intercalados' => $frequencia_dias_intercalados,
                'frequencia_horarios' => $frequencia_horarios,
                'uso_continuo' => $uso_continuo,
                'frequencia_uso' => $frequencia_uso,
                'periodo_tratamento_inicio' => $periodo_tratamento_inicio,
                'periodo_tratamento_termino' => $periodo_tratamento_termino,
                'dias_da_semana' => $dias_da_semana,
                'horario_inicio_uso' => $horario_inicio_uso,
                'quantidade' => $quantidade,
                'recomendacao_medica' => $recomendacao_medica,
                'codigo_usuario_inclusao' => $usuario,
                'codigo_usuario_alteracao' => $usuario,
                'data_inclusao' => date('Y-m-d H:i'),
                //'data_alteracao' => date('Y-m-d H:i'),
                'ativo'=>1
            ];

            $programacao_medicamento = $this->UsuariosMedicamentos->newEntity($fields);

            if (!empty($caminho_image)) {
                $programacao_medicamento->foto_receita = FILE_SERVER . $caminho_image['path'];
            }

            if (! $this->UsuariosMedicamentos->save($programacao_medicamento)) {
                $error[]="Não foi possível cadastrar o medicamento.";
                $this->set(compact('error'));
                return;
            }

        }//fim post
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }

        // saída
        if (!empty($programacao_medicamento)) {
            $this->set(compact('programacao_medicamento'));
        } else {
            $this->set(compact('error'));
        }
    }

    /***
     * Atualiza a programacao de medicamentos do usuario
     * @return |null
     */

    public function updateProgramacaoMedicamentos()
    {
        //verifica se é put
        if ($this->request->is(['put'])) {

            $params = $this->request->getAttribute('params');

            if (!empty($params['codigo_usuarios_medicamentos'])) {
                $codigo_usuarios_medicamentos = $params['codigo_usuarios_medicamentos'];
            } else {
                $error[] = "Código de programção de medicamento não informado";
                $this->set(compact('error'));
                return null;
            }

            $this->loadModel('UsuariosMedicamentos');

            $programacao_medicamento = $this->UsuariosMedicamentos->find()->where(['codigo' => $codigo_usuarios_medicamentos])->first();
            if (empty($programacao_medicamento)) {
                $error[] = "Programação de medicamento não encontrada";
                $this->set(compact('error'));
                return null;
            }

            $usuario = $this->Auth->user('codigo');
            $codigo_medicamentos = (int)$this->request->getData('codigo_medicamentos');
            $codigo_apresentacao = (int)$this->request->getData('codigo_apresentacao');
            $frequencia_dias = (int)$this->request->getData('frequencia_dias');
            $frequencia_dias_intercalados = (int)$this->request->getData('frequencia_dias_intercalados');
            $frequencia_horarios = (int)$this->request->getData('frequencia_horarios');
            $uso_continuo = (int)$this->request->getData('uso_continuo');
            $frequencia_uso = (int)$this->request->getData('frequencia_uso');
            $periodo_tratamento_inicio = $this->request->getData('periodo_tratamento_inicio');
            $periodo_tratamento_termino = $this->request->getData('periodo_tratamento_termino');
            $dias_da_semana = $this->request->getData('dias_da_semana');
            $horario_inicio_uso = $this->request->getData('horario_inicio_uso');
            $quantidade = (int)$this->request->getData('quantidade');
            $recomendacao_medica = $this->request->getData('recomendacao_medica');
            $foto_receita = $this->request->getData('foto_receita');
            $quantos_em_quantos_dias = (int)$this->request->getData('quantos_em_quantos_dias');
            $ativo = (int)$this->request->getData('ativo');

            $hasMedicamento = $this->Medicamentos->find()->where(['codigo' => $codigo_medicamentos])->first();
            if (empty($hasMedicamento)) {
                $error[] = "Medicamento não encontrado";
                $this->set(compact('error'));
                return null;
            }

            $this->loadModel('Apresentacoes');
            $hasApresentacao = $this->Apresentacoes->find()->where(['codigo' => $codigo_apresentacao])->first();
            if (empty($hasApresentacao)) {
                $error[] = "Apresentação não encontrada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($frequencia_dias)) {
                $error[] = "Frequência de dias não informada.";
                $this->set(compact('error'));
                return null;
            }

            if ($frequencia_dias == 2) {
                if (empty($frequencia_dias_intercalados)) {
                    $error[] = "Frequência de dias intercalados não informado";
                    $this->set(compact('error'));
                    return null;

                }
            }

            if (empty($frequencia_horarios)) {
                $error[] = "Frequência de horarios não informada.";
                $this->set(compact('error'));
                return null;
            }

            if (empty($frequencia_uso)) {
                $error[] = "Frequência de uso não informada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($uso_continuo)) {
                $error[] = "Uso continuo não informado";
                $this->set(compact('error'));
                return null;
            }

            if ($uso_continuo == 2) {
                if (empty($periodo_tratamento_inicio)) {
                    $error[] = "Inicio do periodo de tratamento não informado";
                    $this->set(compact('error'));
                    return null;
                }

                if (empty($periodo_tratamento_termino)) {
                    $error[] = "Termino do periodo de tratamento não informado";
                    $this->set(compact('error'));
                    return null;
                }
            }

            if (empty($dias_da_semana)) {
                $error[] = "Dias da semana não informados";
                $this->set(compact('error'));
                return null;
            } else {
                $dias_da_semana = implode(",", $dias_da_semana);
            }

            if (empty($horario_inicio_uso)) {
                $error[] = "Horario de inicio do uso não informado";
                $this->set(compact('error'));
                return null;
            }

            if (empty($quantidade)) {
                $error[] = "Quantidade não informada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($recomendacao_medica)) {
                $error[] = "Recomendação médica não informada";
                $this->set(compact('error'));
                return null;
            }

            if (empty($foto_receita)) {
                $error[] = "Foto da receita não informada";
                $this->set(compact('error'));
                return null;

            }

            // configura a pasta de upload dos arquivos
            $dados = array(
                'file' => $foto_receita,
                'prefix' => 'nina',
                'type' => 'base64'
            );

            // envia a foto para o systemstorage
            $url_imagem = Comum::sendFileToServer($dados);
            if($url_imagem){
                $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});
            }
            
            $programacao_medicamento->codigo_medicamentos = $codigo_medicamentos;
            $programacao_medicamento->codigo_apresentacao = $codigo_apresentacao;
            $programacao_medicamento->frequencia_dias = $frequencia_dias;
            $programacao_medicamento->frequencia_dias_intercalados = $frequencia_dias_intercalados;
            $programacao_medicamento->frequencia_horarios = $frequencia_horarios;
            $programacao_medicamento->uso_continuo = $uso_continuo;
            $programacao_medicamento->frequencia_uso = $frequencia_uso;
            $programacao_medicamento->periodo_tratamento_inicio = $periodo_tratamento_inicio;
            $programacao_medicamento->periodo_tratamento_termino = $periodo_tratamento_termino;
            $programacao_medicamento->dias_da_semana = $dias_da_semana;
            $programacao_medicamento->horario_inicio_uso = $horario_inicio_uso;
            $programacao_medicamento->quantidade = $quantidade;
            $programacao_medicamento->recomendacao_medica = $recomendacao_medica;
            $programacao_medicamento->codigo_usuario_alteracao = $usuario;
            $programacao_medicamento->ativo = $ativo;

            if (!empty($caminho_image['path'])) {
                $programacao_medicamento->foto_receita = FILE_SERVER . $caminho_image['path'];
            }

            $this->UsuariosMedicamentos->save($programacao_medicamento);

        }//fim put
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }

        // saída
        if (!empty($programacao_medicamento)) {
            $this->set(compact('programacao_medicamento'));
        } else {
            $this->set(compact('error'));
        }
    }

    /***
     * Inativa a programacao de medicamentos do usuario
     * @return |null
     */

    public function deleteProgramacaoMedicamentos($codigo=null)
    {
        if ($this->request->is(['delete'])) { 
            
            if (empty($codigo)) {
                $error[] = 'Parâmetro código é obrigatório.';
            }
            
            //Verifica se codigo existe
            $this->loadModel('UsuariosMedicamentos');

            $usuarios_medicamentos = $this->UsuariosMedicamentos->find()->where(['codigo' => $codigo])->first();
            if (empty($usuarios_medicamentos)) {
                $error[] = "Código não encontrado.";
            }

            if(!empty($error)) {
                $this->set(compact('error'));            
            }else {
                
                $data = array(); 

                //dados que serão modificados
                $usuario = $this->Auth->user('codigo');
                $d['ativo'] = 0;
                $d['codigo_usuario_alteracao'] = $usuario;
                $d['data_alteracao'] = date('Y-m-d H:i:s');

                $entity = $this->UsuariosMedicamentos->patchEntity($usuarios_medicamentos, $d);
                
                if($this->UsuariosMedicamentos->save($entity)){
                    $data[] = "Operação realizada com sucesso.";
                    $this->set(compact('data'));
                } else {
                    $error[] = "Falha ao deletar";
                    //$error[] = $entity->errors();
                    $this->set(compact('error'));
                }

            }
        }//fim delete
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }
    }

    /***
     * Recupera a programacao de medicamentos do usuario
     * @return |null
     */

    public function getProgramacaoMedicamentos()
    {
        $data_list = [];
        $error = [];
        // Verifica se é get
        if ($this->request->is(['get'])) {

            $usuario = $this->Auth->user('codigo');
            $this->loadModel('UsuariosMedicamentos');
            $data_list = $this->UsuariosMedicamentos->getListaMedicamentos($usuario);
        } //fim get
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }

        // saída
        if (!empty($data_list)) {
            $this->set('data', ['lista'=>$data_list, 'mensagem'=>'']);
        } else {
            $this->set('data', ['lista'=>[], 'mensagem'=>$error]);
        }
    }


    /***
     * API para retornar a frequência de uso de medicamentos
     */
    public function getFrequenciaUso()
    {
        $arr = [];

        for ($i = 24; $i >= 1; $i--) {
            $frequencia = 24 % $i;

            if(!$frequencia){
                if ($i == 1)
                    $var = ['descricao'=>"A cada $i hora", 'id' => $i];
                else if($i == 24)
                    $var = ['descricao'=>"Diário", 'id' => $i];

                else
                    $var = ['descricao'=>"A cada $i horas", 'id' => $i];

                array_push($arr, $var);
            }


        }

        $this->set('data', $arr);
    }
}
