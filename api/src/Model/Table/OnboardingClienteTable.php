<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * OnboardingCliente Model
 *
 * @method \App\Model\Entity\OnboardingCliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\OnboardingCliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OnboardingCliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OnboardingCliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OnboardingCliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OnboardingCliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OnboardingCliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OnboardingCliente findOrCreate($search, callable $callback = null, $options = [])
 */
class OnboardingClienteTable extends AppTable
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

        $this->setTable('onboarding_cliente');
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
            ->integer('codigo_onboarding')
            ->requirePresence('codigo_onboarding', 'create')
            ->notEmptyString('codigo_onboarding');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('texto')
            ->maxLength('texto', 255)
            ->requirePresence('texto', 'create')
            ->notEmptyString('texto');

        $validator
            ->scalar('imagem')
            ->allowEmptyFile('imagem');

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
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    /**
     * Avaliar lista de onboarding por cliente
     *
     * @param int $codigo_sistema
     * @param int $codigo_cliente
     * @param boolean $inativos   se true irá trazer registros inativos
     * @return ORM Recordset
     */
    public function avaliarListaPorCliente(int $codigo_sistema, int $codigo_cliente, $inativos = false)
    {

        $joins = [
            [
                'table' => 'onboarding',
                'alias' => 'Onboarding',
                'type' => 'INNER',
                'conditions' => 'OnboardingCliente.codigo_onboarding = Onboarding.codigo'
            ]
        ];
        
        $where = [];

        if(!$inativos){
            array_push($where, ['OnboardingCliente.ativo'=> 1]);
        }
        
        array_push($where, [
            'OnboardingCliente.codigo_cliente' => $codigo_cliente,
            'Onboarding.codigo_sistema' => $codigo_sistema
        ]);

        $OnboardingClienteData = $this->find()
                            ->where($where)
                            ->join($joins);

        return $OnboardingClienteData;
    }
}
