<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AgentesRiscosClientes Model
 *
 * @method \App\Model\Entity\AgentesRiscosCliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosCliente findOrCreate($search, callable $callback = null, $options = [])
 */
class AgentesRiscosClientesTable extends Table
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

        $this->setTable('agentes_riscos_clientes');
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
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_agente_risco')
            ->requirePresence('codigo_agente_risco', 'create')
            ->notEmptyString('codigo_agente_risco');

        return $validator;
    }
}
