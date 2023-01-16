<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioEndereco Model
 *
 * @method \App\Model\Entity\UsuarioEndereco get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioEndereco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioEndereco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEndereco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioEndereco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioEndereco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEndereco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEndereco findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioEnderecoTable extends AppTable
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

        $this->setTable('usuario_endereco');
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
            ->integer('codigo_usuario_endereco_tipo')
            ->requirePresence('codigo_usuario_endereco_tipo', 'create')
            ->notEmptyString('codigo_usuario_endereco_tipo');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 256)
            ->allowEmptyString('complemento');

        $validator
            ->integer('numero')
            ->allowEmptyString('numero');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 8)
            ->allowEmptyString('cep');

        $validator
            ->scalar('logradouro')
            ->maxLength('logradouro', 100)
            ->allowEmptyString('logradouro');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 60)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 60)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('estado_descricao')
            ->maxLength('estado_descricao', 60)
            ->allowEmptyString('estado_descricao');

        $validator
            ->scalar('estado_abreviacao')
            ->maxLength('estado_abreviacao', 2)
            ->allowEmptyString('estado_abreviacao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
