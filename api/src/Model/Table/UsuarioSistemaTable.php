<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioSistema Model
 *
 * @method \App\Model\Entity\UsuarioSistema get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioSistema newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioSistema[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioSistema|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioSistema saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioSistema patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioSistema[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioSistema findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioSistemaTable extends AppTable
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

        $this->setTable('usuario_sistema');
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
            ->allowEmptyString('codigo_usuario');

        $validator
            ->requirePresence('codigo_sistema', 'create')
            ->notEmptyString('codigo_sistema');

        // $validator
        //     ->scalar('senha')
        //     ->maxLength('senha', 172)
        //     ->allowEmptyString('senha');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->scalar('token_push')
            ->maxLength('token_push', 255)
            ->allowEmptyString('token_push');

        // $validator
        //     ->scalar('token')
        //     ->maxLength('token', 255)
        //     ->allowEmptyString('token');

        // $validator
        //     ->scalar('platform')
        //     ->maxLength('platform', 18)
        //     ->allowEmptyString('platform');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        // $validator
        //     ->integer('cod_verificacao')
        //     ->allowEmptyString('cod_verificacao');

        // $validator
        //     ->scalar('token_chamadas')
        //     ->maxLength('token_chamadas', 24)
        //     ->allowEmptyString('token_chamadas');

        // $validator
        //     ->scalar('celular')
        //     ->maxLength('celular', 12)
        //     ->allowEmptyString('celular');

        // $validator
        //     ->scalar('model')
        //     ->maxLength('model', 30)
        //     ->allowEmptyString('model');

        // $validator
        //     ->integer('foreign_key')
        //     ->allowEmptyString('foreign_key');

        return $validator;
    }
}
