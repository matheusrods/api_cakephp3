<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Http\Client;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

use InvalidArgumentException;

/**
 * FichasClinicas Controller
 *
 * @property \App\Model\Table\FichasClinicasTable $FichasClinicas
 *
 * @method \App\Model\Entity\FichasClinica[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FichasClinicasController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fichasClinicas = $this->paginate($this->FichasClinicas);

        $this->set(compact('fichasClinicas'));
    }

    /**
     * View method para buscar as questoes da ficha clinica
     *
     * @param string|null $id Fichas Clinica id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $codigo_usuario, int $codigo_pedido_exames)
    {

        //valida se existe o pedido de exame selecionado, senao retorna a index e exibe erro
        $ficha_clinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido_exames])->hydrate(false)->first();
        if(!empty($ficha_clinica)) {
            $error[] = "Pedido de Exame já cadastrado";;
            $this->set(compact('error'));
            return;
        }

        $dadosFichasClinica = $this->FichasClinicas->getFichaClinicaQuestoes($codigo_pedido_exames, $codigo_usuario);

        //variavel auxiliar para o retorno do metodo
        $data = array();
        //verifica se existe os dados da ficha clinica
        if(!empty($dadosFichasClinica)) {
            $data = $dadosFichasClinica;
        }

        return $this->responseJson($data);

    }// fim view

    /**
     * View method para buscar as questoes para montar o form do parecer
     *
     */
    public function viewParecer(int $codigo_pedido_exames)
    {

        //monta os campos do parecer
        $dadosParecer = $this->FichasClinicas->montaParecer($codigo_pedido_exames);

        //variavel auxiliar para o retorno do metodo
        $data = array();
        //verifica se existe os dados da ficha clinica
        if(!empty($dadosParecer)) {

            //valida se ja tem a ficha criada
            $fichaClinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido_exames])->first();
            // debug($fichaClinica);exit;
            $msg = '';
            if(empty($fichaClinica)) {
                $msg = "Não é possível realizar o parecer, pois existem exames pendentes.";
            }
            $dadosParecer['msg'] = $msg;
            $data = $dadosParecer;
        }

        return $this->responseJson($data);

    }// fim viewParecer

    /**
     * [getDados metodo para pegar os dados cadastrados da ficha clinica]
     *
     * @param  int    $codigo_ficha_clinica [description]
     * @return [type]                       [description]
     */
    public function getDadosFichaClinica(int $codigo_pedido_exame)
    {

        //variavel auxiliar para o retorno do metodo
        $data = array();

        //pega os dados da ficha clinica
        $ficha_clinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();

        if(empty($ficha_clinica)) {
            $error[] = "Codigo do pedido exame enviado não existe ficha clinica cadastrada!";
            $this->set(compact('error'));
            return;
        }
        $ficha_clinica = $ficha_clinica->toArray();

        $codigo_ficha_clinica = $ficha_clinica['codigo'];

        //formata a hora pois o cake colocar a funcao dele onde bagunça
        $ficha_clinica['hora_inicio_atendimento'] = $ficha_clinica['hora_inicio_atendimento']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);
        $ficha_clinica['hora_fim_atendimento'] = $ficha_clinica['hora_fim_atendimento']->i18nFormat([\IntlDateFormatter::NONE, \IntlDateFormatter::SHORT]);

        //monta os campos formatados
        $ficha_clinica['pa'] = null;
        if(!is_null($ficha_clinica['pa_sistolica']) || !is_null($ficha_clinica['pa_diastolica'])) {
            $ficha_clinica['pa'] = $ficha_clinica['pa_sistolica'].' X '.$ficha_clinica['pa_diastolica'];
        }
        $ficha_clinica['peso'] =null;
        if(!is_null($ficha_clinica['peso_kg']) || !is_null($ficha_clinica['peso_gr'])) {
            $ficha_clinica['peso'] = $ficha_clinica['peso_kg'].'.'.$ficha_clinica['peso_gr'];
        }
        $ficha_clinica['altura'] = null;
        if(!is_null($ficha_clinica['altura_mt']) || !is_null($ficha_clinica['altura_cm'])) {
            $ficha_clinica['altura'] = $ficha_clinica['altura_mt'].'.'.$ficha_clinica['altura_cm'];
        }

        // debug($ficha_clinica);exit;

        //pega o codigo do pedido de exame
        $codigo_pedido_exames = $ficha_clinica['codigo_pedido_exame'];
        $codigo_usuario = $ficha_clinica['codigo_usuario_inclusao'];

        //monta a respostas das questoes
        $this->loadModel('FichasClinicasRespostas');

        // organiza as respostas em um array no padrão que a view necessita para se relacionar com $this->data
        $respostas = $this->FichasClinicasRespostas->find()
            ->select([
                'codigo_ficha_clinica_questao',
                'resposta' => 'resposta',
                'campo_livre'
            ])
            ->where(['codigo_ficha_clinica' => $codigo_ficha_clinica])
            ->hydrate(false)
            ->toArray();

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
            $dados['FichaClinicaResposta.'.$value['codigo_ficha_clinica_questao'].'_resposta'] = $value['resposta'];

            //verifica a o campo livre
            if(!empty($value['campo_livre'])) {
                if(Comum::isJson($value['campo_livre'])) {
                    // $dados['FichaClinicaResposta.'.$value['codigo_ficha_clinica_questao'].'_resposta_campo_livre'][$value['codigo_ficha_clinica_questao']] = (array)Comum::jsonToArray($value['campo_livre']);
                    $dados['FichaClinicaResposta.'.$value['codigo_ficha_clinica_questao'].'_resposta_campo_livre'][$value['codigo_ficha_clinica_questao']] = json_decode($value['campo_livre']);
                } else {
                    $dados['FichaClinicaResposta.'.$value['codigo_ficha_clinica_questao'].'_resposta_campo_livre'][$value['codigo_ficha_clinica_questao']] = $value['campo_livre'];
                }

                switch ($value['codigo_ficha_clinica_questao']) {
                    case '26':
                    case '161':
                        // $dados['FichaClinicaResposta']['campo_livre'][$key_cl] = $campo_livre_resp[0];
                        $dados['FichaClinicaResposta.'.$value['codigo_ficha_clinica_questao'].'_resposta_campo_livre'][$value['codigo_ficha_clinica_questao']] = $value['campo_livre'];
                        break;
                }

            }//fim campolivre
        }//fim foreach
        // debug($dados);exit;

        //pega os dados da ficha clinica
        $formulario = $this->FichasClinicas->getFichaClinicaQuestoes($codigo_pedido_exames, $codigo_usuario, $codigo_ficha_clinica);

        //observacao
        $formulario['formulario']['observacao']['resposta'] = $ficha_clinica['observacao'];
        $formulario['formulario']['observacao']['resposta_campo_livre'] = array();

        //parecer
        $formulario['formulario']['parecer']['resposta'] = $ficha_clinica['parecer'];
        $formulario['formulario']['parecer']['resposta_campo_livre'] = array();


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
                    if(!empty($questao['codigo'])) {

                        //verifica se tem a resposta
                        if(isset($dados[$questao['name']])) {

                            $resposta = $dados[$questao['name']];

                            //pega o label da resposta - foi comentado pois está anulando respostas que não tem 'conteudo'
                            //$resposta = $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['conteudo'][$dados[$questao['name']]];

                            //print_r($questao['codigo']);
                            //print_r($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['conteudo']);
                            //print_r($dados[$questao['name']]);
                            //echo "<br>";
                            // debug($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['conteudo']);
                            // debug($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['conteudo'][$dados[$questao['name']]]);

                            //verifica se é a questao 155 da ultima menstruacao para formatar
                            if($questao['codigo'] == 155) {
                                $resposta = Comum::formataData($resposta,'ymd','dmy');
                            }

                            //seta as respostas
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $resposta;
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = array();

                            //verifica se tem campo livre
                            if(isset($dados['FichaClinicaResposta.'.$questao['codigo'].'_resposta_campo_livre'][$questao['codigo']])) {
                                //seta o campo livre
                                $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta_campo_livre'] = $dados['FichaClinicaResposta.'.$questao['codigo'].'_resposta_campo_livre'][$questao['codigo']];

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

                                    // $resposta_sub = $dados[$sub['name']];

                                    //pega o label da resposta
                                    // if($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['tipo'] == "RADIO") {
                                    //     //pega o label da resposta
                                    // debug($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['conteudo'][$dados[$sub['name']]]);
                                    // debug(array($keyForm,$keyGrupo,$keyQuestao,$keySub,$sub['name'],$dados[$sub['name']]));

                                    $resposta_sub = null;
                                    if(isset($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['conteudo'][$dados[$sub['name']]])) {
                                        $resposta_sub = $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['conteudo'][$dados[$sub['name']]];
                                    }else{
                                        $resposta_sub = $dados[$sub['name']];
                                    }

                                    //verifica código questão para formatar
                                    if($sub['codigo'] == 307 || $sub['codigo'] == 308) {
                                        $resposta_sub = Comum::formataData($resposta_sub,'ymd','dmy');
                                    }

                                    //seta as respostas
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta'] = $resposta_sub;

                                    //solicitado pelo desenvolvedor vinicius gelles para facilitar o componente que desenvolveram.
                                    if(empty($formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['label'])) {
                                        $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['label'] = $resposta_sub;
                                    }

                                    // debug(array('formulario',$keyForm,$keyGrupo,'questao',$keyQuestao,'sub_questao',$keySub,'resposta'));
                                    // debug($dados[$sub['name']]);
                                    // exit;
                                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = array();

                                    //verifica se tem campo livre
                                    if(isset($dados['FichaClinicaResposta.'.$sub['codigo'].'_resposta_campo_livre'][$sub['codigo']])) {
                                        //seta o campo livre
                                        $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySub]['resposta_campo_livre'] = $dados['FichaClinicaResposta.'.$sub['codigo'].'_resposta_campo_livre'][$sub['codigo']];

                                    }//fim

                                }//verifica se tem o nome

                            }//fim subquestao

                        }//fim verificacao se tem sub questao

                    }//vim verificacao codigo da questao
                    else {
                        //grupo header vem os campos da ficha clinica
                        //separa para pegar o indice 1 que tem o nome do campo
                        $separa_nome = explode(".",$questao['name']);

                        if(isset($ficha_clinica[$separa_nome[1]])) {
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $ficha_clinica[$separa_nome[1]];
                        }

                    }

                }//fim questoes

            }//fim tipogrupo
        }//fim formulario

        // debug($formulario);
        // exit;


        $data = $formulario;

        // $dados['FichaClinicaQuestoes'] = $dadosFichasClinica;

        // debug($dados);exit;

        $this->set(compact('data'));
        return;


    }//fim getDatadosFichaClinia

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $data = '';

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
                $codigo_pedido_exame = $dados['FichaClinica']['codigo_pedido_exame'];

                //valida se ja tem a ficha criada
                // $fichaClinica = $this->FichasClinicas->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();
                // if(!empty($fichaClinica)) {
                //     throw new Exception("Ficha Clinica já exite, para este pedido!");
                // }

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
                    $codigo_exame = $this->Configuracao->getChave("INSERE_EXAME_CLINICO");
                    // debug(array($codigo_exame, $codigo_pedido_exame));exit;

                    //pega o pedido
                    $this->loadModel("ItensPedidosExames");
                    $medico = $this->ItensPedidosExames->find()->select(['codigo_medico'])->where(['codigo_exame' => $codigo_exame,'codigo_pedidos_exames' => $codigo_pedido_exame])->first()->toArray();
                    $codigo_medico = $medico['codigo_medico'];
                    // debug($codigo_medico);exit;
                }
                // debug($codigo_medico);exit;

                //inclui os dados de medico e hora automatico
                $dados["FichaClinica"]["incluido_por"] = $usuario['nome'];
                $dados["FichaClinica"]["codigo_medico"] = $codigo_medico;
                $dados["FichaClinica"]["hora_inicio_atendimento"] = date("H:i:s");
                $dados["FichaClinica"]["hora_fim_atendimento"] = date("H:i:s");

                //seta o codigo do usuario que esta incluindo
                $dados['FichaClinica']['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['FichaClinica']['data_inclusao'] = date('Y-m-d H:i:s');

                $dados['FichaClinica']['peso_gr'] = (!isset($dados['FichaClinica']['peso_gr'])) ? '00' : $dados['FichaClinica']['peso_gr'];

                // debug($dados['FichaClinica']);exit;

                $fichasClinica = $this->FichasClinicas->newEntity($dados['FichaClinica']);

                //verifica se vai gravar corretamente os dados na ficha clinica
                if (!$this->FichasClinicas->save($fichasClinica)) {
                    // debug("opa é aqui");exit;
                    throw new Exception(json_encode($fichasClinica->getValidationErrors($fichasClinica)));
                }

                //recupera o codigo da ficha clinica
                $codigo_ficha_clinica = $fichasClinica->codigo;
                //verifica se tem dados de respostas
                if(!empty($dados['FichaClinicaResposta']) && !empty($codigo_ficha_clinica)) {

                    //variavel complementar de erro para as respostas
                    $erro_reposta = array();

                    $this->loadModel('FichasClinicasRespostas');

                    //varre as respostas da ficha clinica
                    foreach($dados['FichaClinicaResposta'] AS $resposta){

                        //set o codigo da ficha clinica
                        $resposta['codigo_ficha_clinica'] = $codigo_ficha_clinica;
                        $resposta['data_inclusao'] = date('Y-m-d H:i:s');

                        //valida se é a questa 25 para pegar somente a descricao do cid
                        if($resposta['codigo_ficha_clinica_questao'] == 25) {
                            $dResp = json_decode($resposta['resposta']);
                            if(is_object($dResp)) {
                                $resposta['resposta'] = $dResp->doenca;
                            }
                        }

                        // debug($resposta);

                        //seta o que vai gravar no banco de dados de respostas
                        $registro = $this->FichasClinicasRespostas->newEntity($resposta);

                        //verifica se vai gravar corretamente os dados na tabela de respostas da ficha clinica
                        if (!$this->FichasClinicasRespostas->save($registro)) {
                            // debug('aqui');debug($resposta);exit;

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

                //dados de retorno
                $data = [
                    'retorno'=>'Ficha clinica inserida com sucesso!',
                    'codigo_pedido_exame' => $codigo_pedido_exame,
                    'codigo_usuario'=>$codigo_usuario,
                    'codigo_ficha_clinica'=>$codigo_ficha_clinica
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

        $this->set(compact('data'));
        return;


    }//fim add

    /**
     * [addParecer atualiza a ficha clinica com o parecer, anexa o exame aso dando baixa no pedido]
     * @param int $codigo_item_pedido_exame [description]
     */
    public function addParecer()
    {

        $data = '';

        //verfica se é post
        if ($this->request->is('post')) {

            //abrir transacao
            $conn = ConnectionManager::get('default');

            try{

                //abre a transacao
                $conn->begin();

                //seta a variavel para os dados
                $dados = $this->request->getData();
                // debug($dados);exit;

                $this->loadModel('ItensPedidosExames');

                //validacoes
                if(empty($dados['codigo_usuario'])) {
                    throw new Exception("Não foi identificado o parametro codigo_usuario");
                }

                if(empty($dados['codigo_pedido_exame'])) {
                    throw new Exception("Não foi identificado o parametro codigo_pedido_exame");
                }

                // if(empty($dados['codigo_item_pedido_exame'])) {
                //     throw new Exception("Não foi identificado o parametro codigo_item_pedido_exame");
                // }

                //seta o codigo do usuario que fez o post
                $codigo_usuario = $dados['codigo_usuario'];
                $codigo_pedido_exame = $dados['codigo_pedido_exame'];

                //pega o exame aso pelo codigo do pedido_exame
                $item = $this->ItensPedidosExames->find()->where(['codigo_exame' => '52','codigo_pedidos_exames' => $codigo_pedido_exame])->first();
                // debug($item);exit;
                if(empty($item)) {
                    throw new Exception("Não encontramos o aso para dar a baixa!");
                }
                $codigo_item_pedido_exame = $item->codigo;

                //busca a ficha clinica pelo pedido de exame
                $ficha_clinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();
                //verifica se achou a ficha
                if(empty($ficha_clinica)) {
                    throw new Exception("Não foi possivel encontrar a ficha clinica pelo codigo do pedido");
                }
                $codigo_ficha_clinica = $ficha_clinica->codigo;
                //seta o codigo do usuario que esta incluindo
                $dados_ficha = array(
                    'codigo_usuario_alteracao' => $codigo_usuario,
                    'data_alteracao' => date('Y-m-d H:i:s'),
                    'parecer' => $dados['FichaClinica']['parecer'],
                    'parecer_altura' => (isset($dados['FichaClinica']['parecer_altura'])) ? $dados['FichaClinica']['parecer_altura'] : null,
                    'parecer_espaco_confinado' => (isset($dados['FichaClinica']['parecer_espaco_confinado'])) ? $dados['FichaClinica']['parecer_espaco_confinado'] : null
                );
                // debug($dados['FichaClinica']);exit;

                //seta a atualizacao
                $fichasClinica = $this->FichasClinicas->patchEntity($ficha_clinica,$dados_ficha);

                //verifica se vai gravar corretamente os dados na ficha clinica
                if (!$this->FichasClinicas->save($fichasClinica)) {
                    throw new Exception(json_encode($fichasClinica->getValidationErrors($fichasClinica)));
                }

                #############################################################################################
                #############################################################################################
                ######################################ANEXOS#################################################
                #############################################################################################
                #############################################################################################

                //seta o anexo do exame caso tenha
                $arquivo = null;
                if(!empty($dados['AnexosExames']['arquivo'])) {

                    $this->loadModel('AnexosExames');
                    // configura a pasta de upload dos arquivos
                    $dados_exames = array(
                        'file'   => $dados['AnexosExames']['arquivo'],
                        'prefix' => 'nina',
                        'type'   => 'base64'
                    );

                    // envia a foto para o systemstorage
                    $url_imagem = Comum::sendFileToServer($dados_exames);
                    $imagem_caminho_completo = "";
                    $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                    // verifica se foi possível obter o caminho da imagem
                    if(!empty($caminho_image)) {

                        //seta o valor para a imagem que esta sendo criada
                        $imagem_caminho_completo = FILE_SERVER.$caminho_image['path'];

                        // $this->AnexosExames->existsCodigoItemPedidoExame($codigo_item_pedido_exame);
                        $anexos = array(
                            'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                            'caminho_arquivo' => $imagem_caminho_completo,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                            'status' => 1,
                            'codigo_empresa' => 1,
                        );

                        $anexos_exames = $this->AnexosExames->find()->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])->first();
                        if(!empty($anexos_exames)) {
                            $anexos['codigo'] = $anexos_exames->codigo;
                            //seta os dados para atualizacao
                            $anexos = $this->AnexosExames->patchEntity($anexos_exames, $anexos);
                        }
                        else {
                            $anexos = $this->AnexosExames->newEntity($anexos);
                        }

                        if ($this->AnexosExames->save($anexos)) {
                            $arquivo = $imagem_caminho_completo;
                        }
                        else {
                            throw new Exception("Erro ao inserir exame!");
                        }

                    }
                    else {
                        throw new Exception("Problemas em enviar a imagem para o file-server");
                    }

                }//seta o anexo do exame

                $arquivofc = null;
                if(!empty($dados['AnexosFichaClinica']['arquivo'])) {

                    $this->loadModel('AnexosFichasClinicas');
                    // configura a pasta de upload dos arquivos
                    $dados_fc = array(
                        'file'   => $dados['AnexosFichaClinica']['arquivo'],
                        'prefix' => 'nina',
                        'type'   => 'base64'
                    );

                    // envia a foto para o systemstorage
                    $url_imagem = Comum::sendFileToServer($dados_fc);
                    $imagem_caminho_completo = "";
                    $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                    // verifica se foi possível obter o caminho da imagem
                    if(!empty($caminho_image)) {
                        //seta o valor para a imagem que esta sendo criada
                        $imagem_caminho_completo = FILE_SERVER.$caminho_image['path'];

                        $anexos = array(
                            'codigo_ficha_clinica' => $codigo_ficha_clinica,
                            'caminho_arquivo' => $imagem_caminho_completo,
                            'codigo_usuario_inclusao' => $codigo_usuario,
                            'data_inclusao' => date('Y-m-d H:i:s'),
                            'status' => 1,
                            'codigo_empresa' => 1,
                        );

                        $anexos_fc = $this->AnexosFichasClinicas->find()->where(['codigo_ficha_clinica' => $codigo_ficha_clinica])->first();
                        if(!empty($anexos_fc)) {
                            $anexos['codigo'] = $anexos_fc->codigo;
                            //seta os dados para atualizacao
                            $anexos = $this->AnexosFichasClinicas->patchEntity($anexos_fc, $anexos);
                        }
                        else {
                            $anexos = $this->AnexosFichasClinicas->newEntity($anexos);
                        }

                        if ($this->AnexosFichasClinicas->save($anexos)) {
                            $arquivofc = $imagem_caminho_completo;
                        }
                        else {
                            throw new Exception("Erro ao inserir anexo da ficha clinica!");
                        }

                    }
                    else {
                        throw new Exception("Problemas em enviar a imagem para o file-server");
                    }

                }//seta o anexo do exame

                #############################################################################################
                #############################################################################################
                ###################################FIM ANEXOS################################################
                #############################################################################################
                #############################################################################################


                #####################BAIXANDO O EXAME ASO################
                $this->loadModel('ItensPedidosExamesBaixa');
                //verifica se existe baixa para este item
                $baixa = $this->ItensPedidosExamesBaixa->find()->where(['codigo_itens_pedidos_exames' => $codigo_item_pedido_exame])->first();

                //caso nao exista insere
                if(empty($baixa)) {
                    $dados_baixa = array(
                        'codigo_itens_pedidos_exames' => $codigo_item_pedido_exame,
                        'resultado' => $dados['FichaClinica']['parecer'],
                        'data_realizacao_exame' => date('Y-m-d'),
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'data_inclusao' => date('Y-m-d H:i:s'),
                    );

                    $dados_item_pedido_exame_baixa = $this->ItensPedidosExamesBaixa->newEntity($dados_baixa);

                    if(!$this->ItensPedidosExamesBaixa->save($dados_item_pedido_exame_baixa)) {
                        throw new Exception("Não foi possivel dar a baixa no exame correspondente ao parecer!");
                    }//fim baixa

                }//fim verificacao da baixa

                //baixa o pedido
                $this->loadModel('PedidosExames');
                //busca os dados do pedido para atualizacao
                $baixa = $this->PedidosExames->baixaTotalPedidoExame($codigo_pedido_exame);
                // $pedidoExame = $this->PedidosExames->find()->where(['codigo'=>$codigo_pedido_exame])->first();
                // $dados_pedidos['codigo_status_pedidos_exames'] = 3; //baixado total
                // $pedidos = $this->PedidosExames->patchEntity($pedidoExame, $dados_pedidos);
                // if(!$this->PedidosExames->save($pedidos)) {
                //     throw new Exception("Não foi possivel baixar todo o pedido!");
                // }

                //atualzar o item pedido exame

                //fim baixa do pedido

                //dados de retorno
                $data = [
                    'retorno'=>'Ficha clinica atualizada com sucesso, baixa do pedido feita com sucesso!',
                    'codigo_pedido_exame' => $codigo_pedido_exame,
                    'codigo_usuario'=>$codigo_usuario,
                    'codigo_ficha_clinica'=>$codigo_ficha_clinica,
                    'arquivo' => $arquivo,
                    'arquivo_ficha_clinica' => $arquivofc,
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

        $this->set(compact('data'));
        return;

    }//fim addParecer

    public function getParecer($codigo_pedido_exame)
    {

        $this->loadModel('ItensPedidosExames');
        $this->loadModel('FichasClinicas');

        //pega o exame aso pelo codigo do pedido_exame
        $itens_pedidos_exames = $this->ItensPedidosExames->find()->where(['codigo_exame' => '52','codigo_pedidos_exames' => $codigo_pedido_exame])->first();

        //Verifica se tem exame agendado
        if (empty($itens_pedidos_exames)) {
            $error = "Não foi encontrado itens pedidos exames para o código do pedido exame solicitado";
            $this->set(compact('error'));
            return;
        }

        $codigo_item_pedido_exame = $itens_pedidos_exames->codigo;

        //busca a ficha clinica pelo pedido de exame
        $ficha_clinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();

        //Verifica se tem Ficha clinica para o exame
        if (empty($itens_pedidos_exames)) {
            $error = "Não foi encontrado ficha clinica para o exame solicitado";
            $this->set(compact('error'));
            return;
        }

        //Monta array do parecer
        $dados_ficha = array(
            'parecer' => $ficha_clinica['parecer'],
            'parecer_altura' => (isset($ficha_clinica['parecer_altura'])) ? $ficha_clinica['parecer_altura'] : null,
            'parecer_espaco_confinado' => (isset($ficha_clinica['parecer_espaco_confinado'])) ? $ficha_clinica['parecer_espaco_confinado'] : null
        );

        $data = $dados_ficha;

        $this->set(compact('data'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fichas Clinica id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fichasClinica = $this->FichasClinicas->get($id, [
            'contain' => ['Questoes', 'Respostas']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fichasClinica = $this->FichasClinicas->patchEntity($fichasClinica, $this->request->getData());
            if ($this->FichasClinicas->save($fichasClinica)) {
                $this->Flash->success(__('The fichas clinica has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fichas clinica could not be saved. Please, try again.'));
        }
        $questoes = $this->FichasClinicas->Questoes->find('list', ['limit' => 200]);
        $respostas = $this->FichasClinicas->Respostas->find('list', ['limit' => 200]);
        $this->set(compact('fichasClinica', 'questoes', 'respostas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fichas Clinica id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fichasClinica = $this->FichasClinicas->get($id);
        if ($this->FichasClinicas->delete($fichasClinica)) {
            $this->Flash->success(__('The fichas clinica has been deleted.'));
        } else {
            $this->Flash->error(__('The fichas clinica could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
