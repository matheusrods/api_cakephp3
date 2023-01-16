<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArRt Model
 *
 * @method \App\Model\Entity\ArRt get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArRt newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArRt[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArRt|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArRt saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArRt patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArRt[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArRt findOrCreate($search, callable $callback = null, $options = [])
 */
class ArRtTable extends Table
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

        $this->setTable('ar_rt');
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
            ->integer('codigo_agente_risco')
            ->requirePresence('codigo_agente_risco', 'create')
            ->notEmptyString('codigo_agente_risco');

        $validator
            ->integer('codigo_risco_tipo')
            ->requirePresence('codigo_risco_tipo', 'create')
            ->notEmptyString('codigo_risco_tipo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
