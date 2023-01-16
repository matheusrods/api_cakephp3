<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * EnderecoCidade Model
 *
 * @method \App\Model\Entity\EnderecoCidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoCidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EnderecoCidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCidade findOrCreate($search, callable $callback = null, $options = [])
 */
class EnderecoCidadeTable extends AppTable
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

        $this->setTable('endereco_cidade');
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
            ->requirePresence('codigo_endereco_estado', 'create')
            ->notEmptyString('codigo_endereco_estado');

        $validator
            ->integer('codigo_endereco_cep')
            ->allowEmptyString('codigo_endereco_cep');

        $validator
            ->integer('codigo_correio')
            ->allowEmptyString('codigo_correio');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->scalar('abreviacao')
            ->maxLength('abreviacao', 64)
            ->allowEmptyString('abreviacao');

        $validator
            ->boolean('invalido')
            ->notEmptyString('invalido');

        $validator
            ->scalar('ibge')
            ->maxLength('ibge', 7)
            ->allowEmptyString('ibge');

        return $validator;
    }
}
