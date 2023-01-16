<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PdaTemaPdaTemaAcoes Model
 *
 * @method \App\Model\Entity\PdaTemaPdaTemaAco get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaTemaPdaTemaAco findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaTemaPdaTemaAcoesTable extends Table
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

        $this->setTable('pda_tema_pda_tema_acoes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsTo('PdaTema');
        $this->belongsTo('PdaTemaAcoes');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_pda_tema')
            ->allowEmptyString('codigo_pda_tema');

        $validator
            ->integer('codigo_pda_tema_acoes')
            ->allowEmptyString('codigo_pda_tema_acoes');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        return $validator;
    }
}
