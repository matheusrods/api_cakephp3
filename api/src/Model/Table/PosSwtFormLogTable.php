<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PosSwtFormLog Model
 *
 * @method \App\Model\Entity\PosSwtFormLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtFormLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtFormLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormLogTable extends Table
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

        $this->setTable('pos_swt_form_log');
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
            ->integer('codigo_form')
            ->allowEmptyString('codigo_form');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->allowEmptyString('form_tipo');

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

        return $validator;
    }
}
