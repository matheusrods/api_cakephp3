<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FornecedoresAgendasDatasBloqueadas Model
 *
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAgendasDatasBloqueada findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresAgendasDatasBloqueadasTable extends AppTable
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

        $this->setTable('fornecedores_agendas_datas_bloqueadas');
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
            ->date('data')
            ->requirePresence('data', 'create')
            ->notEmptyDate('data');

        $validator
            ->scalar('horarios')
            ->maxLength('horarios', 1000)
            ->allowEmptyString('horarios');

        $validator
            ->allowEmptyString('bloqueado_dia_inteiro');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_lista_de_preco_produto_servico')
            ->requirePresence('codigo_lista_de_preco_produto_servico', 'create')
            ->notEmptyString('codigo_lista_de_preco_produto_servico');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }
}
