<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EstadoCidadeLatLong Model
 *
 * @method \App\Model\Entity\EstadoCidadeLatLong get($primaryKey, $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EstadoCidadeLatLong findOrCreate($search, callable $callback = null, $options = [])
 */
class EstadoCidadeLatLongTable extends Table
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

        $this->setTable('estado_cidade_lat_long');
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
            ->scalar('estado')
            ->maxLength('estado', 2)
            ->allowEmptyString('estado');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 150)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('lat')
            ->maxLength('lat', 50)
            ->allowEmptyString('lat');

        $validator
            ->scalar('long')
            ->maxLength('long', 50)
            ->allowEmptyString('long');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 8)
            ->allowEmptyString('cep');

        $validator
            ->scalar('ibge')
            ->maxLength('ibge', 50)
            ->allowEmptyString('ibge');

        return $validator;
    }
}
