<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * ServicoLog Model
 *
 * @property \App\Model\Table\ClienteProdutoTable&\Cake\ORM\Association\BelongsToMany $ClienteProduto
 * @property \App\Model\Table\ListasDePrecoProdutoTable&\Cake\ORM\Association\BelongsToMany $ListasDePrecoProduto
 *
 * @method \App\Model\Entity\ServicoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ServicoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ServicoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ServicoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServicoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ServicoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ServicoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ServicoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ServicoLogTable extends AppTable
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

        $this->setTable('servico_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('ClienteProduto', [
            'foreignKey' => 'servico_log_id',
            'targetForeignKey' => 'cliente_produto_id',
            'joinTable' => 'cliente_produto_servico_log'
        ]);
        $this->belongsToMany('ListasDePrecoProduto', [
            'foreignKey' => 'servico_log_id',
            'targetForeignKey' => 'listas_de_preco_produto_id',
            'joinTable' => 'listas_de_preco_produto_servico_log'
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
            ->integer('codigo_servico')
            ->requirePresence('codigo_servico', 'create')
            ->notEmptyString('codigo_servico');

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
