<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use App\Utils\Comum;
/**
 * Processos Controller
 *
 * @property \App\Model\Table\ProcessosTable $Processos
 *
 * @method \App\Model\Entity\Processo[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProcessosController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        $this->loadModel("ProcessosTipo");
        $this->loadModel("ProcessosFerramentas");
        $this->loadModel("ProcessosAnexos");
        $this->loadModel("AgentesRiscosEtapas");
        $this->loadModel("AgentesRiscos");
        $this->loadModel("ArRt");
        $this->loadModel("ArrtPa");
        $this->loadModel("ArrtpaRi");
        $this->loadModel("MedidasControle");
        $this->loadModel("Qualificacao");
        $this->loadModel("FerramentasAnalise");
        $this->loadModel("Aprho");
        $this->loadModel("RiscosImpactosSelecionadosDescricoes");
        $this->loadModel("FontesGeradorasExposicao");
        $this->loadModel("EquipamentosAdotados");

        $this->loadModel("HazopsAgentesRiscos");
        $this->loadModel("HazopsKeywordTipo");
        $this->loadModel("HazopsKeyword");
        $this->loadModel("HazopsMedidasControleTipo");
        $this->loadModel("HazopsMedidasControle");
        $this->loadModel("HazopsMedidasControleAnexos");

        $this->loadModel("RiscosTipo");
        $this->loadModel("PerigosAspectos");
        $this->loadModel("RiscosImpactos");
    }

    /**
     * View method
     *
     * @param string|null $codigo_processo Processo id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_processo = null)
    {
        $data = array();

        if (isset($codigo_processo)) {
            $processos = $this->Processos->find()->where(['codigo' => $codigo_processo])->toArray();
        } else {
            $processos = $this->Processos->find()->toArray();
        }

        foreach ($processos as $key => $processo) {

            if ($processo['codigo_processo_tipo'] == 2) {

                $processos_ferramentas = $this->ProcessosFerramentas->find()
                    ->select(['codigo','codigo_processo', 'descricao', 'posicao'])
                    ->where(['codigo_processo' => $processo['codigo']])->toArray();

                $agentes_riscos_relacionados = array();

                $arr = array();

                foreach ($processos_ferramentas as $pf) {
                    $arr[] = $pf['codigo'];
                }

                $arr = implode(",", $arr );

                if (!empty($arr)) {

                    $agentes_riscos_etapas = $this->AgentesRiscosEtapas->getAgentesRiscosEtapas($arr);

                    if (!empty($agentes_riscos_etapas)) {

                        $ag = array();

                        foreach ($agentes_riscos_etapas as $agres) {
                            $ag[] = $agres['AgentesRiscos']['codigo'];
                        }

                        // Agrupa os codigos de agentes de riscos
                        $codigos_ag = array_unique($ag, SORT_REGULAR);

                        foreach ($codigos_ag as $codigo_ag) {

                            // Retorna os agentes de risco relacionados a etapaa
                            $agentes_riscos = $this->getAgentesRiscos($codigo_ag);

                            if (!empty($agentes_riscos['riscos_impactos'])) {

                                foreach ($agentes_riscos['riscos_impactos'] as $key => $ri) {

                                    if (!empty($ri)) {

                                        // Descrição de riscos
                                        $descricao_risco = $this->RiscosImpactosSelecionadosDescricoes->find()
                                            ->select(['codigo', 'codigo_arrtpa_ri', 'descricao_risco', 'descricao_exposicao', 'pessoas_expostas'])
                                            ->where(['codigo_arrtpa_ri' => $ri['codigo']])->first();

                                        if (!empty($descricao_risco)) {

                                            $agentes_riscos['riscos_impactos'][$key]['descricao_risco'] = $descricao_risco;

                                            $fontes_geradoras_exposicao = $this->FontesGeradorasExposicao->find()
                                                ->select(['codigo','codigo_risco_impacto_selecionado_descricao', 'codigo_fonte_geradora_exposicao_tipo'])
                                                ->where(['codigo_risco_impacto_selecionado_descricao' => $descricao_risco['codigo']])->toArray();

                                            if (!empty($fontes_geradoras_exposicao)) {

                                                $agentes_riscos['riscos_impactos'][$key]['descricao_risco']['fontes_geradoras_exposicao'] = $fontes_geradoras_exposicao;
                                            }
                                        }

                                        // Pegar medidas de controle para um risco/impacto
                                        $medidas_controle = $this->MedidasControle->find()
                                            ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_medida_controle_hierarquia_tipo', 'titulo', 'descricao'])
                                            ->where(['codigo_arrtpa_ri' => $ri['codigo']])->toArray();

                                        if (!empty($medidas_controle)) {
                                            $agentes_riscos['riscos_impactos'][$key]['medidas_controle'] = $medidas_controle;
                                        }

                                        // Pegar qualificações para um risco/impacto
                                        $qualificacao = $this->Qualificacao->find()
                                            ->select(['codigo', 'codigo_arrtpa_ri', 'qualitativo', 'quantitativo', 'codigo_metodo_tipo', 'acidente_registrado', 'partes_afetadas', 'resultado_ponderacao'])
                                            ->where(['codigo_arrtpa_ri' => $ri['codigo']])->toArray();

                                        if (!empty($qualificacao)) {

                                            foreach ($qualificacao as $q) {
                                                // Pegar ferramentas de analise da qualificação
                                                $ferramentasAnalise = $this->FerramentasAnalise->find()
                                                    ->where(['codigo_qualificacao' => $q['codigo']])->toArray();

                                                if (!empty($ferramentasAnalise)) {

                                                    $q['ferramentas_analise'] = $ferramentasAnalise;
                                                }

                                                // Pegar APRHO da qualificação
                                                $aprhos = $this->Aprho->find()
                                                    ->select(['codigo', 'codigo_qualificacao', 'exposicao_duracao', 'exposicao_frequencia',
                                                        'codigo_fonte_geradora_exposicao_tipo', 'codigo_fonte_geradora_exposicao', 'codigo_agente_exposicao', 'qualitativo',
                                                        'relevancia', 'aceitabilidade', 'conselho_tecnico_resultado', 'conselho_tecnico_agenda', 'conselho_tecnico_descricao',
                                                        'codigo_conselho_tecnico_arquivo', 'medicoes_resultado', 'medicoes_agenda', 'arquivo_url'])
                                                    ->where(['codigo_qualificacao' => $q['codigo']])->toArray();

                                                if (!empty($aprhos)) {

                                                    $q['aprho'] = $aprhos;

                                                    foreach ($q['aprho'] as $key_aprho => $aprho) {

                                                        $q['aprho'][$key_aprho]['equipamentos_adotados'] = array();

                                                        $equipamentos_adotados = $this->EquipamentosAdotados->find()
                                                            ->select(['codigo', 'codigo_aprho', 'resultados', 'agenda_inspecao',
                                                                'codigo_equipamento_inspecao_tipo', 'codigo_unidade_medicao', 'valor', 'limite_tolerancia'])
                                                            ->where(['codigo_aprho' => $aprho['codigo']])->toArray();

                                                        if (!empty($equipamentos_adotados)) {
                                                            $q['aprho'][$key_aprho]['equipamentos_adotados'] = $equipamentos_adotados;

                                                        }
                                                    }

                                                }
                                            }

                                            $agentes_riscos['riscos_impactos'][$key]['qualificacao'] = $qualificacao;

                                        }
                                    }
                                }

                                $agentes_riscos_relacionados[] = $agentes_riscos;
                            }
                        }
                    }

                    $processo['etapas'] = $processos_ferramentas;
                    $processo['etapas_selecionadas'] = $agentes_riscos_etapas;
                    $processo['agentes_riscos'] = $agentes_riscos_relacionados;
                }

            } else {

                $agentes_riscos_etapas = array();
                $arr_hazopsAgentesRiscos = array();

                $processos_ferramentas = $this->ProcessosFerramentas->find()
                    ->select(['codigo','codigo_processo', 'descricao', 'posicao'])
                    ->where(['codigo_processo' => $processo['codigo']])->toArray();

                $arr = array();

                foreach ($processos_ferramentas as $pf) {
                    $arr[] = $pf['codigo'];
                }

                $arr = implode(",", $arr );

                if (!empty($arr)) {

                    $agentes_riscos_etapas = $this->AgentesRiscosEtapas->getAgentesRiscosEtapas($arr);

                    $ag = array();

                    if (!empty($agentes_riscos_etapas)) {

                        foreach ($agentes_riscos_etapas as $agres) {
                            $ag[] = $agres['AgentesRiscos']['codigo'];
                        }

                        // Trata os codigo AgentesRiscos, converte em string e concatena
                        $string_codigo = json_encode($ag);
                        $string_codigo = str_replace('[', '', $string_codigo);
                        $string_codigo = str_replace(']', '',$string_codigo);
                        $string_codigo = str_replace('"', '',$string_codigo);

                        $codigos_arrtapa_ri = $this->ArrtpaRi->find()->select(['codigo', 'codigo_arrt_pa', 'codigo_risco_impacto'])->where(['codigo_agente_risco IN ('.$string_codigo.')', 'e_hazop' => 1])->toArray();


                        if (!empty($codigos_arrtapa_ri)) {

                            foreach ($codigos_arrtapa_ri as $key => $ag_hazop) {

                                $hazopsAgentesRiscos = $this->HazopsAgentesRiscos->find()->where(['codigo_arrtpa_ri' => $ag_hazop['codigo']])->first();

                                //Descrição de RiscosImpactos
                                $risco_impacto = $this->RiscosImpactos->getRiscosImpactos($ag_hazop['codigo_risco_impacto']);
                                $hazopsAgentesRiscos['codigo_risco_impacto'] = $risco_impacto['codigo'];
                                $hazopsAgentesRiscos['risco_impacto'] = $risco_impacto['descricao'];

                                //Descrição de PerigosAspectos
                                $arrt_pa = $this->ArrtPa->find()->select(['codigo', 'codigo_ar_rt', 'codigo_perigo_aspecto'])->where(['codigo' => $ag_hazop['codigo_arrt_pa']])->first();
                                $perigos_aspectos = $this->PerigosAspectos->getPerigosAspectos($arrt_pa['codigo_perigo_aspecto']);

                                $hazopsAgentesRiscos['codigo_perigo_aspecto'] = $perigos_aspectos['codigo'];
                                $hazopsAgentesRiscos['perigo_aspecto'] = $perigos_aspectos['descricao'];

                                //Descrição de RiscosTipo
                                $ar_rt = $this->ArRt->find()->select(['codigo', 'codigo_risco_tipo'])->where(['codigo' => $arrt_pa['codigo_ar_rt']])->first();
                                $risco_tipo = $this->RiscosTipo->getRiscosTipos($ar_rt['codigo_risco_tipo']);

                                $hazopsAgentesRiscos['codigo_risco_tipo'] = $risco_tipo['codigo'];
                                $hazopsAgentesRiscos['risco_tipo'] = $risco_tipo['descricao'];

                                //Medidas de controle
                                $hazopsAgentesRiscos['medidas_controle'] = array();

                                if (!empty($hazopsAgentesRiscos)) {
                                    // Pegar medidas de controle
                                    $medidas_controle = $this->MedidasControle->find()
                                        ->select(['codigo', 'codigo_arrtpa_ri', 'codigo_medida_controle_hierarquia_tipo', 'titulo', 'descricao'])
                                        ->where(['codigo_arrtpa_ri' => $ag_hazop['codigo']])->toArray();

                                    if (!empty($medidas_controle)) {
                                        $hazopsAgentesRiscos['medidas_controle'] = $medidas_controle;
                                    }
                                }

                                $arr_hazopsAgentesRiscos[$key] = $hazopsAgentesRiscos;

                            }
                        }
                    }
                }

                $processo['hazop_nos'] = $processos_ferramentas;
                $processo['hazop_nos_selecionados'] = $agentes_riscos_etapas;
                $processo['hazop_agentes_riscos'] = $arr_hazopsAgentesRiscos;
            }
        }

        $data[] = $processos;

        $this->set(compact('data'));
    }

    public function getAgentesRiscos($codigo_agente_risco)
    {
        $ar = $this->AgentesRiscos->find()
            ->where(['codigo' => $codigo_agente_risco, 'data_remocao is null'])
            ->first();

        if (empty($ar)) {
            return;
        }

        $ar_rt = $this->ArRt->find()
            ->select(['codigo', 'codigo_agente_risco', 'codigo_risco_tipo'])
            ->where(['codigo_agente_risco' => $codigo_agente_risco])
            ->first();

        $ar_rt['perigos_aspectos'] = array();
        $ar_rt['riscos_impactos']  = array();

        if (!empty($ar_rt['codigo'])) {

            $arrt_pa = $this->ArrtPa->find()
                ->select(['codigo', 'codigo_ar_rt', 'codigo_perigo_aspecto'])
                ->where(['codigo_ar_rt' => $ar_rt['codigo']])
                ->toArray();

            $ar_rt['perigos_aspectos'] = $arrt_pa;
        } else {
            return;
        }

        if (!empty($ar_rt['perigos_aspectos'])) {

            foreach ($ar_rt['perigos_aspectos'] as $key => $perigos_aspectos ) {

                $arrtpa_ri = $this->ArrtpaRi->getRiscosImpactos($perigos_aspectos['codigo']);

//                $arrtpa_ri = $this->ArrtpaRi->find()
//                    ->select(['codigo', 'codigo_arrt_pa', 'codigo_risco_impacto'])
//                    ->where(['codigo_arrt_pa' => $perigos_aspectos['codigo']])
//                    ->toArray();

                $ar_rt['riscos_impactos'] = $arrtpa_ri;
            }
        }

        return $ar_rt;
    }

    /**
     * Edit method
     *
     * @param string|null $id Processo id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($codigo_processo)
    {
        //seta para o retorno do objeto
        $data = array();

        //pega os dados que veio do post
        $dados = $this->request->getData();

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

        // Retorna dados do processo
        $processo = $this->Processos->find()->where(['codigo' => $codigo_processo])->first();

        if ($this->request->is(['patch', 'put'])) {

            //Define campos gerados pelo sistema
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');

            $entityProcesso = $this->Processos->patchEntity($processo, $dados);

            if (!$this->Processos->save($processo)) {
                $data['message'] = 'Erro ao editar em Processos';
                $data['error'] = $entityProcesso->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityProcesso;
        }

        $this->set(compact('data'));
    }

    /**
     * getTipos()
     */
    public function getTipos()
    {
        $data = $this->ProcessosTipo->find();

        $this->set(compact('data'));
    }

    /**
     *
     * url: /api/processos/etapa (POST)
     * payload:
     {
       "codigo_processo": 3,
       "descricao": "Segunda etapa",
       "posicao": 2
     }
     *
     * url: /api/processos/etapa (PUT)
     * payload:
     {
       "codigo": 1,
       "descricao": "Segunda etapa Editando",
       "posicao": 2
     }
     *
     */
    public function postPutProcessoEtapa()
    {
        //Abre a transação
        $this->connect->begin();

        try {

            //seta para o retorno do objeto
            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

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
                throw new Exception("Logar novamente o usuario");
            }

            if ($this->request->is('put')) {

                $arr_pf = array();
                $inseridos = array();

                foreach ($dados as $key => $dado) {

                    if (!isset($dado['codigo'])) {

                        //Define campos gerados pelo sistema
                        $dado['codigo_usuario_inclusao'] = $codigo_usuario;
                        $dado['data_inclusao'] = date('Y-m-d H:i:s');

                        //Cria no entity para salvar no banco
                        $entityProcessosFerramentas = $this->ProcessosFerramentas->newEntity($dado);

                        if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                            $data['message'] = 'Erro ao inserir em ProcessosFerramentas';
                            $data['error'] = $entityProcessosFerramentas->errors();
                            $this->set(compact('data'));
                            return;
                        }

                        $inseridos[] = $entityProcessosFerramentas;

                    } else {
                        $processoFerramenta = $this->ProcessosFerramentas->find()->where(['codigo' => $dado['codigo']])->first();

                        // Verifica se exite etapa para editar
                        if (empty($processoFerramenta)) {
                            $data['errors'][$key]['message'] = "Codigo da etapa '" . $dados[$key]['codigo'] . "' inexistente";
                        } else {
                            // Armazena objeto no array
                            $arr_pf[] = $processoFerramenta;
                        }
                    }
                }

                // Se algum erro for encontrado, interrompe a requisição e retorna os erros
                if (!empty($data['errors'])) {
                    unset($data['inseridos']);
                    $this->set(compact('data'));
                    return;
                }

                // Trata os codigo ProcessosFerramentas, converte em string e concatena
                $arr_codigo = array_column($arr_pf, 'codigo');
                $string_codigo = json_encode($arr_codigo);
                $string_codigo = str_replace('[', '', $string_codigo);
                $string_codigo = str_replace(']', '',$string_codigo);

                if (!empty($string_codigo)) {

                    // Verifica se tem etapas relacionadas a AgentesRiscosEtapas
                    // Retorna o codigo_agente_risco para filtrar todas
                    // as etapas relacionadas a AgentesRiscosEtapas
                    $agentesRiscosEtapasGroup = $this->AgentesRiscosEtapas->find()
                        ->select(['codigo_agente_risco'])
                        ->where(['codigo_processo_ferramenta IN ('.$string_codigo.') '])
                        ->group('codigo_agente_risco')
                        ->toArray();

                    $age_codigo = array_column($agentesRiscosEtapasGroup, 'codigo_agente_risco');
                    $string_age_codigo = json_encode($age_codigo);
                    $string_age_codigo = str_replace('[', '', $string_age_codigo);
                    $string_age_codigo = str_replace(']', '',$string_age_codigo);

                    // Retorna todas as etapas relacionadas a AgentesRiscosEtapas
                    if (!empty($agentesRiscosEtapasGroup)) {
                        $agentesRiscosEtapas = $this->AgentesRiscosEtapas->find()
                            ->select(['codigo_processo_ferramenta'])
                            ->where(['codigo_agente_risco IN ('.$string_age_codigo.') '])
                            ->toArray();

                        $desvinculados = array();
                        foreach ($agentesRiscosEtapas as $ag_etapa) {

                            $codigo = $ag_etapa['codigo_processo_ferramenta'];

                            // Verifica se o codigo da etapa existe no array da requisição
                            if (array_search($codigo, array_column($arr_pf, 'codigo')) === false) {

                                $desvinculados[] = $codigo;

                                //Remove etapas relacionadas a AgentesRiscosEtapas
                                $this->AgentesRiscosEtapas->deleteAll(array(
                                    'AgentesRiscosEtapas.codigo_processo_ferramenta' => $codigo
                                ), false);
                            }
                        }
                    }

                    $processosFerramentasGroup = $this->ProcessosFerramentas->find()
                        ->select(['codigo_processo'])
                        ->where(['codigo IN ('.$string_codigo.') '])
                        ->group('codigo_processo')
                        ->first();

                    // Filtra codigos de novas etapas inseridas para não retorna-las na listagem
                    $inseridos_codigo = array_column($inseridos, 'codigo');
                    $string_inseridos_codigo = json_encode($inseridos_codigo);
                    $string_inseridos_codigo = str_replace('[', '', $string_inseridos_codigo);
                    $string_inseridos_codigo = str_replace(']', '',$string_inseridos_codigo);

                    if (!empty($string_inseridos_codigo)) {
                        $where = array(
                            'codigo_processo' => $processosFerramentasGroup['codigo_processo'],
                            'codigo not IN ('.$string_inseridos_codigo.')'
                        );
                    } else {
                        $where = array(
                            'codigo_processo' => $processosFerramentasGroup['codigo_processo']
                        );
                    }

                    // Retorna todas as etapas relacionadas a ProcessosFerramentas exceto as que foram inseridas
                    if (!empty($processosFerramentasGroup)) {
                        $processosFerramentas = $this->ProcessosFerramentas->find()
                            ->select(['codigo'])
                            ->where([$where])
                            ->toArray();

                        $removidos = array();
                        foreach ($processosFerramentas as $etapa) {

                            $codigo = $etapa['codigo'];

                            // Verifica se o codigo da etapa existe no array da requisição
                            if (array_search($codigo, array_column($arr_pf, 'codigo')) === false) {

                                // Inseri codigo das etapas que foram removidas
                                $removidos[] = $codigo;

                                //Remove etapas
                                $this->ProcessosFerramentas->deleteAll(array(
                                    'ProcessosFerramentas.codigo' => $codigo
                                ), false);
                            }
                        }
                    }
                }

                //Cria no entity para editar no banco
                $index_dados = 0;
                foreach ($arr_pf as $pf) {

                    if ($pf['codigo'] == $dados[$index_dados]['codigo']) {

                        //Define campos gerados pelo sistema
                        $dados[$index_dados]['codigo_usuario_alteracao'] = $codigo_usuario;
                        $dados[$index_dados]['data_alteracao'] = date('Y-m-d H:i:s');

                        $entityProcessosFerramentas = $this->ProcessosFerramentas->patchEntity($pf, $dados[$index_dados]);
                    }

                    $index_dados++;

                    if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                        $data['message'] = 'Erro ao editar em ProcessosFerramentas';
                        $data['error'] = $entityProcessosFerramentas->errors();
                        $this->set(compact('data'));
                        return;
                    }

                    $data[] = $entityProcessosFerramentas;

                }

            } else {
                // [POST] insert
                //Define campos gerados pelo sistema
                $dados['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['data_inclusao'] = date('Y-m-d H:i:s');

                //Cria no entity para salvar no banco
                $entityProcessosFerramentas = $this->ProcessosFerramentas->newEntity($dados);

                if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                    $data['message'] = 'Erro ao inserir em ProcessosFerramentas';
                    $data['error'] = $entityProcessosFerramentas->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityProcessosFerramentas;
            }

            if (!empty($inseridos)) {
                foreach ($inseridos as $i) {
                    $data[] = $i;
                }
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

            if (!empty($removidos)) {
                $this->set(compact('removidos'));
            }

            if (!empty($desvinculados)) {
                $this->set(compact('desvinculados'));
            }

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getProcessoEtapas($codigo_processo)
    {
        $data = $this->ProcessosFerramentas->getEtapas($codigo_processo);

        $this->set(compact('data'));
    }

    /**
     *
     * url: /api/processos/hazop (POST)
     * payload:
     {
        "codigo_processo": 4,
        "descricao": "Descricao do primeiro hazop",
        "equipamentos": "Equipamento 1 hazop",
        "finalidades": "finalidade 1 hazop"
     }
     *
     * url: /api/processos/hazop (PUT)
     * payload:
     {
        "codigo": 4,
        "descricao": "primeiro hazop editado",
        "equipamentos": "Equipamento 1 hazop editado",
        "finalidades": "finalidade 1 hazop editado"
     }
     *
     */
    public function postPutProcessoHazop()
    {
        //Abre a transação
        $this->connect->begin();

        try {

            //seta para o retorno do objeto
            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

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
                throw new Exception("Logar novamente o usuario");
            }

            if ($this->request->is('put')) {

                $inseridos = array();

                $processos = $this->Processos->find()->where(['codigo' => $dados['codigo_processo']])->first();

                if (empty($processos)) {
                    $error = 'Não foi possivel encontrar o processo!';
                    $this->set(compact('error'));
                    return;
                }

                //Cria no entity para salvar no banco
                $entityProcessos = $this->Processos->patchEntity($processos, $dados);

                if (!$this->Processos->save($entityProcessos)) {
                    $data['message'] = 'Erro ao editar em Processos';
                    $data['error'] = $entityProcessos->errors();
                    $this->set(compact('data'));
                    return;
                }

                foreach ($dados['hazop_nos'] as $key => $dado) {

                    if (!isset($dado['codigo'])) {

                        //Define campos gerados pelo sistema
                        $dado['codigo_processo'] = $dados['codigo_processo'];
                        $dado['codigo_usuario_inclusao'] = $codigo_usuario;
                        $dado['data_inclusao'] = date('Y-m-d H:i:s');

                        //Cria no entity para salvar no banco
                        $entityProcessosFerramentas = $this->ProcessosFerramentas->newEntity($dado);

                        if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                            $data['message'] = 'Erro ao inserir em ProcessosFerramentas';
                            $data['error'] = $entityProcessosFerramentas->errors();
                            $this->set(compact('data'));
                            return;
                        }

                        $inseridos[] = $entityProcessosFerramentas;

                    } else {
                        $processoFerramenta = $this->ProcessosFerramentas->find()->where(['codigo' => $dado['codigo']])->first();

                        // Verifica se exite etapa para editar
                        if (empty($processoFerramenta)) {
                            $data['errors'][$key]['message'] = "Codigo da etapa '" . $dados[$key]['codigo'] . "' inexistente";
                        } else {

                            // Armazena objeto no array
                            $dado['codigo_usuario_alteracao'] = $codigo_usuario;
                            $dado['data_alteracao'] = date('Y-m-d H:i:s');

                            $entityProcessosFerramentas = $this->ProcessosFerramentas->patchEntity($processoFerramenta, $dado);

                            if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                                $data['message'] = 'Erro ao editar em ProcessosFerramentas';
                                $data['error'] = $entityProcessosFerramentas->errors();
                                $this->set(compact('data'));
                                return;
                            }

                            $data[] = $entityProcessosFerramentas;

                            $editados[] = $processoFerramenta;
                        }
                    }
                }

                // Se algum erro for encontrado, interrompe a requisição e retorna os erros
                if (!empty($data['errors'])) {
                    unset($data['inseridos']);
                    $this->set(compact('data'));
                    return;
                }

                // Trata os codigo ProcessosFerramentas, converte em string e concatena
                $arr_codigo = array_column($editados, 'codigo');
                $string_codigo = json_encode($arr_codigo);
                $string_codigo = str_replace('[', '', $string_codigo);
                $string_codigo = str_replace(']', '',$string_codigo);

                if (!empty($string_codigo)) {


                    // Filtra codigos de novas etapas inseridas para não retorna-las na listagem
                    $inseridos_codigo = array_column($inseridos, 'codigo');
                    $string_inseridos_codigo = json_encode($inseridos_codigo);
                    $string_inseridos_codigo = str_replace('[', '', $string_inseridos_codigo);
                    $string_inseridos_codigo = str_replace(']', '',$string_inseridos_codigo);

                    if (!empty($string_inseridos_codigo)) {
                        $where = array(
                            'codigo_processo' => $dados['codigo_processo'],
                            'codigo not IN ('.$string_codigo . ',' . $string_inseridos_codigo .')'
                        );
                    } else {
                        $where = array(
                            'codigo_processo' => $dados['codigo_processo'],
                            'codigo not IN ('.$string_codigo.')'
                        );
                    }

                    // Retorna todas os hazops relacionados a ProcessosFerramentas exceto as que foram inseridas e editados
                    $processosFerramentas = $this->ProcessosFerramentas->find()
                        ->select(['codigo'])
                        ->where([$where])
                        ->toArray();

                    $removidos = array();
                    foreach ($processosFerramentas as $hazop) {

                        $codigo = $hazop['codigo'];

                        // Verifica se o codigo do hazop existe no array da requisição
                        if (array_search($codigo, array_column($editados, 'codigo')) === false) {

                            // Inseri codigo dos hazops que foram removidos
                            $removidos[] = $codigo;

                            //Remove etapas
                            $this->ProcessosFerramentas->deleteAll(array(
                                'ProcessosFerramentas.codigo' => $codigo
                            ), false);
                        }
                    }
                }


            } else {
                // [POST] insert
                foreach ($dados['hazop_nos'] as $dado) {
                    //Define campos gerados pelo sistema
                    $dado['codigo_processo'] = $dados['codigo_processo'];
                    $dado['codigo_usuario_inclusao'] = $codigo_usuario;
                    $dado['data_inclusao'] = date('Y-m-d H:i:s');

                    //Cria no entity para salvar no banco
                    $entityProcessosFerramentas = $this->ProcessosFerramentas->newEntity($dado);

                    if (!$this->ProcessosFerramentas->save($entityProcessosFerramentas)) {
                        $data['message'] = 'Erro ao inserir em ProcessosFerramentas';
                        $data['error'] = $entityProcessosFerramentas->errors();
                        $this->set(compact('data'));
                        return;
                    }

                    $data[] = $entityProcessosFerramentas;
                }
            }

            if (!empty($inseridos)) {
                foreach ($inseridos as $i) {
                    $data[] = $i;
                }
            }

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));

            if (!empty($removidos)) {
                $this->set(compact('removidos'));
            }

            if (!empty($desvinculados)) {
                $this->set(compact('desvinculados'));
            }

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function getProcessoHazop($codigo_processo)
    {
        $data = $this->ProcessosFerramentas->getHazop($codigo_processo);

        foreach ($data as $hazop) {

            $hazop['hazop_nos'] = $this->ProcessosHazopsNos->find()->where(['codigo_processos_ferramentas' => $hazop['codigo']]);
        }

        $this->set(compact('data'));
    }

    public function postPutProcessoHazopNos()
    {
        //Abre a transação
        $this->connect->begin();

        try {

            //seta para o retorno do objeto
            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

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
                throw new Exception("Logar novamente o usuario");
            }

            if ($this->request->is('put') && isset($dados['codigo'])) {

                $processosHazopsNos = $this->ProcessosHazopsNos->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($processosHazopsNos)) {
                    $data['message'] = 'Erro ao editar ProcessosHazopsNos';
                    $data['error'] = 'Codigo da ProcessosHazopsNos inexistente';
                    $this->set(compact('data'));
                    return;
                }
                //Define campos gerados pelo sistema
                $dados['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                //Cria no entity para salvar no banco
                $entityProcessosHazopsNos = $this->ProcessosHazopsNos->patchEntity($processosHazopsNos, $dados);

            } else {
                //Define campos gerados pelo sistema
                $dados['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['data_inclusao'] = date('Y-m-d H:i:s');

                //Cria no entity para salvar no banco
                $entityProcessosHazopsNos = $this->ProcessosFerramentas->newEntity($dados);

            }

            if (!$this->ProcessosHazopsNos->save($entityProcessosHazopsNos)) {
                $data['message'] = 'Erro ao inserir em ProcessosHazopsNos 1';
                $data['error'] = $entityProcessosHazopsNos->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityProcessosHazopsNos;

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

    public function postPutFotosProcesso()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST e PUT

        //Abre a transação
        $this->connect->begin();

        try {

            //pega os dados que veio do post
            $params = $this->request->getData();

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
                throw new Exception("Logar novamente o usuario");
            }

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

            $processo = $this->Processos->find()->where(['codigo' => $params['codigo_processo']])->first();

            if (empty($processo)) {
                $error = 'Código do processo não encontrado';
                $this->set(compact('error'));
                return;
            }

            //verifica se subiu corretamente a imagem
            if (!empty($caminho_image)) {

                //seta o valor para a imagem que esta sendo criada
                $fotos = FILE_SERVER.$caminho_image['path'];

                $dados_processo_anexo = array();


                if ($this->request->is(['put'])) {

                    $processoAnexos = $this->ProcessosAnexos->find()->where(['codigo' => $params['codigo']])->first();

                    if (empty($processoAnexos)) {
                        $error = 'Código do processoAnexos não encontrado';
                        $this->set(compact('error'));
                        return;
                    }

                    $processoAnexos["codigo_usuario_alteracao"] = $codigo_usuario;
                    $processoAnexos["data_alteracao"] = date('Y-m-d H:i:s');
                    $processoAnexos["codigo_processo"] = $processo['codigo'];
                    $processoAnexos["arquivo_url"] = $fotos;

                    $entityProcessosAnexos = $this->ProcessosAnexos->patchEntity($processoAnexos, $dados_processo_anexo);

                } else {

                    $dados_processo_anexo["codigo_usuario_inclusao"] = $codigo_usuario;
                    $dados_processo_anexo["data_inclusao"] = date('Y-m-d H:i:s');
                    $dados_processo_anexo["codigo_processo"] = $processo['codigo'];
                    $dados_processo_anexo["arquivo_url"] = $fotos;

                    $entityProcessosAnexos = $this->ProcessosAnexos->newEntity($dados_processo_anexo);
                }

                //salva os dados
                if (!$this->ProcessosAnexos->save($entityProcessosAnexos)) {
                    $data['message'] = 'Erro ao inserir em ProcessosAnexos';
                    $data['error'] = $entityProcessosAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }

                $data[] = $entityProcessosAnexos;

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

    public function getFotosProcessos($codigo_processo, $codigo_processo_anexo = null)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        if (!empty($codigo_processo_anexo)) {
            $data = $this->ProcessosAnexos->find()->where(['codigo' => $codigo_processo_anexo, 'data_remocao is null'])->first();
        } else {
            $data = $this->ProcessosAnexos->find()->where(['codigo_processo' => $codigo_processo, 'data_remocao is null']);
        }

        $this->set(compact('data'));
    }

    public function deleteFotosProcessos($codigo_processo, $codigo_processo_anexo = null)
    {
        $this->request->allowMethod(['delete']); // aceita apenas DELETE

        if (!empty($codigo_processo_anexo)) {
            $processosAnexos = $this->ProcessosAnexos->find()->where(['codigo_processo' => $codigo_processo, 'codigo' => $codigo_processo_anexo])->first();
            $dados_processo_anexo["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_processo_anexo["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($processosAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            $entityProcessosAnexos = $this->ProcessosAnexos->patchEntity($processosAnexos, $dados_processo_anexo);

            //salva os dados no banco
            if (!$this->ProcessosAnexos->save($entityProcessosAnexos)) {
                $data['message'] = 'Erro ao remover em ProcessosAnexos';
                $data['error'] = $entityProcessosAnexos->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = "Anexo removido com sucesso!";
        } else {
            $processosAnexos = $this->ProcessosAnexos->find()->where(['codigo_processo' => $codigo_processo])->toArray();
            $dados_processo_anexo["data_alteracao"] = date('Y-m-d H:i:s');
            $dados_processo_anexo["data_remocao"]   = date('Y-m-d H:i:s');

            if (empty($processosAnexos)) {
                $error = 'Não foi encontrado anexo para remover';
                $this->set(compact('error'));
                return;
            }

            foreach ($processosAnexos as $processoAnexo) {

                $entityProcessosAnexos = $this->ProcessosAnexos->patchEntity($processoAnexo, $dados_processo_anexo);

                //salva os dados
                if (!$this->ProcessosAnexos->save($entityProcessosAnexos)) {
                    $data['message'] = 'Erro ao remover em ProcessosAnexos';
                    $data['error'] = $entityProcessosAnexos->errors();
                    $this->set(compact('data'));
                    return;
                }
            }

            $data[] = "Anexos removidos com sucesso!";
        }

        $this->set(compact('data'));

    }


}
