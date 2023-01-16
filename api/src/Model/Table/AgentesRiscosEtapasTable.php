<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AgentesRiscosEtapas Model
 *
 * @method \App\Model\Entity\AgentesRiscosEtapa get($primaryKey, $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRiscosEtapa findOrCreate($search, callable $callback = null, $options = [])
 */
class AgentesRiscosEtapasTable extends Table
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

        $this->setTable('agentes_riscos_etapas');
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
            ->integer('codigo_agente_risco')
            ->requirePresence('codigo_agente_risco', 'create')
            ->notEmptyString('codigo_agente_risco');

        $validator
            ->integer('codigo_processo_ferramenta')
            ->requirePresence('codigo_processo_ferramenta', 'create')
            ->notEmptyString('codigo_processo_ferramenta');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getAgentesRiscosEtapas($codigo_processo_ferramenta)
    {
        $fields = array(
            'AgentesRiscosEtapas.codigo',
            'AgentesRiscos.codigo',
            'AgentesRiscosEtapas.codigo_processo_ferramenta'
        );

        $joins  = array(
            array(
                'table' => 'agentes_riscos',
                'alias' => 'AgentesRiscos',
                'type' => 'INNER',
                'conditions' => 'AgentesRiscos.codigo = AgentesRiscosEtapas.codigo_agente_risco and AgentesRiscos.data_remocao is null',
            )
        );

        $conditions = array(
            'AgentesRiscosEtapas.codigo_processo_ferramenta IN ('. $codigo_processo_ferramenta .')'
        );

        //executa
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->toArray();

        return $dados;

    }


}
