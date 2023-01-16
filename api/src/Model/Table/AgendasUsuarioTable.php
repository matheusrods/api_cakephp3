<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AgendasUsuario Model
 *
 * @method \App\Model\Entity\AgendasUsuario get($primaryKey, $options = [])
 * @method \App\Model\Entity\AgendasUsuario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AgendasUsuario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AgendasUsuario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgendasUsuario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgendasUsuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AgendasUsuario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AgendasUsuario findOrCreate($search, callable $callback = null, $options = [])
 */
class AgendasUsuarioTable extends Table
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

        $this->setTable('agendas_usuario');
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
            ->integer('codigo_agenda')
            ->requirePresence('codigo_agenda', 'create')
            ->notEmptyString('codigo_agenda');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
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

        return $validator;
    }
}
