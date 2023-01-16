<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresEnderecoLog Model
 *
 * @method \App\Model\Entity\FornecedoresEnderecoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresEnderecoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresEnderecoLogTable extends Table
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

        $this->setTable('fornecedores_endereco_log');
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
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_fornecedor_endereco')
            ->allowEmptyString('codigo_fornecedor_endereco');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->integer('codigo_endereco')
            ->allowEmptyString('codigo_endereco');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 128)
            ->allowEmptyString('complemento');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 10)
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
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->decimal('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->decimal('longitude')
            ->allowEmptyString('longitude');

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
            ->allowEmptyString('acao_sistema');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
