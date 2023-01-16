<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedidasControle Model
 *
 * @method \App\Model\Entity\MedidasControle get($primaryKey, $options = [])
 * @method \App\Model\Entity\MedidasControle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MedidasControle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControle|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControle saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedidasControle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MedidasControle findOrCreate($search, callable $callback = null, $options = [])
 */
class MedidasControleTable extends Table
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

        $this->setTable('medidas_controle');
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
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_medida_controle_hierarquia_tipo')
            ->requirePresence('codigo_medida_controle_hierarquia_tipo', 'create')
            ->notEmptyString('codigo_medida_controle_hierarquia_tipo');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->allowEmptyString('titulo');

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
