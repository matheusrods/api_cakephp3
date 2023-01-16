<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;


/**
 * FuncionarioLiberacaoTrabalho Model
 *
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho get($primaryKey, $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioLiberacaoTrabalho findOrCreate($search, callable $callback = null, $options = [])
 */
class FuncionarioLiberacaoTrabalhoTable extends AppTable
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

        $this->setTable('funcionario_liberacao_trabalho');
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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_cargo')
            ->allowEmptyString('codigo_cargo');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->date('data_inicio_previsao')
            ->allowEmptyDate('data_inicio_previsao');

        $validator
            ->date('data_fim_previsao')
            ->allowEmptyDate('data_fim_previsao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_func_setor_cargo')
            ->allowEmptyString('codigo_func_setor_cargo');

        return $validator;
    }
}
