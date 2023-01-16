<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClienteQuestionariosLog Model
 *
 * @method \App\Model\Entity\ClienteQuestionariosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionariosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteQuestionariosLogTable extends Table
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

        $this->setTable('cliente_questionarios_log');
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
            ->integer('codigo_cliente_questionario')
            ->requirePresence('codigo_cliente_questionario', 'create')
            ->notEmptyString('codigo_cliente_questionario');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyString('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('data_alteracao')
            ->allowEmptyString('data_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }
}
