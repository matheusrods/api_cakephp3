<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TiposLocaisAtendimento Model
 *
 * @method \App\Model\Entity\TiposLocaisAtendimento get($primaryKey, $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TiposLocaisAtendimento findOrCreate($search, callable $callback = null, $options = [])
 */
class TiposLocaisAtendimentoTable extends Table
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

        $this->setTable('tipos_locais_atendimento');
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
            ->scalar('descricao')
            ->maxLength('descricao', 120)
            ->allowEmptyString('descricao');

        $validator
            ->allowEmptyString('ativo');

        return $validator;
    }
}
