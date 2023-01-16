<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Http\Client;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\Utility\Hash;

use InvalidArgumentException;

/**
 * FichasAssistenciais Controller
 *
 *
 * @method \App\Model\Entity\FichasAssistenciai[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FichasAssistenciaisController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fichasAssistenciais = $this->paginate($this->FichasAssistenciais);

        $this->set(compact('fichasAssistenciais'));
    }

    /**
     * View method
     *
     * @param string|null $id Fichas Assistenciai id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $codigo_usuario, int $codigo_pedido_exames)
    {

        //variavel auxiliar para o retorno do metodo
        $data = array();

        $dados = $this->FichasAssistenciais->getFichaAssistencialQuestoes($codigo_pedido_exames, $codigo_usuario);
        //verifica se existe os dados da ficha clinica
        if(!empty($dados)) {
            $data = $dados;
        }

        $this->set(compact('data'));

    }// fim view

     /**
     * [getDatadosFichaAssistencial metodo para pegar os dados cadastrados da ficha assistencial]
     *
     * @param  int    $codigo_ficha_assisstencial [description]
     * @return [type]                       [description]
     */
    public function getDatadosFichaAssistencial(int $codigo_pedido_exame)
    {

        //variavel auxiliar para o retorno do metodo
        $data = array();

        //pega os dados da ficha clinica
        $ficha_assistencial = $this->FichasAssistenciais->find()->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();

        if(empty($ficha_assistencial)) {
            $error[] = "Codigo da ficha assistencial não encontrado!";
            $this->set(compact('error'));
            return;
        }
        $ficha_assistencial = $ficha_assistencial->toArray();
        $codigo_ficha_assistencial = $ficha_assistencial['codigo'];

        //formata a hora pois o cake colocar a funcao dele onde bagunça
        $ficha_assistencial['hora_inicio_atendimento'] = $ficha_assistencial['hora_inicio_atendimento']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);
        $ficha_assistencial['hora_fim_atendimento'] = $ficha_assistencial['hora_fim_atendimento']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);

        //monta os campos formatados
        $ficha_assistencial['pa'] = null;
        if(!is_null($ficha_assistencial['pa_sistolica']) || !is_null($ficha_assistencial['pa_diastolica'])) {
            $ficha_assistencial['pa'] = $ficha_assistencial['pa_sistolica'].' X '.$ficha_assistencial['pa_diastolica'];
        }
        $ficha_assistencial['peso'] =null;
        if(!is_null($ficha_assistencial['peso_kg']) || !is_null($ficha_assistencial['peso_gr'])) {
            $ficha_assistencial['peso'] = $ficha_assistencial['peso_kg'].'.'.$ficha_assistencial['peso_gr'];
        }
        $ficha_assistencial['altura'] = null;
        if(!is_null($ficha_assistencial['altura_mt']) || !is_null($ficha_assistencial['altura_cm'])) {
            $ficha_assistencial['altura'] = $ficha_assistencial['altura_mt'].'.'.$ficha_assistencial['altura_cm'];
        }

        // debug($ficha_assistencial);exit;

        $imp_atestado = false;
        $imp_receita = false;

        //pega os dados de atestado
        $this->loadModel('Atestados');
        $atestado = $this->Atestados->find()->where(['Atestados.codigo' => $ficha_assistencial['codigo_atestado']])->first();
        //verifica se tem atestados
        $dados_atestado = array();
        if(!empty($atestado)) {

            $imp_atestado = true;

            //seta o indice
            $dados_atestado['atestado'] = $atestado->toArray();

            // debug($dados_atestado);exit;

            //$dados_atestado['atestado']['data_afastamento_periodo'] = $dados_atestado['atestado']['data_afastamento_periodo']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);
            $dados_atestado['atestado']['data_afastamento_periodo'] = date_format(date_create($dados_atestado['atestado']['data_afastamento_periodo']),"d/m/Y");
            $dados_atestado['atestado']['data_retorno_periodo'] = date_format(date_create($dados_atestado['atestado']['data_retorno_periodo']),"d/m/Y");

            //formatacao de horas
            if(!empty($dados_atestado['atestado']['hora_afastamento'])) {
                $dados_atestado['atestado']['hora_afastamento'] = $dados_atestado['atestado']['hora_afastamento']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);;
            }
            if(!empty($dados_atestado['atestado']['hora_retorno'])) {
                $dados_atestado['atestado']['hora_retorno'] = $dados_atestado['atestado']['hora_retorno']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);;
            }

            if($dados_atestado['atestado']['habilita_afastamento_em_horas']) {
                $dados_atestado['atestado']['habilita_afastamento_em_horas'] = 'Sim';
            }

            $this->loadModel('Profissional');
            $medicos = $this->Profissional->find()->where(['codigo' => $atestado['codigo_medico']])->first();
            $dados_atestado['atestado']['nome_medico'] = $medicos->nome;

            //pega o cid
            $this->loadModel('AtestadosCid');
            $joinCid = array(
                'table' => 'cid',
                'alias' => 'Cid',
                'type' => 'INNER',
                'conditions' => 'Cid.codigo = AtestadosCid.codigo_cid'
            );
            $atestado_cid = $this->AtestadosCid->find()
                ->select(['Cid.descricao'])
                ->join($joinCid)
                ->where(['codigo_atestado' => $atestado['codigo']])
                ->hydrate(false)
                ->all();
            //verifica se tem dados
            if(!empty($atestado_cid)) {

                $dados_atestado['atestado_cid'] = array();
                foreach($atestado_cid->toArray() AS $cids) {
                    $dados_atestado['atestado_cid'][] = $cids['Cid']['descricao'];
                }//foreach

            }//fim if


        }//fim atestados

        // debug($dados_atestado);exit;

        //pega o codigo do pedido de exame
        $codigo_pedido_exames = $ficha_assistencial['codigo_pedido_exame'];
        $codigo_usuario = $ficha_assistencial['codigo_usuario_inclusao'];

        //monta a respostas das questoes
        $this->loadModel('FichasAssistenciaisRespostas');

        // organiza as respostas em um array no padrão que a view necessita para se relacionar com $this->data
        $respostas = $this->FichasAssistenciaisRespostas->find()->where(['codigo_ficha_assistencial' => $codigo_ficha_assistencial])->hydrate(false)->toArray();


        $dados = array();
        //varre as respostas
        foreach ($respostas as $key => $value) {
            //verifica a resposta
            if(Comum::isJson($value['resposta'])) {
                $value['resposta'] = (array)json_decode($value['resposta']);
                if(count($value['resposta']) == 1) {
                    $value['resposta'] = $value['resposta'][key($value['resposta'])];
                }
            }//fim resposta
            $dados['FichaAssistencialResposta.'.$value['codigo_ficha_assistencial_questao'].'_resposta'] = $value['resposta'];

            //verifica a o campo livre
            if(!empty($value['campo_livre'])) {

                if(Comum::isJson($value['campo_livre'])) {
                    // $dados['FichaAssistencialResposta.'.$value['codigo_ficha_assistencial_questao'].'_resposta_campo_livre'][$value['codigo_ficha_assistencial_questao']] = (array)Comum::jsonToArray($value['campo_livre']);
                    $dados['FichaAssistencialResposta.'.$value['codigo_ficha_assistencial_questao'].'_resposta_campo_livre'][$value['codigo_ficha_assistencial_questao']] = json_decode($value['campo_livre']);
                } else {
                    $dados['FichaAssistencialResposta.'.$value['codigo_ficha_assistencial_questao'].'_resposta_campo_livre'][$value['codigo_ficha_assistencial_questao']] = $value['campo_livre'];
                }

            }//fim campolivre
        }//fim foreach
        // debug($dados); exit;

        //pega os dados da ficha clinica
        $formulario = $this->FichasAssistenciais->getFichaAssistencialQuestoes($codigo_pedido_exames, $codigo_usuario);

        //varre as questoes para colocar as respostas
        foreach ($formulario['formulario'] as $keyForm => $form) {
            //varre os grupos
            foreach($form AS $keyGrupo => $tipoGrupo) {

                if(!isset($tipoGrupo['questao'])) {
                    continue;
                }

                //varre as questoes
                foreach($tipoGrupo['questao'] AS $keyQuestao => $questao) {

                    //configura o formulario
                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = null;
                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = array();

                    //verifica se tem codigo da questao
                    if(!empty($questao['codigo']) && is_int($questao['codigo'])) {

                        //verifica se tem a resposta
                        if(isset($dados[$questao['name']])) {


                            //verificacao para impressao da receita
                            if($questao['codigo'] == 177 && $dados[$questao['name']] == 1) {
                                $imp_receita = true;
                            }

                            //seta as respostas
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $dados[$questao['name']];
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = array();

                            //verifica se tem campo livre
                            if(isset($dados['FichaAssistencialResposta.'.$questao['codigo'].'_resposta_campo_livre'][$questao['codigo']])) {
                                //seta o campo livre
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = $dados['FichaAssistencialResposta.'.$questao['codigo'].'_resposta_campo_livre'][$questao['codigo']];

                            }//fim

                        }//verifica se tem o nome

                        // debug($questao);
                        // exit;


                        //verifica se tem subquestao
                        if(!empty($questao['sub_questao'])) {

                            //varre a subquestao
                            foreach ($questao['sub_questao'] as $keySub => $sub) {

                                //configura o formulario
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta'] = null;
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = array();

                                //verifica se tem a resposta
                                if(isset($dados[$sub['name']])) {

                                    //seta as respostas
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta'] = $dados[$sub['name']];

                                    // debug(array('formulario',$keyForm,$keyGrupo,'questao',$keyQuestao,'sub_questao',$keySub,'resposta'));
                                    // debug($dados[$sub['name']]);
                                    // exit;
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = array();

                                    //verifica se tem campo livre
                                    if(isset($dados['FichaAssistencialResposta.'.$sub['codigo'].'_resposta_campo_livre'][$sub['codigo']])) {
                                        //seta o campo livre
                                        $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = $dados['FichaAssistencialResposta.'.$sub['codigo'].'_resposta_campo_livre'][$sub['codigo']];

                                    }//fim

                                }//verifica se tem o nome

                            }//fim subquestao

                        }//fim verificacao se tem sub questao

                    }//vim verificacao codigo da questao
                    else {

                        //verifica se é atestado o grupo
                        // if($keyGrupo == 9) {
                        if($tipoGrupo['descricao'] == 'ATESTADO MÉDICO') {

                            //Resposta para atestado medico igual a '0' como default
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = 0;

                            $separa_nome = explode(".",$questao['name']);

                            if(!isset($separa_nome[2])) {
                                continue;
                            }

                            if(isset($dados_atestado['atestado'][$separa_nome[2]])) {
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $dados_atestado['atestado'][$separa_nome[2]];
                            }

                            //verifica se tem cid dos atestados
                            if(isset($dados_atestado['atestado_cid'])) {
                                if(!empty($dados_atestado['atestado_cid'])) {
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = $dados_atestado['atestado_cid'];
                                }

                            }

                            //verifica se tem subquestao
                            if(!empty($questao['sub_questao'])) {

                                //varre a subquestao
                                foreach ($questao['sub_questao'] as $keySub => $sub) {

                                    //configura o formulario
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta'] = null;
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = array();

                                    $separa_nome_sub = explode(".",$sub['name']);
                                    if(!isset($separa_nome_sub[2])) {
                                        continue;
                                    }

                                    if(isset($dados_atestado['atestado'][$separa_nome_sub[2]])) {
                                        $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta'] = $dados_atestado['atestado'][$separa_nome_sub[2]];
                                    }

                                }//fim subquestao

                            }//fim verificacao se tem sub questao

                            // debug($separa_nome);
                        }
                        else {
                            //grupo header vem os campos da ficha clinica
                            //separa para pegar o indice 1 que tem o nome do campo
                            $separa_nome = explode(".",$questao['name']);

                            if(!isset($separa_nome[1])) {
                                continue;
                            }

                            if(isset($ficha_assistencial[$separa_nome[1]])) {
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $ficha_assistencial[$separa_nome[1]];
                            }
                        }


                    }

                }//fim questoes

            }//fim tipogrupo
        }//fim formulario

        //seta para as impressoes de atestados e receitas medicas
        $formulario['imp_atestado'] = $imp_atestado;
        $formulario['imp_receita'] = $imp_receita;

        // debug($formulario);
        // exit;
        $data = $formulario;

        $this->set(compact('data'));
        return;


    }//fim getDatadosFichaAssistencial

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $data = array();

        //verfica se é post
        if ($this->request->is('post')) {

            //abrir transacao
            $conn = ConnectionManager::get('default');

            try{

                //abre a transacao
                $conn->begin();

                //seta a variavel para os dados
                $dados = $this->request->getData();

                //seta o codigo do usuario que fez o post
                $codigo_usuario = $dados['codigo_usuario'];
                $codigo_pedido_exame = $dados['FichaAssistencial']['codigo_pedido_exame'];

                //seta o codigo do usuario que esta incluindo
                $dados['FichaAssistencial']['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['FichaAssistencial']['data_inclusao'] = date('Y-m-d H:i:s');
                $dados['FichaAssistencial']['codigo_empresa'] = 1;

                //verifica se existe o codigo_pedido_exame
                $this->loadModel("PedidosExames");
                $pedido_exame = $this->PedidosExames->find()->where(['codigo' => $codigo_pedido_exame])->first();
                if(empty($pedido_exame)) {
                    throw new Exception("Favor passar um pedido de exames valido!");
                }

                //busca o nome do usuatio
                $this->loadModel("Usuario");
                $usuario = $this->Usuario->find()->select(["nome","codigo_medico"])->where(['codigo' => $codigo_usuario])->first()->toArray();

                $codigo_medico = null;
                if(!empty($usuario['codigo_medico'])) {
                    $codigo_medico = $usuario['codigo_medico'];
                }
                else {

                    //pega o codigo do aso
                    $this->loadModel('Configuracao');
                    $codigo_exame = $this->Configuracao->getChave("FICHA_ASSISTENCIAL");
                    // debug(array($codigo_exame, $codigo_pedido_exame));exit;

                    //pega o pedido
                    $this->loadModel("ItensPedidosExames");
                    $medico = $this->ItensPedidosExames->find()->select(['codigo_medico'])->where(['codigo_exame IN ('.$codigo_exame.')' , 'codigo_pedidos_exames' => $codigo_pedido_exame])->first()->toArray();
                    $codigo_medico = $medico['codigo_medico'];
                    // debug($codigo_medico);exit;
                }
                //inclui os dados de medico e hora automatico
                $dados["FichaAssistencial"]["codigo_medico"] = $codigo_medico;

                // debug($dados);exit;

                $fichasAssistenciai = $this->FichasAssistenciais->newEntity($dados['FichaAssistencial']);

                //verifica se vai gravar corretamente os dados na ficha clinica
                if (!$this->FichasAssistenciais->save($fichasAssistenciai)) {
                    // debug($fichasAssistenciai);
                    //verifica o erro
                    if(!empty($fichasAssistenciai->getValidationErrors($fichasAssistenciai))) {
                        $erro_assistencial = $fichasAssistenciai->getValidationErrors($fichasAssistenciai);
                    }
                    else {
                        $erro_assistencial = $fichasAssistenciai->errors;
                    }

                    throw new Exception(json_encode($erro_assistencial));
                }

                //recupera o codigo da ficha clinica
                $codigo_ficha_assistencial = $fichasAssistenciai->codigo;
                //verifica se tem dados de respostas
                if(!empty($dados['FichaAssistencialResposta']) && !empty($codigo_ficha_assistencial)) {

                    //variavel complementar de erro para as respostas
                    $erro_reposta = array();

                    $this->loadModel('FichasAssistenciaisRespostas');

                    //varre as respostas da ficha clinica
                    foreach($dados['FichaAssistencialResposta'] AS $resposta){

                        //set o codigo da ficha clinica
                        $resposta['codigo_ficha_assistencial'] = $codigo_ficha_assistencial;
                        $resposta['data_inclusao'] = date('Y-m-d H:i:s');
                        $resposta['observacao'] = $dados["FichaAssistencial"]["observacao"];

                        //seta o que vai gravar no banco de dados de respostas
                        $registro = $this->FichasAssistenciaisRespostas->newEntity($resposta);

                        //verifica se vai gravar corretamente os dados na tabela de respostas da ficha clinica
                        if (!$this->FichasAssistenciaisRespostas->save($registro)) {
                            // debug('aqui');debug($registro);

                            //verifica o erro
                            if(!empty($registro->getValidationErrors($registro))) {
                                $erro_reposta[] = $registro->getValidationErrors($registro);
                            }
                            else {
                                $erro_reposta[] = $registro->errors;
                            }

                        }//fim respostas

                    }//fim foreach

                    //verifica se tem algum erro caso ocorra volta todo os dados para serem inputados novamente
                    if(!empty($erro_reposta)) {
                        throw new Exception(json_encode($erro_reposta));
                    }//fim erro

                }//fim verificacao de dados de respostas

                //insere um atestado médico
                if(isset($dados['FichaAssistencial']['AtestadoMedico'])) {
                    //insere atestados médicos
                    $atestados = $dados['FichaAssistencial']['AtestadoMedico'][0];

                    //verfica se tem que inserir um novo atestado
                    if($atestados['exibir_ficha_assistencial'] == 1) {
                        //pega o endereco do fornecedor pelo pedido de exames
                        $this->loadModel('ItensPedidosExames');
                        $endereco_atestado = $this->ItensPedidosExames->getMontaEnderecoFornecedor($codigo_pedido_exame);

                        //pega o codigo do funcionario pelo pedido de exame
                        $this->loadModel('PedidosExames');
                        $info_funcionario = $this->PedidosExames->find()->select(['codigo_cliente_funcionario','codigo_func_setor_cargo'])->where(['codigo'=>$codigo_pedido_exame])->first()->toArray();

                        $cids10 = $atestados['cid10'];
                        unset($atestados['cid10']);

                        $dados_atestado['Atestado'] = array_merge($endereco_atestado, $atestados, $info_funcionario);
                        // debug($dados_atestado);exit;

                        $dados_atestado['Atestado']['codigo_medico'] = $codigo_medico;
                        $dados_atestado['Atestado']['codigo_usuario_inclusao'] = $codigo_usuario;
                        $dados_atestado['Atestado']['codigo_empresa'] = 1;
                        // $dados_atestado['Atestado']['data_afastamento_periodo'] = (!empty($dados_atestado['Atestado']['data_afastamento_periodo'])) ? Comum::formataData($dados_atestado['Atestado']['data_afastamento_periodo'],'dmy','ymd') : null ;
                        // $dados_atestado['Atestado']['data_retorno_periodo'] = (!empty($dados_atestado['Atestado']['data_retorno_periodo'])) ? Comum::formataData($dados_atestado['Atestado']['data_retorno_periodo'],'dmy','ymd') : null;

                        $this->loadModel('Atestados');
                        $new_atestados = $this->Atestados->newEntity($dados_atestado['Atestado']);
                        if(!$this->Atestados->save($new_atestados)){
                            // debug($new_atestados);
                            //verifica o erro
                            if(!empty($new_atestados->getValidationErrors($new_atestados))) {
                                $erro_atestado = $new_atestados->getValidationErrors($new_atestados);
                            }
                            else {
                                $erro_atestado = $new_atestados->errors;
                            }

                            throw new Exception(json_encode($erro_atestado));
                        }

                        $codigo_atestado = $new_atestados->codigo;

                        if(empty(!empty($codigo_atestado))) {
                            throw new Exception("Não foi possivel gravar o atestado!");
                        }
                        // debug($new_atestados);

                        if(!empty($cids10)) {

                            $cids = $this->montaInserirAtestadosCid($cids10, $new_atestados->codigo);

                            if(count($cids) > 0){
                                $this->loadModel('AtestadosCid');

                                // debug($cids);

                                foreach($cids as $cid){

                                    $cid['codigo_usuario_inclusao'] = $codigo_usuario;

                                    $atestadoCid = $this->AtestadosCid->newEntity($cid);
                                    if(!$this->AtestadosCid->save($atestadoCid)){
                                        //verifica o erro
                                        if(!empty($registro->getValidationErrors($registro))) {
                                            $erro_reposta_cid['ATESTADOS_CID'][] = $registro->getValidationErrors($registro);
                                        }
                                        else {
                                            $erro_reposta_cid['ATESTADOS_CID'][] = $registro->errors;
                                        }
                                    }//FINAL INCLUIR ATESTADO CID
                                }//FINAL FOREACH $cids

                                //verifica se tem algum erro caso ocorra volta todo os dados para serem inputados novamente
                                if(!empty($erro_reposta_cid)) {
                                    throw new Exception(json_encode($erro_reposta_cid));
                                }//fim erro


                            }//FINAL COUNT $cids MAIOR QUE ZERO
                        }

                        //atualiza a ficha assistencial com o codigo do atestado
                        $fichasAssistenciai = $this->FichasAssistenciais->get($codigo_ficha_assistencial);
                        $newFicha['FichasAssistenciais']['codigo_atestado'] = $codigo_atestado;
                        $fichasAssistenciai = $this->FichasAssistenciais->patchEntity($fichasAssistenciai, $newFicha);
                        if (!$this->FichasAssistenciais->save($fichasAssistenciai)) {
                            throw new Exception('Problema ao atualizar ficha assistencial');
                        }


                    }//fim exibir atestado



                }//fim atestado médico


                //dados de retorno
                $data = [
                    'retorno'=>'Ficha assistencial inserida com sucesso!',
                    'codigo_pedido_exame' => $codigo_pedido_exame,
                    'codigo_usuario'=>$codigo_usuario,
                    'codigo_ficha_assistencial'=>$codigo_ficha_assistencial
                ];

                $conn->commit();

            } catch (\Exception $e) {

                //rollback da transacao
                $conn->rollback();

                $error[] = $e->getMessage();
                $this->set(compact('error'));
                return;
            }//fim try/catch

        }//fim do post

        // $questoes = $this->FichasClinicas->Questoes->find('list', ['limit' => 200]);
        // $respostas = $this->FichasClinicas->Respostas->find('list', ['limit' => 200]);
        // $this->set(compact('fichasClinica', 'questoes', 'respostas'));

        $this->set(compact('data'));
        return;

    }

    /**
     * Edit method
     *
     * @param string|null $id Fichas Assistenciai id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fichasAssistenciai = $this->FichasAssistenciais->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fichasAssistenciai = $this->FichasAssistenciais->patchEntity($fichasAssistenciai, $this->request->getData());
            if ($this->FichasAssistenciais->save($fichasAssistenciai)) {
                $this->Flash->success(__('The fichas assistenciai has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fichas assistenciai could not be saved. Please, try again.'));
        }
        $this->set(compact('fichasAssistenciai'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fichas Assistenciai id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fichasAssistenciai = $this->FichasAssistenciais->get($id);
        if ($this->FichasAssistenciais->delete($fichasAssistenciai)) {
            $this->Flash->success(__('The fichas assistenciai has been deleted.'));
        } else {
            $this->Flash->error(__('The fichas assistenciai could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }


     /**
     * [montaInserirAtestadosCid Monta array para inserir em atestados Cid]
     * @param  [array] $cids10 [array com a(s) descrições de cids]
     * @param  [int]   $atestado_id [id do ultimo atestado incluindo]
     * @return [array]         [retorna array para inserir em atestados Cid]
     */
    public function montaInserirAtestadosCid($cids10, $atestado_codigo=null)
    {

        $this->loadModel('Cid');
        // $conditions_cid['descricao'] = Set::extract('{n}.doenca',$cids10);
        $cid = Hash::extract($cids10,'{*}.doenca');
        $conditions_cid["descricao IN"] =  $cid;

        $retorno_cid = $this->Cid->find()
            ->select(['codigo'])
            ->where($conditions_cid)
            ->hydrate(false)
            ->toArray();

        $cids = array();
        foreach($retorno_cid as $codigo_cid){
            $cids[] = array('codigo_cid' => $codigo_cid['codigo'], 'codigo_atestado' => $atestado_codigo);
        }

        return $cids;

    }//FINAL FUNCTION montaInserirAtestadosCid
}
