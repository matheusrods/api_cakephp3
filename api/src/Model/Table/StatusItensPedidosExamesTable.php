<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StatusItensPedidosExames Model
 *
 * @method \App\Model\Entity\StatusItensPedidosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StatusItensPedidosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class StatusItensPedidosExamesTable extends Table
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

        $this->setTable('status_itens_pedidos_exames');
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
            ->scalar('descicao')
            ->maxLength('descicao', 50)
            ->requirePresence('descicao', 'create')
            ->notEmptyString('descicao');

        return $validator;
    }
}
