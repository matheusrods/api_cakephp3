<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
// use App\Model\Table\AppTable;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * Processos Model
 *
 * @method \App\Model\Entity\Processo get($primaryKey, $options = [])
 * @method \App\Model\Entity\Processo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Processo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Processo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Processo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Processo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Processo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Processo findOrCreate($search, callable $callback = null, $options = [])
 */
class ProcessosTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('processos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        //instancia as models tables que iremos utilizar durante toda table
        $this->ProcessosFerramentas = TableRegistry::get('ProcessosFerramentas');
        $this->AgentesRiscosEtapas = TableRegistry::get('AgentesRiscosEtapas');
        $this->AgentesRiscos = TableRegistry::get('AgentesRiscos');
        $this->ArRt = TableRegistry::get('ArRt');
        $this->ArrtPa = TableRegistry::get('ArrtPa');
        $this->ArrtpaRi = TableRegistry::get('ArrtpaRi');
        $this->MedidasControle = TableRegistry::get('MedidasControle');
        $this->Qualificacao = TableRegistry::get('Qualificacao');
        $this->FerramentasAnalise = TableRegistry::get('FerramentasAnalise');
        $this->Aprho = TableRegistry::get('Aprho');
        $this->RiscosImpactosSelecionadosDescricoes = TableRegistry::get('RiscosImpactosSelecionadosDescricoes');
        $this->FontesGeradorasExposicao = TableRegistry::get('FontesGeradorasExposicao');
        $this->EquipamentosAdotados = TableRegistry::get('EquipamentosAdotados');
        $this->HazopsAgentesRiscos = TableRegistry::get('HazopsAgentesRiscos');
        $this->RiscosTipo = TableRegistry::get('RiscosTipo');
        $this->PerigosAspectos = TableRegistry::get('PerigosAspectos');
        $this->RiscosImpactos = TableRegistry::get('RiscosImpactos');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_levantamento_chamado')
            ->requirePresence('codigo_levantamento_chamado', 'create')
            ->notEmptyString('codigo_levantamento_chamado');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->allowEmptyString('titulo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_processo_tipo')
            ->allowEmptyString('codigo_processo_tipo');

        $validator
            ->scalar('hazop_descricao')
            ->maxLength('hazop_descricao', 255)
            ->allowEmptyString('hazop_descricao');

        $validator
            ->scalar('hazop_equipamento')
            ->maxLength('hazop_equipamento', 255)
            ->allowEmptyString('hazop_equipamento');

        $validator
            ->scalar('hazop_finalidade')
            ->maxLength('hazop_finalidade', 255)
            ->allowEmptyString('hazop_finalidade');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        return $validator;
    }

    /**
     * [getPrcessos metodo para buscar os processos/etapas/etapas selecionadas /riscos impactos]
     * @param  [type] $codigo_processo [description]
     * @return [type]                  [description]
     */
    public function getProcessos($codigo_processo=null,$codigo_levantamento = null)
    {

        // debug($codigo_processo);
        // return;

        //verifica qual parametro foi passado
        if (!is_null($codigo_processo)) {
            $processos = $this->find()->where(['codigo' => $codigo_processo])->toArray();
        }
        else if(!is_null($codigo_levantamento)) {
            $processos = $this->find()->where(['codigo_levantamento_chamado' => $codigo_levantamento])->toArray();
        } 
        
        //verifica se temos processos
        if(empty($processos)) {
            return array();
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

            } 
            else {

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
            }//fim else
        }//fim foreach
        
        return (isset($processos[0]) ? $processos[0] : $processos);

    }//fim getProcessos

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

}
