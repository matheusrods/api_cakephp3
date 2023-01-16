<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Controller\AppController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * AgentesRiscos Controller
 *
 * @property \App\Model\Table\AgentesRiscosTable $AgentesRiscos
 *
 * @method \App\Model\Entity\AgentesRisco[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AgentesRiscosController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        $this->loadModel("ProcessosFerramentas");
        $this->loadModel("AgentesRiscos");
        $this->loadModel("AgentesRiscosEtapas");
        $this->loadModel("RiscosTipo");
        $this->loadModel("PerigosAspectos");
        $this->loadModel("RiscosImpactos");
        $this->loadModel("ArRt");
        $this->loadModel("ArrtPa");
        $this->loadModel("ArrtpaRi");
        $this->loadModel("FontesGeradorasExposicaoTipo");
        $this->loadModel("FontesGeradorasExposicao");
        $this->loadModel("RiscosImpactosSelecionadosDescricoes");
        $this->loadModel("RiscosImpactosSelecionadosAnexos");
        $this->loadModel("MedidasControleHierarquiaTipo");
        $this->loadModel("MedidasControle");
        $this->loadModel("MedidasControleAnexos");
        $this->loadModel("Qualificacao");
        $this->loadModel("FerramentasAnalise");
        $this->loadModel("Aprho");
        $this->loadModel("EquipamentosAdotados");

        $this->loadModel("HazopsAgentesRiscos");
        $this->loadModel("HazopsKeywordTipo");

        $this->loadModel("UsuariosDados");
        $this->loadModel("Funcionarios");
        $this->loadModel("ClienteFuncionario");
        $this->loadModel("FuncionarioSetoresCargos");
        $this->loadModel("UsuarioAgentesRiscos");

        $this->loadModel("AgentesRiscosClientes");
        $this->loadModel("Processos");
        $this->loadModel("ProcessosAnexos");
        $this->loadModel("UsuarioFuncao");

        $this->loadModel("FuncionarioSetoresCargos");
        $this->loadModel("GruposEconomicosClientes");        
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $agentesRiscos = $this->paginate($this->AgentesRiscos);

        $this->set(compact('agentesRiscos'));
    }

    /**
     * View method
     *
     * @param string|null $id Agentes Risco id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $agentesRisco = $this->AgentesRiscos->get($id, [
            'contain' => [],
        ]);

        $this->set('agentesRisco', $agentesRisco);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $request = $this->request->getData();

            $codigo_usuario_inclusao = $this->getAuthUser();

            foreach ($request as $key => $dados) {

                //Declara array
                $arr_risco_tipo = array();
                $arr_perigo_aspecto = array();
                $arr_risco_impacto = array();

                // Inserir em ArRt
                $arr_risco_tipo['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;
                $arr_risco_tipo['codigo_agente_risco'] = $dados['codigo_agente_risco'];
                $arr_risco_tipo['codigo_risco_tipo'] = $dados['codigo_risco_tipo'];

                $entityArRt = $this->ArRt->newEntity($arr_risco_tipo);

                if (!$this->ArRt->save($entityArRt)) {
                    $data['message'] = 'Erro ao inserir em ArRt';
                    $data['error'] = $entityArRt->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[$key]['codigo_ar_rt'] = $entityArRt['codigo'];

                // Inserir em ArrtPa
                foreach ($dados['perigos_aspectos'] as $keyPA => $perigo_aspecto) {

                    $arr_perigo_aspecto['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;
                    $arr_perigo_aspecto['codigo_ar_rt'] = $entityArRt['codigo'];
                    $arr_perigo_aspecto['codigo_perigo_aspecto'] = $perigo_aspecto['codigo_perigo_aspecto'];

                    $entityArrtPa = $this->ArrtPa->newEntity($arr_perigo_aspecto);

                    if (!$this->ArrtPa->save($entityArrtPa)) {
                        $data['message'] = 'Erro ao inserir em ArrtPa';
                        $data['error'] = $entityArrtPa->errors();
                        $this->set(compact('data'));
                        return;
                    }

                    $data[$key]['perigos_aspectos'][$keyPA]['codigo_arrt_pa'] = $entityArrtPa['codigo'];

                    // Inserir em riscos/impactos
                    foreach ($perigo_aspecto['riscos_impactos'] as $keyRI => $risco_impacto) {

                        $arr_risco_impacto['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;
                        $arr_risco_impacto['codigo_arrt_pa'] = $entityArrtPa['codigo'];
                        $arr_risco_impacto['codigo_risco_impacto'] = $risco_impacto['codigo_risco_impacto'];

                        $entityArrtpaRi = $this->ArrtpaRi->newEntity($arr_risco_impacto);

                        if (!$this->ArrtpaRi->save($entityArrtpaRi)) {
                            $data['message'] = 'Erro ao inserir em ArrtpaRi';
                            $data['error'] = $entityArrtpaRi->errors();
                            $this->set(compact('data'));
                            return;
                        }

                        $data[$key]['perigos_aspectos'][$keyPA]['ricos_impactos'][$keyRI]['codigo_arrtpa_ri'] = $entityArrtpaRi['codigo'];
                    }
                }
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function postAgentesRiscos()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            $dados['codigo_usuario_inclusao'] = $codigo_usuario;

            $entityAgentesRiscos = $this->AgentesRiscos->newEntity($dados);

            if (!$this->AgentesRiscos->save($entityAgentesRiscos)) {
                $data['message'] = 'Erro ao inserir em AgentesRiscos';
                $data['error'] = $entityAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }

            // Inserir codigo de etapas selecionadas em AgentesRiscosEtapas
            foreach ($dados['ferramentas_selecionadas'] as $etapa) {

                $etapa['codigo_usuario_inclusao'] = $codigo_usuario;
                $etapa['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];

                $entityAgentesRiscosEtapas = $this->AgentesRiscosEtapas->newEntity($etapa);

                if (!$this->AgentesRiscosEtapas->save($entityAgentesRiscosEtapas)) {
                    $data['message'] = 'Erro ao inserir em AgentesRiscosEtapas';
                    $data['error'] = $entityAgentesRiscosEtapas->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data['ferramentas_selecionadas'][] = $entityAgentesRiscosEtapas;
            }

            $data['agentes_riscos'] = array();
            // Inserir Agentes de riscos
            foreach ($dados['agentes_riscos'] as $key => $dado) {

                //Declara array
                $arr_risco_tipo = array();
                $arr_perigo_aspecto = array();
                $arr_risco_impacto = array();

                // Inserir em ArRt
                $arr_risco_tipo['codigo_usuario_inclusao'] = $codigo_usuario;
                $arr_risco_tipo['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];
                $arr_risco_tipo['codigo_risco_tipo'] = $dado['codigo_risco_tipo'];

                $entityArRt = $this->ArRt->newEntity($arr_risco_tipo);

                if (!$this->ArRt->save($entityArRt)) {
                    $data['message'] = 'Erro ao inserir em ArRt';
                    $data['error'] = $entityArRt->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data['agentes_riscos'][] = $entityArRt;

                // Inserir em perigos_aspectos
                $data['agentes_riscos'][$key]['perigos_aspectos'] = array();

                foreach ($dado['perigos_aspectos'] as $keyPA => $perigo_aspecto) {

                    $arr_perigo_aspecto['codigo_usuario_inclusao'] = $codigo_usuario;
                    $arr_perigo_aspecto['codigo_ar_rt'] = $entityArRt['codigo'];
                    $arr_perigo_aspecto['codigo_perigo_aspecto'] = $perigo_aspecto['codigo_perigo_aspecto'];

                    $entityArrtPa = $this->ArrtPa->newEntity($arr_perigo_aspecto);

                    if (!$this->ArrtPa->save($entityArrtPa)) {
                        $data['message'] = 'Erro ao inserir em ArrtPa';
                        $data['error'] = $entityArrtPa->errors();
                        $this->set(compact('data'));
                        return;
                    }

                    $data['agentes_riscos'][$key]['perigos_aspectos'][] = $entityArrtPa;

                    // Inserir em riscos_impactos
                    $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'] = array();

                    foreach ($perigo_aspecto['riscos_impactos'] as $keyRI => $risco_impacto) {

                        $arr_risco_impacto['codigo_usuario_inclusao'] = $codigo_usuario;
                        $arr_risco_impacto['codigo_arrt_pa'] = $entityArrtPa['codigo'];
                        $arr_risco_impacto['codigo_risco_impacto'] = $risco_impacto['codigo_risco_impacto'];

                        $entityArrtpaRi = $this->ArrtpaRi->newEntity($arr_risco_impacto);

                        if (!$this->ArrtpaRi->save($entityArrtpaRi)) {
                            $data['message'] = 'Erro ao inserir em ArrtpaRi';
                            $data['error'] = $entityArrtpaRi->errors();
                            $this->set(compact('data'));
                            return;
                        }

                        // Inseri os riscos/impactos no array para exibir no final da requisição
                        $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][] = $entityArrtpaRi;

                        // ==============================================================================================
                        // Inseri o codigo_cliente, codigo_agente_risco e codigo_arrtpa_ri na tabela auxiliar
                        // para selecionar na lista de riscos de GHE's
                        $riscos_ghe = array();
                        $riscos_ghe['codigo_cliente']      = $dados['codigo_cliente'];
                        $riscos_ghe['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];
                        $riscos_ghe['codigo_arrtpa_ri']    = $entityArrtpaRi['codigo'];

                        $entityAgentesRiscosClientes = $this->AgentesRiscosClientes->newEntity($riscos_ghe);

                        if (!$this->AgentesRiscosClientes->save($entityAgentesRiscosClientes)) {
                            $data['message'] = 'Erro ao inserir em AgentesRiscosClientes';
                            $data['error'] = $entityAgentesRiscosClientes->errors();
                            $this->set(compact('data'));
                            return;
                        }
                        // Fim de agentes de riscos auxiliar para GHE's
                        // ==============================================================================================

                        // Inserir Descrições
                        if (!empty($risco_impacto['descricao_risco'])) {

                            // Declara array para inserir as medidas de controle em $data
                            $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['descricao_risco'] = array();

                            // Inserir em RiscosImpactosSelecionadosDescricoes
                            $dadosDescricoes = array();

                            $dadosDescricoes['codigo_arrtpa_ri']        = $entityArrtpaRi['codigo'];
                            $dadosDescricoes['descricao_risco']         = $risco_impacto['descricao_risco']['descricao_risco'];
                            $dadosDescricoes['descricao_exposicao']     = $risco_impacto['descricao_risco']['descricao_exposicao'];
                            $dadosDescricoes['pessoas_expostas']        = $risco_impacto['descricao_risco']['pessoas_expostas'];
                            $dadosDescricoes['codigo_usuario_inclusao'] = $codigo_usuario;

                            $entityRiscosImpactosSelecionadosDescricoes = $this->RiscosImpactosSelecionadosDescricoes->newEntity($dadosDescricoes);

                            if (!$this->RiscosImpactosSelecionadosDescricoes->save($entityRiscosImpactosSelecionadosDescricoes)) {
                                $data['message'] = 'Erro ao inserir em RiscosImpactosSelecionadosDescricoes';
                                $data['error'] = $entityRiscosImpactosSelecionadosDescricoes->errors();
                                $this->set(compact('data'));
                                return;
                            }

                            $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['descricao_risco'][] = $entityRiscosImpactosSelecionadosDescricoes;

                            // Inserrir em FontesGeradorasExposicao
                            $dadosFontesGeradorasExposicao = array();

                            if (!empty($risco_impacto['descricao_risco']['fontes_geradoras_exposicao'])) {

                                // Declara array para inserir as ferramentas_analise em $data
                                $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['descricao_risco']['fontes_geradoras_exposicao'] = array();

                                foreach ($risco_impacto['descricao_risco']['fontes_geradoras_exposicao'] as $keyFge => $fonte_geradora_exposicao) {

                                    $dadosFontesGeradorasExposicao['codigo_risco_impacto_selecionado_descricao'] = $entityRiscosImpactosSelecionadosDescricoes['codigo'];
                                    $dadosFontesGeradorasExposicao['codigo_fonte_geradora_exposicao_tipo'] = $fonte_geradora_exposicao['codigo_fonte_geradora_exposicao_tipo'];
                                    $dadosFontesGeradorasExposicao['codigo_usuario_inclusao']     = $codigo_usuario;

                                    $entityFontesGeradorasExposicao = $this->FontesGeradorasExposicao->newEntity($dadosFontesGeradorasExposicao);

                                    if (!$this->FontesGeradorasExposicao->save($entityFontesGeradorasExposicao)) {
                                        $data['message'] = 'Erro ao inserir em FontesGeradorasExposicao';
                                        $data['error'] = $entityFontesGeradorasExposicao->errors();
                                        $this->set(compact('data'));
                                        return;
                                    }

                                    $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['descricao_risco']['fontes_geradoras_exposicao'][$keyFge][] = $entityFontesGeradorasExposicao;

                                }
                            }
                        }

                        // Inserir Medidas de controle
                        if (!empty($risco_impacto['medidas_controle'])) {

                            // Declara array para inserir as medidas de controle em $data
                            $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['medidas_controle'] = array();

                            foreach ($risco_impacto['medidas_controle'] as $keyMc => $medida_controle) {


                                $medida_controle['codigo_usuario_inclusao'] = $codigo_usuario;
                                $medida_controle['codigo_arrtpa_ri'] = $entityArrtpaRi['codigo'];


                                $entityMedidasControle = $this->MedidasControle->newEntity($medida_controle);

                                //salva os dados
                                if (!$this->MedidasControle->save($entityMedidasControle)) {
                                    $data['message'] = 'Erro ao inserir em MedidasControle';
                                    $data['error'] = $entityMedidasControle->errors();
                                    $this->set(compact('data'));
                                    return;
                                }

                                $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['medidas_controle'][] = $entityMedidasControle;

                            }
                        }

                        // Inserir Qualificações
                        if (!empty($risco_impacto['qualificacao'])) {

                            // Declara array para inserir as qualificações em $data
                            $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'] = array();

                            foreach ($risco_impacto['qualificacao'] as $keyQ => $qualificacao) {

                                $qualificacao['codigo_usuario_inclusao'] = $codigo_usuario;
                                $qualificacao['codigo_arrtpa_ri'] = $entityArrtpaRi['codigo'];

                                $entityQualificacao = $this->Qualificacao->newEntity($qualificacao);
                                //salva os dados
                                if (!$this->Qualificacao->save($entityQualificacao)) {
                                    $data['message'] = 'Erro ao inserir em Qualificacao';
                                    $data['error'] = $entityQualificacao->errors();
                                    $this->set(compact('data'));
                                    return;
                                }

                                $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'][] = $entityQualificacao;

                                // Inserir ferramentas de analise
                                if (!empty($qualificacao['ferramentas_analise'])) {

                                    // Declara array para inserir as ferramentas_analise em $data
                                    $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'][$keyQ]['ferramentas_analise'] = array();

                                    foreach ($qualificacao['ferramentas_analise'] as $ferramenta_analise) {

                                        $ferramenta_analise['codigo_usuario_inclusao'] = $codigo_usuario;
                                        $ferramenta_analise['codigo_qualificacao'] = $entityQualificacao['codigo'];

                                        $entityFerramentasAnalise = $this->FerramentasAnalise->newEntity($ferramenta_analise);
                                        //salva os dados
                                        if (!$this->FerramentasAnalise->save($entityFerramentasAnalise)) {
                                            $data['message'] = 'Erro ao inserir em FerramentasAnalise';
                                            $data['error'] = $entityFerramentasAnalise->errors();
                                            $this->set(compact('data'));
                                            return;
                                        }

                                        $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'][$keyQ]['ferramentas_analise'][] = $entityFerramentasAnalise;
                                    }
                                }

                                // Inserir APRHO
                                if (!empty($qualificacao['aprho'])) {

                                    // Declara array para inserir as APRHO em $data
                                    $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'][$keyQ]['aprho'] = array();

                                    foreach ($qualificacao['aprho'] as $aprho) {

                                        //Função para upload de foto
                                        $foto = "";

                                        if (!empty($aprho['foto'])) {

                                            //monta o array para enviar
                                            $dados_foto = array(
                                                'file'   => $aprho['foto'],
                                                'prefix' => 'seguranca',
                                                'type'   => 'base64'
                                            );

                                            //url de imagem
                                            $url_imagem = Comum::sendFileToServer($dados_foto);
                                            //pega o caminho da imagem
                                            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                                            if (!empty($caminho_image)) {

                                                //seta o valor para a imagem que esta sendo criada
                                                $foto = FILE_SERVER . $caminho_image['path'];
                                            }
                                        }

                                        $aprho['codigo_usuario_inclusao'] = $codigo_usuario;
                                        $aprho['codigo_qualificacao'] = $entityQualificacao['codigo'];
                                        $aprho['arquivo_url'] = $foto;

                                        $entityAprho = $this->Aprho->newEntity($aprho);
                                        //salva os dados
                                        if (!$this->Aprho->save($entityAprho)) {
                                            $data['message'] = 'Erro ao inserir em Aprho';
                                            $data['error'] = $entityAprho->errors();
                                            $this->set(compact('data'));
                                            return;
                                        }

                                        $data['agentes_riscos'][$key]['perigos_aspectos'][$keyPA]['riscos_impactos'][$keyRI]['qualificacao'][$keyQ]['aprho'][] = $entityAprho;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function postAgentesRiscosHazop()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            $processos = $this->Processos->find()->where(['codigo' => $dados['codigo_processo']])->first();

            if (empty($processos)) {
                $error = 'Não foi possivel encontrar o processo!';
                $this->set(compact('error'));
                return;
            }

            //Inseri e gera codigo de Agente de risco
            $agente_risco = array();
            $agente_risco['codigo_usuario_inclusao'] = $codigo_usuario;

            $entityAgentesRiscos = $this->AgentesRiscos->newEntity($agente_risco);

            if (!$this->AgentesRiscos->save($entityAgentesRiscos)) {
                $data['message'] = 'Erro ao inserir em AgentesRiscos';
                $data['error'] = $entityAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }

            // Inseri as etapas selecionadas
            $dadosEtapa = array();
            $data['hazop_selecionados'] = array();

            foreach ($dados['ferramentas_selecionadas'] as $hazop) {

                $processo_ferramentas = $this->ProcessosFerramentas->find()->where(['codigo' => $hazop['codigo_processo_ferramenta']])->first();

                if (empty($processo_ferramentas)) {
                    $error = 'codigo_processo_ferramenta não encontrado!';
                    $this->set(compact('error'));
                    return;
                }

                $dadosEtapa['codigo_usuario_inclusao'] = $entityAgentesRiscos['codigo_usuario_inclusao'];
                $dadosEtapa['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];
                $dadosEtapa['codigo_processo_ferramenta'] = $hazop['codigo_processo_ferramenta'];

                $entityAgentesRiscosEtapas = $this->AgentesRiscosEtapas->newEntity($dadosEtapa);

                if (!$this->AgentesRiscosEtapas->save($entityAgentesRiscosEtapas)) {
                    $data['message'] = 'Erro ao inserir em AgentesRiscosEtapas';
                    $data['error'] = $entityAgentesRiscosEtapas->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data['hazop_selecionados'] = $entityAgentesRiscosEtapas;
            }

            //Inserir em ArRt
            $ar_rt = array(
                "codigo_agente_risco" => $entityAgentesRiscos['codigo'],
                "codigo_risco_tipo" => $dados['agentes_riscos']['codigo_risco_tipo'],
                "codigo_usuario_inclusao" => $codigo_usuario
            );

            $entityArRt = $this->ArRt->newEntity($ar_rt);

            if (!$this->ArRt->save($entityArRt)) {
                $data['message'] = 'Erro ao inserir em ArRt';
                $data['error'] = $entityArRt->errors();
                $this->set(compact('data'));
                return;
            }

            $data['ar_rt'] = $entityArRt;

            //Inserir em ArrtPa
            $arrt_pa = array(
                "codigo_agente_risco" => $entityAgentesRiscos['codigo'],
                "codigo_ar_rt" => $entityArRt['codigo'],
                "codigo_perigo_aspecto" => $dados['agentes_riscos']['codigo_perigo_aspecto'],
                "codigo_usuario_inclusao" => $codigo_usuario
            );

            $entityArrtPa = $this->ArrtPa->newEntity($arrt_pa);

            if (!$this->ArrtPa->save($entityArrtPa)) {
                $data['message'] = 'Erro ao inserir em ArrtPa';
                $data['error'] = $entityArrtPa->errors();
                $this->set(compact('data'));
                return;
            }

            $data['arrt_pa'] = $entityArrtPa;

            //Inserir em Arrtpa_Ri
            $ag_ri = array();
            $ag_ri['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];
            $ag_ri['codigo_arrt_pa'] = $entityArrtPa['codigo'];
            $ag_ri['codigo_risco_impacto'] = $dados['agentes_riscos']['codigo_risco_impacto'];
            $ag_ri['codigo_usuario_inclusao'] = $codigo_usuario;
            $ag_ri['e_hazop'] = 1;

            $entityArrtpaRi = $this->ArrtpaRi->newEntity($ag_ri);

            if (!$this->ArrtpaRi->save($entityArrtpaRi)) {
                $data['message'] = 'Erro ao inserir em ArrtpaRi';
                $data['error'] = $entityArrtpaRi->errors();
                $this->set(compact('data'));
                return;
            }

            $data['arrtpa_ri'] = $entityArrtpaRi;

            $dados['agentes_riscos']['codigo_usuario_inclusao'] = $codigo_usuario;
            $dados['agentes_riscos']['codigo_arrtpa_ri'] = $entityArrtpaRi['codigo'];
            $dados['agentes_riscos']['codigo_cliente'] = $dados['codigo_cliente'];

            $entityHazopsAgentesRiscos = $this->HazopsAgentesRiscos->newEntity($dados['agentes_riscos']);

            if (!$this->HazopsAgentesRiscos->save($entityHazopsAgentesRiscos)) {
                $data['message'] = 'Erro ao inserir em HazopsAgentesRiscos';
                $data['error'] = $entityHazopsAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }

            $data['hazops_agentes_riscos'] = $entityHazopsAgentesRiscos;

            // Inserir keywords selecionados
            if (!empty($dados['agentes_riscos']['medidas_controle'])) {

                $data['hazops_agentes_riscos']['medidas_controle'] = array();

                foreach ($dados['agentes_riscos']['medidas_controle'] as $medidas_controle) {

                    $medidas_controle['codigo_arrtpa_ri'] = $entityArrtpaRi['codigo'];
                    $medidas_controle['codigo_usuario_inclusao'] = $codigo_usuario;
                    $medidas_controle['codigo_hazop_agente_risco'] = $entityHazopsAgentesRiscos['codigo'];

                    $entityMedidasControle = $this->MedidasControle->newEntity($medidas_controle);

                    //salva os dados
                    if (!$this->MedidasControle->save($entityMedidasControle)) {
                        $data['message'] = 'Erro ao inserir em MedidasControle';
                        $data['error'] = $entityMedidasControle->errors();
                        $this->set(compact('data'));
                        return;
                    }

                    $data['hazops_agentes_riscos']['medidas_controle'][] = $entityMedidasControle;
                }
            }

            //Salva a transação
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }

    }

    public function getAgentesRiscos($codigo_ar_rt)
    {
        $ar_rt = $this->ArRt->find()
            ->select(['codigo', 'codigo_agente_risco', 'codigo_risco_tipo'])
            ->where(['codigo' => $codigo_ar_rt])
            ->first();

        $ar_rt['perigos_aspectos'] = array();
        $ar_rt['riscos_impactos']  = array();

        if (!empty($ar_rt)) {

            $arrt_pa = $this->ArrtPa->find()
                ->select(['codigo', 'codigo_ar_rt', 'codigo_perigo_aspecto'])
                ->where(['codigo_ar_rt' => $ar_rt['codigo']])
                ->toArray();

            $ar_rt['perigos_aspectos'] = $arrt_pa;
        }

        if (!empty($ar_rt['perigos_aspectos'])) {

            foreach ($ar_rt['perigos_aspectos'] as $key => $perigos_aspectos ) {

                $arrtpa_ri = $this->ArrtpaRi->find()
                    ->select(['codigo', 'codigo_arrt_pa', 'codigo_risco_impacto'])
                    ->where(['codigo_arrt_pa' => $perigos_aspectos['codigo']])
                    ->toArray();

                $ar_rt['riscos_impactos'][$key] = $arrtpa_ri;
            }
        }

        $data[] = $ar_rt;

        $this->set(compact('data'));
    }

    public function addEtapas()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $dados['codigo_usuario_inclusao'] = $this->getAuthUser();

            $entityAgentesRiscos = $this->AgentesRiscos->newEntity($dados);

            if (!$this->AgentesRiscos->save($entityAgentesRiscos)) {
                $data['message'] = 'Erro ao inserir em AgentesRiscos';
                $data['error'] = $entityAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }

            $dadosEtapa = array();

            foreach ($dados['etapas'] as $etapa) {

                $dadosEtapa['codigo_usuario_inclusao'] = $entityAgentesRiscos['codigo_usuario_inclusao'];
                $dadosEtapa['codigo_agente_risco'] = $entityAgentesRiscos['codigo'];
                $dadosEtapa['codigo_processo_ferramenta'] = $etapa['codigo'];

                $entityAgentesRiscosEtapas = $this->AgentesRiscosEtapas->newEntity($dadosEtapa);

                if (!$this->AgentesRiscosEtapas->save($entityAgentesRiscosEtapas)) {
                    $data['message'] = 'Erro ao inserir em AgentesRiscosEtapas';
                    $data['error'] = $entityAgentesRiscosEtapas->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = $entityAgentesRiscos;

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function postDescricao()
    {
        $this->request->allowMethod(['post']); // aceita apenas PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $dados['codigo_usuario_inclusao'] = $this->getAuthUser();

            $get_arrtpa_ri = $this->ArrtpaRi->find()->where(['codigo' => $dados['codigo_arrtpa_ri']])->first();

            if (empty($get_arrtpa_ri)) {
                $data['message'] = 'Não foi encontrado dados referente ao codigo_arrtpa_ri informado.';
                $this->set(compact('data'));
                return;
            }

            // Inserir em RiscosImpactosSelecionadosDescricoes
            $dadosDescricoes = array();

            $dadosDescricoes['codigo_arrtpa_ri'] = $dados['codigo_arrtpa_ri'];
            $dadosDescricoes['descricao_risco']         = $dados['descricao_risco'];
            $dadosDescricoes['descricao_exposicao']     = $dados['descricao_exposicao'];
            $dadosDescricoes['pessoas_expostas']        = $dados['pessoas_expostas'];
            $dadosDescricoes['codigo_usuario_inclusao'] = $dados['codigo_usuario_inclusao'];

            $entityRiscosImpactosSelecionadosDescricoes = $this->RiscosImpactosSelecionadosDescricoes->newEntity($dadosDescricoes);

            if (!$this->RiscosImpactosSelecionadosDescricoes->save($entityRiscosImpactosSelecionadosDescricoes)) {
                $data['message'] = 'Erro ao inserir em RiscosImpactosSelecionadosDescricoes';
                $data['error'] = $entityRiscosImpactosSelecionadosDescricoes->errors();
                $this->set(compact('data'));
                return;
            }

            // Inserrir em FontesGeradorasExposicao
            $dadosFontesGeradorasExposicao = array();

            foreach ($dados['fontes_gerados_exposicao'] as $fonte_geradora_exposicao) {

                $dadosFontesGeradorasExposicao['codigo_risco_impacto_selecionado_descricao'] = $entityRiscosImpactosSelecionadosDescricoes['codigo'];
                $dadosFontesGeradorasExposicao['codigo_fonte_geradora_exposicao_tipo'] = $fonte_geradora_exposicao['codigo_fonte_geradora_exposicao_tipo'];
                $dadosFontesGeradorasExposicao['codigo_usuario_inclusao']     = $dados['codigo_usuario_inclusao'];

                $entityFontesGeradorasExposicao = $this->FontesGeradorasExposicao->newEntity($dadosFontesGeradorasExposicao);

                if (!$this->FontesGeradorasExposicao->save($entityFontesGeradorasExposicao)) {
                    $data['message'] = 'Erro ao inserir em FontesGeradorasExposicao';
                    $data['error'] = $entityFontesGeradorasExposicao->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = $entityRiscosImpactosSelecionadosDescricoes;

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function putDescricao()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $dados['codigo_usuario_inclusao'] = $this->getAuthUser();

            $get_arrtpa_ri = $this->ArrtpaRi->find()->where(['codigo' => $dados['codigo_arrtpa_ri']])->first();

            if (empty($get_arrtpa_ri)) {
                $data['message'] = 'Não foi encontrado dados referente ao codigo_arrtpa_ri informado.';
                $this->set(compact('data'));
                return;
            }

            // Editar em RiscosImpactosSelecionadosDescricoes

            $get_riscos_impactos_selecionados_descricoes = $this->RiscosImpactosSelecionadosDescricoes->find()->where(['codigo_arrtpa_ri' => $dados['codigo_arrtpa_ri']])->first();

            $dadosDescricoes = array();

            $dadosDescricoes['codigo_arrtpa_ri'] = $dados['codigo_arrtpa_ri'];
            $dadosDescricoes['descricao_risco']         = $dados['descricao_risco'];
            $dadosDescricoes['descricao_exposicao']     = $dados['descricao_exposicao'];
            $dadosDescricoes['pessoas_expostas']        = $dados['pessoas_expostas'];
            $dadosDescricoes['codigo_usuario_alteracao'] = $dados['codigo_usuario_inclusao'];
            $dadosDescricoes['data_alteracao']           = date('Y-m-d H:i:s');

            $entityRiscosImpactosSelecionadosDescricoes = $this->RiscosImpactosSelecionadosDescricoes->patchEntity($get_riscos_impactos_selecionados_descricoes, $dadosDescricoes);

            if (!$this->RiscosImpactosSelecionadosDescricoes->save($entityRiscosImpactosSelecionadosDescricoes)) {
                $data['message'] = 'Erro ao inserir em RiscosImpactosSelecionadosDescricoes';
                $data['error'] = $entityRiscosImpactosSelecionadosDescricoes->errors();
                $this->set(compact('data'));
                return;
            }

            // Editar em FontesGeradorasExposicao
            $dadosFontesGeradorasExposicao = array();

            //Remove dados anteriores e inseri novos
            $this->FontesGeradorasExposicao->deleteAll(array(
                'FontesGeradorasExposicao.codigo_risco_impacto_selecionado_descricao' => $entityRiscosImpactosSelecionadosDescricoes['codigo']
            ), false);

            foreach ($dados['fontes_gerados_exposicao'] as $fonte_geradora_exposicao) {

                $dadosFontesGeradorasExposicao['codigo_risco_impacto_selecionado_descricao'] = $entityRiscosImpactosSelecionadosDescricoes['codigo'];
                $dadosFontesGeradorasExposicao['codigo_fonte_geradora_exposicao_tipo'] = $fonte_geradora_exposicao['codigo_fonte_geradora_exposicao_tipo'];
                $dadosFontesGeradorasExposicao['codigo_usuario_inclusao']     = $dados['codigo_usuario_inclusao'];

                $entityFontesGeradorasExposicao = $this->FontesGeradorasExposicao->newEntity($dadosFontesGeradorasExposicao);

                if (!$this->FontesGeradorasExposicao->save($entityFontesGeradorasExposicao)) {
                    $data['message'] = 'Erro ao inserir em FontesGeradorasExposicao';
                    $data['error'] = $entityFontesGeradorasExposicao->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = $entityRiscosImpactosSelecionadosDescricoes;

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getFotosDescricao($codigo_arrtpa_ri, $codigo_risco_impacto_selecionado_anexo = null)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        if (!empty($codigo_risco_impacto_selecionado_anexo)) {
            $data = $this->RiscosImpactosSelecionadosAnexos->find()->where(['codigo' => $codigo_risco_impacto_selecionado_anexo, 'data_remocao is null'])->first();
        } else {
            $data = $this->RiscosImpactosSelecionadosAnexos->find()->where(['codigo_arrtpa_ri' => $codigo_arrtpa_ri, 'data_remocao is null']);
        }

        $this->set(compact('data'));
    }

    public function postPutFotosDescricao()
    {
        $this->request->allowMethod(['post', 'put']); // aceita apenas POST e PUT

        //Abre a transação
        $this->connect->begin();

        try {

            //pega os dados que veio do post
            $params = $this->request->getData();

            $codigo_usuario_inclusao = $this->getAuthUser();

            if (empty($params['foto'])) {
                $data['error'] = "Precisa enviar a foto";
                $this->set(compact('data'));
                return;
            }
            //monta o array para enviar
            $dados = array(
                'file'   => $params['foto'],
                'prefix' => 'seguranca',
                'type'   => 'base64'
            );

            //url de imagem
            $url_imagem = Comum::sendFileToServer($dados);
            //pega o caminho da imagem
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

            $riscosImpactosSelecionados = $this->ArrtpaRi->find()->where(['codigo' => $params['codigo_arrtpa_ri']])->first();

            if (empty($riscosImpactosSelecionados)) {
                $error = 'Código do ArrtpaRi não encontrado';
                $this->set(compact('error'));
                return;
            }

            //verifica se subiu corretamente a imagem
            if (!empty($caminho_image)) {

                //seta o valor para a imagem que esta sendo criada
                $fotos = FILE_SERVER.$caminho_image['path'];

                $dados_riscos_impactos_selecionados_anexo = array();

                if ($this->request->is(['put'])) {

                    $riscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->find()->where(['codigo' => $params['codigo']])->first();

                    if (empty($riscosImpactosSelecionadosAnexos)) {
                        $error = 'Código do RiscosImpactosSelecionadosAnexos não encontrado';
                        $this->set(compact('error'));
                        return;
                    }

                    $dados_riscos_impactos_selecionados_anexo["codigo_usuario_alteracao"] = $codigo_usuario_inclusao;
                    $dados_riscos_impactos_selecionados_anexo["data_alteracao"] = date('Y-m-d H:i:s');
                    $dados_riscos_impactos_selecionados_anexo["codigo_arrtpa_ri"] = $riscosImpactosSelecionadosAnexos['codigo_arrtpa_ri'];
                    $dados_riscos_impactos_selecionados_anexo["arquivo_url"] = $fotos;

                    $entityRiscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->patchEntity($riscosImpactosSelecionadosAnexos, $dados_riscos_impactos_selecionados_anexo);

                } else {

                    $dados_riscos_impactos_selecionados_anexo["codigo_usuario_inclusao"] = $codigo_usuario_inclusao;
                    $dados_riscos_impactos_selecionados_anexo["codigo_arrtpa_ri"] = $riscosImpactosSelecionados['codigo'];
                    $dados_riscos_impactos_selecionados_anexo["arquivo_url"] = $fotos;

                    $entityRiscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->newEntity($dados_riscos_impactos_selecionados_anexo);
                }

                //salva os dados
                if (!$this->RiscosImpactosSelecionadosAnexos->save($entityRiscosImpactosSelecionadosAnexos)) {
                    $data['message'] = 'Erro ao inserir em RiscosImpactosSelecionadosAnexos';
                    $data['error'] = $entityRiscosImpactosSelecionadosAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityRiscosImpactosSelecionadosAnexos;

                // Salva dados
                $this->connect->commit();

                $this->set(compact('data'));

            } else {
                $error = "Problemas em enviar a imagem para o file-server";
            }

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function deleteFotosDescricao($codigo_arrtpa_ri, $codigo_risco_impacto_selecionado_anexo = null)
    {
        $this->request->allowMethod(['delete']); // aceita apenas DELETE

        if (!empty($codigo_risco_impacto_selecionado_anexo)) {
            $riscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->find()->where(['codigo_arrtpa_ri' => $codigo_arrtpa_ri, 'codigo' => $codigo_risco_impacto_selecionado_anexo])->first();
            $dados_riscos_impactos_selecionados_anexos["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_riscos_impactos_selecionados_anexos["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($riscosImpactosSelecionadosAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            $entityRiscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->patchEntity($riscosImpactosSelecionadosAnexos, $dados_riscos_impactos_selecionados_anexos);

            //salva os dados no banco
            if (!$this->RiscosImpactosSelecionadosAnexos->save($entityRiscosImpactosSelecionadosAnexos)) {
                $data['message'] = 'Erro ao remover em RiscosImpactosSelecionadosAnexos';
                $data['error'] = $entityRiscosImpactosSelecionadosAnexos->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = "Anexo removido com sucesso!";
        } else {
            $riscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->find()->where(['codigo_arrtpa_ri' => $codigo_arrtpa_ri])->toArray();
            $dados_riscos_impactos_selecionados_anexos["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_riscos_impactos_selecionados_anexos["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($riscosImpactosSelecionadosAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            foreach ($riscosImpactosSelecionadosAnexos as $riscoImpactoSelecionadoAnexo) {

                $entityRiscosImpactosSelecionadosAnexos = $this->RiscosImpactosSelecionadosAnexos->patchEntity($riscoImpactoSelecionadoAnexo, $dados_riscos_impactos_selecionados_anexos);

                //salva os dados
                if (!$this->RiscosImpactosSelecionadosAnexos->save($entityRiscosImpactosSelecionadosAnexos)) {
                    $data['message'] = 'Erro ao remover em RiscosImpactosSelecionadosAnexos';
                    $data['error'] = $entityRiscosImpactosSelecionadosAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = "Anexos removidos com sucesso!";
        }

        $this->set(compact('data'));

    }

    public function getRiscosTipo($codigo_cliente)
    {

        $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente)->codigo_cliente_matriz;
        
        $dados = $this->RiscosTipo->find()
            ->select([
                'codigo',
                'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
                'cor',
                'icone',
                'codigo_usuario_inclusao',
                'codigo_usuario_alteracao',
                'data_inclusao',
                'data_alteracao',
                'ativo',
                'codigo_cliente'
            ])
            ->where(['ativo' => 1, 'codigo_cliente' => $codigo_cliente_matriz]);

        $this->set(compact('dados'));
    }

    public function getPerigosAspectos($codigo_cliente)
    {

        $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente)->codigo_cliente_matriz;
        
        $dados = $this->PerigosAspectos->find()
            ->select([
                'codigo',
                'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
                'codigo_risco_tipo',
                'codigo_perigo_aspecto_tipo',
                'codigo_usuario_inclusao',
                'codigo_usuario_alteracao',
                'data_inclusao',
                'data_alteracao',
                'ativo',
                'codigo_cliente'
            ])
            ->where(['ativo' => 1, 'codigo_cliente' => $codigo_cliente_matriz]);

        $this->set(compact('dados'));
    }

    public function getRiscosImpactos($codigo_cliente)
    {

        $codigo_cliente_matriz = $this->GruposEconomicosClientes->getCodigoClienteMatriz($codigo_cliente)->codigo_cliente_matriz;
    
        $dados = $this->RiscosImpactos->find()
            ->select([
                'codigo',
                'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
                'codigo_perigo_aspecto',
                'codigo_usuario_inclusao',
                'codigo_usuario_alteracao',
                'limite_tolerancia',
                'data_inclusao',
                'data_alteracao',
                'codigo_metodo_tipo',
                'ativo',
                'codigo_cliente',
                'codigo_risco_impacto_tipo'
            ])
            ->where(['ativo' => 1, 'codigo_cliente' => $codigo_cliente_matriz]);

        $this->set(compact('dados'));
    }

    public function getMedidasDeControleHierarquia()
    {
        $dados = $this->MedidasControleHierarquiaTipo->find()->select(['codigo', 'descricao']);

        $this->set(compact('dados'));
    }

    public function getFontesExposixao()
    {
        $dados = $this->FontesGeradorasExposicaoTipo->find()->select(['codigo', 'descricao']);

        $this->set(compact('dados'));
    }

    public function postPutMedidasControle()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_inclusao = $this->getAuthUser();

            $riscosImpactosSelecionados = $this->ArrtpaRi->find()->where(['codigo' => $dados['codigo_arrtpa_ri']])->first();

            if (empty($riscosImpactosSelecionados)) {
                $error = 'Código do ArrtpaRi não encontrado';
                $this->set(compact('error'));
                return;
            }

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de Medidas de controle é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getMedidasControle = $this->MedidasControle->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getMedidasControle)) {
                    $error = 'Não foi encontrado medida de controle para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_inclusao;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityMedidasControle = $this->MedidasControle->patchEntity($getMedidasControle, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;

                $entityMedidasControle = $this->MedidasControle->newEntity($dados);
            }

            //salva os dados
            if (!$this->MedidasControle->save($entityMedidasControle)) {
                $data['message'] = 'Erro ao inserir em MedidasControle';
                $data['error'] = $entityMedidasControle->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityMedidasControle;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function deleteMedidasControle($codigo_medida_controle)
    {
        $getMedidasControle = $this->MedidasControle->find()->where(['codigo' => $codigo_medida_controle])->first();

        if (empty($getMedidasControle)) {
            $error = 'Medida de controle não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->MedidasControle->delete($getMedidasControle)) {
            $error = 'Erro ao remover a medida de controle!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "Medida de controle removida com sucesso!";

        $this->set(compact('data'));

    }

    public function postPutFotosMedidasControle()
    {
        $this->request->allowMethod(['post', 'put']); // aceita apenas POST e PUT

        //Abre a transação
        $this->connect->begin();

        try {

            //pega os dados que veio do post
            $params = $this->request->getData();

            $codigo_usuario_inclusao = $this->getAuthUser();

            if (empty($params['foto'])) {
                $data['error'] = "Precisa enviar a foto";
                $this->set(compact('data'));
                return;
            }
            //monta o array para enviar
            $dados = array(
                'file'   => $params['foto'],
                'prefix' => 'seguranca',
                'type'   => 'base64'
            );

            //url de imagem
            $url_imagem = Comum::sendFileToServer($dados);
            //pega o caminho da imagem
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

            //verifica se subiu corretamente a imagem
            if (!empty($caminho_image)) {

                //seta o valor para a imagem que esta sendo criada
                $fotos = FILE_SERVER.$caminho_image['path'];

                $dados_medidas_controle = array();

                $getMedidasControles = $this->MedidasControle->find()->where(['codigo' => $params['codigo_medida_controle']])->first();

                if (empty($getMedidasControles)) {
                    $error = 'Código de MedidasControle não encontrado';
                    $this->set(compact('error'));
                    return;
                }

                if ($this->request->is(['put'])) {

                    $medidasControle = $this->MedidasControleAnexos->find()->where(['codigo' => $params['codigo']])->first();

                    if (empty($medidasControle)) {
                        $error = 'Código de MedidasControleAnexos não encontrado';
                        $this->set(compact('error'));
                        return;
                    }

                    $dados_medidas_controle["codigo_usuario_alteracao"] = $codigo_usuario_inclusao;
                    $dados_medidas_controle["data_alteracao"] = date('Y-m-d H:i:s');
                    $dados_medidas_controle["codigo_medida_controle"] = $medidasControle['codigo_medida_controle'];
                    $dados_medidas_controle["arquivo_url"] = $fotos;

                    $entityMedidasControle = $this->MedidasControleAnexos->patchEntity($medidasControle, $dados_medidas_controle);

                } else {

                    $dados_medidas_controle["codigo_usuario_inclusao"] = $codigo_usuario_inclusao;
                    $dados_medidas_controle["codigo_medida_controle"] = $params['codigo_medida_controle'];
                    $dados_medidas_controle["arquivo_url"] = $fotos;

                    $entityMedidasControle = $this->MedidasControleAnexos->newEntity($dados_medidas_controle);
                }

                //salva os dados
                if (!$this->MedidasControleAnexos->save($entityMedidasControle)) {
                    $data['message'] = 'Erro ao inserir em MedidasControleAnexos';
                    $data['error'] = $entityMedidasControle->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityMedidasControle;

                // Salva dados
                $this->connect->commit();

                $this->set(compact('data'));

            } else {
                $error = "Problemas em enviar a imagem para o file-server";
            }

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getFotosMedidasControle($codigo_medida_controle, $codigo = null)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        if (!empty($codigo)) {

            $data = $this->MedidasControleAnexos->find()->where(['codigo' => $codigo, 'data_remocao is null'])->first();
        } else {
            $data = $this->MedidasControleAnexos->find()->where(['codigo_medida_controle' => $codigo_medida_controle, 'data_remocao is null']);
        }

        $this->set(compact('data'));
    }

    public function deleteFotosMedidasControle($codigo_medida_controle, $codigo_medida_controle_anexo = null)
    {
        $this->request->allowMethod(['delete']); // aceita apenas DELETE

        $codigo_usuario_logado = $this->getAuthUser();

        if (!empty($codigo_medida_controle_anexo)) {

            $medidasControleAnexos = $this->MedidasControleAnexos->find()->where(['codigo' => $codigo_medida_controle_anexo])->first();

            $dados_medidas_controle_anexos["codigo_usuario_alteracao"] = $codigo_usuario_logado;
            $dados_medidas_controle_anexos["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_medidas_controle_anexos["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($medidasControleAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            $entityMedidasControleAnexos = $this->MedidasControleAnexos->patchEntity($medidasControleAnexos, $dados_medidas_controle_anexos);

            //salva os dados no banco
            if (!$this->MedidasControleAnexos->save($entityMedidasControleAnexos)) {
                $data['message'] = 'Erro ao remover em MedidasControleAnexos';
                $data['error'] = $entityMedidasControleAnexos->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = "Anexo removido com sucesso!";

        } else {
            $medidasControleAnexos = $this->MedidasControleAnexos->find()->where(['codigo_medida_controle' => $codigo_medida_controle])->toArray();
            $dados_medidas_controle_anexos["codigo_usuario_alteracao"] = $codigo_usuario_logado;
            $dados_medidas_controle_anexos["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_medidas_controle_anexos["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($medidasControleAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            foreach ($medidasControleAnexos as $medidaControleAnexo) {

                $entityMedidasControleAnexos = $this->MedidasControleAnexos->patchEntity($medidaControleAnexo, $dados_medidas_controle_anexos);

                //salva os dados
                if (!$this->MedidasControleAnexos->save($entityMedidasControleAnexos)) {
                    $data['message'] = 'Erro ao remover em MedidasControleAnexos';
                    $data['error'] = $entityMedidasControleAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = "Anexos removidos com sucesso!";
        }

        $this->set(compact('data'));

    }

    public function addHazops()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();
            $dados['codigo_usuario_inclusao'] = $codigo_usuario;

            $entityHazopsAgentesRiscos = $this->HazopsAgentesRiscos->newEntity($dados);

            if (!$this->HazopsAgentesRiscos->save($entityHazopsAgentesRiscos)) {
                $data['message'] = 'Erro ao inserir em HazopsAgentesRiscos';
                $data['error'] = $entityHazopsAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }


            $dadosHazopNos = array();

            foreach ($dados['hazop_nos'] as $hazop_nos) {

                $dadosHazopNos['codigo_usuario_inclusao'] = $codigo_usuario;
                $dadosHazopNos['codigo_hazop_agente_risco'] = $entityHazopsAgentesRiscos['codigo'];
                $dadosHazopNos['codigo_processo_hazop_nos'] = $hazop_nos['codigo_processo_hazop_nos'];

                $entityHazopsNosAgentesRiscos = $this->HazopsNosAgentesRiscos->newEntity($dadosHazopNos);

                if (!$this->HazopsNosAgentesRiscos->save($entityHazopsNosAgentesRiscos)) {
                    $data['message'] = 'Erro ao inserir em HazopsNosAgentesRiscos';
                    $data['error'] = $entityHazopsNosAgentesRiscos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityHazopsNosAgentesRiscos;
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function putHazopsAgentesRiscos()
    {

        $this->request->allowMethod(['put']); // aceita apenas PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            $hazopsAgentesRiscos = $this->HazopsAgentesRiscos->find()->where(['codigo' => $dados['codigo']])->first();

            if (empty($hazopsAgentesRiscos)) {
                $error = 'HazopsAgentesRiscos não encontrado';
                $this->set(compact('error'));
                return;
            }

            $dados['codigo_usuario_alteracao'] = $codigo_usuario;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');

            $entityHazopsAgentesRiscos = $this->HazopsAgentesRiscos->patchEntity($hazopsAgentesRiscos, $dados);

            //salva os dados
            if (!$this->HazopsAgentesRiscos->save($entityHazopsAgentesRiscos)) {
                $data['message'] = 'Erro ao editar em HazopsAgentesRiscos';
                $data['error'] = $entityHazopsAgentesRiscos->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityHazopsAgentesRiscos;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function postPutHazopsMedidasControleTipo()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                $getHazopsMedidasControleTipo = $this->HazopsMedidasControleTipo->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getHazopsMedidasControleTipo)) {
                    $error = 'Não foi encontrado medida de controle tipo para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityHazopsMedidasControleTipo = $this->HazopsMedidasControleTipo->patchEntity($getHazopsMedidasControleTipo, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario;

                $entityHazopsMedidasControleTipo = $this->HazopsMedidasControleTipo->newEntity($dados);
            }

            //salva os dados
            if (!$this->HazopsMedidasControleTipo->save($entityHazopsMedidasControleTipo)) {
                $data['message'] = 'Erro ao inserir em HazopsMedidasControleTipo';
                $data['error'] = $entityHazopsMedidasControleTipo->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityHazopsMedidasControleTipo;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getHazopsMedidasControleTipo()
    {
        $dados = $this->HazopsMedidasControleTipo->find()->select(['codigo', 'descricao']);

        $this->set(compact('dados'));
    }

    public function postPutHazopsMedidasControle()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            $hazopsAgentesRiscos = $this->HazopsAgentesRiscos->find()->where(['codigo' => $dados['codigo_hazop_agente_risco']])->first();

            if (empty($hazopsAgentesRiscos)) {
                $error = 'Código do HazopsAgentesRiscos não encontrado';
                $this->set(compact('error'));
                return;
            }

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de Medidas de controle Hazop é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getHazopsMedidasControle = $this->HazopsMedidasControle->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getHazopsMedidasControle)) {
                    $error = 'Não foi encontrado medida de controle para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityHazopsMedidasControle = $this->HazopsMedidasControle->patchEntity($getHazopsMedidasControle, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario;

                $entityHazopsMedidasControle = $this->HazopsMedidasControle->newEntity($dados);
            }

            //salva os dados
            if (!$this->HazopsMedidasControle->save($entityHazopsMedidasControle)) {
                $data['message'] = 'Erro ao inserir em HazopsMedidasControle';
                $data['error'] = $entityHazopsMedidasControle->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityHazopsMedidasControle;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getHazopsMedidasControle($codigo_hazop_agente_risco)
    {
        $dados = $this->HazopsMedidasControle->find()
            ->select(['codigo', 'codigo_hazop_agente_risco', 'codigo_hazop_medida_controle_tipo', 'descricao'])
            ->where(['codigo_hazop_agente_risco' => $codigo_hazop_agente_risco]);

        $this->set(compact('dados'));
    }

    public function deleteHazopsMedidasControle($codigo)
    {
        $getHazopsMedidasControle = $this->HazopsMedidasControle->find()->where(['codigo' => $codigo])->first();

        if (empty($getHazopsMedidasControle)) {
            $error = 'Medida de controle hazop não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->HazopsMedidasControle->delete($getHazopsMedidasControle)) {
            $error = 'Erro ao remover a medida de controle hazop!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "Medida de controle hazop removida com sucesso!";

        $this->set(compact('data'));

    }

    public function postPutFotosHazopsMedidasControle()
    {
        $this->request->allowMethod(['post', 'put']); // aceita apenas POST e PUT

        //Abre a transação
        $this->connect->begin();

        try {

            //pega os dados que veio do post
            $params = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            if (empty($params['foto'])) {
                $data['error'] = "Precisa enviar a foto";
                $this->set(compact('data'));
                return;
            }
            //monta o array para enviar
            $dados = array(
                'file'   => $params['foto'],
                'prefix' => 'seguranca',
                'type'   => 'base64'
            );

            //url de imagem
            $url_imagem = Comum::sendFileToServer($dados);

            //pega o caminho da imagem
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

            //verifica se subiu corretamente a imagem
            if (!empty($caminho_image)) {

                //seta o valor para a imagem que esta sendo criada
                $fotos = FILE_SERVER.$caminho_image['path'];

                $dados_medidas_controle = array();

                $getHazopsMedidasControle = $this->HazopsMedidasControle->find()->where(['codigo' => $params['codigo_hazop_medida_controle']])->first();

                if (empty($getHazopsMedidasControle)) {
                    $error = 'Código de HazopsMedidasControle não encontrado';
                    $this->set(compact('error'));
                    return;
                }

                if ($this->request->is(['put'])) {

                    $medidasControle = $this->HazopsMedidasControleAnexos->find()->where(['codigo' => $params['codigo']])->first();

                    if (empty($medidasControle)) {
                        $error = 'Código de HazopsMedidasControleAnexos não encontrado';
                        $this->set(compact('error'));
                        return;
                    }

                    $dados_medidas_controle["codigo_usuario_alteracao"] = $codigo_usuario;
                    $dados_medidas_controle["data_alteracao"] = date('Y-m-d H:i:s');
                    $dados_medidas_controle["codigo_hazop_medida_controle"] = $medidasControle['codigo_hazop_medida_controle'];
                    $dados_medidas_controle["arquivo_url"] = $fotos;

                    $entityHazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->patchEntity($medidasControle, $dados_medidas_controle);

                } else {

                    $dados_medidas_controle["codigo_usuario_inclusao"] = $codigo_usuario;
                    $dados_medidas_controle["codigo_hazop_medida_controle"] = $params['codigo_hazop_medida_controle'];
                    $dados_medidas_controle["arquivo_url"] = $fotos;

                    $entityHazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->newEntity($dados_medidas_controle);
                }

                //salva os dados
                if (!$this->HazopsMedidasControleAnexos->save($entityHazopsMedidasControleAnexos)) {
                    $data['message'] = 'Erro ao inserir em HazopsMedidasControleAnexos';
                    $data['error'] = $entityHazopsMedidasControleAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityHazopsMedidasControleAnexos;

                // Salva dados
                $this->connect->commit();

                $this->set(compact('data'));

            } else {
                $error = "Problemas em enviar a imagem para o file-server";
            }

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function deleteFotosHazopsMedidasControle($codigo_hazop_medida_controle, $codigo_hazop_medida_controle_anexo = null)
    {
        $this->request->allowMethod(['delete']); // aceita apenas DELETE

        //Abre a transação
        $this->connect->begin();

        try {

            $codigo_usuario_logado = $this->getAuthUser();

            if (!empty($codigo_hazop_medida_controle_anexo)) {

                $hazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->find()->where(['codigo' => $codigo_hazop_medida_controle_anexo])->first();

                $dados_hazop_medidas_controle_anexos["codigo_usuario_alteracao"] = $codigo_usuario_logado;
                $dados_hazop_medidas_controle_anexos["data_alteracao"] = date('Y-m-d H:i:s');
                $dados_hazop_medidas_controle_anexos["data_remocao"]   = date('Y-m-d H:i:s');

                if (empty($hazopsMedidasControleAnexos)) {
                    $error = 'Não foi encontrado anexo para remover';
                    $this->set(compact('error'));
                    return;
                }

                $entityHazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->patchEntity($hazopsMedidasControleAnexos, $dados_hazop_medidas_controle_anexos);

                //salva os dados no banco
                if (!$this->HazopsMedidasControleAnexos->save($entityHazopsMedidasControleAnexos)) {
                    $data['message'] = 'Erro ao remover em HazopsMedidasControleAnexos';
                    $data['error'] = $entityHazopsMedidasControleAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityHazopsMedidasControleAnexos;

            } else {
                $hazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->find()->where(['codigo_hazop_medida_controle' => $codigo_hazop_medida_controle])->toArray();

                $dados_hazop_medidas_controle_anexos["codigo_usuario_alteracao"] = $codigo_usuario_logado;
                $dados_hazop_medidas_controle_anexos["data_alteracao"] = date('Y-m-d H:i:s');
                $dados_hazop_medidas_controle_anexos["data_remocao"]   = date('Y-m-d H:i:s');

                if (empty($hazopsMedidasControleAnexos)) {
                    $error = 'Não foi encontrado anexo para remover';
                    $this->set(compact('error'));
                    return;
                }

                foreach ($hazopsMedidasControleAnexos as $medidaControleAnexo) {

                    $entityHazopsMedidasControleAnexos = $this->HazopsMedidasControleAnexos->patchEntity($medidaControleAnexo, $dados_hazop_medidas_controle_anexos);

                    //salva os dados
                    if (!$this->HazopsMedidasControleAnexos->save($entityHazopsMedidasControleAnexos)) {
                        $data['message'] = 'Erro ao remover em HazopsMedidasControleAnexos';
                        $data['error'] = $entityHazopsMedidasControleAnexos->errors();
                        $this->set(compact('data'));
                        return;
                    }
                }

                $data[] = "Anexos hazops removidos com sucesso!";
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * HazopsKeywordTipo
     * POST, PUT method
     */
    public function postPutHazopsKeywordTipo()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de HazopsKeywordTipo é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getHazopsKeywordTipo = $this->HazopsKeywordTipo->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getHazopsKeywordTipo)) {
                    $error = 'Não foi encontrado HazopsKeywordTipo para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityHazopsKeywordTipo = $this->HazopsKeywordTipo->patchEntity($getHazopsKeywordTipo, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityHazopsKeywordTipo = $this->HazopsKeywordTipo->newEntity($dados);
            }

            //salva os dados
            if (!$this->HazopsKeywordTipo->save($entityHazopsKeywordTipo)) {
                $data['message'] = 'Erro ao inserir em HazopsKeywordTipo';
                $data['error'] = $entityHazopsKeywordTipo->errors();
                $this->set(compact('data'));
                return;
            }

            $data = $entityHazopsKeywordTipo;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * HazopsKeywordTipo
     * GET method
     */
    public function getHazopsKeywordTipo()
    {
        $data = $this->HazopsKeywordTipo->find()->select(['codigo', 'descricao', 'data_inclusao', 'data_alteracao']);

        $this->set(compact('data'));
    }

    /**
     * HazopsKeywordTipo
     * DELETE method
     */
    public function deleteHazopsKeywordTipo($codigo)
    {
        $getHazopsKeywordTipo = $this->HazopsKeywordTipo->find()->where(['codigo' => $codigo])->first();

        if (empty($getHazopsKeywordTipo)) {
            $error = 'HazopsKeywordTipo não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->HazopsKeywordTipo->delete($getHazopsKeywordTipo)) {
            $error = 'Erro ao remover a HazopsKeywordTipo!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "HazopsKeywordTipo removido com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * HazopsKeyword
     * POST, PUT method
     */
    public function postPutHazopsKeyword()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de HazopsKeyword é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getHazopsKeyword = $this->HazopsKeyword->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getHazopsKeyword)) {
                    $error = 'Não foi encontrado HazopsKeyword para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityHazopsKeyword = $this->HazopsKeyword->patchEntity($getHazopsKeyword, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityHazopsKeyword = $this->HazopsKeyword->newEntity($dados);
            }

            //salva os dados
            if (!$this->HazopsKeyword->save($entityHazopsKeyword)) {
                $data['message'] = 'Erro ao inserir em HazopsKeyword';
                $data['error'] = $entityHazopsKeyword->errors();
                $this->set(compact('data'));
                return;
            }

            $data = $entityHazopsKeyword;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * HazopsKeyword
     * GET method
     */
    public function getHazopsKeyword($codigo_hazop_agente_risco)
    {

        $data = $this->HazopsKeyword->find()
            ->select(['codigo', 'codigo_hazop_keyword_tipo', 'codigo_hazop_agente_risco', 'data_inclusao', 'data_alteracao'])
            ->where(['codigo_hazop_agente_risco' => $codigo_hazop_agente_risco]);

        $this->set(compact('data'));
    }

    public function getMeuRiscosByUser($codigo_usuario)
    {

        $getUsuariosDados = $this->UsuariosDados->find()
            ->select(['cpf'])
            ->where(['codigo_usuario' => $codigo_usuario])->first();

        if (empty($getUsuariosDados)) {
            $error = 'Código de Usuario não encontrado!';
            $this->set(compact('error'));
            return;
        }

        if (!empty($getUsuariosDados['cpf'])) {

            $funcionarios = $this->Funcionarios->find()->where(['cpf' => $getUsuariosDados['cpf']])->first();

            if (empty($funcionarios)) {
                $error = 'Usuário não é funcionário';
                $this->set(compact('error'));
                return;
            }

            $cliente_funcionario = $this->ClienteFuncionario->find()->where(['codigo_funcionario' => $funcionarios['codigo']])->first();

            $funcionario_setores_cargos = $this->FuncionarioSetoresCargos->find()
                ->select(['codigo', 'codigo_cliente', 'codigo_setor', 'codigo_cargo'])
                ->where(['codigo_cliente_funcionario' => $cliente_funcionario['codigo'], 'data_fim is NULL '])->first();

            $codigo_setor = $funcionario_setores_cargos['codigo_setor'];
            $codigo_cargo = $funcionario_setores_cargos['codigo_cargo'];

            $get_agentes_riscos = $this->AgentesRiscos->getAgentesRiscosByGhe($codigo_setor, $codigo_cargo);

            // Listagem dos riscos
            foreach ($get_agentes_riscos as $key => $ri) {

                //Pega asinatura
                $assinaturas = $this->UsuarioAgentesRiscos->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_usuario', 'data_assinatura', 'arquivo_url'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri'], 'codigo_usuario' => $codigo_usuario])->first();

                $get_agentes_riscos[$key]['assinatura'] = $assinaturas;

                // Pega as fotos de agentes de riscos
                $agente_riscos_anexos = $this->RiscosImpactosSelecionadosAnexos->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'arquivo_url'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri'], 'data_remocao is null']);

                if (!empty($agente_riscos_anexos)) {
                    $get_agentes_riscos[$key]['agentes_riscos_anexos'] = $agente_riscos_anexos;
                }

                // Descrição de riscos
                $descricao_risco = $this->RiscosImpactosSelecionadosDescricoes->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'descricao_risco', 'descricao_exposicao', 'pessoas_expostas'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri']])->first();

                if (!empty($descricao_risco)) {
                    $fontes_geradoras_exposicao = $this->FontesGeradorasExposicao->find()
                        ->select(['codigo','codigo_risco_impacto_selecionado_descricao', 'codigo_fonte_geradora_exposicao_tipo'])
                        ->where(['codigo_risco_impacto_selecionado_descricao' => $descricao_risco['codigo']])->toArray();

                    if (!empty($fontes_geradoras_exposicao)) {
                        $get_agentes_riscos[$key]['fontes_geradoras_exposicao'] = $fontes_geradoras_exposicao;
                    }

                    $get_agentes_riscos[$key]['descricao_risco'] = $descricao_risco['descricao_risco'];
                    $get_agentes_riscos[$key]['descricao_exposicao'] = $descricao_risco['descricao_exposicao'];

                    $etapasAfetadas = $this->AgentesRiscosEtapas->find()
                        ->select([
                            'codigo' => 'AgentesRiscosEtapas.codigo', 
                            'codigo_processo_ferramenta' => 'AgentesRiscosEtapas.codigo_processo_ferramenta',
                            'codigo_processo' => 'ProcessosFerramentas.codigo_processo',
                            'descricao' => 'ProcessosFerramentas.descricao',
                            'posicao' => 'ProcessosFerramentas.posicao',
                        ])
                        ->join([
                            [
                                'table' => 'processos_ferramentas',
                                'alias' => 'ProcessosFerramentas',
                                'type' => 'INNER',
                                'conditions' => 'ProcessosFerramentas.codigo = AgentesRiscosEtapas.codigo_processo_ferramenta',
                            ],
                        ])
                        ->where(['codigo_agente_risco' => $ri['codigo_agente_risco']])
                        ->toArray();
                    
                    $processo = null;

                    if (is_array($etapasAfetadas) && count($etapasAfetadas) > 0) {
                        $processo = $this->Processos->find()
                            ->where(['codigo' => $etapasAfetadas[0]['codigo_processo']])
                            ->first();

                        if (!empty($processo)) {
                            $anexosProcesso = $this->ProcessosAnexos->find()
                                ->select([
                                    'codigo',
                                    'arquivo_url',
                                    'codigo_usuario_inclusao',
                                    'codigo_usuario_alteracao',
                                    'data_inclusao',
                                    'data_alteracao',
                                ])
                                ->where([
                                    'codigo_processo' => $etapasAfetadas[0]['codigo_processo'],
                                    'data_remocao IS NULL'
                                ])
                                ->toArray();

                            $processo['anexos'] = $anexosProcesso;
                        }
                    }
                    
                    $get_agentes_riscos[$key]['etapas_afetadas'] = $etapasAfetadas;
                    $get_agentes_riscos[$key]['processo'] = $processo;
                }

                // Pegar medidas de controle para um risco/impacto
                $medidas_controle = $this->MedidasControle->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_medida_controle_hierarquia_tipo', 'titulo', 'descricao'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri']])->toArray();

                if (!empty($medidas_controle)) {

                    foreach ($medidas_controle as $mc) {

                        // Retorna as fotos de medidas de controle
                        $medidas_controle_anexos = $this->MedidasControleAnexos->find()
                            ->select(['codigo', 'codigo_medida_controle', 'arquivo_url'])
                            ->where(['codigo_medida_controle' => $mc['codigo'], 'data_remocao is null' ]);

                        $mc['medidas_controle_anexos'] = $medidas_controle_anexos;

                    }

                    $get_agentes_riscos[$key]['medidas_controle'] = $medidas_controle;
                }
            }
        }

        return $get_agentes_riscos;
    }

    public function getMeuRiscosByUserTime($codigo_usuario, $codigo_gestor = null)
    {

        $getUsuariosDados = $this->UsuariosDados->find()
            ->select(['cpf'])
            ->where(['codigo_usuario' => $codigo_usuario])->first();

        if (empty($getUsuariosDados)) {
            $error = 'Código de Usuario não encontrado!';
            $this->set(compact('error'));
            return;
        }

        if (!empty($getUsuariosDados['cpf'])) {

            $funcionarios = $this->Funcionarios->find()->where(['cpf' => $getUsuariosDados['cpf']])->first();

            if (empty($funcionarios)) {
                $error = 'Usuário não é funcionário';
                $this->set(compact('error'));
                return;
            }

            $cliente_funcionario = $this->ClienteFuncionario->find()->where(['codigo_funcionario' => $funcionarios['codigo']])->first();

            $funcionario_setores_cargos = $this->FuncionarioSetoresCargos->find()
                ->select(['codigo', 'codigo_cliente', 'codigo_setor', 'codigo_cargo'])
                ->where(['codigo_cliente_funcionario' => $cliente_funcionario['codigo'], 'data_fim is NULL '])->first();

            $codigo_setor = $funcionario_setores_cargos['codigo_setor'];
            $codigo_cargo = $funcionario_setores_cargos['codigo_cargo'];

            $get_agentes_riscos = $this->AgentesRiscos->getAgentesRiscosByGhe($codigo_setor, $codigo_cargo);

            // Listagem dos riscos
            foreach ($get_agentes_riscos as $key => $ri) {

                // Pega as fotos de agentes de riscos
                $agente_riscos_anexos = $this->RiscosImpactosSelecionadosAnexos->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'arquivo_url'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri'], 'data_remocao is null']);

                if (!empty($agente_riscos_anexos)) {
                    $get_agentes_riscos[$key]['agentes_riscos_anexos'] = $agente_riscos_anexos;
                }

                // Descrição de riscos
                $descricao_risco = $this->RiscosImpactosSelecionadosDescricoes->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'descricao_risco', 'descricao_exposicao', 'pessoas_expostas'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri']])->first();

                if (!empty($descricao_risco)) {

                    $fontes_geradoras_exposicao = $this->FontesGeradorasExposicao->find()
                        ->select(['codigo','codigo_risco_impacto_selecionado_descricao', 'codigo_fonte_geradora_exposicao_tipo'])
                        ->where(['codigo_risco_impacto_selecionado_descricao' => $descricao_risco['codigo']])->toArray();

                    if (!empty($fontes_geradoras_exposicao)) {

                        $get_agentes_riscos[$key]['fontes_geradoras_exposicao'] = $fontes_geradoras_exposicao;
                    }
                }

                // Pegar medidas de controle para um risco/impacto
                $medidas_controle = $this->MedidasControle->find()
                    ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_medida_controle_hierarquia_tipo', 'titulo', 'descricao'])
                    ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri']])->toArray();

                if (!empty($medidas_controle)) {

                    foreach ($medidas_controle as $mc) {

                        // Retorna as fotos de medidas de controle
                        $medidas_controle_anexos = $this->MedidasControleAnexos->find()
                            ->select(['codigo', 'codigo_medida_controle', 'arquivo_url'])
                            ->where(['codigo_medida_controle' => $mc['codigo'], 'data_remocao is null' ]);

                        $mc['medidas_controle_anexos'] = $medidas_controle_anexos;

                    }

                    $get_agentes_riscos[$key]['medidas_controle'] = $medidas_controle;
                }

                //Funcionarios expostos ao risco
                $funcionarios = $this->FuncionarioSetoresCargos->getFuncionariosExpostos($codigo_cargo, $codigo_gestor);

                $get_agentes_riscos[$key]['usuarios_expostos'] = $funcionarios;

                //Arrays de Assinaturas
                $get_agentes_riscos[$key]['assinadas'] = array();
                $get_agentes_riscos[$key]['pendentes'] = array();

                foreach ($funcionarios as $func) {

                    $assinaturas = $this->UsuarioAgentesRiscos->find()
                        ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_usuario', 'data_assinatura', 'arquivo_url'])
                        ->where(['codigo_arrtpa_ri' => $ri['codigo_arrtpa_ri'], 'codigo_usuario' => $func['codigo']])->first();

                    if (!empty($assinaturas)) {
                        $get_agentes_riscos[$key]['assinadas'][] = $assinaturas;
                    } else {
                        $get_agentes_riscos[$key]['pendentes'][] = $func;
                    }

                }
            }
        }

        return $get_agentes_riscos;
    }

    public function getUsuarioMeusRiscos($codigo_usuario)
    {

        $data = array();

        $agentes_de_riscos = $this->getMeuRiscosByUser($codigo_usuario);

        $data = $agentes_de_riscos;

        $this->set(compact('data'));

    }

    public function getMeuTimeRiscos($codigo_usuario)
    {

        $data = array();
        $array_riscos_time = array();

        $meu_time = $this->UsuarioFuncao->getUsuarioMeuTime($codigo_usuario);

        foreach ($meu_time as $time) {

            $codigo = (int) $time['codigo_usuario'];
            $meus_riscos = $this->getMeuRiscosByUserTime($codigo, $codigo_usuario);

            $array_riscos_time[] = $meus_riscos;
        }

        $arr_riscos = array();
        foreach ($array_riscos_time as $art) {

            foreach ($art as $dados) {
                $arr_riscos[] = $dados;
            }
        }

        $riscos_time = array_unique($arr_riscos, SORT_REGULAR);

        $data['riscos-time'] = array_values($riscos_time);


        $this->set(compact('data'));
    }

    public function pustUsuarioAgenteRisco()
    {

        $this->request->allowMethod(['post']); // aceita apenas POST

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $params = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if (empty($params['foto'])) {
                $data['error'] = "Precisa enviar a foto";
                $this->set(compact('data'));
                return;
            }
            //monta o array para enviar
            $dados = array(
                'file'   => $params['foto'],
                'prefix' => 'seguranca',
                'type'   => 'base64'
            );

            //url de imagem
            $url_imagem = Comum::sendFileToServer($dados);
            //pega o caminho da imagem
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

            //verifica se subiu corretamente a imagem
            if (!empty($caminho_image)) {

                //seta o valor para a imagem que esta sendo criada
                $fotos = FILE_SERVER.$caminho_image['path'];


                $params["codigo_usuario_inclusao"] = $codigo_usuario_logado;
                $params["data_inclusao"] = date('Y-m-d H:i:s');
                $params["data_assinatura"] = date('Y-m-d H:i:s');
                $params["arquivo_url"] = $fotos;

                $entityUsuarioAgentesRiscos = $this->UsuarioAgentesRiscos->newEntity($params);

                //salva os dados
                if (!$this->UsuarioAgentesRiscos->save($entityUsuarioAgentesRiscos)) {
                    $data['message'] = 'Erro ao inserir em UsuarioAgentesRiscos';
                    $data['error'] = $entityUsuarioAgentesRiscos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityUsuarioAgentesRiscos;

                // Salva dados
                $this->connect->commit();

                $this->set(compact('data'));

            } else {
                $error = "Problemas em enviar a imagem para o file-server";
                $this->set(compact('error'));
            }


        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function deleteAgentesRiscos($codigo_agente_risco)
    {

        $this->request->allowMethod(['delete']); // aceita apenas delete

        //Abre a transação
        $this->connect->begin();

        try {
            $dados = array();

            $agentes_riscos = $this->AgentesRiscos->find()
                ->where(['codigo' => $codigo_agente_risco])->first();

            if (!empty($agentes_riscos)) {

                $codigo_usuario_logado = $this->getAuthUser();
                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados["data_alteracao"] = date('Y-m-d H:i:s');
                $dados["data_remocao"] = date('Y-m-d H:i:s');

                $entityAgentesRiscos = $this->AgentesRiscos->patchEntity($agentes_riscos, $dados );

                if (!$this->AgentesRiscos->save($entityAgentesRiscos)) {
                    $data['message'] = 'Erro ao remover em AgentesRiscos';
                    $data['error'] = $entityAgentesRiscos->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            // finalizada transacao
            $this->connect->commit();

            $message[] = "Removido com sucesso";
            $this->set(compact('message'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getAuthUser()
    {
        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if (empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        //seta o codigo usuario
        $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

        if (empty($codigo_usuario)) {
            $error = 'Logar novamente o usuario';
            $this->set(compact('error'));
            return;
        }

        return $codigo_usuario;
    }
}
