<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FichasClinicasGrupoQuestoes Model
 *
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasGrupoQuesto findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasClinicasGrupoQuestoesTable extends AppTable
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

        $this->setTable('fichas_clinicas_grupo_questoes');
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
            ->maxLength('descricao', 500)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }
}
