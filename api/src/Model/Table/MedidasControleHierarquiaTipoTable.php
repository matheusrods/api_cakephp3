<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedidasControleHierarquiaTipo Model
 *
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleHierarquiaTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class MedidasControleHierarquiaTipoTable extends Table
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

        $this->setTable('medidas_controle_hierarquia_tipo');
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
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

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
