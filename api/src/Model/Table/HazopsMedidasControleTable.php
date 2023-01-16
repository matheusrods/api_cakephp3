<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HazopsMedidasControle Model
 *
 * @method \App\Model\Entity\HazopsMedidasControle get($primaryKey, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControle findOrCreate($search, callable $callback = null, $options = [])
 */
class HazopsMedidasControleTable extends Table
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

        $this->setTable('hazops_medidas_controle');
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
            ->integer('codigo_hazop_agente_risco')
            ->requirePresence('codigo_hazop_agente_risco', 'create')
            ->notEmptyString('codigo_hazop_agente_risco');

        $validator
            ->integer('codigo_hazop_medida_controle_tipo')
            ->requirePresence('codigo_hazop_medida_controle_tipo', 'create')
            ->notEmptyString('codigo_hazop_medida_controle_tipo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

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
