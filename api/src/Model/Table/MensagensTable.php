<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mensagens Model
 *
 * @method \App\Model\Entity\Mensagem get($primaryKey, $options = [])
 * @method \App\Model\Entity\Mensagem newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Mensagem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Mensagem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mensagem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Mensagem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Mensagem[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Mensagem findOrCreate($search, callable $callback = null, $options = [])
 */
class MensagensTable extends Table
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

        $this->setTable('mensagens');
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
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_usuario_from')
            ->allowEmptyString('codigo_usuario_from', null, 'create');

        $validator
            ->scalar('mensagem')
            ->maxLength('mensagem', 255)
            ->requirePresence('mensagem', 'create')
            ->notEmptyString('mensagem');

        $validator
            ->dateTime('data_leitura')
            ->allowEmptyDateTime('data_leitura');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        $validator
            ->scalar('link')
            ->maxLength('link', 255)
            ->allowEmptyString('link');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
