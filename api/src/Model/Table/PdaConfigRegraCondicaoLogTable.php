<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PdaConfigRegraCondicaoLog Model
 *
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicaoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaConfigRegraCondicaoLogTable extends Table
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

        $this->setTable('pda_config_regra_condicao_log');
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
            ->integer('codigo_pda_config_regra_condicao')
            ->requirePresence('codigo_pda_config_regra_condicao', 'create')
            ->notEmptyString('codigo_pda_config_regra_condicao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pda_config_regra')
            ->requirePresence('codigo_pda_config_regra', 'create')
            ->notEmptyString('codigo_pda_config_regra');

        $validator
            ->integer('codigo_pda_tema_condicao')
            ->allowEmptyString('codigo_pda_tema_condicao');

        $validator
            ->integer('codigo_pda_tema_acoes')
            ->allowEmptyString('codigo_pda_tema_acoes');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->allowEmptyString('codigo_acoes_melhorias_status');

        $validator
            ->integer('codigo_origem_ferramentas')
            ->allowEmptyString('codigo_origem_ferramentas');

        $validator
            ->integer('codigo_pos_criticidade')
            ->allowEmptyString('codigo_pos_criticidade');

        $validator
            ->integer('qtd_dias')
            ->allowEmptyString('qtd_dias');

        $validator
            ->scalar('condicao')
            ->maxLength('condicao', 10)
            ->allowEmptyString('condicao');

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
