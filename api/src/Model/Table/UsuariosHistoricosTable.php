<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuariosHistoricos Model
 *
 * @method \App\Model\Entity\UsuariosHistorico get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosHistorico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosHistorico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosHistorico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosHistorico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosHistorico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosHistorico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosHistorico findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosHistoricosTable extends Table
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

        $this->setTable('usuarios_historicos');
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
            ->scalar('remote_addr')
            ->maxLength('remote_addr', 15)
            ->requirePresence('remote_addr', 'create')
            ->notEmptyString('remote_addr');

        $validator
            ->scalar('http_user_agent')
            ->maxLength('http_user_agent', 150)
            ->requirePresence('http_user_agent', 'create')
            ->notEmptyString('http_user_agent');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('message')
            ->maxLength('message', 50)
            ->allowEmptyString('message');

        $validator
            ->notEmptyString('fail');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->dateTime('data_logout')
            ->allowEmptyDateTime('data_logout');

        $validator
            ->integer('codigo_sistema')
            ->allowEmptyString('codigo_sistema');

        return $validator;
    }
}
