<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FichasAssistenciaisGrupoQuestoes Model
 *
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisGrupoQuesto findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasAssistenciaisGrupoQuestoesTable extends Table
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

        $this->setTable('fichas_assistenciais_grupo_questoes');
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
