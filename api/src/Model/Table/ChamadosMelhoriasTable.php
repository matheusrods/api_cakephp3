<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ChamadosMelhorias Model
 *
 * @method \App\Model\Entity\ChamadosMelhorias get($primaryKey, $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ChamadosMelhorias findOrCreate($search, callable $callback = null, $options = [])
 */
class ChamadosMelhoriasTable extends Table
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

        $this->setTable('chamados_melhorias');
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
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_prazo_conclusao')
            ->requirePresence('data_prazo_conclusao', 'create')
            ->notEmptyDateTime('data_prazo_conclusao');

        $validator
            ->integer('responsavel')
            ->requirePresence('responsavel', 'create')
            ->notEmptyString('responsavel');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
