<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Produto Model
 *
 * @property \App\Model\Table\ClienteTable&\Cake\ORM\Association\BelongsToMany $Cliente
 * @property \App\Model\Table\ProdutoServicoTable&\Cake\ORM\Association\BelongsToMany $ProdutoServico
 * @property \App\Model\Table\ListasDePrecoTable&\Cake\ORM\Association\BelongsToMany $ListasDePreco
 * @property \App\Model\Table\ServicoTable&\Cake\ORM\Association\BelongsToMany $Servico
 * @property \App\Model\Table\PropostasCredenciamentoTable&\Cake\ORM\Association\BelongsToMany $PropostasCredenciamento
 *
 * @method \App\Model\Entity\Produto get($primaryKey, $options = [])
 * @method \App\Model\Entity\Produto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Produto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Produto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Produto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Produto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Produto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Produto findOrCreate($search, callable $callback = null, $options = [])
 */
class ProdutoTable extends AppTable
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

        $this->setTable('produto');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Cliente', [
            'foreignKey' => 'produto_id',
            'targetForeignKey' => 'cliente_id',
            'joinTable' => 'cliente_produto'
        ]);
        $this->belongsToMany('ProdutoServico', [
            'foreignKey' => 'produto_id',
            'targetForeignKey' => 'produto_servico_id',
            'joinTable' => 'cliente_produto_servico'
        ]);
        $this->belongsToMany('ListasDePreco', [
            'foreignKey' => 'produto_id',
            'targetForeignKey' => 'listas_de_preco_id',
            'joinTable' => 'listas_de_preco_produto'
        ]);
        $this->belongsToMany('Servico', [
            'foreignKey' => 'produto_id',
            'targetForeignKey' => 'servico_id',
            'joinTable' => 'produto_servico'
        ]);
        $this->belongsToMany('PropostasCredenciamento', [
            'foreignKey' => 'produto_id',
            'targetForeignKey' => 'propostas_credenciamento_id',
            'joinTable' => 'propostas_credenciamento_produto'
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
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->scalar('codigo_naveg')
            ->maxLength('codigo_naveg', 20)
            ->allowEmptyString('codigo_naveg');

        $validator
            ->scalar('codigo_ccusto_naveg')
            ->maxLength('codigo_ccusto_naveg', 20)
            ->allowEmptyString('codigo_ccusto_naveg');

        $validator
            ->scalar('codigo_formula_naveg')
            ->maxLength('codigo_formula_naveg', 2)
            ->allowEmptyString('codigo_formula_naveg');

        $validator
            ->boolean('faturamento')
            ->allowEmptyString('faturamento');

        $validator
            ->scalar('codigo_formula_naveg_sp')
            ->maxLength('codigo_formula_naveg_sp', 2)
            ->allowEmptyString('codigo_formula_naveg_sp');

        $validator
            ->boolean('controla_volume')
            ->notEmptyString('controla_volume');

        $validator
            ->scalar('codigo_servico_prefeitura')
            ->maxLength('codigo_servico_prefeitura', 5)
            ->allowEmptyString('codigo_servico_prefeitura');

        $validator
            ->decimal('formula_valor_acima_de')
            ->allowEmptyString('formula_valor_acima_de');

        $validator
            ->scalar('codigo_formula_naveg_sp_acima')
            ->maxLength('codigo_formula_naveg_sp_acima', 2)
            ->allowEmptyString('codigo_formula_naveg_sp_acima');

        $validator
            ->scalar('codigo_formula_naveg_acima')
            ->maxLength('codigo_formula_naveg_acima', 2)
            ->allowEmptyString('codigo_formula_naveg_acima');

        $validator
            ->decimal('valor_acima_irrf')
            ->notEmptyString('valor_acima_irrf');

        $validator
            ->decimal('percentual_irrf')
            ->notEmptyString('percentual_irrf');

        $validator
            ->decimal('percentual_irrf_acima')
            ->notEmptyString('percentual_irrf_acima');

        $validator
            ->boolean('mensalidade')
            ->allowEmptyString('mensalidade');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_antigo')
            ->allowEmptyString('codigo_antigo');

        return $validator;
    }
}
