<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\ORM\TableRegistry;


/**
 * PosSwtFormFacilitadores Model
 *
 * @method \App\Model\Entity\PosSwtFormFacilitadore get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormFacilitadore findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormFacilitadoresTable extends AppTable
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

        $this->setTable('pos_swt_form_facilitadores');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_form_facilitadores');
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
            ->integer('codigo_form')
            ->allowEmptyString('codigo_form');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 20)
            ->allowEmptyString('cpf');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_form_respondido')
            ->allowEmptyString('codigo_form_respondido');

        return $validator;
    }
}
