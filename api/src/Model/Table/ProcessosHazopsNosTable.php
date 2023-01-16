<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProcessosHazopsNos Model
 *
 * @method \App\Model\Entity\ProcessosHazopsNo get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosHazopsNo findOrCreate($search, callable $callback = null, $options = [])
 */
class ProcessosHazopsNosTable extends Table
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

        $this->setTable('processos_hazops_nos');
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
            ->integer('codigo_processos_ferramentas')
            ->requirePresence('codigo_processos_ferramentas', 'create')
            ->notEmptyString('codigo_processos_ferramentas');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->integer('posicao')
            ->requirePresence('posicao', 'create')
            ->notEmptyString('posicao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
