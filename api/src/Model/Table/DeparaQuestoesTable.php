<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DeparaQuestoes Model
 *
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsToMany $Respostas
 *
 * @method \App\Model\Entity\DeparaQuesto get($primaryKey, $options = [])
 * @method \App\Model\Entity\DeparaQuesto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DeparaQuesto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuesto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DeparaQuesto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DeparaQuesto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuesto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuesto findOrCreate($search, callable $callback = null, $options = [])
 */
class DeparaQuestoesTable extends Table
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

        $this->setTable('depara_questoes');

        $this->belongsToMany('Respostas', [
            'foreignKey' => 'depara_questo_id',
            'targetForeignKey' => 'resposta_id',
            'joinTable' => 'depara_questoes_respostas'
        ]);
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
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->integer('codigo_questao_questionario')
            ->allowEmptyString('codigo_questao_questionario');

        $validator
            ->integer('codigo_questao_ficha_clinica')
            ->allowEmptyString('codigo_questao_ficha_clinica');

        return $validator;
    }
}
