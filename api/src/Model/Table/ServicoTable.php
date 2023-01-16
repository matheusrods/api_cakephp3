<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Servico Model
 *
 * @property \App\Model\Table\ClienteProdutoTable&\Cake\ORM\Association\BelongsToMany $ClienteProduto
 * @property \App\Model\Table\ClienteProdutoTable&\Cake\ORM\Association\BelongsToMany $ClienteProduto
 * @property \App\Model\Table\ClienteProdutoTable&\Cake\ORM\Association\BelongsToMany $ClienteProduto
 * @property \App\Model\Table\ListasDePrecoProdutoTable&\Cake\ORM\Association\BelongsToMany $ListasDePrecoProduto
 * @property \App\Model\Table\ProdutoTable&\Cake\ORM\Association\BelongsToMany $Produto
 * @property \App\Model\Table\TabelaModeloTable&\Cake\ORM\Association\BelongsToMany $TabelaModelo
 *
 * @method \App\Model\Entity\Servico get($primaryKey, $options = [])
 * @method \App\Model\Entity\Servico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Servico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Servico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Servico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Servico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Servico findOrCreate($search, callable $callback = null, $options = [])
 */
class ServicoTable extends AppTable
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

        $this->setTable('servico');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('ClienteProduto', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'cliente_produto_id',
            'joinTable' => 'cliente_produto_servico'
        ]);
        $this->belongsToMany('ClienteProduto', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'cliente_produto_id',
            'joinTable' => 'cliente_produto_servico2'
        ]);
        $this->belongsToMany('ClienteProduto', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'cliente_produto_id',
            'joinTable' => 'cliente_produto_servico2_log'
        ]);
        $this->belongsToMany('ListasDePrecoProduto', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'listas_de_preco_produto_id',
            'joinTable' => 'listas_de_preco_produto_servico'
        ]);
        $this->belongsToMany('Produto', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'produto_id',
            'joinTable' => 'produto_servico'
        ]);
        $this->belongsToMany('TabelaModelo', [
            'foreignKey' => 'servico_id',
            'targetForeignKey' => 'tabela_modelo_id',
            'joinTable' => 'tabela_modelo_servico'
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
            ->scalar('tipo_servico')
            ->maxLength('tipo_servico', 1)
            ->allowEmptyString('tipo_servico');

        $validator
            ->scalar('codigo_externo')
            ->maxLength('codigo_externo', 20)
            ->allowEmptyString('codigo_externo');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_antigo')
            ->allowEmptyString('codigo_antigo');

        $validator
            ->integer('codigo_classificacao_servico')
            ->allowEmptyString('codigo_classificacao_servico');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
