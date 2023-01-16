<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * ClienteProdutoServico2 Model
 *
 * @method \App\Model\Entity\ClienteProdutoServico2 get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2 saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProdutoServico2 findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteProdutoServico2Table extends AppTable
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

        $this->setTable('cliente_produto_servico2');
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
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->integer('codigo_cliente_produto')
            ->requirePresence('codigo_cliente_produto', 'create')
            ->notEmptyString('codigo_cliente_produto');

        $validator
            ->requirePresence('codigo_servico', 'create')
            ->notEmptyString('codigo_servico');

        $validator
            ->decimal('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->integer('codigo_cliente_pagador')
            ->allowEmptyString('codigo_cliente_pagador');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('qtd_premio_minimo')
            ->notEmptyString('qtd_premio_minimo');

        $validator
            ->numeric('valor_premio_minimo')
            ->notEmptyString('valor_premio_minimo');

        $validator
            ->decimal('valor_maximo')
            ->allowEmptyString('valor_maximo');

        $validator
            ->scalar('ip')
            ->maxLength('ip', 15)
            ->allowEmptyString('ip');

        $validator
            ->scalar('browser')
            ->maxLength('browser', 100)
            ->allowEmptyString('browser');

        $validator
            ->boolean('consulta_embarcador')
            ->notEmptyString('consulta_embarcador');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->decimal('valor_unit_premio_minimo')
            ->allowEmptyString('valor_unit_premio_minimo');

        $validator
            ->integer('quantidade')
            ->allowEmptyString('quantidade');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
