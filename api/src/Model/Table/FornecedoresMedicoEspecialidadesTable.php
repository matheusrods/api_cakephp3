<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresMedicoEspecialidades Model
 *
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedicoEspecialidade findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresMedicoEspecialidadesTable extends Table
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

        $this->setTable('fornecedores_medico_especialidades');
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
            ->integer('codigo_medico')
            ->requirePresence('codigo_medico', 'create')
            ->notEmptyString('codigo_medico');

        $validator
            ->integer('codigo_especialidade')
            ->requirePresence('codigo_especialidade', 'create')
            ->notEmptyString('codigo_especialidade');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
