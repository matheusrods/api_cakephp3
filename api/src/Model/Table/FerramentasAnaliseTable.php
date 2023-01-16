<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FerramentasAnalise Model
 *
 * @method \App\Model\Entity\FerramentasAnalise get($primaryKey, $options = [])
 * @method \App\Model\Entity\FerramentasAnalise newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FerramentasAnalise[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnalise|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FerramentasAnalise saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FerramentasAnalise patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnalise[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnalise findOrCreate($search, callable $callback = null, $options = [])
 */
class FerramentasAnaliseTable extends Table
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

        $this->setTable('ferramentas_analise');
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
            ->integer('codigo_qualificacao')
            ->allowEmptyString('codigo_qualificacao');

        $validator
            ->integer('codigo_ferramenta_analise_tipo')
            ->allowEmptyString('codigo_ferramenta_analise_tipo');

        $validator
            ->scalar('ferramenta_analise_resultado')
            ->allowEmptyString('ferramenta_analise_resultado');

        $validator
            ->scalar('ferramenta_analise_level')
//            ->maxLength('ferramenta_analise_level', 255)
            ->allowEmptyString('ferramenta_analise_level');

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
