<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * AtestadosCid Model
 *
 * @method \App\Model\Entity\AtestadosCid get($primaryKey, $options = [])
 * @method \App\Model\Entity\AtestadosCid newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AtestadosCid[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosCid|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AtestadosCid saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AtestadosCid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosCid[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosCid findOrCreate($search, callable $callback = null, $options = [])
 */
class AtestadosCidTable extends AppTable
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

        $this->setTable('atestados_cid');
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
            ->integer('codigo_atestado')
            ->requirePresence('codigo_atestado', 'create')
            ->notEmptyString('codigo_atestado');

        $validator
            ->integer('codigo_cid')
            ->allowEmptyString('codigo_cid');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
