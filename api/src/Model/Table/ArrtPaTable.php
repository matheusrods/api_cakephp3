<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArrtPa Model
 *
 * @method \App\Model\Entity\ArrtPa get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArrtPa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArrtPa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArrtPa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtPa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtPa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtPa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtPa findOrCreate($search, callable $callback = null, $options = [])
 */
class ArrtPaTable extends Table
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

        $this->setTable('arrt_pa');
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
            ->integer('codigo_ar_rt')
            ->requirePresence('codigo_ar_rt', 'create')
            ->notEmptyString('codigo_ar_rt');

        $validator
            ->integer('codigo_perigo_aspecto')
            ->requirePresence('codigo_perigo_aspecto', 'create')
            ->notEmptyString('codigo_perigo_aspecto');

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
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
