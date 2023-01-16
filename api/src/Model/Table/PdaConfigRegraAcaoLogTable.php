<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PdaConfigRegraAcaoLog Model
 *
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraAcaoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaConfigRegraAcaoLogTable extends Table
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

        $this->setTable('pda_config_regra_acao_log');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_pda_config_regra_acao')
            ->requirePresence('codigo_pda_config_regra_acao', 'create')
            ->notEmptyString('codigo_pda_config_regra_acao');

        $validator
            ->integer('codigo_pda_config_regra')
            ->requirePresence('codigo_pda_config_regra', 'create')
            ->notEmptyString('codigo_pda_config_regra');

        $validator
            ->integer('tipo_acao')
            ->requirePresence('tipo_acao', 'create')
            ->notEmptyString('tipo_acao');

        $validator
            ->allowEmptyString('ativo');

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

        $validator
            ->allowEmptyString('acao_sistema');

        return $validator;
    }
}
