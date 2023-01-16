<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * FichasAssistenciais Model
 *
 * @property \App\Model\Table\QuestoesTable&\Cake\ORM\Association\BelongsToMany $Questoes
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsToMany $Respostas
 * @property \App\Model\Table\TipoUsoTable&\Cake\ORM\Association\BelongsToMany $TipoUso
 *
 * @method \App\Model\Entity\FichasAssistenciai get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasAssistenciai newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciai[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciai|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciai saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciai patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciai[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciai findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasAssistenciaisTable extends AppTable
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

        $this->setTable('fichas_assistenciais');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Questoes', [
            'foreignKey' => 'fichas_assistenciai_id',
            'targetForeignKey' => 'questo_id',
            'joinTable' => 'fichas_assistenciais_questoes'
        ]);
        $this->belongsToMany('Respostas', [
            'foreignKey' => 'fichas_assistenciai_id',
            'targetForeignKey' => 'resposta_id',
            'joinTable' => 'fichas_assistenciais_respostas'
        ]);
        $this->belongsToMany('TipoUso', [
            'foreignKey' => 'fichas_assistenciai_id',
            'targetForeignKey' => 'tipo_uso_id',
            'joinTable' => 'fichas_assistenciais_tipo_uso'
        ]);
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
            ->integer('codigo_pedido_exame')
            ->requirePresence('codigo_pedido_exame', 'create')
            ->notEmptyString('codigo_pedido_exame');

        $validator
            ->integer('codigo_medico')
            ->requirePresence('codigo_medico', 'create')
            ->notEmptyString('codigo_medico');

        $validator
            ->integer('pa_sistolica')
            ->allowEmptyString('pa_sistolica');

        $validator
            ->integer('pa_diastolica')
            ->allowEmptyString('pa_diastolica');

        $validator
            ->integer('pulso')
            ->allowEmptyString('pulso');

        $validator
            ->decimal('circunferencia_abdominal')
            ->allowEmptyString('circunferencia_abdominal');

        $validator
            ->decimal('circunferencia_quadril')
            ->allowEmptyString('circunferencia_quadril');

        $validator
            ->integer('peso_kg')
            ->allowEmptyString('peso_kg');

        $validator
            ->integer('peso_gr')
            ->allowEmptyString('peso_gr');

        $validator
            ->integer('altura_mt')
            ->allowEmptyString('altura_mt');

        $validator
            ->integer('altura_cm')
            ->allowEmptyString('altura_cm');

        $validator
            ->integer('imc')
            ->allowEmptyString('imc');

        $validator
            ->integer('parecer')
            ->allowEmptyString('parecer');

        $validator
            ->integer('parecer_altura')
            ->allowEmptyString('parecer_altura');

        $validator
            ->integer('parecer_espaco_confinado')
            ->allowEmptyString('parecer_espaco_confinado');

        $validator
            ->integer('codigo_atestado')
            ->allowEmptyString('codigo_atestado');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->time('hora_inicio_atendimento')
            ->requirePresence('hora_inicio_atendimento', 'create')
            ->notEmptyTime('hora_inicio_atendimento');

        $validator
            ->time('hora_fim_atendimento')
            ->requirePresence('hora_fim_atendimento', 'create')
            ->notEmptyTime('hora_fim_atendimento');

        return $validator;
    }

    /**
     * [getFichaAssistencialQuestoes
     * metodo para pegar as questoes da ficha assistencial
     * ]
     *
     * @param  [type] $codigo_pedido_exames [description]
     * @param  [type] $codigo_usuario       [description]
     * @return [type]                       [description]
     */
    public function getFichaAssistencialQuestoes($codigo_pedido_exame, $codigo_usuario)
    {

        //instancia as tabela para ajudar no metodo
        $this->Usuario = TableRegistry::get('Usuario');
        $this->PedidosExames = TableRegistry::get('PedidosExames');
        $this->FichasClinicas = TableRegistry::get('FichasClinicas');
        $this->FichasAssistenciaisTipoUso = TableRegistry::get('FichasAssistenciaisTipoUso');

        //valida se existe o pedido de exame selecionado, senao retorna a index e exibe erro
        $pedido_exame = $this->PedidosExames->find()
            ->where([
                'codigo' => $codigo_pedido_exame,
                'codigo_status_pedidos_exames' => '5'
            ])
            ->hydrate(false)
            ->first();
        //verifica se o pedido de exame esta cancelado
        if(!empty($pedidos_exames)) {
            return "Pedido de Exame cancelado, favor tentar com outro pedido de exame!";
        }//fim verificacao se pedido de exame existe

        //pega os dados do parecer
        $verificaParecer = $this->verificaParecer($codigo_pedido_exame);

        //pega os dados complementares pelo pedido de exame
        $dados = $this->PedidosExames->obtemDadosComplementares($codigo_pedido_exame);

        list($dados_medicoes,$dados_depara) = $this->FichasClinicas->carrega_dados_questionario($dados['Funcionario']['cpf']);
        // debug(json_decode($dados_depara));exit;
        $fc['pressao_sis'] = (isset($dados_medicoes[0]['pressao_sis'])) ? $dados_medicoes[0]['pressao_sis'] : '';
        $fc['pressao_dia'] = (isset($dados_medicoes[0]['pressao_dia'])) ? $dados_medicoes[0]['pressao_dia'] : '';
        $fc['peso_kg'] = (isset($dados_medicoes[0]['peso_kg'])) ? $dados_medicoes[0]['peso_kg'] : '';
        $fc['peso_g'] = (isset($dados_medicoes[0]['peso_g'])) ? $dados_medicoes[0]['peso_g'] : '';
        $fc['altura_m'] = (isset($dados_medicoes[0]['altura_m'])) ? $dados_medicoes[0]['altura_m'] : '';
        $fc['altura_cm'] = (isset($dados_medicoes[0]['altura_cm'])) ? $dados_medicoes[0]['altura_cm'] : '';
        $fc['circ_abdom'] = (isset($dados_medicoes[0]['circ_abdom'])) ? $dados_medicoes[0]['circ_abdom'] : '';
        $fc['circ_quadril'] = (isset($dados_medicoes[0]['circ_quadril'])) ? $dados_medicoes[0]['circ_quadril'] : '';

        $lyn_desc = '';
        $pressao = '';
        if(!empty($fc['pressao_sis']) && !empty($fc['pressao_dia'])) {
            $pressao = $fc['pressao_sis']."X".$fc['pressao_dia'];
            $lyn_desc = "Questão selecionada via do Lyn!";
        }
        $peso = '';
        if(!empty($fc['peso_kg']) && !empty($fc['peso_g'])) {

            if(strstr($fc['peso_kg'],'.') || strstr($fc['peso_kg'],',')){
                $peso_kg = (strstr($fc['peso_kg'],',')) ? explode(",",$fc['peso_kg']) : explode(".",$fc['peso_kg']);
                $fc['peso_kg'] = $peso_kg[0];
            }

            if(strstr($fc['peso_g'],'.') || strstr($fc['peso_g'],',')){
                $peso_g = (strstr($fc['peso_g'],',')) ? explode(",",$fc['peso_g']) : explode(".",$fc['peso_g']);
                $fc['peso_g'] = $peso_g[1];
            }

            $peso = $fc['peso_kg'].'.'.$fc['peso_g'];
            $lyn_desc = "Questão selecionada via do Lyn!";
        }
        $altura = '';
        if(!empty($fc['altura_m']) && !empty($fc['altura_cm'])) {

            if(strstr($fc['altura_m'],'.') || strstr($fc['altura_m'],',')){
                $alt_m = (strstr($fc['altura_m'],',')) ? explode(",",$fc['altura_m']) : explode(".",$fc['altura_m']);
                $fc['altura_m'] = $alt_m[0];
            }

            if(strstr($fc['altura_cm'],'.') || strstr($fc['altura_cm'],',')){
                $alt_cm = (strstr($fc['altura_cm'],',')) ? explode(",",$fc['altura_cm']) : explode(".",$fc['altura_cm']);
                $fc['altura_cm'] = $alt_cm[1];
            }

            $altura = $fc['altura_m'].'.'.$fc['altura_cm'];
            $lyn_desc = "Questão selecionada via do Lyn!";
        }
        $circ_abdom = '';
        if(!empty($fc['circ_abdom'])) {
            $circ_abdom = $fc['circ_abdom'];
            $lyn_desc = "Questão selecionada via do Lyn!";
        }
        $circ_quadril = '';
        if(!empty($fc['circ_quadril'])) {
            $circ_quadril = $fc['circ_quadril'];
            $lyn_desc = "Questão selecionada via do Lyn!";
        }

        //pega os campos da ficha clinica que possam a vir preenchidos
        $dados_fa['grupo_header'] = array(
            array(
                'descricao' => "DADOS PRINCIPAIS",
                'questao' => array(
                    array(
                        "name" => "FichaAssistencial.codigo_medico",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Médico:",
                        "obrigatorio"=> 1,
                        "conteudo" => $dados['Medico'],
                        "default" => null,
                        "lyn" => null,

                        "codigo" => 'dp1',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => $dados['Medico'],
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.hora_inicio_atendimento",
                        "tipo" => "HOUR",
                        "tamanho" => "4",
                        "label" => "Horário de início de atendimento:",
                        "obrigatorio"=> 1,
                        "conteudo" => date("H:i"),
                        "default" => null,
                        "lyn" => null,

                        "codigo" => 'dp2',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.hora_fim_atendimento",
                        "tipo" => "HOUR",
                        "tamanho" => "4",
                        "label" => "Horário de finalização de atendimento:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
                        "lyn" => null,

                        "codigo" => 'dp3',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                )
            ),
            array(
                'descricao' => "DADOS ANTROPOMÉTRICOS",
                'questao' => array(
                    array(
                        "name" => "FichaAssistencial.pa",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Pressão arterial (mmHg):",
                        "obrigatorio"=> 1,
                        "conteudo" => $pressao,
                        "default" => $pressao,
                        "lyn" => (!empty($pressao)) ? $lyn_desc : null,

                        "codigo" => 'dp4',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.pulso",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Pulso (bpm - batimentos por minuto):",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
                        "lyn" => null,

                        "codigo" => 'dp5',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.circunferencia_abdominal",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Circunferência abdominal (cm)",
                        "obrigatorio"=> 1,
                        "conteudo" => $circ_abdom,
                        "default" => $circ_abdom,
                        "lyn" => (!empty($circ_abdom)) ? $lyn_desc : null,

                        "codigo" => 'dp6',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.peso",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Peso (kg):",
                        "obrigatorio"=> 1,
                        "conteudo" => $peso,
                        "default" => $peso,
                        "lyn" => (!empty($peso)) ? $lyn_desc : null,

                        "codigo" => 'dp7',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.altura",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Altura (cm):",
                        "obrigatorio"=> 1,
                        "conteudo" => $altura,
                        "default" => $altura,
                        "lyn" => (!empty($altura)) ? $lyn_desc : null,

                        "codigo" => 'dp8',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.circunferencia_quadril",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Circunferência quadril (cm):",
                        "obrigatorio"=> 1,
                        "conteudo" => $circ_quadril,
                        "default" => $circ_quadril,
                        "lyn" => (!empty($circ_quadril)) ? $lyn_desc : null,

                        "codigo" => 'dp9',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "name" => "FichaAssistencial.imc",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Índice de Massa Corpórea (IMC):",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->get_mensagem_imc($peso,$altura),
                        "default" => $this->get_mensagem_imc($peso,$altura),
                        "lyn" => $lyn_desc,

                        "codigo" => 'dp10',
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "options" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                )
            )
        );
        // debug($dados_fa);exit;

        $questoes = $this->montaQuestoes($dados['Funcionario'], $dados['codigo']);
        $questoes = array_merge($dados_fa,$questoes);

        // $dados_fa['AtestadoMedico']['habilita_afastamento_em_horas'] = '';
        // $dados_fa['AtestadoMedico']['data_retorno_periodo'] = '';

        $combos = $this->carrega_combos();

        //pega os campos add do questionario o atestado
        $questoes['grupo'][] = array(
            'descricao' => 'ATESTADO MÉDICO',
            'questao' => array(
                array(
                    "codigo" => 'a11',
                    "name" => "FichaAssistencial.AtestadoMedico.exibir_ficha_assistencial",
                    "tipo" => "RADIO",
                    "tamanho" => "12",
                    "label" => "Atestado",
                    "conteudo" => array(1 => 'Sim', 0 => 'Não'),
                    "default" => 0,

                    "obrigatorio"=> 0,
                    "lyn" => null,
                    "farmaco_campo_exibir" => 0,
                    "campo_livre" => null,
                    "menstruacao" => null,
                    "risco_campo_exibir" => 0,
                    "multiplos_riscos" => false,
                    "risco_formulario" => null,
                    "options" => null,
                    "multiplos_farmacos" => false,
                    "farmaco" => null,
                    "cids_campo_exibir" => 0,
                    "multiplos_cids" => false,
                    "cids" => null,
                    "sub_questao_campo_exibir" => '1',
                    "sub_questao" => array(
                        array(
                            "codigo" => 'a12',
                            "name" => "FichaAssistencial.AtestadoMedico.habilita_afastamento_em_horas",
                            "tipo" => "CHECKBOX",
                            "obrigatorio" => 0,
                            "tamanho" => "12",
                            "label" => "Afastamento em horas:",
                            "conteudo" => array("1" => ""),
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => array("Sim" => ""),
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a13',
                            "name" => "FichaAssistencial.AtestadoMedico.data_afastamento_periodo",
                            "tipo" => "DATE",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Período de Afastamento de:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a14',
                            "name" => "FichaAssistencial.AtestadoMedico.data_retorno_periodo",
                            "tipo" => "DATE",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Período de Afastamento até:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a15',
                            "name" => "FichaAssistencial.AtestadoMedico.afastamento_em_dias",
                            "tipo" => "INPUT",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Dias afastado:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a16',
                            "name" => "FichaAssistencial.AtestadoMedico.hora_afastamento",
                            "tipo" => "HOUR",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Período de Horas de:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a17',
                            "name" => "FichaAssistencial.AtestadoMedico.hora_retorno",
                            "tipo" => "HOUR",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Período de Horas até:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a18',
                            "name" => "FichaAssistencial.AtestadoMedico.afastamento_em_horas",
                            "tipo" => "HOUR",
                            "obrigatorio" => 0,
                            "tamanho" => "4",
                            "label" => "Horas afastado:",
                            "conteudo" => null,
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => null,
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a19',
                            "name" => "FichaAssistencial.AtestadoMedico.codigo_motivo_licenca",
                            "tipo" => "SELECT",
                            "obrigatorio" => 0,
                            "tamanho" => "12",
                            "label" => "Motivo da Licença:",
                            "conteudo" => $combos['MotivoAfastamento'],
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => $combos['MotivoAfastamento'],
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),
                        array(
                            "codigo" => 'a20',
                            "name" => "FichaAssistencial.AtestadoMedico.codigo_motivo_esocial",
                            "tipo" => "SELECT",
                            "obrigatorio" => 0,
                            "tamanho" => "12",
                            "label" => "Motivo da Licença (Tabela 18 - eSocial):",
                            "conteudo" => $combos['MotivoAfastamentoEsocial'],
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => $combos['MotivoAfastamentoEsocial'],
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => false,
                            "cids" => null,
                            "lyn" => null,

                        ),

                        array(
                            "codigo" => 'a21',
                            "name" => "FichaAssistencial.AtestadoMedico.cid10",
                            "tipo" => "HIDDEN",
                            "obrigatorio" => 0,
                            "tamanho" => "12",
                            "label" => "CID10:",
                            "conteudo" => '',
                            "farmaco_campo_exibir" => null,
                            "campo_livre" => null,
                            "menstruacao" => null,
                            "default" => null,
                            "risco_campo_exibir" => 0,
                            "multiplos_riscos" => false,
                            "risco_formulario" => null,
                            "options" => "",
                            "multiplos_farmacos" => false,
                            "farmaco" => null,
                            "cids_campo_exibir" => 0,
                            "multiplos_cids" => true,
                            "cids" => array(
                                "cid" => array(
                                    "name" => "FichaAssistencial.AtestadoMedico.cid10.0.doenca",
                                    "tipo" => "INPUT",
                                    "label" => "CID10:",
                                    "busca" => "add_cid",
                                    "conteudo" => null
                                )
                            ),
                            "lyn" => null,

                        ),
                    ),
                ),
            ),
        );

        $dados_principais = array(
            'codigo_pedido_exame' => $dados['codigo'],
            'empresa' => $dados['Empresa']['razao_social'],
            'unidade' => $dados['Unidade']['razao_social'],
            'setor' => $dados['setor'],
            'funcionario' => $dados['Funcionario']['nome'],
            'cpf' => $dados['Funcionario']['cpf'],
            'idade' => $dados['idade'],
            'data_admissao' => $dados['ClienteFuncionario']['admissao'],
            'sexo' => $dados['sexo'],
            'cargo' => $dados['cargo'],
            'tipo_pedido_exame' => $dados['tipo_pedido_exame'],
        );

        $dados_array = [
            'dados_principais' => $dados_principais,
            'historico' => false,
            'formulario' => $questoes,
        ];

        // $dados_array = [
        //     'dados' => $dados_fa,
        //     // 'verificaParecer' => $verificaParecer,
        //     'combos' => $combos,
        //     'dados_medicoes' => array(),
        //     'dados_depara' => array(),
        //     'historico' => false,
        //     'formulario' => $questoes,
        // ];

        return $dados_array;

    }// fim getFichaAssistencialQuestoes($codigo_pedido_exames, $codigo_usuario)


    /**
     * [verificaParecer
     * metodo para pegar os dados dos pareceres retornando se o pedido esta parcialmente baixado, tem risco por altura, e por confinamento
     * ]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [
     *
     *     'todos_pedidos_baixados' => '0',
     *     'risco_por_altura' => 'N',
     *     'risco_por_confinamento' => 'N'
     * ]
     */
    public function verificaParecer($codigo_pedido_exame = null){
        $return = 0;
        if(!is_null($codigo_pedido_exame)) {
            $query = '
                SELECT
                CASE WHEN
                (
                SELECT count(pe.codigo) FROM pedidos_exames pe
                INNER JOIN itens_pedidos_exames ipe
                ON (ipe.codigo_pedidos_exames = pe.codigo)
                WHERE pe.codigo = '.$codigo_pedido_exame.'
                ) > (
                SELECT count(pe.codigo) FROM pedidos_exames pe
                INNER JOIN itens_pedidos_exames ipe
                ON (ipe.codigo_pedidos_exames = pe.codigo)
                INNER JOIN itens_pedidos_exames_baixa ipeb
                ON (ipeb.codigo_itens_pedidos_exames = ipe.codigo)
                WHERE pe.codigo = '.$codigo_pedido_exame.'
                )
                THEN 0
                WHEN
                (
                SELECT count(pe.codigo) FROM pedidos_exames pe
                INNER JOIN itens_pedidos_exames ipe
                ON (ipe.codigo_pedidos_exames = pe.codigo)
                WHERE pe.codigo = '.$codigo_pedido_exame.'
                ) = (
                SELECT count(pe.codigo) FROM pedidos_exames pe
                INNER JOIN itens_pedidos_exames ipe
                ON (ipe.codigo_pedidos_exames = pe.codigo)
                INNER JOIN itens_pedidos_exames_baixa ipeb
                ON (ipeb.codigo_itens_pedidos_exames = ipe.codigo)
                WHERE pe.codigo = '.$codigo_pedido_exame.'
                )
                THEN 1
                END
                AS todos_pedidos_baixados,

                CASE WHEN (SELECT   ri.risco_caracterizado_por_altura
                        FROM rhhealth.dbo.pedidos_exames pe
                            INNER JOIN rhhealth.dbo.funcionario_setores_cargos fsc ON fsc.codigo = pe.codigo_func_setor_cargo
                            INNER JOIN rhhealth.dbo.cliente_funcionario cf ON cf.codigo = fsc.codigo_cliente_funcionario
                            INNER JOIN RHHealth.dbo.cliente c ON c.codigo = cf.codigo_cliente_matricula
                            INNER JOIN RHHealth.dbo.cargos cg ON cg.codigo = fsc.codigo_cargo
                            INNER JOIN RHHealth.dbo.setores st ON st.codigo = fsc.codigo_setor
                            INNER JOIN RHHealth.dbo.clientes_setores cs ON (cs.codigo_setor = st.codigo )
                            INNER JOIN RHHealth.dbo.grupo_exposicao ge  ON (ge.codigo_cargo = cg.codigo AND ge.codigo_cliente_setor = cs.codigo)
                            INNER JOIN RHHealth.dbo.grupos_exposicao_risco ger ON (ger.codigo_grupo_exposicao = ge.codigo)
                            INNER JOIN RHHealth.dbo.riscos ri ON (ri.codigo = ger.codigo_risco)
                        WHERE pe.codigo = '.$codigo_pedido_exame.' and ri.risco_caracterizado_por_altura is not null and ri.risco_caracterizado_por_altura <> 0) = 1 THEN \'S\' ELSE \'N\' END AS risco_por_altura,

                CASE WHEN (SELECT   ri.risco_caracterizado_por_trabalho_confinado
                        FROM rhhealth.dbo.pedidos_exames pe
                            INNER JOIN rhhealth.dbo.funcionario_setores_cargos fsc ON fsc.codigo = pe.codigo_func_setor_cargo
                            INNER JOIN rhhealth.dbo.cliente_funcionario cf ON cf.codigo = fsc.codigo_cliente_funcionario
                            INNER JOIN RHHealth.dbo.cliente c ON c.codigo = cf.codigo_cliente_matricula
                            INNER JOIN RHHealth.dbo.cargos cg ON cg.codigo = fsc.codigo_cargo
                            INNER JOIN RHHealth.dbo.setores st ON st.codigo = fsc.codigo_setor
                            INNER JOIN RHHealth.dbo.clientes_setores cs ON (cs.codigo_setor = st.codigo )
                            INNER JOIN RHHealth.dbo.grupo_exposicao ge  ON (ge.codigo_cargo = cg.codigo AND ge.codigo_cliente_setor = cs.codigo)
                            INNER JOIN RHHealth.dbo.grupos_exposicao_risco ger ON (ger.codigo_grupo_exposicao = ge.codigo)
                            INNER JOIN RHHealth.dbo.riscos ri ON (ri.codigo = ger.codigo_risco)
                        WHERE pe.codigo = '.$codigo_pedido_exame.' and ri.risco_caracterizado_por_trabalho_confinado is not null and ri.risco_caracterizado_por_trabalho_confinado <> 0) = 1 then \'S\' ELSE \'N\' END  AS risco_por_confinamento
                ';
            //executa a query
            $conn = ConnectionManager::get('default');
            $return =  $conn->execute($query)->fetchAll('assoc');

            $return = $return[0];
        }

        return $return;

    }//FINAL FUNCTION verificaParecer

    public function get_mensagem_imc($imc)
    {
        //nao informado
        $msg_imc = 'Não informado!';

        //verifica qual msg
        if(($imc > 0.0) && ($imc < 18.5)){
            $msg_imc = 'Magro ou baixo peso';
        }
        elseif(($imc >= 18.5) && ($imc < 24.99)){
            $msg_imc = 'Normal ou eutrófico';
        }
        elseif(($imc >= 25) && ($imc < 29.99)){
            $msg_imc = 'Sobrepeso ou pré-obeso';
        }
        elseif(($imc >= 30) && ($imc < 34.99)){
            $msg_imc = 'Obesidade';
        }
        elseif(($imc >= 35) && ($imc < 39.99)){
            $msg_imc = 'Obesidade';
        }
        elseif(($imc >= 40)){
            $msg_imc = 'Obesidade (grave)';
        }

        return $msg_imc;
    }//FINAL FUNCTION get_mensagem_imc

    /**
     * [carrega_combos metodo para carregar os campos que vamos retornar da api]
     *
     * @return [type] [description]
     */
    public function carrega_combos()
    {
        //instancia as models
        $this->MotivosAfastamento = TableRegistry::get('MotivosAfastamento');
        $this->TiposLocaisAtendimento = TableRegistry::get('TiposLocaisAtendimento');
        $this->EnderecoEstado = TableRegistry::get('EnderecoEstado');
        $this->Esocial = TableRegistry::get('Esocial');

        $fields = array(
            'codigo',
            'descricao' => "RHHealth.dbo.ufn_decode_utf8_string(descricao)"
        );

        $dados_motivo_afastamento = $this->MotivosAfastamento->find()->select($fields)->order(['descricao'])->hydrate(false)->all()->toArray();
        $motivo_afastamento = array();
        foreach($dados_motivo_afastamento AS $ma){
            $motivo_afastamento[$ma['codigo']] = $this->replaceSpecialChar($ma['descricao']);
        }

        $tipo_local_atendimento = $this->TiposLocaisAtendimento->find('list', ['keyField' => 'codigo','valueField' => 'descricao'])->order(['descricao'])->toArray();
        // debug($tipo_local_atendimento);exit;

        $estados = $this->EnderecoEstado->find('list', ['keyField' => 'codigo','valueField' => 'abreviacao'])->where(['codigo_endereco_pais' => 1])->toArray();
        $estados[''] = 'UF';
        ksort($estados);

        $esocial = $this->carrega_motivo_afastamento_esocial();
        $motivo_afastamento_esocial = array();
        if(!empty($esocial)) {
            foreach($esocial AS $val) {
                $motivo_afastamento_esocial[$val['codigo']] = $val['descricao'];
            }
        }

        $retorno = array(
            'MotivoAfastamento' => $motivo_afastamento,
            'TipoLocalAtendimento' => $tipo_local_atendimento,
            'Estados' => $estados,
            'MotivoAfastamentoEsocial' => $motivo_afastamento_esocial
        );

        return $retorno;

    }//FINAL FUNCTION carrega_combos

    /**
     * Helper para trocar caracteres especiais de uma string
     */
    public function replaceSpecialChar($busca)
    {
        //Inserir letras e caracteres especiais que precisam ser trocados
        $arrChars = array(
            "Ã" => "Ç",
            "ȁ"  => "Ç",
            ""  => "í",
        );

        foreach ($arrChars as $index => $char) {

            if (strpos($busca, $index) !== false) {
                $busca = str_replace($index, $char, $busca);
            }
        }
        return $busca;
    }

    /**
     * [carrega_motivo_afastamento_esocial description]
     *
     * metodo para pegar os dados do esocial pertinentes
     *
     * @param  [type] $esocial_codigo_editar [description]
     * @return [type]                        [description]
     */
    public function carrega_motivo_afastamento_esocial($esocial_codigo_editar = NULL)
    {
        $this->Esocial = TableRegistry::get('Esocial');

        $conditions['Esocial.tabela'] = 18;
        $conditions['OR'][0]['Esocial.ativo'] = 1;
        $conditions['OR'][1]['Esocial.ativo'] = 0;
        $conditions['OR'][1]['Esocial.codigo'] = $esocial_codigo_editar;

        $fields = array(
                'codigo'=>'Esocial.codigo',
                'descricao'=>"CONCAT(Esocial.codigo_descricao,' - ', Esocial.descricao)"
            );

        $dados = $this->Esocial->find()
            ->select($fields)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        return $dados;

    }// fim esocial

    /**
     * [montaQuestoes metodo para montar as questoes da ficha clinica]
     *
     * @param  array  $dados_funcionario [description]
     * @return [type]                    [description]
     */
    public function montaQuestoes($dados_funcionario = array(), $codigo_pedido_exame)
    {
        //instancia as classes tables
        $this->FichasAssistenciaisRespostas = TableRegistry::get('FichasAssistenciaisRespostas');
        $this->FichasAssistenciaisQuestoes = TableRegistry::get('FichasAssistenciaisQuestoes');
        $this->FichasAssistenciaisGrupoQuestoes = TableRegistry::get('FichasAssistenciaisGrupoQuestoes');

        if(!empty($dados_funcionario['sexo'])) {
            $conditionsQuestao[] = "(exibir_se_sexo = '".$dados_funcionario['sexo']."' OR exibir_se_sexo IS NULL)";
        }

        $fields = [
            'codigo',
            'name'=>"CONCAT('FichaAssistencialResposta.',codigo,'_resposta')",
            'tipo',
            'campo_livre_label',
            'observacao',
            'obrigatorio',
            'ajuda',
            'tamanho'=>'span',
            'label' => '(CASE WHEN observacao IS NULL THEN label ELSE CONCAT(label,\'(\',observacao,\')\') END)',
            'conteudo',
            'quebra_linha',
            'opcao_selecionada',
            'opcao_abre_menu_escondido',
            'farmaco_ativo',
            'opcao_exibe_label',
            'multiplas_cids_ativo',
            'ativo',
            'farmaco_campo_exibir' => 'NULL',
            'multiplas_cids_esconde_outros' => 'NULL',
            'riscos_ativo' => 'NULL',
            'descricao_ativo' => 'NULL',
            'ordenacao'
        ];

        //variavel auxiliar para montar as questoes
        $dados_questoes = array();

        //pega o grupo de questoes
        $grupos_questoes = $this->FichasAssistenciaisGrupoQuestoes->find()
            ->select(['codigo','descricao'])
            ->where(['ativo' => 1, 'codigo IN (7,9,10)'])
            ->hydrate(false)
            // ->all()
            ->toArray();
        // debug($grupos_questoes);exit;

        //verifica se existe registro
        if(!empty($grupos_questoes)) {

            //varre os grupos
            foreach ($grupos_questoes as $key_grupo => $grupo) {

                //busca as questoes principais do grupo
                $questoes = $this->FichasAssistenciaisQuestoes->find()
                    ->select($fields)
                    ->where([
                        'codigo_ficha_assistencial_grupo_questao' => $grupo['codigo'],
                        'codigo_ficha_assistencial_questao IS NULL',
                        'ativo' => 1,
                        $conditionsQuestao
                    ])
                    ->hydrate(false)
                    ->toArray();

                //verifica se achou alguma questao principal
                if(!empty($questoes)) {

                    //seta o valor da fichas grupos
                    $dados_questoes['grupo'][$key_grupo] = $grupo;

                    //varre as questoes
                    foreach ($questoes as $key_questao => $questao) {

                        //aplica a regra para add uma nova questao no formulario ou não
                        $add_questao = $this->aplicaRegraQuestoes($questao, $codigo_pedido_exame);
                        $add_questao['sub_questao_campo_exibir'] = null;

                        //seta a questao princial
                        // $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao] = $questao;
                        $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao] = $add_questao;

                        //seta a questao princial
                        // $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao] = $questao;

                        //busca as sub questoes do grupo
                        $sub_questoes = $this->FichasAssistenciaisQuestoes->find()
                            ->select($fields)
                            ->where([
                                'codigo_ficha_assistencial_questao' => $questao['codigo'],
                                'ativo' => 1,
                                $conditionsQuestao
                            ])
                            ->hydrate(false)
                            ->toArray();

                        //seta para conter o indice declarando o indice
                        $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao'] = array();
                        //verifica se existe a subquestao
                        if(!empty($sub_questoes)) {

                            //para exibir o farmaco quando tiver
                            if(isset($add_questao['conteudo'][1])) {
                                $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao_campo_exibir'] = 1;
                            }

                            if(isset($add_questao['conteudo']["Sim"])) {
                                $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao_campo_exibir'] = "Sim";
                            }

                            //varre as subuqestoes
                            foreach($sub_questoes as $key_sub_questao => $sub) {

                                //aplica a regra para add uma nova questao no formulario ou não
                                $add_sub_questao = $this->aplicaRegraQuestoes($sub);

                                //seta com o valor real
                                $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao'][$key_sub_questao] = $add_sub_questao;

                            }// fim sub

                            //seta com o valor real
                            // $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao'] = $sub_questoes;
                        }

                    }//fim questoe
                }// fim questoes
            }//fim foreach grupos_questoes
        }//fim grupos
        // debug($dados_questoes);exit;

        //varre os dados dos grupos
        foreach ($dados_questoes['grupo'] as $keyGrupo => $grupoQuestao) {

            //varre as questoes
            //neste momento iremos colocar algumas validações e acrescentar alguns campos
            foreach ($grupoQuestao['questao'] as $keyQuestao => $questao) {

                //aplica a regra para add uma nova questao no formulario ou não
                // $add_questao = $this->aplicaRegraQuestoes($questao);
                // $dados_questoes['grupo'][$keyGrupo]['questao'][$keyQuestao] = $add_questao;

                // valida os campos obrigatorios
                if($questao['obrigatorio']) {
                    $this->FichasAssistenciaisRespostas->validate[$questao['codigo'].'_resposta'] = array(
                        'rule' => 'notEmpty',
                        'message' => 'Este campo é obrigatório',
                        'required' => true
                        );
                }//fim obrigatorio

                //verifica se existe sub_questoes
                if(!empty($questao['sub_questao'])) {
                    foreach ($questao['sub_questao'] as $keySubQuestao => $subquestao) {

                        //aplica a regra para add uma nova questao no formulario ou não
                        // $add_questao = $this->aplicaRegraQuestoes($questao);
                        // $dados_questoes['grupo'][$keyGrupo]['questao'][$keyQuestao]['sub_questao'][$keySubQuestao] = $add_questao;

                        if($subquestao['obrigatorio']) {
                            $this->FichasAssistenciaisRespostas->validate[$subquestao['codigo'].'_resposta'] = array(
                                'rule' => 'notEmpty',
                                'message' => 'Este campo é obrigatório',
                                'required' => true
                                );
                        }
                    }
                }//fim sub questoes
            }//fim questao
        }//fim foreach grupo
        //===============================================

        return $dados_questoes;


    }//FINAL FUNCTION montaQuestoes

    /**
     * [aplicaRegraQuestoes
     *
     * metodo para aplicar a regra de negocio para cada parametro passado da ficha clinica devolvendo para o metodo que consumiu com os dados da ficha clinica
     *
     * ]
     * @param  [array] $dados [parametro com os dados de regras para filtros e deixar dinamico a ficha clinica]
     * @return [json]        [retorna json no formato que deve ser montado a sub da sub questão, muitas vezes com os dados do select em questão como combos]
     */
    public function aplicaRegraQuestoes($dados, $codigo_pedido_exame)
    {
        //variavel auxiliar
        $add_questao = array();

        //verifica se tem conteudo o parametro dados
        if(!empty($dados)) {

            //seta os dados para o add_questao
            $add_questao = $dados;

            //seta os indices adicionais
            $add_questao['campo_livre'] = (isset($add_questao['campo_livre']) ? $add_questao['campo_livre'] : null);
            $add_questao['menstruacao'] = (isset($add_questao['menstruacao']) ? $add_questao['menstruacao'] : null);
            $add_questao['default'] = (isset($add_questao['default']) ? $add_questao['default'] : null);
            $add_questao['risco_campo_exibir'] = (isset($add_questao['risco_campo_exibir']) ? $add_questao['risco_campo_exibir'] : 0);
            $add_questao['multiplos_riscos'] = (isset($add_questao['multiplos_riscos']) ? $add_questao['multiplos_riscos'] : false);
            $add_questao['risco_formulario'] = (isset($add_questao['risco_formulario']) ? $add_questao['risco_formulario'] : null);
            $add_questao['options'] = (isset($add_questao['options']) ? $add_questao['options'] : null);
            $add_questao['multiplos_farmacos'] = (isset($add_questao['multiplos_farmacos']) ? $add_questao['multiplos_farmacos'] : false);
            $add_questao['farmaco'] = (isset($add_questao['farmaco']) ? $add_questao['farmaco'] : null);
            $add_questao['cids_campo_exibir'] = (isset($add_questao['cids_campo_exibir']) ? $add_questao['cids_campo_exibir'] : 0);
            $add_questao['multiplos_cids'] = (isset($add_questao['multiplos_cids']) ? $add_questao['multiplos_cids'] : false);
            $add_questao['cids'] = (isset($add_questao['cids']) ? $add_questao['cids'] : null);
            $add_questao['lyn'] = (isset($add_questao['lyn']) ? $add_questao['lyn'] : null);


            //variaveis auxiliares para montar os combos quando precisar
            $riscos = array(
                '' => 'Selecione um risco..',
                'Ruído' => 'Ruído',
                'Calor' => 'Calor',
                'Pressão' => 'Pressão',
                'Umidade' => 'Umidade',
                'Radiação' => 'Radiação',
                'Vibrações' => 'Vibrações',
                'Poeiras' => 'Poeiras',
                'Fumos' => 'Fumos',
                'Gases' => 'Gases',
                'Agentes Patogênicos' => 'Agentes Patogênicos',
                'Outros' => 'Outros'
            );


            //monta o array com os dados dos riscos quando ele estiver ativo
            $riscos_list = array(
                'funcao' => array(
                    'name' => 'FichaAssistencialResposta.riscos.'.$add_questao['codigo'].'.0.funcao_resposta',
                    'tipo' => 'INPUT',
                    'label' => 'Função:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'risco' => array(
                    'name' => 'FichaAssistencialResposta.riscos.'.$add_questao['codigo'].'.0.risco',
                    'tipo' => 'SELECT',
                    'label' => 'Risco:',
                    'busca' => null,
                    'conteudo' => $riscos,
                ),
                'inicio' => array(
                    'name' => 'FichaAssistencialResposta.riscos.'.$add_questao['codigo'].'.0.inicio',
                    'tipo' => 'DATE',
                    'label' => 'Inicio:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'termino' => array(
                    'name' => 'FichaAssistencialResposta.riscos.'.$add_questao['codigo'].'.0.termino',
                    'tipo' => 'DATE',
                    'label' => 'Termino:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'outros' => array(
                    'name' => 'FichaAssistencialResposta.riscos.'.$add_questao['codigo'].'.0.risco_outros',
                    'tipo' => 'INPUT',
                    'label' => 'Digite o risco não previsto:',
                    'busca' => null,
                    'conteudo' => null,
                ),
            );

            //verifica se o valor dentro do indice conteudo esta como json
            $add_questao['conteudo'] = json_decode($add_questao['conteudo'],true);

            if(!empty($add_questao['campo_livre_label']) && strtoupper($add_questao['campo_livre_label']) != 'PARENTESCO') {

                //para montar o campo livre
                $add_questao['campo_livre'] = array(
                    'name' => 'FichaAssistencialResposta.campo_livre.'.$add_questao['codigo'],
                    'tipo' => 'INPUT',
                    'label' => $add_questao['campo_livre_label'],
                    'busca' => null,
                    'conteudo' => null,
                );

            }// fim campo livre label

            $add_questao['tipo_uso'] = 0;

            switch ($add_questao['tipo']) {

                //CASO O CAMPO SEJA DO TIPO INPUT OU FLOAT:
                case 'VARCHAR':
                case 'FLOAT':

                    $add_questao['tipo'] = "INPUT";

                    //verificacao quando menstruacao
                    if ($add_questao['label'] == 'ÚLTIMA MENSTRUAÇÃO:') {
                        //retorna a menstruacao para verificar se é maior que 30 dias e pergunta se é gravidez??
                        $add_questao['menstruacao'] = 1;
                    }//fim verificacao menstruacao

                break;

                //CASO O CAMPO SEJA DO TIPO BOOLEANO OU RADIO:
                case 'BOOLEANO':

                    $add_questao['tipo'] = "RADIO";

                    // se booleano deixar apenas as respostas "sim e "não" disponiveis
                    $add_questao['conteudo'] = array(1 => 'Sim', 0 => 'Não');
                    $add_questao['default'] = 0;

                case 'CID10':
                    $add_questao['multiplos_cids'] = true;
                case 'FARMACO':
                case 'RADIO':

                    $add_questao['tipo'] = "RADIO";

                    if(!empty($add_questao['opcao_selecionada'])) {
                        $add_questao['default'] = $add_questao['opcao_selecionada'];
                    }

                    // cria o input
                    $add_questao['farmaco_campo_exibir'] = (empty($add_questao['farmaco_campo_exibir']) ? '0' : $add_questao['farmaco_campo_exibir']);

                    //verifica se tem riscos ativos
                    if($add_questao['riscos_ativo'] == 1) {

                        $add_questao['risco_campo_exibir'] = "Sim";
                        $add_questao['multiplos_riscos'] = 1;

                        //seta o array com os riscos pre definidos
                        $add_questao['risco_formulario'] = $riscos_list;
                    }//fim riscos_ativos

                break;

                //CASO O CAMPO SEJA DO TIPO CHECKBOX:
                case 'CHECKBOX':

                    //coloca o valor do conteudo no options
                    $add_questao['options'] = $add_questao['conteudo'];

                break;

                case 'COMBO':
                case 'SELECT':

                    $add_questao['tipo'] = "SELECT";

                    //coloca o valor do conteudo no options
                    $add_questao['options'] = $add_questao['conteudo'];

                break;

                case 'PRESCRICAO':

                    $add_questao['tipo'] = "BOOLEANO";
                    $add_questao['conteudo'] = array(1 => 'Sim', 0 => 'Não');
                    $add_questao['default'] = 0;

                    $add_questao['tipo_uso'] = 1;

                break;

            } //fim switch


            //verifica o codigo da questao
            switch ($add_questao['codigo']) {
                case '174':
                case '176':
                case '177':
                    $add_questao['tipo'] = "RADIO";
                    // se booleano deixar apenas as respostas "sim e "não" disponiveis
                    $add_questao['conteudo'] = array(1 => 'Sim', 0 => 'Não');
                    break;
            }


            // <!-- Monta o módulo fármaco -->

            //verifica se tem farmaco par ativar
            if($add_questao['farmaco_ativo']) {

                $add_questao['multiplos_farmacos'] = true;

                //para exibir o farmaco quando tiver
                if(isset($add_questao['conteudo'][1])) {
                    $add_questao['farmaco_campo_exibir'] = 1;
                }

                if(isset($add_questao['conteudo']["Sim"])) {
                    $add_questao['farmaco_campo_exibir'] = "Sim";
                }

                $add_questao['farmaco'] = $this->getFarmaco($add_questao['codigo'],'campo_livre', $add_questao['tipo_uso'], $codigo_pedido_exame);

            } //fim farmaco ativo
            //<!-- fim modulo farmaco -->
            //multiplos cids
            if($add_questao['multiplas_cids_ativo'] == 1 || isset($add_questao['conteudo']['subquestion_exibe_multiplas_cids'])) {

                if(isset($add_questao['conteudo']['subquestion_exibe_multiplas_cids'])) {
                    $add_questao['multiplos_cids'] = true;
                    $add_questao['cids_campo_exibir'] = "subquestion_exibe_multiplas_cids";
                }
                else {
                    $add_questao['cids_campo_exibir'] = 1;
                }


                $add_questao['cids'] = array(
                    'cid' => array(
                        'name' => 'FichaAssistencialResposta.cid10.'.$add_questao['codigo'].'.0.doenca',
                        'tipo' => 'INPUT',
                        'label' => 'CID10:',
                        'busca' => 'add_cid',
                        'conteudo' => null
                    )
                );

                if($add_questao['farmaco_ativo']) {
                    $add_questao['cids']['farmaco'] = $this->getFarmaco($add_questao['codigo'],'cid10', $codigo_pedido_exame);
                }

            }//fim multiplos cids

        }//fim verificacao do parametro dados

        // debug($add_questao);exit;

        //retira do array os indices que nao vai precisar
        unset($add_questao['campo_livre_label']);
        unset($add_questao['observacao']);
        unset($add_questao['ajuda']);
        unset($add_questao['quebra_linha']);
        unset($add_questao['opcao_selecionada']);
        unset($add_questao['opcao_abre_menu_escondido']);
        unset($add_questao['farmaco_ativo']);
        unset($add_questao['opcao_exibe_label']);
        unset($add_questao['multiplas_cids_ativo']);
        unset($add_questao['ativo']);
        unset($add_questao['tipo_uso']);
        // unset($add_questao['farmaco_campo_exibir']);
        unset($add_questao['multiplas_cids_esconde_outros']);
        unset($add_questao['riscos_ativo']);
        unset($add_questao['descricao_ativo']);
        unset($add_questao['ordenacao']);

        // monta o arrays
        return $add_questao;


    }// fim aplicaRegraQuestoes

    /**
     * [getFarmaco
     * metodo para montar o farmaco
     * ]
     * @param  string $tipo [description]
     * @return [type]       [description]
     */
    public function getFarmaco($codigo, $tipo = 'campo_livre',$tipo_uso = 0, $codigo_pedido_exame = null)
    {

        //para montar o combo
        $data_aprazamento = array();
        $data_aprazamento[''] = 'Aprazamento..';
        // for($i = 1; $i <= 24; $i++){
        //     $data_aprazamento['A cada '.$i.' hora'] = 'A cada '.$i.' hora';
        // }
        $data_aprazamento['A cada 4 horas'] = 'A cada 4 horas';
        $data_aprazamento['A cada 6 horas'] = 'A cada 6 horas';
        $data_aprazamento['A cada 8 horas'] = 'A cada 8 horas';
        $data_aprazamento['A cada 12 horas'] = 'A cada 12 horas';
        $data_aprazamento['A cada 24 horas'] = 'A cada 24 horas';

        //para montar o combo
        $data_dose = array();
        $data_dose[''] = 'Dose..';
        $data_dose['1/2'] = '1/2';
        for($i = 1; $i <= 10; $i++){
            $data_dose[$i] = $i;
            $data_dose[$i.' 1/2'] = $i.' 1/2';
        }
        //fim de montar os combos

        //monta o farmaco list
        $farmaco_list = array(
            'farmaco' => array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.farmaco',
                'tipo' => 'INPUT',
                'label' => 'Fármaco:',
                'busca' => 'add_medicamentos',
                'conteudo' => null,
            ),
            'posologia' => array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.posologia',
                'tipo' => 'INPUT',
                'label' => 'Posologia:',
                'busca' => null,
                'conteudo' => null,
            ),
            'aprazamento' => array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.aprazamento',
                'tipo' => 'SELECT',
                'label' => 'Aprazamento:',
                'busca' => null,
                'conteudo' => $data_aprazamento,
            ),
            'dose_diaria' => array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.dose_diaria',
                'tipo' => 'SELECT',
                'label' => 'Dose diária:',
                'busca' => null,
                'conteudo' => $data_dose,
            ),
        );

        if($tipo_uso) {
            $opcoes_tipo_uso = $this->FichasAssistenciaisTipoUso->find('list', ['keyField' => 'codigo','valueField' => 'descricao'])->order(['descricao' => 'ASC'])->toArray();

            $fichasAssistenciaisRespostas = TableRegistry::getTableLocator()->get('FichasAssistenciaisRespostas');

            $fields = array(
                'codigo_ficha_assistencial_resposta'    => 'FichasAssistenciaisRespostas.codigo',
                'observacao'    => 'FichasAssistenciaisRespostas.observacao',
            );

            $joins = array(
                array(
                    'table' => 'fichas_assistenciais',
                    'alias' => 'FichasAssistenciais',
                    'type'  => 'INNER',
                    'conditions' => 'FichasAssistenciais.codigo_pedido_exame = '.$codigo_pedido_exame.' '
                )
            );

            $conditions = " FichasAssistenciaisRespostas.codigo_ficha_assistencial = FichasAssistenciais.codigo and FichasAssistenciaisRespostas.codigo_ficha_assistencial_questao = ". $codigo;

            $query = $fichasAssistenciaisRespostas->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->first();

            $farmaco_list['duracao'] = array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.duracao',
                'tipo' => 'INPUT',
                'label' => 'Duração:',
                'busca' => null,
                'conteudo' => null,
            );

            $farmaco_list['tipo_uso'] = array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.tipo_uso',
                'tipo' => 'SELECT',
                'label' => 'Tipo Uso:',
                'busca' => null,
                'conteudo' => $opcoes_tipo_uso,
            );

            $farmaco_list['observacao'] = array(
                'name' => 'FichaAssistencialResposta.'.$tipo.'.'.$codigo.'.observacao',
                'tipo' => 'TEXTAREA',
                'label' => 'Observação:',
                'busca' => null,
                'conteudo' => $query['observacao'],
            );
        }

        return $farmaco_list;

    }//fim getFarmaco


}
