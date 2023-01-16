<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresContatoLog Model
 *
 * @method \App\Model\Entity\FornecedoresContatoLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContatoLog findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresContatoLogTable extends Table
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

        $this->setTable('fornecedores_contato_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
        //$this->foreign_key('codigo_fornecedor');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_inclusao' => 'new',
                    'data_alteracao' => 'always',
                ]
            ]
        ]);
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
            ->requirePresence('codigo_fornecedor_contato', 'create')
            ->notEmptyString('codigo_fornecedor_contato');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->requirePresence('codigo_tipo_retorno', 'create')
            ->notEmptyString('codigo_tipo_retorno');

        $validator
            ->allowEmptyString('ddi');

        $validator
            ->allowEmptyString('ddd');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 256)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->allowEmptyString('nome');

        $validator
            ->allowEmptyString('ramal');

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
