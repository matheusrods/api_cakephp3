<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PdaConfigRegraLog Model
 *
 * @method \App\Model\Entity\PdaConfigRegraLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaConfigRegraLogTable extends Table
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

        $this->setTable('pda_config_regra_log');
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
            ->integer('codigo_pda_config_regra')
            ->requirePresence('codigo_pda_config_regra', 'create')
            ->notEmptyString('codigo_pda_config_regra');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pda_tema')
            ->requirePresence('codigo_pda_tema', 'create')
            ->notEmptyString('codigo_pda_tema');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->allowEmptyString('codigo_acoes_melhorias_status');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('assunto')
            ->maxLength('assunto', 255)
            ->allowEmptyString('assunto');

        $validator
            ->scalar('mensagem')
            ->maxLength('mensagem', 255)
            ->allowEmptyString('mensagem');

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
