<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;




/**
 * FichasClinicas Model
 *
 * @property \App\Model\Table\QuestoesTable&\Cake\ORM\Association\BelongsToMany $Questoes
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsToMany $Respostas
 *
 * @method \App\Model\Entity\FichasClinica get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasClinica newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasClinica[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinica|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinica saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinica patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinica[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinica findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasClinicasTable extends AppTable
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

        $this->setTable('fichas_clinicas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Questoes', [
            'foreignKey' => 'fichas_clinica_id',
            'targetForeignKey' => 'questo_id',
            'joinTable' => 'fichas_clinicas_questoes'
        ]);
        $this->belongsToMany('Respostas', [
            'foreignKey' => 'fichas_clinica_id',
            'targetForeignKey' => 'resposta_id',
            'joinTable' => 'fichas_clinicas_respostas'
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
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('incluido_por')
            ->maxLength('incluido_por', 255)
            ->requirePresence('incluido_por', 'create')
            ->notEmptyString('incluido_por');

        $validator
            ->time('hora_inicio_atendimento')
            ->requirePresence('hora_inicio_atendimento', 'create')
            ->notEmptyTime('hora_inicio_atendimento');

        $validator
            ->time('hora_fim_atendimento')
            ->requirePresence('hora_fim_atendimento', 'create')
            ->notEmptyTime('hora_fim_atendimento');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

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
            ->decimal('circunferencia_quadril')
            ->allowEmptyString('circunferencia_quadril');

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
            ->scalar('imc')
            ->maxLength('imc', 50)
            ->allowEmptyString('imc');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * [getFichaClinicaQuestoes metodo para pegar as questoes para serem respondidadas pelo medico]
     * @param  [type] $codigo_pedido_exame [codigo do pedido de exame que está querendo relacionar a ficha clinica]
     * @return [type]                      [description]
     */
    public function getFichaClinicaQuestoes(int $codigo_pedido_exame, int $codigo_usuario)
    {


        //instancia as tabela para ajudar no metodo
        $Usuario = TableRegistry::get('Usuario');
        $PedidosExames = TableRegistry::get('PedidosExames');

        //valida se o pedido de exame não está cancelado
        $pedido_exame = $PedidosExames->find()->select('codigo_status_pedidos_exames')->where(['codigo'=>$codigo_pedido_exame])->hydrate(false)->first();
        if($pedido_exame['codigo_status_pedidos_exames'] == 5) {
            return 'O pedido de exame selecionado foi cancelado.';
        }//fim pedidos de exames

        //pega os dados complementares da fichca clinica
        $dados = $PedidosExames->obtemDadosComplementares($codigo_pedido_exame, true);

        //pega os dados do usuario para incluir na ficha clinica
        $usuario = $Usuario->find()->where(['codigo' => $codigo_usuario])->hydrate(false)->first();

        list($dados_medicoes,$dados_depara) = $this->carrega_dados_questionario($dados['Funcionario']['cpf']);

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
        $dados_fc['grupo_header'] = array(
            array(
                'descricao' => "DADOS PRINCIPAIS",
                'questao' => array(
                    array(
                        "name" => "FichaClinica.incluido_por",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Incluído por:",
                        "obrigatorio"=> 1,
                        "conteudo" => $usuario['nome'],
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.codigo_medico",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Médico:",
                        "obrigatorio"=> 1,
                        "conteudo" => $dados['Medico'],
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.hora_inicio_atendimento",
                        "tipo" => "HOUR",
                        "tamanho" => "4",
                        "label" => "Horário de início de atendimento:",
                        "obrigatorio"=> 1,
                        "conteudo" => date("H:i"),
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.hora_fim_atendimento",
                        "tipo" => "HOUR",
                        "tamanho" => "4",
                        "label" => "Horário de finalização de atendimento:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
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
                'descricao' => "MEDIÇÕES",
                'questao' => array(
                    array(
                        "name" => "FichaClinica.pa",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Pressão arterial (mmHg):",
                        "obrigatorio"=> 1,
                        "conteudo" => $pressao,
                        "default" => $pressao,
                        "lyn" => (!empty($pressao)) ? $lyn_desc : null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.pulso",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Pulso (bpm - batimentos por minuto):",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
                        "lyn" => null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.circunferencia_abdominal",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Circunferência abdominal (cm)",
                        "obrigatorio"=> 1,
                        "conteudo" => $circ_abdom,
                        "default" => $circ_abdom,
                        "lyn" => (!empty($circ_abdom)) ? $lyn_desc : null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.peso",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Peso (kg):",
                        "obrigatorio"=> 1,
                        "conteudo" => $peso,
                        "default" => $peso,
                        "lyn" => (!empty($peso)) ? $lyn_desc : null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.altura",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Altura (cm):",
                        "obrigatorio"=> 1,
                        "conteudo" => $altura,
                        "default" => $altura,
                        "lyn" => (!empty($altura)) ? $lyn_desc : null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.circunferencia_quadril",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Circunferência quadril (cm):",
                        "obrigatorio"=> 1,
                        "conteudo" => $circ_quadril,
                        "default" => $circ_quadril,
                        "lyn" => (!empty($circ_quadril)) ? $lyn_desc : null,

                        "codigo" => null,
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
                        "name" => "FichaClinica.imc",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Índice de Massa Corpórea (IMC):",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->get_mensagem_imc($peso,$altura),
                        "default" => $this->get_mensagem_imc($peso,$altura),
                        "lyn" => $lyn_desc,

                        "codigo" => null,
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

        //pega os dados de historico pelo pedido de exames
        $historico = $this->getHistorico($codigo_pedido_exame);

        //monta as questoes
        $questoes = $this->montaQuestoes($dados['Funcionario'],$dados_depara);
        $questoes = array_merge($dados_fc,$questoes);
        //pega os campos add do questionario
        $questoes['observacao'] = array(
            "name" => "FichaClinica.observacao",
            "tipo" => "TEXT",
            "tamanho" => "12",
            "label" => "OBSERVAÇÃO",
            "conteudo" => null,
            "default" => null,

            "obrigatorio"=> 0,
            "lyn" => null,
            "codigo" => null,
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
            "sub_questao" => array(),
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
            'historico' => $historico,
            'formulario' => $questoes,
        ];

        // debug($dados_array);exit;

        //retorna os dados da ficha clinica para montar
        return $dados_array;

    }//fim getQuestoes

    /**
     * [get_mensagem_imc para retornar a msg do imc]
     * @param  [type] $imc [description]
     * @return [type]      [description]
     */
    public function get_mensagem_imc($peso,$altura)
    {

        //nao informado
        $msg_imc = 'Não informado!';
        $imc = 0.0;

        if($peso != "" && $peso != '.' && $peso != '0.' && $peso != '0.0'
            && $altura != '' && $altura != '.' && $altura != '0.' && $altura != '0.0') {

            // console.log(peso+"--"+altura);

            $imc = ($peso / ($altura * $altura));

            // console.log(imc);

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

        }//fim if

        return $msg_imc;

    }// fim get_imc

    /**
     * [carrega_dados_questionario carrega os dados do questionario e faz o de/para com o lyn]
     * @param  [type] $cpf_funcionario [cpf do funcionario para buscar na base de dados do lyn]
     * @return [type]                  [description]
     */
    public function carrega_dados_questionario($cpf_funcionario)
    {
        $this->DeparaQuestoes = TableRegistry::get('DeparaQuestoes');
        $this->DeparaQuestoesRespostas = TableRegistry::get('DeparaQuestoesRespostas');
        $this->UsuariosDados = TableRegistry::get('UsuariosDados');

        //pega as questoes e perguntas do questionario
        $depara['questoes'] = $this->DeparaQuestoes->find('list',['keyField' => 'codigo_questao_ficha_clinica','valueField' => 'codigo_questao_questionario'])->where(['codigo_questao_ficha_clinica <> 0'])->toArray();

        ///////
        $options['fields'] = array(
            'codigo_questao'=>'Respostas.codigo_questao',
            'maior_codigo'=>'MAX(Respostas.codigo)'
        );

        $options['joins'] = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo'
            ),
            array(
                'table' => 'respostas',
                'alias' => 'Respostas',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = Respostas.codigo_usuario'
            ),
        );
        // $cpf_funcionario = '93091800059';
        // $query_cte = $this->UsuariosDados->find('sql',$options);
        $query_cte = $this->UsuariosDados->find()
            ->select($options['fields'])
            ->join($options['joins'])
            ->where(["UsuariosDados.cpf = '".$cpf_funcionario."'"])
            ->group(['Respostas.codigo_questao'])
            ->sql();

        // print $cpf_funcionario;
        // print $query_cte;exit;

        $cte = "WITH CTE AS (".$query_cte.")";

        $query = $cte.' SELECT codigo_questao, ( SELECT codigo_resposta FROM respostas WHERE codigo = maior_codigo ) AS codigo_resposta FROM CTE ORDER BY codigo_questao';

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados_respostas =  $conn->execute($query)->fetchAll('assoc');

        foreach ($dados_respostas as $value) {
            $depara['respostas'][ $value['codigo_questao'] ] = $value['codigo_resposta'];
        }

        $dados = $this->DeparaQuestoesRespostas->find()->where(['resposta_ficha_clinica IS NOT NULL'])->hydrate(false)->toArray();
        foreach ($dados as $value) {
            $respostas_questoes[ $value['codigo_questao_questionario'] ][ $value['codigo_resposta_questionario'] ] = $value['resposta_ficha_clinica'];
        }
        // debug($depara);
        // debug($respostas_questoes);
        // exit;

        /////////
        $dados_depara = array();
        foreach ($depara['questoes'] as $key => $questao) {
            if ( isset($depara['respostas'][$questao]) && isset($respostas_questoes[$questao]) ){
                $dados_depara[] = array($key , $respostas_questoes[$questao][ $depara['respostas'][$questao] ]);
            }
        }
        // debug($dados_depara);exit;
        /////////

        ///////
        $options2['fields'] = array(
            'pressao_sis'=>'UsuarioPressaoArterial.pressao_arterial_sistolica',
            'pressao_dia'=>'UsuarioPressaoArterial.pressao_arterial_diastolica',
            'peso_kg'=>'FLOOR(UsuarioImc.peso)',
            'peso_g'=>'RIGHT(UsuarioImc.peso,2)',
            'altura_m'=>'UsuarioImc.altura / 100',
            'altura_cm'=>'UsuarioImc.altura % 100',
            'circ_abdom'=>'UsuarioAbdominal.circ_abdom',
            'circ_quadril'=>'UsuarioAbdominal.circ_quadril'
        );

        $options2['joins'] = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo'
            ),
            array(
                'table' => 'usuarios_pressao_arterial',
                'alias' => 'UsuarioPressaoArterial',
                'type' => 'LEFT',
                'conditions' => 'UsuarioPressaoArterial.codigo = (SELECT TOP 1 codigo FROM usuarios_pressao_arterial WHERE codigo_usuario = Usuario.codigo ORDER BY data_inclusao DESC)'
            ),
            array(
                'table' => 'usuarios_imc',
                'alias' => 'UsuarioImc',
                'type' => 'LEFT',
                'conditions' => 'UsuarioImc.codigo = (SELECT TOP 1 codigo FROM usuarios_imc WHERE codigo_usuario = Usuario.codigo ORDER BY data_inclusao DESC)'
            ),
            array(
                'table' => 'usuarios_abdominal',
                'alias' => 'UsuarioAbdominal',
                'type' => 'LEFT',
                'conditions' => 'UsuarioAbdominal.codigo = (SELECT TOP 1 codigo FROM usuarios_abdominal WHERE codigo_usuario = Usuario.codigo ORDER BY data_inclusao DESC)'
            ),
        );

        $options2['conditions'] = array(
            'UsuariosDados.cpf' => $cpf_funcionario
        );

        $dados_medicoes = $this->UsuariosDados->find()
            ->select($options2['fields'])
            ->join($options2['joins'])
            ->where($options2['conditions'])
            // ->sql();
            ->hydrate(false)
            ->toArray();
        ///////

        // debug($dados_medicoes);exit;

        return array($dados_medicoes,json_encode($dados_depara));
    }// fim carrega_dados_questionario

    /**
     * [getHistorico description]
     *
     * pega os dados de historico caso exista
     *
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function getHistorico($codigo_pedido_exame = null)
    {
        $this->HistoricoFichaClinica = TableRegistry::get('HistoricoFichaClinica');
        $this->PedidosExames = TableRegistry::get('PedidosExames');

        $historico = false;
        //verifica se tem codigo de pedido de exames
        if(!empty($codigo_pedido_exame)) {
            //pega a montagem da query
            $dadosHistorico = $this->HistoricoFichaClinica->getDadosPedidoExames($codigo_pedido_exame);
            //pega os dados
            $historico_dados = $this->PedidosExames->find()
                ->select($dadosHistorico['fields'])
                ->join($dadosHistorico['joins'])
                ->where($dadosHistorico['conditions'])
                ->hydrate(false)
                ->first();

            if($historico_dados) {
                $historico = true;
            }

        }//fim codigo_pedido_exames

        return $historico;

    }//fim getHistorico

    /**
     * [verificaParecer verifica o parecer pelo pedido do exame]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function verificaParecer($codigo_pedido_exame = null)
    {
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
                            INNER JOIN RHHealth.dbo.clientes_setores cs ON (cs.codigo_setor = st.codigo AND cs.codigo_cliente_alocacao = fsc.codigo_cliente_alocacao )
                            INNER JOIN RHHealth.dbo.grupo_exposicao ge  ON (ge.codigo_cargo = cg.codigo AND ge.codigo_cliente_setor = cs.codigo)
                            INNER JOIN RHHealth.dbo.grupos_exposicao_risco ger ON (ger.codigo_grupo_exposicao = ge.codigo)
                            INNER JOIN RHHealth.dbo.riscos ri ON (ri.codigo = ger.codigo_risco)
                        WHERE pe.codigo = '.$codigo_pedido_exame.' and ri.risco_caracterizado_por_altura is not null and ri.risco_caracterizado_por_altura <> 0
                        GROUP BY ri.risco_caracterizado_por_altura) = 1 THEN \'S\' ELSE \'N\' END AS risco_por_altura,

                CASE WHEN (SELECT   ri.risco_caracterizado_por_trabalho_confinado
                        FROM rhhealth.dbo.pedidos_exames pe
                            INNER JOIN rhhealth.dbo.funcionario_setores_cargos fsc ON fsc.codigo = pe.codigo_func_setor_cargo
                            INNER JOIN rhhealth.dbo.cliente_funcionario cf ON cf.codigo = fsc.codigo_cliente_funcionario
                            INNER JOIN RHHealth.dbo.cliente c ON c.codigo = cf.codigo_cliente_matricula
                            INNER JOIN RHHealth.dbo.cargos cg ON cg.codigo = fsc.codigo_cargo
                            INNER JOIN RHHealth.dbo.setores st ON st.codigo = fsc.codigo_setor
                            INNER JOIN RHHealth.dbo.clientes_setores cs ON (cs.codigo_setor = st.codigo AND cs.codigo_cliente_alocacao = fsc.codigo_cliente_alocacao)
                            INNER JOIN RHHealth.dbo.grupo_exposicao ge  ON (ge.codigo_cargo = cg.codigo AND ge.codigo_cliente_setor = cs.codigo)
                            INNER JOIN RHHealth.dbo.grupos_exposicao_risco ger ON (ger.codigo_grupo_exposicao = ge.codigo)
                            INNER JOIN RHHealth.dbo.riscos ri ON (ri.codigo = ger.codigo_risco)
                        WHERE pe.codigo = '.$codigo_pedido_exame.' and ri.risco_caracterizado_por_trabalho_confinado is not null and ri.risco_caracterizado_por_trabalho_confinado <> 0
                        GROUP BY ri.risco_caracterizado_por_trabalho_confinado) = 1 then \'S\' ELSE \'N\' END  AS risco_por_confinamento
                ';
            // debug($query);exit;

             //executa a query
            $conn = ConnectionManager::get('default');
            $return =  $conn->execute($query)->fetchAll('assoc');

            $return = $return[0];

        }//fim codigo pedido de exame

        return $return;

    }// fim verificaParecer

    /**
     * [montaQuestoes monta as questoes da ficha clinica]
     * @param  array  $dados_funcionario [description]
     * @return [type]                    [description]
     */
    public function montaQuestoes($dados_funcionario = array(), $dados_depara)
    {

        //instancia as classes tables
        $this->FichasClinicasGrupoQuestoes = TableRegistry::get('FichasClinicasGrupoQuestoes');
        $this->FichasClinicasQuestoes = TableRegistry::get('FichasClinicasQuestoes');
        $this->FichasClinicasRespostas = TableRegistry::get('FichasClinicasRespostas');

        if(!empty($dados_funcionario['sexo'])) {
            $conditionsQuestao[] = "(exibir_se_sexo = '".$dados_funcionario['sexo']."' OR exibir_se_sexo IS NULL)";
        }

        $fields = [
            'codigo',
            'name'=>"CONCAT('FichaClinicaResposta.',codigo,'_resposta')",
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
            'farmaco_campo_exibir',
            'multiplas_cids_esconde_outros',
            'riscos_ativo',
            'descricao_ativo',
            'ordenacao'
        ];

        //variavel auxiliar para montar as questoes
        $dados_questoes = array();

        //pega o grupo de questoes
        $grupos_questoes = $this->FichasClinicasGrupoQuestoes->find()
            ->select(['codigo','descricao'])
            ->where(['ativo' => 1])
            ->hydrate(false)
            // ->all()
            ->toArray();
        // debug($grupo_questoes);

        //verifica se existe registro
        if(!empty($grupos_questoes)) {

            //varre os grupos
            foreach ($grupos_questoes as $key_grupo => $grupo) {

                //busca as questoes principais do grupo
                $questoes = $this->FichasClinicasQuestoes->find()
                    ->select($fields)
                    ->where([
                        'codigo_ficha_clinica_grupo_questao' => $grupo['codigo'],
                        'codigo_ficha_clinica_questao IS NULL',
                        'ativo' => 1,
                        $conditionsQuestao
                    ])
                    // ->sql();
                    ->hydrate(false)
                    ->toArray();
                // debug($questoes);exit;

                //verifica se achou alguma questao principal
                if(!empty($questoes)) {

                    //seta o valor da fichas grupos
                    $dados_questoes['grupo'][$key_grupo] = $grupo;

                    //varre as questoes
                    foreach ($questoes as $key_questao => $questao) {

                        //aplica a regra para add uma nova questao no formulario ou não
                        $add_questao = $this->aplicaRegraQuestoes($questao, $dados_depara);
                        $add_questao['sub_questao_campo_exibir'] = null;

                        //seta a questao princial
                        // $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao] = $questao;
                        $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao] = $add_questao;

                        //busca as sub questoes do grupo
                        $sub_questoes = $this->FichasClinicasQuestoes->find()
                            ->select($fields)
                            ->where([
                                'codigo_ficha_clinica_questao' => $questao['codigo'],
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

                            if(isset($add_questao['conteudo']["Alterado"])) {
                                $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao_campo_exibir'] = "Alterado";
                            }

                            //varre as subuqestoes
                            foreach($sub_questoes as $key_sub_questao => $sub) {

                                //aplica a regra para add uma nova questao no formulario ou não
                                $add_sub_questao = $this->aplicaRegraQuestoes($sub, $dados_depara);

                                //seta com o valor real
                                $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao'][$key_sub_questao] = $add_sub_questao;

                            }// fim sub

                            // //seta com o valor real
                            // $dados_questoes['grupo'][$key_grupo]['questao'][$key_questao]['sub_questao'] = $sub_questoes;
                        }

                    }//fim questoe
                }// fim questoes


            }//fim foreach grupos_questoes
        }//fim grupos
        // debug($dados_questoes);exit;

        // debug($joins);
        // debug($containConditions);

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
                    $this->FichasClinicasRespostas->validate[$questao['codigo'].'_resposta'] = array(
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
                            $this->FichasClinicasRespostas->validate[$subquestao['codigo'].'_resposta'] = array(
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

    }// fim montaQuestoes

    /**
     * [aplicaRegraQuestoes
     *
     * metodo para aplicar a regra de negocio para cada parametro passado da ficha clinica devolvendo para o metodo que consumiu com os dados da ficha clinica
     *
     * ]
     * @param  [array] $dados [parametro com os dados de regras para filtros e deixar dinamico a ficha clinica]
     * @return [json]        [retorna json no formato que deve ser montado a sub da sub questão, muitas vezes com os dados do select em questão como combos]
     */
    public function aplicaRegraQuestoes($dados, $depara)
    {
        //variavel auxiliar
        $add_questao = array();

        //verifica se tem conteudo o parametro dados
        if(!empty($dados)) {

            //seta os dados para o add_questao
            $add_questao = $dados;

            //seta os indices adicionais
            $add_questao['campo_livre'] = null;
            $add_questao['menstruacao'] = null;
            $add_questao['default'] = null;
            $add_questao['risco_campo_exibir'] = 0;
            $add_questao['multiplos_riscos'] = false;
            $add_questao['risco_formulario'] = null;
            $add_questao['options'] = null;
            $add_questao['multiplos_farmacos'] = false;
            $add_questao['farmaco'] = null;
            $add_questao['cids_campo_exibir'] = 0;
            $add_questao['multiplos_cids'] = false;
            $add_questao['cids'] = null;
            $add_questao['lyn'] = null;


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
                    'name' => 'FichaClinicaResposta.riscos.'.$add_questao['codigo'].'.0.funcao_resposta',
                    'tipo' => 'INPUT',
                    'label' => 'Função:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'risco' => array(
                    'name' => 'FichaClinicaResposta.riscos.'.$add_questao['codigo'].'.0.risco',
                    'tipo' => 'SELECT',
                    'label' => 'Risco:',
                    'busca' => null,
                    'conteudo' => $riscos,
                ),
                'inicio' => array(
                    'name' => 'FichaClinicaResposta.riscos.'.$add_questao['codigo'].'.0.inicio',
                    'tipo' => 'DATE',
                    'label' => 'Inicio:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'termino' => array(
                    'name' => 'FichaClinicaResposta.riscos.'.$add_questao['codigo'].'.0.termino',
                    'tipo' => 'DATE',
                    'label' => 'Termino:',
                    'busca' => null,
                    'conteudo' => null,
                ),
                'outros' => array(
                    'name' => 'FichaClinicaResposta.riscos.'.$add_questao['codigo'].'.0.risco_outros',
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
                    'name' => 'FichaClinicaResposta.campo_livre.'.$add_questao['codigo'],
                    'tipo' => 'INPUT',
                    'label' => $add_questao['campo_livre_label'],
                    'busca' => null,
                    'conteudo' => null,
                );

            }// fim campo livre label

            switch ($add_questao['tipo']) {

                //CASO O CAMPO SEJA DO TIPO VARCHAR OU FLOAT:
                case 'VARCHAR':
                case 'FLOAT':

                    $add_questao['tipo'] = "INPUT";

                    if ( $add_questao['label'] == 'OUTRAS (CID10)') {
                        $add_questao['tipo'] = "HIDDEN";
                        $add_questao['default'] = 1;
                    }

                    //verificacao quando menstruacao
                    if ($add_questao['label'] == 'ÚLTIMA MENSTRUAÇÃO:') {
                        $add_questao['tipo'] = "DATE";
                        //retorna a menstruacao para verificar se é maior que 30 dias e pergunta se é gravidez??
                        $add_questao['menstruacao'] = 1;
                    }//fim verificacao menstruacao

                    if ($add_questao['codigo'] == 307 || $add_questao['codigo'] == 308) {
                        $add_questao['tipo'] = "DATE";
                    }

                break;

                //CASO O CAMPO SEJA DO TIPO BOOLEANO OU RADIO:
                case 'BOOLEANO':

                    $add_questao['tipo'] = "RADIO";

                    // se booleano deixar apenas as respostas "sim e "não" disponiveis
                    $add_questao['conteudo'] = array(1 => 'Sim', 0 => 'Não');
                    $add_questao['default'] = 0;

                case 'RADIO':

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

            } //fim switch

            //multiplos cids
            if($add_questao['multiplas_cids_ativo'] == 1 || isset($add_questao['conteudo']['subquestion_exibe_multiplas_cids'])) {

                if(isset($add_questao['conteudo']['subquestion_exibe_multiplas_cids'])) {
                    $add_questao['multiplos_cids'] = true;
                    $add_questao['cids_campo_exibir'] = "subquestion_exibe_multiplas_cids";
                }
                else {
                    $add_questao['cids_campo_exibir'] = "1";
                }

                $add_questao['cids'] = array(
                    'cid' => array(
                        'name' => 'FichaClinicaResposta.cid10.'.$add_questao['codigo'].'.0.doenca',
                        'tipo' => 'INPUT',
                        'label' => 'CID10:',
                        'busca' => 'add_cid',
                        'conteudo' => null
                    )
                );

                if($add_questao['farmaco_ativo']) {
                    $add_questao['cids']['farmaco'] = $this->getFarmaco($add_questao['codigo'],'cid10');
                }

            }//fim multiplos cids
            else {
                // <!-- Monta o módulo fármaco -->

                //verifica se tem farmaco par ativar
                if($add_questao['farmaco_ativo']) {

                    $add_questao['multiplos_farmacos'] = false;

                    //para exibir o farmaco quando tiver
                    if(isset($add_questao['conteudo'][1])) {
                        $add_questao['farmaco_campo_exibir'] = 1;
                    }

                    if(isset($add_questao['conteudo']["Sim"])) {
                        $add_questao['farmaco_campo_exibir'] = "Sim";
                    }


                    $add_questao['farmaco'] = $this->getFarmaco($add_questao['codigo'],'campo_livre');

                } //fim farmaco ativo
                //<!-- fim modulo farmaco -->
            }//fim verificacao cid com farmaco

            switch ($add_questao['codigo']) {
                case '302':                
                    $add_questao['label'] = "OUTROS:";
                    break;
                case '274':
                    $add_questao['label'] = "STATUS RESULTADO EXAME:";
                    break;
                case '312':
                case '313':
                case '314':
                case '315':
                case '316':
                case '317':
                case '318':
                case '319':
                case '320':
                case '321':
                case '322':
                case '323':
                case '324':                
                    //para montar o campo livre
                    $add_questao['campo_livre'] = array(
                        'name' => 'FichaClinicaResposta.campo_livre.'.$add_questao['codigo'],
                        'tipo' => 'INPUT',
                        'label' => "Alteração não prevista e descrição",
                        'busca' => null,
                        'conteudo' => null,
                    );

                    break;
            }

        }//fim verificacao do parametro dados

        // debug($add_questao);exit;

        //verifica se existe dados de depara vindos do lyn
        if(!empty($depara)) {
            $depara = json_decode($depara);

            //varre o array do depara respondido pelo lyn
            foreach($depara AS $dp) {
                if($dp[0] == $add_questao['codigo']) {
                    $add_questao['lyn'] = "Questão selecionada via do Lyn!";
                    $add_questao['default'] = $dp[1];
                    $add_questao['opcao_selecionada'] = $dp[1];
                }
            }//fim foreach depara
        }//fim depara


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
    public function getFarmaco($codigo, $tipo = 'campo_livre')
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
                'name' => 'FichaClinicaResposta.'.$tipo.'.'.$codigo.'.farmaco',
                'tipo' => 'INPUT',
                'label' => 'Fármaco:',
                'busca' => 'add_medicamentos',
                'conteudo' => null,
            ),
            'posologia' => array(
                'name' => 'FichaClinicaResposta.'.$tipo.'.'.$codigo.'.posologia',
                'tipo' => 'INPUT',
                'label' => 'Posologia:',
                'busca' => null,
                'conteudo' => null,
            ),
            'aprazamento' => array(
                'name' => 'FichaClinicaResposta.'.$tipo.'.'.$codigo.'.aprazamento',
                'tipo' => 'SELECT',
                'label' => 'Aprazamento:',
                'busca' => null,
                'conteudo' => $data_aprazamento,
            ),
            'dose_diaria' => array(
                'name' => 'FichaClinicaResposta.'.$tipo.'.'.$codigo.'.dose_diaria',
                'tipo' => 'SELECT',
                'label' => 'Dose diária:',
                'busca' => null,
                'conteudo' => $data_dose,
            ),
        );

        return $farmaco_list;

    }//fim getFarmaco

    /**
     * [montaParecer monta os campos do parecer]
     * @param  int    $codigo_pedido_exame [codigo do pedido de exame]
     * @return [type]                      [description]
     */
    public function montaParecer(int $codigo_pedido_exame)
    {

        //pega o parecer da ficha clinica pelo pedido
        $verificaParecer = $this->verificaParecer($codigo_pedido_exame);

        // debug($verificaParecer);exit;

        $questoes['parecer'] = array(
            "name" => "FichaClinica.parecer",
            "tipo" => "RADIO",
            "tamanho" => "12",
            "label" => "PARECER:",
            "conteudo" => array(1 => 'Apto', 0 => 'Inapto'),
            "obrigatorio"=> 1,
        );

        //verifica se tem risco por altura
        $questoes['risco_altura'] = json_decode("{}");
        if($verificaParecer['risco_por_altura'] == "S") {
            $questoes['risco_altura'] = array(
                "name" => "FichaClinica.parecer_altura",
                "tipo" => "RADIO",
                "tamanho" => "12",
                "label" => "PARECER ALTURA:",
                "conteudo" => array(1 => 'Apto para trabalhar em altura', 0 => 'Inapto para trabalhar em altura'),
                "obrigatorio"=> 1,
            );
        }//fim altura

        //verifica se tem risco por altura
        $questoes['risco_confinamento'] = json_decode("{}");
        if($verificaParecer['risco_por_confinamento'] == "S") {
            $questoes['risco_confinamento'] = array(
                "name" => "FichaClinica.parecer_espaco_confinado",
                "tipo" => "RADIO",
                "tamanho" => "12",
                "label" => "PARECER CONFINAMENTO:",
                "conteudo" => array(1 => 'Apto para trabalho em espaço confinado', 0 => 'Inapto para trabalho em espaço confinado'),
                "obrigatorio"=> 1,
            );
        }//fim altura

        $questoes['anexo'] = array(
            "name" => "AnexosExames.arquivo",
            "tipo" => "FILE",
            "tamanho" => "12",
            "label" => "Upload do Aso:",
            "obrigatorio"=> 0,
        );

        $questoes['anexo_ficha'] =  array(
            "name" => "AnexosFichaClinica.arquivo",
            "tipo" => "FILE",
            "tamanho" => "12",
            "label" => "Upload da Ficha Clínica:",
            "obrigatorio"=> 0,
        );

        return $questoes;

    }//fim parecer

}
