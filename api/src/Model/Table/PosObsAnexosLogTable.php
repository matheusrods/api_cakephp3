<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsAnexosLog Model
 *
 * @method \App\Model\Entity\PosObsAnexosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsAnexosLogTable extends Table
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

        $this->setTable('pos_obs_anexos_log');
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
            ->integer('codigo_obs_anexo')
            ->requirePresence('codigo_obs_anexo', 'create')
            ->notEmptyString('codigo_obs_anexo');

        $validator
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_pos_anexo')
            ->requirePresence('codigo_pos_anexo', 'create')
            ->notEmptyString('codigo_pos_anexo');

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
