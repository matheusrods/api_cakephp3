<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * ClienteEndereco Model
 *
 * @method \App\Model\Entity\ClienteEndereco get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteEndereco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteEndereco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteEndereco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteEndereco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteEndereco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteEndereco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteEndereco findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteEnderecoTable extends AppTable
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

        $this->setTable('cliente_endereco');
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->integer('codigo_endereco')
            ->allowEmptyString('codigo_endereco');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 256)
            ->allowEmptyString('complemento');

        $validator
            ->integer('numero')
            ->allowEmptyString('numero');

        $validator
            ->dateTime('data_inclusao')
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
