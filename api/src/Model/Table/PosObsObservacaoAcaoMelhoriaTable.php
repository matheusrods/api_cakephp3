<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsObservacaoAcaoMelhoria Model
 *
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacaoAcaoMelhoria findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsObservacaoAcaoMelhoriaTable extends Table
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

        $this->setTable('pos_obs_observacao_acao_melhoria');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsTo('PosObsObservacao');
        $this->belongsTo('AcoesMelhorias');

        $this->setEntityClass('App\Model\Entity\PosObsObservacaoAcaoMelhoria');
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
            ->integer('obs_observacao_id')
            ->requirePresence('obs_observacao_id', 'create')
            ->notEmptyString('obs_observacao_id');

        $validator
            ->integer('acoes_melhoria_id')
            ->requirePresence('acoes_melhoria_id', 'create')
            ->notEmptyString('acoes_melhoria_id');

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
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }
}
