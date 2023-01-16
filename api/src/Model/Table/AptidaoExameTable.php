<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AptidaoExame Model
 *
 * @method \App\Model\Entity\AptidaoExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\AptidaoExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AptidaoExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AptidaoExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AptidaoExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AptidaoExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AptidaoExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AptidaoExame findOrCreate($search, callable $callback = null, $options = [])
 */
class AptidaoExameTable extends Table
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

        $this->setTable('aptidao_exame');
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
            ->allowEmptyString('check_telefone');

        $validator
            ->allowEmptyString('check_email');

        $validator
            ->allowEmptyString('check_requisitos');

        return $validator;
    }
}
