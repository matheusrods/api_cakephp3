<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedicoCalendarioHorarios Model
 *
 * @method \App\Model\Entity\MedicoCalendarioHorario get($primaryKey, $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendarioHorario findOrCreate($search, callable $callback = null, $options = [])
 */
class MedicoCalendarioHorariosTable extends Table
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

        $this->setTable('medico_calendario_horarios');
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
            ->integer('codigo_medico_calendario')
            ->requirePresence('codigo_medico_calendario', 'create')
            ->notEmptyString('codigo_medico_calendario');

        $validator
            ->integer('dia_semana')
            ->requirePresence('dia_semana', 'create')
            ->notEmptyString('dia_semana');

        // $validator
        //     ->scalar('hora_inicio_manha')
        //     ->maxLength('hora_inicio_manha', 5)
        //     ->requirePresence('hora_inicio_manha', 'create');

        // $validator
        //     ->scalar('hora_fim_manha')
        //     ->maxLength('hora_fim_manha', 5)
        //     ->requirePresence('hora_fim_manha', 'create');

        // $validator
        //     ->scalar('hora_inicio_tarde')
        //     ->maxLength('hora_inicio_tarde', 5)
        //     ->requirePresence('hora_inicio_tarde', 'create');

        // $validator
        //     ->scalar('hora_fim_tarde')
        //     ->maxLength('hora_fim_tarde', 5)
        //     ->requirePresence('hora_fim_tarde', 'create');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }
}
