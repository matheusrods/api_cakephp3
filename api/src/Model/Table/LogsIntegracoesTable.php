<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * LogsIntegracoes Model
 *
 * @method \App\Model\Entity\LogsIntegraco get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogsIntegraco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogsIntegraco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogsIntegraco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogsIntegraco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogsIntegraco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogsIntegraco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogsIntegraco findOrCreate($search, callable $callback = null, $options = [])
 */
class LogsIntegracoesTable extends AppTable
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

        $this->setTable('logs_integracoes');
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

        // $validator
        //     ->integer('codigo_cliente')
        //     ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('arquivo')
            ->maxLength('arquivo', 50)
            ->requirePresence('arquivo', 'create')
            ->notEmptyString('arquivo');

        $validator
            ->scalar('conteudo')
            ->requirePresence('conteudo', 'create')
            ->notEmptyString('conteudo');

        $validator
            ->scalar('retorno')
            ->requirePresence('retorno', 'create')
            ->notEmptyString('retorno');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('sistema_origem')
            ->maxLength('sistema_origem', 100)
            ->requirePresence('sistema_origem', 'create')
            ->notEmptyString('sistema_origem');

        $validator
            ->scalar('status')
            ->maxLength('status', 1)
            ->allowEmptyString('status');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 500)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('tipo_operacao')
            ->maxLength('tipo_operacao', 1)
            ->allowEmptyString('tipo_operacao');

        $validator
            ->dateTime('reprocessado')
            ->allowEmptyDateTime('reprocessado');

        $validator
            ->dateTime('finalizado')
            ->allowEmptyDateTime('finalizado');

        $validator
            ->dateTime('data_arquivo')
            ->allowEmptyDateTime('data_arquivo');

        // $validator
        //     ->integer('codigo_usuario_inclusao')
        //     ->allowEmptyString('codigo_usuario_inclusao');

        return $validator;
    }
}
