<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsRiscosLog Model
 *
 * @method \App\Model\Entity\PosObsRiscosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRiscosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsRiscosLogTable extends Table
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

        $this->setTable('pos_obs_riscos_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->setEntityClass('App\Model\Entity\PosObsRiscosLog');
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_arrt_pa')
            ->requirePresence('codigo_arrt_pa', 'create')
            ->notEmptyString('codigo_arrt_pa');

        $validator
            ->integer('codigo_arrt')
            ->requirePresence('codigo_arrt', 'create')
            ->notEmptyString('codigo_arrt');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->allowEmptyString('acao_sistema');

        return $validator;
    }
}
