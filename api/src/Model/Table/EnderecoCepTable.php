<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EnderecoCep Model
 *
 * @method \App\Model\Entity\EnderecoCep get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoCep newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EnderecoCep[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCep|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCep saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCep patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCep[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCep findOrCreate($search, callable $callback = null, $options = [])
 */
class EnderecoCepTable extends Table
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

        $this->setTable('endereco_cep');
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
            ->requirePresence('codigo_endereco_pais', 'create')
            ->notEmptyString('codigo_endereco_pais');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 8)
            ->requirePresence('cep', 'create')
            ->notEmptyString('cep');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        return $validator;
    }
}
