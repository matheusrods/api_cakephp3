<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedidasControleAnexos Model
 *
 * @method \App\Model\Entity\MedidasControleAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControleAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class MedidasControleAnexosTable extends Table
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

        $this->setTable('medidas_controle_anexos');
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
            ->integer('codigo_medida_controle')
            ->requirePresence('codigo_medida_controle', 'create')
            ->notEmptyString('codigo_medida_controle');

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
