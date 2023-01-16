<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * PushOutbox Model
 *
 * @method \App\Model\Entity\PushOutbox get($primaryKey, $options = [])
 * @method \App\Model\Entity\PushOutbox newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PushOutbox[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PushOutbox|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushOutbox saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PushOutbox patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PushOutbox[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PushOutbox findOrCreate($search, callable $callback = null, $options = [])
 */
class PushOutboxTable extends AppTable
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

        $this->setTable('push_outbox');
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
            ->integer('codigo_key')
            ->requirePresence('codigo_key', 'create')
            ->notEmptyString('codigo_key');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->scalar('fone_para')
            ->maxLength('fone_para', 13)
            ->requirePresence('fone_para', 'create')
            ->notEmptyString('fone_para');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 50)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('mensagem')
            ->maxLength('mensagem', 255)
            ->allowEmptyString('mensagem');

        $validator
            ->scalar('extra_data')
            ->maxLength('extra_data', 255)
            ->requirePresence('extra_data', 'create')
            ->notEmptyString('extra_data');

        $validator
            ->dateTime('data_envio')
            ->allowEmptyDateTime('data_envio');

        $validator
            ->dateTime('liberar_envio_em')
            ->allowEmptyDateTime('liberar_envio_em');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('sistema_origem')
            ->maxLength('sistema_origem', 100)
            ->requirePresence('sistema_origem', 'create')
            ->notEmptyString('sistema_origem');

        $validator
            ->scalar('modulo_origem')
            ->maxLength('modulo_origem', 100)
            ->allowEmptyString('modulo_origem');

        $validator
            ->scalar('platform')
            ->maxLength('platform', 18)
            ->allowEmptyString('platform');

        $validator
            ->scalar('observacao')
            ->maxLength('observacao', 255)
            ->allowEmptyString('observacao');

        $validator
            ->integer('foreign_key')
            ->allowEmptyString('foreign_key');

        $validator
            ->scalar('model')
            ->maxLength('model', 255)
            ->allowEmptyString('model');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        return $validator;
    }
}
