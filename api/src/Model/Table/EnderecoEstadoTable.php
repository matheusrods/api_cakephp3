<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EnderecoEstado Model
 *
 * @method \App\Model\Entity\EnderecoEstado get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoEstado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EnderecoEstado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEstado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoEstado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoEstado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEstado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEstado findOrCreate($search, callable $callback = null, $options = [])
 */
class EnderecoEstadoTable extends Table
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

        $this->setTable('endereco_estado');
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
            ->requirePresence('codigo_endereco_pais', 'create')
            ->notEmptyString('codigo_endereco_pais');

        $validator
            ->scalar('abreviacao')
            ->maxLength('abreviacao', 2)
            ->requirePresence('abreviacao', 'create')
            ->notEmptyString('abreviacao');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->allowEmptyString('descricao')
            ->add('descricao', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['descricao']));

        return $rules;
    }
}
