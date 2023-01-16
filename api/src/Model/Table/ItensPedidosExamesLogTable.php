<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ItensPedidosExamesLog Model
 *
 * @method \App\Model\Entity\ItensPedidosExamesLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExamesLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ItensPedidosExamesLogTable extends Table
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

        $this->setTable('itens_pedidos_exames_log');
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
            ->integer('codigo_itens_pedidos_exames')
            ->requirePresence('codigo_itens_pedidos_exames', 'create')
            ->notEmptyString('codigo_itens_pedidos_exames');

        $validator
            ->integer('codigo_pedidos_exames')
            ->allowEmptyString('codigo_pedidos_exames');

        $validator
            ->integer('codigo_exame')
            ->allowEmptyString('codigo_exame');

        $validator
            ->decimal('valor')
            ->allowEmptyString('valor');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        /*$validator
            ->allowEmptyString('tipo_atendimento');

        $validator
            ->date('data_agendamento')
            ->allowEmptyDate('data_agendamento');

        $validator
            ->scalar('hora_agendamento')
            ->maxLength('hora_agendamento', 5)
            ->allowEmptyString('hora_agendamento');

        $validator
            ->integer('codigo_tipos_exames_pedidos')
            ->allowEmptyString('codigo_tipos_exames_pedidos');

        $validator
            ->integer('tipo_agendamento')
            ->allowEmptyString('tipo_agendamento');*/

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        /*$validator
            ->integer('codigo_cliente_assinatura')
            ->allowEmptyString('codigo_cliente_assinatura');

        $validator
            ->date('data_realizacao_exame')
            ->allowEmptyDate('data_realizacao_exame');

        $validator
            ->integer('compareceu')
            ->allowEmptyString('compareceu');

        $validator
            ->boolean('recebimento_digital')
            ->notEmptyString('recebimento_digital');

        $validator
            ->boolean('recebimento_enviado')
            ->notEmptyString('recebimento_enviado');*/

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        /*$validator
            ->dateTime('data_notificacao_nc')
            ->allowEmptyDateTime('data_notificacao_nc');

        $validator
            ->decimal('valor_custo')
            ->allowEmptyString('valor_custo');*/

        return $validator;
    }
}
