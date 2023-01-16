<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bancos Model
 *
 * @method \App\Model\Entity\Banco get($primaryKey, $options = [])
 * @method \App\Model\Entity\Banco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Banco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Banco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Banco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Banco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Banco findOrCreate($search, callable $callback = null, $options = [])
 */
class BancosTable extends Table
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

        $this->setTable('bancos');
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
            ->scalar('descricao')
            ->maxLength('descricao', 170)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('codigo_banco')
            ->maxLength('codigo_banco', 25)
            ->requirePresence('codigo_banco', 'create')
            ->notEmptyString('codigo_banco');

        return $validator;
    }
}
