<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Utils\Comum;
use App\Model\Table\PosSwtFormParticipantesTable;
use Exception;

/**
 * PosSwtFormRespondido Controller
 *
 * @property \App\Model\Table\PosSwtFormRespondidoTable $PosSwtFormRespondido
 *
 * @method \App\Model\Entity\PosSwtFormRespondido[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PosSwtFormRespondidoController extends ApiController
{
    public $connection;
    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow();

        $this->loadModel('PosSwtFormQuestao');
        $this->loadModel('PosMetas');
        $this->loadModel('Usuario');
        $this->loadModel('UsuariosResponsaveis');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        // seta para o retorno
        $data = array();
        $error = array();

        //verifica se é post
        if ($this->request->is('post')) {

            //pega os dados que veio do post
            $dados = $this->request->getData();
            // debug($dados);exit;

            //variavel com os erros caso existam
            $validacoes = $this->validacao_form_resp($dados);


            //verifica se existe erros
            if (isset($validacoes['error'])) {
                $error[]  = $validacoes['validations'];
            } //fim else das validacoes
            else {

                //pega o codigo do usuario logado
                $dados_token = $this->getDadosToken();
                $codigo_usuario = $dados_token->codigo_usuario;

                //pega o codigo do setor do usuario logado
                $dados_usuario_alocacao = $this->Usuario->obterDadosDoUsuarioAlocacao($codigo_usuario);
                $codigo_setor = $dados_usuario_alocacao->FuncionarioSetorCargo['codigo_setor'];

                // debug($codigo_setor);exit;


                //metodo responsavel para gravar os dados
                $return = $this->PosSwtFormRespondido->addFormResposta($codigo_usuario, $codigo_setor, $dados);
                // debug($return); exit;
                if (isset($return['error'])) {
                    $error[] = $return['error'];
                } else {

                    //calcular o resultado do walk talk
                    $resultado_calc = $this->PosSwtFormRespondido->calculaResultadoForm($return);


                    if ($dados['form_tipo'] == 1) {
                        $data['texto']['titulo'] = 'Índice de percepçãp(IP)';
                        $data['texto']['sub_titulo'] = 'Com base nas informações preenchidas, o Índice de Percepção de Riscos & Impactos (IP) foi:';
                    } else if ($dados['form_tipo'] == 2) {
                        $data['texto']['titulo'] = 'Qualidade do Walk & Talk (IQO)';
                        $data['texto']['sub_titulo'] = 'Com base na sua análise do Gestor, o Índice de Qualidade da Observação (iQO) foi:';
                    }

                    $data['resultado']['resultado'] = $resultado_calc['resultado'];
                    $data['resultado']['media_area'] = $resultado_calc['media_area'];
                    $data['resultado']['media_cliente'] = $resultado_calc['media_cliente'];

                    // debug($return);
                    // exit;
                } //fim verificacao se deu algum erro ao inserir as respostas ou as acoes de melhorias
            }
        } else {
            //erro de metodo
            $error[]   = 'Erro de metodo aguardando POST';
        }

        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }
    } //fim add

    /**
     * [validacao_usuario description]
     *
     * metodo para validar os dados do post enviado para gravar o walk talk
     *
     * validações:
     *     valida a estrtura do json
     *     valida os dados obrigatórios
     *         - observador
     *         - local
     *         - resumo [data e hora]
     *         - participantes
     *         - facilitador
     *         - form tipo (1-> safety, 2-> qualidade)
     *         - form (respostas do formulario)
     *
     * @param  [type] $dados [description]
     * @return [type]        [description]
     */
    public function validacao_form_resp($dados)
    {
        //variavel de erro
        $error = array();

        //valida se tem a estrutura do json
        if (empty($dados)) {
            $error['error'] = true;
            $error['validations']['dados'] = 'Não existem dados a serem processados.';
        }

        if (!isset($dados['form_codigo'])) {
            $error['error'] = true;
            $error['validations']['form_codigo'] = 'Necessário o indice: (form_codigo).';
        } else {
            if (empty($dados['form_codigo'])) {
                $error['error'] = true;
                $error['validations']['form_codigo'] = 'Necessário o valor: (form_codigo).';
            } else {

                if (!isset($dados['form_tipo'])) {
                    $error['error'] = true;
                    $error['validations']['form_tipo'] = 'Necessário o indice: (form.form_tipo).';
                } else {
                    if (empty($dados['form_tipo'])) {
                        $error['error'] = true;
                        $error['validations']['form_tipo'] = 'Necessário o valor: (form.form_tipo).';
                    } else {

                        if (!isset($dados['codigo_usuario'])) { // para o tipo 1 é o observador
                            $error['error'] = true;
                            $error['validations']['codigo_usuario'] = 'Necessário o indice: (codigo_usuario).';
                        } else {
                            if (empty($dados['codigo_usuario'])) {
                                $error['error'] = true;
                                $error['validations']['codigo_usuario'] = 'Necessário o valor: (codigo_usuario).';
                            }
                        }

                        //formulario walk talk
                        if ($dados['form_tipo'] == "1") {

                            // if(!isset($dados['local_observacao'])) {
                            //     $error['error'] = true;
                            //     $error['validations']['local_observacao'] = 'Necessário o indice: (local_observacao).';
                            // }
                            // else {
                            //     if(empty($dados['local_observacao'])){
                            //         $error['error'] = true;
                            //         $error['validations']['local_observacao'] = 'Necessário o valor: (local_observacao).';
                            //     }
                            // }


                            if (!isset($dados['resumo'])) {
                                $error['error'] = true;
                                $error['validations']['resumo'] = 'Necessário o indice: (resumo).';
                            } else {
                                if (empty($dados['resumo'])) {
                                    $error['error'] = true;
                                    $error['validations']['resumo'] = 'Necessário o valor: (resumo).';
                                } else {
                                    if (!isset($dados['resumo']['data_obs'])) {
                                        $error['error'] = true;
                                        $error['validations']['resumo']['data_obs'] = 'Necessário o indice: (resumo.data_obs).';
                                    } else {
                                        if (empty($dados['resumo']['data_obs'])) {
                                            $error['error'] = true;
                                            $error['validations']['resumo']['data_obs'] = 'Necessário o valor: (resumo.data_obs).';
                                        }
                                    }

                                    if (!isset($dados['resumo']['hora_obs'])) {
                                        $error['error'] = true;
                                        $error['validations']['resumo']['hora_obs'] = 'Necessário o indice: (resumo.hora_obs).';
                                    } else {
                                        if (empty($dados['resumo']['hora_obs'])) {
                                            $error['error'] = true;
                                            $error['validations']['resumo']['hora_obs'] = 'Necessário o valor: (resumo.hora_obs).';
                                        }
                                    }

                                    if (!isset($dados['resumo']['desc_atividade'])) {
                                        $error['error'] = true;
                                        $error['validations']['resumo']['desc_atividade'] = 'Necessário o indice: (resumo.desc_atividade).';
                                    } else {
                                        if (empty($dados['resumo']['desc_atividade'])) {
                                            $error['error'] = true;
                                            $error['validations']['resumo']['desc_atividade'] = 'Necessário o valor: (resumo.desc_atividade).';
                                        }
                                    }

                                    if (!isset($dados['resumo']['codigo_cliente_localidade'])) {
                                        $error['error'] = true;
                                        $error['validations']['resumo']['codigo_cliente_localidade'] = 'Necessário o indice: (resumo.codigo_cliente_localidade).';
                                    } else {
                                        if (empty($dados['resumo']['codigo_cliente_localidade'])) {
                                            $error['error'] = true;
                                            $error['validations']['resumo']['codigo_cliente_localidade'] = 'Necessário o valor: (resumo.codigo_cliente_localidade).';
                                        }
                                    }

                                    if (!isset($dados['resumo']['descricao'])) {
                                        $error['error'] = true;
                                        $error['validations']['resumo']['descricao'] = 'Necessário o indice: (resumo.descricao).';
                                    } else {
                                        if (empty($dados['resumo']['descricao'])) {
                                            $error['error'] = true;
                                            $error['validations']['resumo']['descricao'] = 'Necessário o valor: (resumo.descricao).';
                                        }
                                    }
                                }
                            } //fim resumo

                            if (!isset($dados['participantes'])) {
                                $error['error'] = true;
                                $error['validations']['participantes'] = 'Necessário o indice: (participantes).';
                            } else {
                                if (empty($dados['participantes'])) {
                                    $error['error'] = true;
                                    $error['validations']['participantes'] = 'Necessário o valor: (participantes).';
                                }
                            }

                            if (!isset($dados['facilitador'])) {
                                $error['error'] = true;
                                $error['validations']['facilitador'] = 'Necessário o indice: (facilitador).';
                            } else {
                                if (empty($dados['facilitador'])) {
                                    $error['error'] = true;
                                    $error['validations']['facilitador'] = 'Necessário o valor: (facilitador).';
                                }
                            }
                        } // fim tipo walk talk
                    } //fim else empty form_tipo
                } //fim else se existe form tipo

            }
        } //fim form


        if (!isset($dados['respostas'])) {
            $error['error'] = true;
            $error['validations']['respostas'] = 'Necessário o indice: (respostas).';
        } else {
            if (empty($dados['respostas'])) {
                $error['error'] = true;
                $error['validations']['respostas'] = 'Necessário o valor: (respostas).';
            } else {
                //validar se todas as perguntas tem valor do formulario
                //formata as questoes
                $array_questoes = array();
                foreach ($dados['respostas'] as $arr_titulo) {
                    $codigo_titulo = $arr_titulo['codigo_titulo'];
                    foreach ($arr_titulo['questao'] as $resp) {
                        $array_questoes[$codigo_titulo][$resp['codigo']] = $resp['codigo'];
                    }
                }

                //busca as questoes cadastradas do formulario
                $questoes = $this->PosSwtFormQuestao->find()->select(['codigo', 'codigo_form_titulo'])->where(['codigo_form' => $dados['form_codigo'], 'ativo' => 1])->enableHydration(false)->toArray();
                // debug($questoes);exit;
                //varre as questoes passadas
                $valida_codigos_respostas = false;
                //varre as questoes
                foreach ($questoes as $val_quest) {
                    if (!isset($array_questoes[$val_quest['codigo_form_titulo']][$val_quest['codigo']])) {
                        $valida_codigos_respostas = true;
                        $error['error'] = true;
                        $error['validations']['respostas'][$val_quest['codigo']] = 'Necessário o valor: (respostas: ' . $val_quest['codigo'] . ");";
                    }
                }
            }
        }

        return $error;
    } //fim validacao_form_resp

    /**
     * [swtHome metodo para apresentar os cards da home]
     * @return [type] [description]
     */
    public function getHome()
    {

        $data = [];

        //verifica se é post
        if ($this->request->is('post')) {

            //pega os dados que veio do post
            $filtros = $this->request->getData();


            ##############################VERIFICACAO DOS FILTROS ##############################
            //verifica se tem o filtro de data
            if (!isset($filtros['periodo_de'])) {
                $filtro_periodo_de = date('Y-m-01 00:00:00');
            } else if (empty($filtros['periodo_de'])) {
                $filtro_periodo_de = date('Y-m-01 00:00:00');
                // $filtro_periodo_de = date('Y-m-t 23:59:59');
            } else {
                $filtro_periodo_de = Comum::formataData($filtros['periodo_de'], 'dmy', 'ymd') . " 00:00:00";
            }
            //ate
            if (!isset($filtros['periodo_ate'])) {
                $filtro_periodo_ate = date('Y-m-t 23:59:59');
            } else if (empty($filtros['periodo_ate'])) {
                $filtro_periodo_ate = date('Y-m-t 23:59:59');
            } else {
                $filtro_periodo_ate = Comum::formataData($filtros['periodo_ate'], 'dmy', 'ymd') . " 23:59:59";
            }

            $dados_token = $this->getDadosToken();
            $codigo_usuario = $dados_token->codigo_usuario;

            if (isset($filtros['por_autor'])) {
                switch ($filtros['por_autor']) {
                    case '0': //todos
                        break;
                    case '1': //area

                        //pega o codigo do setor do usuario logado
                        $dados_usuario_alocacao = $this->Usuario->obterDadosDoUsuarioAlocacao($codigo_usuario);
                        $codigo_setor = $dados_usuario_alocacao->FuncionarioSetorCargo['codigo_setor'];

                        $filtros['codigo_setor'] = $codigo_setor;

                        break;
                    case '2': //somente meus
                        $filtros['codigo_usuario'] = $codigo_usuario;
                        break;
                    default:
                        $filtros['codigo_usuario'] = $codigo_usuario;
                        break;
                }
            } //veifica se tem filtro por_autor

            $filtros['periodo_de'] = $filtro_periodo_de;
            $filtros['periodo_ate'] = $filtro_periodo_ate;
            ############################## FIM VERIFICACAO DOS FILTROS ##############################

            // debug($filtros);
            // debug(array($filtro_periodo_de,$filtro_periodo_ate));
            // exit;


            //verifica se tem dados do usuario
            if (empty($codigo_usuario)) {
                $error = "Necessário estar logado no app";
                $this->set(compact('error'));
            }

            /**********METAS******************/
            $dados_meta = $this->PosMetas->getMetaArea($codigo_usuario);

            $total_walk_talk = $this->PosSwtFormRespondido->find()->where([
                "(PosSwtFormRespondido.codigo_usuario_observador = $codigo_usuario)",
                'PosSwtFormRespondido.ativo = 1',
                'PosSwtFormRespondido.codigo_form_respondido_swt IS NULL'
            ])
                ->all()
                ->count();

            $data_atual     = strtotime(date("Y-m-d"));
            $data_inclusao  = strtotime($dados_meta['data_inclusao']);
            $data_alteracao = !is_null($dados_meta['data_alteracao']) ? strtotime($dados_meta['data_alteracao']) : '';

            if (!is_null($dados_meta['data_alteracao'])) { //Se a meta foi alterada, considerar o campo data_alteracao ao inves do data_inclusao

                $expira_em = date('d/m/Y', strtotime("+" . $dados_meta['dia_follow_up'] . " months", $data_alteracao));

                if ($data_atual < $data_alteracao) {

                    $data['meta'] = [
                        'msg' => 'SUA META' . ($dados_meta !== null ? (' ATÉ ' . $expira_em) : ''),
                        'total' => ($dados_meta === null ? 'X' : $total_walk_talk) . '/' . ($dados_meta['valor'] ?: 0) . ' Walk & Talk realizados'
                    ];
                } else {

                    $data['meta'] = [
                        'msg' => 'SUA META' . ($dados_meta !== null ? (' expirou em ' . date("d/m/Y", $data_alteracao)) : ''),
                        'total' => ($dados_meta === null ? 'X' : $total_walk_talk) . '/' . ($dados_meta['valor'] ?: 0) . ' Walk & Talk realizados'
                    ];
                }
            } else {

                $expira_em = date('d/m/Y', strtotime("+" . $dados_meta['dia_follow_up'] . " months", $data_inclusao));

                if ($data_atual < $data_inclusao) {

                    $data['meta'] = [
                        'msg' => 'SUA META' . ($dados_meta !== null ? (' ATÉ ' . $expira_em) : ''),
                        'total' => ($dados_meta === null ? 'X' : $total_walk_talk) . '/' . ($dados_meta['valor'] ?: 0) . ' Walk & Talk realizados'
                    ];
                } else {

                    $data['meta'] = [
                        'msg' => 'SUA META' . ($dados_meta !== null ? (' expirou em ' . date("d/m/Y", $data_inclusao)) : ''),
                        'total' => ($dados_meta === null ? 'X' : $total_walk_talk) . '/' . ($dados_meta['valor'] ?: 0) . ' Walk & Talk realizados'
                    ];
                }
            }

            /**********FIM METAS******************/

            //busca se é usuario responsavel
            $bool_usuario_responsavel = false;

            //pega o codigo do cliente que o suuario esta vinculado
            $usuario_cliente = $this->Usuario->obterClientePorCodigoUsuario($codigo_usuario);

            $usuario_responsavel = $this->UsuariosResponsaveis->find()->where(['codigo_usuario' => $codigo_usuario, 'codigo_cliente' => $usuario_cliente[0]['codigo']])->first();
            if (!empty($usuario_responsavel)) {
                $bool_usuario_responsavel = true;
            }

            $filtros['usuario_responsavel'] = $bool_usuario_responsavel;
            $filtros['codigo_cliente_localidade'] = $usuario_cliente[0]['codigo'];

            $codigo_unidades_usuario_logado = $this->Usuario->obterUnidadesUsuarioLogado($codigo_usuario); //pega as unidades do usuario logado

            $filtros['codigo_unidades'] = $codigo_unidades_usuario_logado;

            $swt_home = $this->PosSwtFormRespondido->getSwtAll($filtros);


            foreach ($swt_home as $key => $dados) {

                $swt_home[$key]['participantes'] = [];

                $this->PosSwtFormParticipantes = TableRegistry::getTableLocator()->get('PosSwtFormParticipantes');

                $participantes = $this->PosSwtFormParticipantes->getByCodigoFormRespondido($dados['codigo']);

                foreach ($participantes as $participante) {

                    $swt_home[$key]['participantes'][] = $participante;
                }


                $strSql = "select psfam.codigo_acao_melhoria from  pos_swt_form_acao_melhoria psfam
                        INNER join acoes_melhorias am on am.codigo = psfam.codigo_acao_melhoria and am.codigo_acoes_melhorias_status <> 5
                        where psfam.codigo_form_respondido = " . $dados['codigo'] . " ";
                //Retorna os dados da consulta ao banco
                $result = $this->connection->execute($strSql)->fetchAll('assoc');

                if ($dados['codigo_form_respondido_swt'] > 0 && count($result) > 0) {
                    $swt_home[$key]['status_codigo'] = 3;
                    $swt_home[$key]['status_desc'] = "Em andamento";
                    $swt_home[$key]['status_cor'] = "#5CB3FF";
                }

                if (!empty($dados['codigo_usuario_facilitador'])) {

                    if (!is_null($dados['codigo_usuario_facilitador'])) {
                        if ($dados['codigo_usuario_facilitador'] != $codigo_usuario) {
                            unset($swt_home[$key]);
                        }
                    }
                }
            }

            $data['swt_home'] = array_values($swt_home);
        } //fim is post

        // debug($dados);
        // exit;
        $this->set(compact('data'));
    } //fim home

    /**
     * [getDetalhes pega os dados do swt com as perguntas e respostas]
     * @param  [type] $codigo [description]
     * @return [type]         [description]
     */
    public function getDetalhes($codigo_form_respondido)
    {
        $data = [];

        //valida se tem os parametros corretamente
        if (empty($codigo_form_respondido)) {
            $error = "Parametro vazio (form)";
            $this->set(compact('error'));
            return;
        }

        $dados_token = $this->getDadosToken();
        $codigo_usuario = $dados_token->codigo_usuario;

        $detalhes = $this->PosSwtFormRespondido->getSwtDetalhe($codigo_usuario, $codigo_form_respondido);
        // debug($detalhes);
        // exit;

        if (isset($detalhes['error'])) {
            $error = $detalhes['error'];
            $this->set(compact('error'));
            return;
        }

        $data = $detalhes;

        $this->set(compact('data'));
    } //fim getDetalhes

}
