<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PacientesDadosTrabalho Model
 *
 * @method \App\Model\Entity\PacientesDadosTrabalho get($primaryKey, $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PacientesDadosTrabalho findOrCreate($search, callable $callback = null, $options = [])
 */
class PacientesDadosTrabalhoTable extends Table
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

        $this->setTable('pacientes_dados_trabalho');
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
            ->integer('codigo_paciente')
            ->requirePresence('codigo_paciente', 'create')
            ->notEmptyString('codigo_paciente');

        // $validator
        //     ->integer('codigo_cliente')
        //     ->requirePresence('codigo_cliente', 'create')
        //     ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_pacientes_categoria')
            ->allowEmptyString('codigo_pacientes_categoria');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
