<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;


/**
 * Audiometrias Model
 *
 * @method \App\Model\Entity\Audiometria get($primaryKey, $options = [])
 * @method \App\Model\Entity\Audiometria newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Audiometria[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Audiometria|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Audiometria saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Audiometria patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Audiometria[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Audiometria findOrCreate($search, callable $callback = null, $options = [])
 */
class AudiometriasTable extends AppTable
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

        $this->setTable('audiometrias');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
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
            ->integer('codigo_funcionario')
            ->requirePresence('codigo_funcionario', 'create')
            ->notEmptyString('codigo_funcionario');

        $validator
            ->date('data_exame')
            ->requirePresence('data_exame', 'create')
            ->notEmptyDate('data_exame');

        $validator
            ->integer('tipo_exame')
            ->requirePresence('tipo_exame', 'create')
            ->notEmptyString('tipo_exame');

        $validator
            ->integer('resultado')
            ->requirePresence('resultado', 'create')
            ->notEmptyString('resultado');

        $validator
            ->scalar('aparelho')
            ->maxLength('aparelho', 128)
            ->requirePresence('aparelho', 'create')
            ->notEmptyString('aparelho');

        $validator
            ->scalar('ref_seq')
            ->maxLength('ref_seq', 128)
            ->requirePresence('ref_seq', 'create')
            ->notEmptyString('ref_seq');

        $validator
            ->scalar('fabricante')
            ->maxLength('fabricante', 128)
            ->requirePresence('fabricante', 'create')
            ->notEmptyString('fabricante');

        $validator
            ->date('calibracao')
            ->requirePresence('calibracao', 'create')
            ->notEmptyDate('calibracao');

        $validator
            ->decimal('esq_va_025')
            ->allowEmptyString('esq_va_025');

        $validator
            ->decimal('esq_va_050')
            ->allowEmptyString('esq_va_050');

        $validator
            ->decimal('esq_va_1')
            ->allowEmptyString('esq_va_1');

        $validator
            ->decimal('esq_va_2')
            ->allowEmptyString('esq_va_2');

        $validator
            ->decimal('esq_va_3')
            ->allowEmptyString('esq_va_3');

        $validator
            ->decimal('esq_va_4')
            ->allowEmptyString('esq_va_4');

        $validator
            ->decimal('esq_va_6')
            ->allowEmptyString('esq_va_6');

        $validator
            ->decimal('esq_va_8')
            ->allowEmptyString('esq_va_8');

        $validator
            ->decimal('dir_va_025')
            ->allowEmptyString('dir_va_025');

        $validator
            ->decimal('dir_va_050')
            ->allowEmptyString('dir_va_050');

        $validator
            ->decimal('dir_va_1')
            ->allowEmptyString('dir_va_1');

        $validator
            ->decimal('dir_va_2')
            ->allowEmptyString('dir_va_2');

        $validator
            ->decimal('dir_va_3')
            ->allowEmptyString('dir_va_3');

        $validator
            ->decimal('dir_va_4')
            ->allowEmptyString('dir_va_4');

        $validator
            ->decimal('dir_va_6')
            ->allowEmptyString('dir_va_6');

        $validator
            ->decimal('dir_va_8')
            ->allowEmptyString('dir_va_8');

        $validator
            ->decimal('esq_vo_025')
            ->allowEmptyString('esq_vo_025');

        $validator
            ->decimal('esq_vo_050')
            ->allowEmptyString('esq_vo_050');

        $validator
            ->decimal('esq_vo_1')
            ->allowEmptyString('esq_vo_1');

        $validator
            ->decimal('esq_vo_2')
            ->allowEmptyString('esq_vo_2');

        $validator
            ->decimal('esq_vo_3')
            ->allowEmptyString('esq_vo_3');

        $validator
            ->decimal('esq_vo_4')
            ->allowEmptyString('esq_vo_4');

        $validator
            ->decimal('esq_vo_6')
            ->allowEmptyString('esq_vo_6');

        $validator
            ->decimal('esq_vo_8')
            ->allowEmptyString('esq_vo_8');

        $validator
            ->decimal('dir_vo_025')
            ->allowEmptyString('dir_vo_025');

        $validator
            ->decimal('dir_vo_050')
            ->allowEmptyString('dir_vo_050');

        $validator
            ->decimal('dir_vo_1')
            ->allowEmptyString('dir_vo_1');

        $validator
            ->decimal('dir_vo_2')
            ->allowEmptyString('dir_vo_2');

        $validator
            ->decimal('dir_vo_3')
            ->allowEmptyString('dir_vo_3');

        $validator
            ->decimal('dir_vo_4')
            ->allowEmptyString('dir_vo_4');

        $validator
            ->decimal('dir_vo_6')
            ->allowEmptyString('dir_vo_6');

        $validator
            ->decimal('dir_vo_8')
            ->allowEmptyString('dir_vo_8');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->boolean('em_analise')
            ->allowEmptyString('em_analise');

        $validator
            ->boolean('ocupacional')
            ->allowEmptyString('ocupacional');

        $validator
            ->boolean('agravamento')
            ->allowEmptyString('agravamento');

        $validator
            ->boolean('estavel')
            ->allowEmptyString('estavel');

        $validator
            ->integer('ouve_bem')
            ->allowEmptyString('ouve_bem');

        $validator
            ->integer('zumbido_ouvido')
            ->allowEmptyString('zumbido_ouvido');

        $validator
            ->integer('trauma_ouvidos')
            ->allowEmptyString('trauma_ouvidos');

        $validator
            ->integer('doenca_auditiva')
            ->allowEmptyString('doenca_auditiva');

        $validator
            ->integer('local_ruidoso')
            ->allowEmptyString('local_ruidoso');

        $validator
            ->integer('realizou_exame')
            ->allowEmptyString('realizou_exame');

        $validator
            ->boolean('repouso_auditivo')
            ->notEmptyString('repouso_auditivo');

        $validator
            ->decimal('horas_repouso_auditivo')
            ->allowEmptyString('horas_repouso_auditivo');

        $validator
            ->scalar('observacoes')
            ->maxLength('observacoes', 500)
            ->allowEmptyString('observacoes');

        $validator
            ->integer('meatoscopia_od')
            ->allowEmptyString('meatoscopia_od');

        $validator
            ->integer('meatoscopia_oe')
            ->allowEmptyString('meatoscopia_oe');

        $validator
            ->scalar('str_od_dbna')
            ->maxLength('str_od_dbna', 128)
            ->allowEmptyString('str_od_dbna');

        $validator
            ->scalar('str_oe_dbna')
            ->maxLength('str_oe_dbna', 128)
            ->allowEmptyString('str_oe_dbna');

        $validator
            ->scalar('irf_od')
            ->maxLength('irf_od', 128)
            ->allowEmptyString('irf_od');

        $validator
            ->scalar('irf_oe')
            ->maxLength('irf_oe', 128)
            ->allowEmptyString('irf_oe');

        $validator
            ->scalar('laf_od_dbna')
            ->maxLength('laf_od_dbna', 128)
            ->allowEmptyString('laf_od_dbna');

        $validator
            ->scalar('laf_oe_dbna')
            ->maxLength('laf_oe_dbna', 128)
            ->allowEmptyString('laf_oe_dbna');

        $validator
            ->scalar('observacoes2')
            ->maxLength('observacoes2', 500)
            ->allowEmptyString('observacoes2');

        $validator
            ->integer('codigo_itens_pedidos_exames')
            ->requirePresence('codigo_itens_pedidos_exames', 'create')
            ->notEmptyString('codigo_itens_pedidos_exames');

        return $validator;
    }

    //atributos auxiliares
    public $tipos_exames = array(
        '1' => 'Exame admissional',
        '2' => 'Exame periódico',
        '3' => 'Exame demissional',
        '4' => 'Retorno ao trabalho',
        '5' => 'Mudança de função',
        '6' => 'Monitoração pontual',
        '7' => 'Pontual'
        );

    public $resultados = array(
        '10' => 'Normal',
        '1' => 'Alterado'
        );

    public $refseq = array(
        '1' => 'Referencial',
        '2' => 'Sequencial'
        );

    public $meatoscopias = array(
        "10" => "Normal",
        "1" => "Alterado",
        "2" => "Sem obstrução",
        "3" => "Com obstrução parcial",
        "4" => "Com obstrução total"
        );

    public $simnao = array(
        1 => 'Sim',
        0 => 'Não'
    );

    /**
     * [getCamposAudiometria metodo para montar os campos do formulario da audiometria]
     * @param  [type] $codigo_usuario           [codigo do usuario do sistema logado]
     * @param  [type] $codigo_item_pedido_exame [codigo do item do pedido de exame, tem que ser ter o exame audiometria senao retorna erro]
     * @return [type]                           [description]
     */
    public function getCamposAudiometria($codigo_usuario, $codigo_pedido_exame)
    {

        //instancia os itens
        $this->PedidosExames = TableRegistry::get('PedidosExames');
        $this->ItensPedidosExames = TableRegistry::get('ItensPedidosExames');
        $this->Funcionarios = TableRegistry::get('Funcionarios');
        $this->AparelhosAudiometricos = TableRegistry::get('AparelhosAudiometricos');
        $this->Configuracao = TableRegistry::get('Configuracao');

        //pega o codigo da audiometria
        $codigo_exame_audiometrico = $this->Configuracao->getChave('INSERE_EXAME_AUDIOMETRICO');

        //pega os dados do pedido de exame
        $itens_pedidos_exames = $this->ItensPedidosExames->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame, 'codigo_exame' => $codigo_exame_audiometrico])->first();
        if(empty($itens_pedidos_exames)) {
            return "Erro ao encontrar o item para audiometria!";
        }

        $itens_pedidos_exames = $itens_pedidos_exames->toArray();

        //seta a variavel auxiliar
        $dados = $this->PedidosExames->obtemDadosComplementares($itens_pedidos_exames['codigo_pedidos_exames']);

        $dados_principais = array(
            'codigo_pedido_exame' => $dados['codigo'],
            'codigo_itens_pedidos_exames' => $itens_pedidos_exames['codigo'],
            'empresa' => $dados['Empresa']['razao_social'],
            'unidade' => $dados['Unidade']['razao_social'],
            'setor' => $dados['setor'],
            'codigo_funcionario' => $dados['Funcionario']['codigo'],
            'funcionario' => $dados['Funcionario']['nome'],
            'cpf' => $dados['Funcionario']['cpf'],
            'idade' => $dados['idade'],
            'data_admissao' => $dados['ClienteFuncionario']['admissao'],
            'sexo' => $dados['sexo'],
            'cargo' => $dados['cargo'],
            'tipo_pedido_exame' => $dados['tipo_pedido_exame'],
        );

        //aparelhos audiometricos
        $aparelhos_audiometricos = $this->AparelhosAudiometricos->find('list',['keyField' => 'codigo','valueField' => 'descricao'])->where(['ativo = 1'])->toArray();

        // debug($dados_principais);exit;
        $questoes['grupo_header'] = array();
        $questoes['grupo'] = array(
            array(
                'descricao' => "DADOS PRINCIPAIS",
                'questao' => array(
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.data_exame",
                        "tipo" => "DATE",
                        "tamanho" => "4",
                        "label" => "Data do Exame:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.resultado",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Resultado:",
                        "obrigatorio"=> 1,
                        "conteudo" => (!empty($this->resultados)) ? $this->resultados : array(),
                        "options" => (!empty($this->resultados)) ? $this->resultados : array(),
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.ref_seq",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Ref / Seq:",
                        "obrigatorio"=> 1,
                        "conteudo" => (!empty($this->refseq)) ? $this->refseq : array(),
                        "options" => (!empty($this->refseq)) ? $this->refseq : array(),
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.aparelho",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Aparelho:",
                        "obrigatorio"=> 1,
                        "conteudo" => (!empty($this->aparelhos_audiometricos)) ? $this->aparelhos_audiometricos : $aparelhos_audiometricos,
                        "options" => (!empty($this->aparelhos_audiometricos)) ? $this->aparelhos_audiometricos : array(),
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.fabricante",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Fabricante:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.calibracao",
                        "tipo" => "DATE",
                        "tamanho" => "4",
                        "label" => "Calibração:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.em_analise",
                        "tipo" => "CHECKBOX",
                        "tamanho" => "4",
                        "label" => "Em análise:",
                        "obrigatorio"=> 1,
                        "conteudo" => array('1' => 'Em análise'),
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.ocupacional",
                        "tipo" => "CHECKBOX",
                        "tamanho" => "4",
                        "label" => "Ocupacional:",
                        "obrigatorio"=> 1,
                        "conteudo" => array('1' => 'Ocupacional'),
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.agravamento",
                        "tipo" => "CHECKBOX",
                        "tamanho" => "4",
                        "label" => "Agravamento:",
                        "obrigatorio"=> 1,
                        "conteudo" => array('1' => 'Agravamento'),
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.estavel",
                        "tipo" => "CHECKBOX",
                        "tamanho" => "4",
                        "label" => "Estável:",
                        "obrigatorio"=> 1,
                        "conteudo" => array('1' => 'Estável'),
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.ouve_bem",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Ouve bem?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.zumbido_ouvido",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Zumbido no ouvido?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.trauma_ouvidos",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Já sofreu trauma nos ouvidos?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.doenca_auditiva",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Já apresentou alguma doença auditiva?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.local_ruidoso",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Já trabalhou em local ruidoso?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.realizou_exame",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Já realizou este exame anteriormente?",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.repouso_auditivo",
                        "tipo" => "RADIO",
                        "tamanho" => "4",
                        "label" => "Repouso auditivo:",
                        "obrigatorio"=> 1,
                        "conteudo" => $this->simnao,
                        "options" => $this->simnao,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.horas_repouso_auditivo",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "Quantas horas de repouso auditivo:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
                        "multiplos_farmacos" => false,
                        "farmaco" => null,
                        "cids_campo_exibir" => 0,
                        "multiplos_cids" => false,
                        "cids" => null,
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.observacoes",
                        "tipo" => "TEXTAREA",
                        "tamanho" => "12",
                        "label" => "Observações:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "options" => null,
                        "default" => null,
                        "lyn" => null,
                        "farmaco_campo_exibir" => 0,
                        "campo_livre" => null,
                        "menstruacao" => null,
                        "risco_campo_exibir" => 0,
                        "multiplos_riscos" => false,
                        "risco_formulario" => null,
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
                'descricao' => "Meatoscopia",
                'questao' => array(
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.meatoscopia_od",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Meatoscopia (OD):",
                        "obrigatorio"=> 1,
                        "conteudo" => (!empty($this->meatoscopias)) ? $this->meatoscopias : array(),
                        "default" => array(),
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.meatoscopia_oe",
                        "tipo" => "SELECT",
                        "tamanho" => "4",
                        "label" => "Meatoscopia (OE):",
                        "obrigatorio"=> 1,
                        "conteudo" => (!empty($this->meatoscopias)) ? $this->meatoscopias : array(),
                        "default" => array(),
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                )
            ),
            array(
                'descricao' => "Logoaudiometria",
                'questao' => array(
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.str_od_dbna",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "SRT(OD) dBNA:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.str_oe_dbna",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "SRT(OE) dBNA:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.irf_od",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "IRF(OD) %:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.irf_oe",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "IRF(OE) %:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.laf_od_dbna",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "LAF(OD) dBNA:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                    array(
                        "codigo" => null,
                        "name" => "Audiometria.laf_oe_dbna",
                        "tipo" => "INPUT",
                        "tamanho" => "4",
                        "label" => "LAF(OE) dBNA:",
                        "obrigatorio"=> 1,
                        "conteudo" => null,
                        "default" => null,
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
                        'sub_questao_campo_exibir' => null,
                        "sub_questao" => array(),
                    ),
                )
            ),
        );

        $dados_audiometria['table'] = array(
            array(
                "titulo" => "Limiares Tonais - Orelha esquerda",
                'cabecalhos' => array(
                    "1" => "KHz",
                    "2" => "Via Aérea",
                    "3" => "Via Óssea"
                ),
                'linha' => array(
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "0.25"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_025",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_025",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "0.50"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_050",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_050",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "1"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_1",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_1",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "2"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_2",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_2",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "3"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_3",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_3",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "4"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_4",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_4",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "6"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_6",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_vo_6",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "8"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.esq_va_8",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "esq_vo_8",
                            "conteudo" => ""
                        ),
                    )
                ),
            ),

            array(

                "titulo" => "Limiares Tonais - Orelha direita",
                'cabecalhos' => array(
                    "1" => "KHz",
                    "2" => "Via Aérea",
                    "3" => "Via Óssea"
                ),
                'linha' => array(

                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "0.25"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_025",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_025",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "0.50"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_050",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_050",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "1"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_1",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_1",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "2"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_2",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_2",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "3"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_3",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_3",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "4"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_4",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_4",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "6"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_6",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_6",
                            "conteudo" => ""
                        ),
                    ),
                    array(
                        array(
                            "tipo" => "LABEL",
                            "label" => "8"
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_va_8",
                            "conteudo" => ""
                        ),
                        array(
                            "tipo" => "INPUT",
                            "name" => "Audiometria.dir_vo_8",
                            "conteudo" => ""
                        ),
                    )
                )
            ),
            array(
                "label" => 'Observações:',
                "name" => "Audiometria.observacoes2",
                "tipo" => 'TEXTAREA',
                "conteudo" => ''
            )
        );


        $formulario = array_merge($questoes,$dados_audiometria);


        $dados_array = [
            'dados_principais' => $dados_principais,
            'historico' => false,
            'formulario' => $formulario,
        ];

        // debug($dados_array);exit;

        return $dados_array;

    }//fim getCamposAudiometria

    public function getAudiometriaPorPedidoExame($codigo_pedido_exame){

        //$fields = $this->Audiometrias;
        $joins  = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItensPedidosExames',
                'type' => 'INNER',
                'conditions' => "ItensPedidosExames.codigo_pedidos_exames = ".$codigo_pedido_exame." and ItensPedidosExames.codigo_exame = 130"
            )
        );
        $conditions = "Audiometrias.codigo_itens_pedidos_exames = ItensPedidosExames.codigo";

        $dados = $this->find()
            //->select($fields)
            ->join($joins)
            ->where($conditions)
            ->limit(20)
            ->first();

        return $dados;

    }


}
