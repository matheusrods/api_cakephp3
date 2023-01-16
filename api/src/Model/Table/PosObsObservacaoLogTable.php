<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PosObsObservacaoLog Model
 *
 * @method \App\Model\Entity\PosObsObservacaoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsObservacaoLogTable extends Table
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

        $this->setTable('pos_obs_observacao_log');
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_unidade')
            ->allowEmptyString('codigo_unidade');

        $validator
            ->integer('codigo_pos_categoria')
            ->requirePresence('codigo_pos_categoria', 'create')
            ->notEmptyString('codigo_pos_categoria');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('codigo_status')
            ->requirePresence('codigo_status', 'create')
            ->notEmptyString('codigo_status');

        $validator
            ->integer('codigo_status_responsavel')
            ->requirePresence('codigo_status_responsavel', 'create')
            ->notEmptyString('codigo_status_responsavel');

        $validator
            ->dateTime('data_observacao')
            ->requirePresence('data_observacao', 'create')
            ->notEmptyDateTime('data_observacao');

        $validator
            ->scalar('descricao_usuario_observou')
            ->requirePresence('descricao_usuario_observou', 'create')
            ->notEmptyString('descricao_usuario_observou');

        $validator
            ->scalar('descricao_usuario_acao')
            ->allowEmptyString('descricao_usuario_acao');

        $validator
            ->scalar('descricao_usuario_sugestao')
            ->allowEmptyString('descricao_usuario_sugestao');

        $validator
            ->integer('codigo_local_descricao')
            ->allowEmptyString('codigo_local_descricao');

        $validator
            ->integer('codigo_pos_obs_local')
            ->requirePresence('codigo_pos_obs_local', 'create')
            ->allowEmptyString('codigo_pos_obs_local');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->allowEmptyString('observacao_criticidade');

        $validator
            ->allowEmptyString('qualidade_avaliacao');

        $validator
            ->scalar('qualidade_descricao_complemento')
            ->allowEmptyString('qualidade_descricao_complemento');

        $validator
            ->scalar('qualidade_descricao_participantes_tratativa')
            ->allowEmptyString('qualidade_descricao_participantes_tratativa');

        $validator
            ->integer('codigo_usuario_status')
            ->requirePresence('codigo_usuario_status', 'create')
            ->notEmptyString('codigo_usuario_status');

        $validator
            ->dateTime('data_status')
            ->requirePresence('data_status', 'create')
            ->notEmptyDateTime('data_status');

        $validator
            ->scalar('descricao_status')
            ->allowEmptyString('descricao_status');

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
