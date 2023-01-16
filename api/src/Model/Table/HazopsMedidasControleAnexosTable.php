<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HazopsMedidasControleAnexos Model
 *
 * @method \App\Model\Entity\HazopsMedidasControleAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsMedidasControleAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class HazopsMedidasControleAnexosTable extends Table
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

        $this->setTable('hazops_medidas_controle_anexos');
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
            ->integer('codigo_hazop_medida_controle')
            ->allowEmptyString('codigo_hazop_medida_controle');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->allowEmptyString('arquivo_url');

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

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }
}
