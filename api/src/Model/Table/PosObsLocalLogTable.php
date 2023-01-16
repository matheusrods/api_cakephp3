<?php

namespace App\Model\Table;

use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsLocalLog Model
 *
 * @method \App\Model\Entity\PosObsLocalLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsLocalLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsLocalLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocalLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocalLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocalLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocalLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocalLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsLocalLogTable extends Table
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

        $this->setTable('pos_obs_local_log');
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
            ->integer('codigo_pos_obs_olcal')
            ->requirePresence('codigo_pos_obs_olcal', 'create')
            ->notEmptyString('codigo_pos_obs_olcal');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->scalar('descricao')
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
