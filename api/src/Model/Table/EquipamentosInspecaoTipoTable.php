<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EquipamentosInspecaoTipo Model
 *
 * @method \App\Model\Entity\EquipamentosInspecaoTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosInspecaoTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class EquipamentosInspecaoTipoTable extends Table
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

        $this->setTable('equipamentos_inspecao_tipo');
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
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_unidade_medicao')
            ->requirePresence('codigo_unidade_medicao', 'create')
            ->notEmptyString('codigo_unidade_medicao');

        $validator
            ->decimal('valor')
            ->allowEmptyString('valor');

        $validator
            ->decimal('limite_tolerancia')
            ->allowEmptyString('limite_tolerancia');

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

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        return $validator;
    }
}
