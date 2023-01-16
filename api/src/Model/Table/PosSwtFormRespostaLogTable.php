<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PosSwtFormRespostaLog Model
 *
 * @method \App\Model\Entity\PosSwtFormRespostaLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormRespostaLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormRespostaLogTable extends Table
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

        $this->setTable('pos_swt_form_resposta_log');
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
            ->integer('codigo_form_resposta')
            ->allowEmptyString('codigo_form_resposta');

        $validator
            ->integer('codigo_form')
            ->allowEmptyString('codigo_form');

        $validator
            ->integer('codigo_form_questao')
            ->allowEmptyString('codigo_form_questao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_criticidade')
            ->allowEmptyString('codigo_criticidade');

        $validator
            ->scalar('resposta')
            ->maxLength('resposta', 255)
            ->allowEmptyString('resposta');

        $validator
            ->scalar('motivo')
            ->allowEmptyString('motivo');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
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

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->integer('codigo_form_respondido')
            ->allowEmptyString('codigo_form_respondido');

        return $validator;
    }
}
