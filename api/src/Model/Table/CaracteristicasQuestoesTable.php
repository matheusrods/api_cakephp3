<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * CaracteristicasQuestoes Model
 *
 * @method \App\Model\Entity\CaracteristicasQuesto get($primaryKey, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuesto findOrCreate($search, callable $callback = null, $options = [])
 */
class CaracteristicasQuestoesTable extends AppTable
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

        $this->setTable('caracteristicas_questoes');
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
            ->integer('codigo_caracteristica')
            ->requirePresence('codigo_caracteristica', 'create')
            ->notEmptyString('codigo_caracteristica');

        $validator
            ->integer('codigo_questao')
            ->requirePresence('codigo_questao', 'create')
            ->notEmptyString('codigo_questao');

        return $validator;
    }
}
