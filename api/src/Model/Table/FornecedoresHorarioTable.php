<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FornecedoresHorario Model
 *
 * @method \App\Model\Entity\FornecedoresHorario get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresHorario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresHorario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHorario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresHorario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresHorario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHorario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHorario findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresHorarioTable extends AppTable
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

        $this->setTable('fornecedores_horario');
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
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('de_hora')
            ->requirePresence('de_hora', 'create')
            ->notEmptyString('de_hora');

        $validator
            ->integer('ate_hora')
            ->requirePresence('ate_hora', 'create')
            ->notEmptyString('ate_hora');

        $validator
            ->scalar('dias_semana')
            ->maxLength('dias_semana', 255)
            ->allowEmptyString('dias_semana');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
