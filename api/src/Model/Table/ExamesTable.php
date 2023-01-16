<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Exames Model
 *
 * @property \App\Model\Table\ExamesLogTable&\Cake\ORM\Association\BelongsToMany $ExamesLog
 * @property \App\Model\Table\ExamesLogTable&\Cake\ORM\Association\BelongsToMany $ExamesLog
 * @property \App\Model\Table\ItensPedidosTable&\Cake\ORM\Association\BelongsToMany $ItensPedidos
 * @property \App\Model\Table\PedidosTable&\Cake\ORM\Association\BelongsToMany $Pedidos
 * @property \App\Model\Table\PropostasCredenciamentoTable&\Cake\ORM\Association\BelongsToMany $PropostasCredenciamento
 * @property \App\Model\Table\RiscosTable&\Cake\ORM\Association\BelongsToMany $Riscos
 *
 * @method \App\Model\Entity\Exame get($primaryKey, $options = [])
 * @method \App\Model\Entity\Exame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Exame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Exame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Exame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Exame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Exame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Exame findOrCreate($search, callable $callback = null, $options = [])
 */
class ExamesTable extends AppTable
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

        $this->setTable('exames');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('ExamesLog', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'exames_log_id',
            'joinTable' => 'anexos_exames_log'
        ]);
        $this->belongsToMany('ExamesLog', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'exames_log_id',
            'joinTable' => 'grupos_exames_log'
        ]);
        $this->belongsToMany('ItensPedidos', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'itens_pedido_id',
            'joinTable' => 'itens_pedidos_exames'
        ]);
        $this->belongsToMany('Pedidos', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'pedido_id',
            'joinTable' => 'pedidos_exames'
        ]);
        $this->belongsToMany('PropostasCredenciamento', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'propostas_credenciamento_id',
            'joinTable' => 'propostas_credenciamento_exames'
        ]);
        $this->belongsToMany('Riscos', [
            'foreignKey' => 'exame_id',
            'targetForeignKey' => 'risco_id',
            'joinTable' => 'riscos_exames'
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
            ->requirePresence('codigo_servico', 'create')
            ->notEmptyString('codigo_servico');

        $validator
            ->integer('codigo_rh')
            ->allowEmptyString('codigo_rh');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 100)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('periodo_meses')
            ->maxLength('periodo_meses', 5)
            ->allowEmptyString('periodo_meses');

        $validator
            ->scalar('periodo_apos_demissao')
            ->maxLength('periodo_apos_demissao', 5)
            ->allowEmptyString('periodo_apos_demissao');

        $validator
            ->integer('codigo_tabela_amb')
            ->allowEmptyString('codigo_tabela_amb');

        $validator
            ->integer('codigo_tuss')
            ->allowEmptyString('codigo_tuss');

        $validator
            ->integer('codigo_ch')
            ->allowEmptyString('codigo_ch');

        $validator
            ->integer('empresa_cliente')
            ->allowEmptyString('empresa_cliente');

        $validator
            ->integer('exame_auto')
            ->allowEmptyString('exame_auto');

        $validator
            ->integer('laboral')
            ->allowEmptyString('laboral');

        $validator
            ->scalar('tela_resultado')
            ->maxLength('tela_resultado', 50)
            ->allowEmptyString('tela_resultado');

        $validator
            ->scalar('referencia')
            ->maxLength('referencia', 1000)
            ->allowEmptyString('referencia');

        $validator
            ->scalar('unidade_medida')
            ->maxLength('unidade_medida', 10)
            ->allowEmptyString('unidade_medida');

        $validator
            ->scalar('recomendacoes')
            ->maxLength('recomendacoes', 1000)
            ->allowEmptyString('recomendacoes');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->allowEmptyString('sexo');

        $validator
            ->scalar('conduta_exame')
            ->maxLength('conduta_exame', 1000)
            ->allowEmptyString('conduta_exame');

        $validator
            ->integer('controla_validacoes')
            ->allowEmptyString('controla_validacoes');

        $validator
            ->scalar('codigo_esocial')
            ->maxLength('codigo_esocial', 255)
            ->allowEmptyString('codigo_esocial');

        $validator
            ->scalar('material_biologico')
            ->maxLength('material_biologico', 10)
            ->allowEmptyString('material_biologico');

        $validator
            ->scalar('interpretacao_exame')
            ->maxLength('interpretacao_exame', 10)
            ->allowEmptyString('interpretacao_exame');

        $validator
            ->dateTime('data_incio_monitoracao')
            ->allowEmptyDateTime('data_incio_monitoracao');

        $validator
            ->integer('exame_excluido_convocacao')
            ->allowEmptyString('exame_excluido_convocacao');

        $validator
            ->integer('exame_excluido_ppp')
            ->allowEmptyString('exame_excluido_ppp');

        $validator
            ->integer('exame_excluido_aso')
            ->allowEmptyString('exame_excluido_aso');

        $validator
            ->integer('exame_excluido_pcmso')
            ->allowEmptyString('exame_excluido_pcmso');

        $validator
            ->integer('exame_excluido_anual')
            ->allowEmptyString('exame_excluido_anual');

        $validator
            ->integer('exame_excluido_rac')
            ->allowEmptyString('exame_excluido_rac');

        $validator
            ->integer('exame_admissional')
            ->allowEmptyString('exame_admissional');

        $validator
            ->integer('exame_periodico')
            ->allowEmptyString('exame_periodico');

        $validator
            ->integer('exame_demissional')
            ->allowEmptyString('exame_demissional');

        $validator
            ->integer('exame_retorno')
            ->allowEmptyString('exame_retorno');

        $validator
            ->integer('exame_mudanca')
            ->allowEmptyString('exame_mudanca');

        $validator
            ->scalar('periodo_idade')
            ->maxLength('periodo_idade', 100)
            ->allowEmptyString('periodo_idade');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->scalar('qtd_periodo_idade')
            ->maxLength('qtd_periodo_idade', 5)
            ->allowEmptyString('qtd_periodo_idade');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('qualidade_vida')
            ->allowEmptyString('qualidade_vida');

        $validator
            ->scalar('periodo_idade_2')
            ->maxLength('periodo_idade_2', 5)
            ->allowEmptyString('periodo_idade_2');

        $validator
            ->scalar('qtd_periodo_idade_2')
            ->maxLength('qtd_periodo_idade_2', 5)
            ->allowEmptyString('qtd_periodo_idade_2');

        $validator
            ->scalar('periodo_idade_3')
            ->maxLength('periodo_idade_3', 5)
            ->allowEmptyString('periodo_idade_3');

        $validator
            ->scalar('qtd_periodo_idade_3')
            ->maxLength('qtd_periodo_idade_3', 5)
            ->allowEmptyString('qtd_periodo_idade_3');

        $validator
            ->scalar('periodo_idade_4')
            ->maxLength('periodo_idade_4', 5)
            ->allowEmptyString('periodo_idade_4');

        $validator
            ->scalar('qtd_periodo_idade_4')
            ->maxLength('qtd_periodo_idade_4', 5)
            ->allowEmptyString('qtd_periodo_idade_4');

        $validator
            ->boolean('exame_audiometria')
            ->allowEmptyString('exame_audiometria');

        $validator
            ->integer('exame_monitoracao')
            ->allowEmptyString('exame_monitoracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_esocial_27')
            ->allowEmptyString('codigo_esocial_27');

        return $validator;
    }

    public function obterLista(array $params){

        $descricao = null;
        $where = '';

        $fields = array(
            'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
            'codigo',
        );

        if(isset($params['descricao'])){
            $descricao = $params['descricao'];
            $where = "descricao LIKE '%".utf8_encode($descricao)."%'";

            $dados = $this->find()
            ->select($fields)
            ->where($where)
            ->hydrate(false)
            ->toArray();

        } else {
            $dados = $this->find()
            ->hydrate(false)
            ->toArray();
        }

        return $dados;
    }
}
