<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuarioValidarToken Model
 *
 * @method \App\Model\Entity\UsuarioValidarToken get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioValidarToken findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioValidarTokenTable extends Table
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

        $this->setTable('usuario_validar_token');
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
            ->integer('codigo_sistema')
            ->requirePresence('codigo_sistema', 'create')
            ->notEmptyString('codigo_sistema');

        $validator
            ->integer('codigo_sistema_validar_token_tipo')
            ->requirePresence('codigo_sistema_validar_token_tipo', 'create')
            ->notEmptyString('codigo_sistema_validar_token_tipo');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->dateTime('tempo_validacao')
            ->allowEmptyDateTime('tempo_validacao');

        $validator
            ->boolean('validado')
            ->notEmptyString('validado');

        $validator
            ->scalar('destino_descricao')
            ->maxLength('destino_descricao', 255)
            ->requirePresence('destino_descricao', 'create')
            ->notEmptyString('destino_descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
