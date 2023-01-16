<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ItensPedidosExamesBaixaLog Model
 *
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesBaixaLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ItensPedidosExamesBaixaLogTable extends Table
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

        $this->setTable('itens_pedidos_exames_baixa_log');
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
            ->integer('codigo_itens_pedidos_exames_baixa')
            ->requirePresence('codigo_itens_pedidos_exames_baixa', 'create')
            ->notEmptyString('codigo_itens_pedidos_exames_baixa');

        $validator
            ->integer('codigo_itens_pedidos_exames')
            ->requirePresence('codigo_itens_pedidos_exames', 'create')
            ->notEmptyString('codigo_itens_pedidos_exames');

        /*$validator
            ->integer('resultado')
            ->requirePresence('resultado', 'create')
            ->notEmptyString('resultado');

        $validator
            ->date('data_validade')
            ->allowEmptyDate('data_validade');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->date('data_realizacao_exame')
            ->allowEmptyDate('data_realizacao_exame');

        $validator
            ->integer('codigo_aparelho_audiometrico')
            ->allowEmptyString('codigo_aparelho_audiometrico');*/

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        /*$validator
            ->boolean('fornecedor_particular')
            ->notEmptyString('fornecedor_particular');

        $validator
            ->boolean('pedido_importado')
            ->notEmptyString('pedido_importado');

        $validator
            ->boolean('integracao_cliente')
            ->allowEmptyString('integracao_cliente');*/

        return $validator;
    }
}
