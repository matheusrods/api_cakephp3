<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsLocaisLog Model
 *
 * @method \App\Model\Entity\PosObsLocaisLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocaisLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsLocaisLogTable extends Table
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

        $this->setTable('pos_obs_locais_log');
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
            ->integer('codigo_obs_local')
            ->requirePresence('codigo_obs_local', 'create')
            ->notEmptyString('codigo_obs_local');

        $validator
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_local_empresa')
            ->allowEmptyString('codigo_local_empresa');

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
