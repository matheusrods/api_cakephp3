<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EquipamentosAdotados Model
 *
 * @method \App\Model\Entity\EquipamentosAdotado get($primaryKey, $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EquipamentosAdotado findOrCreate($search, callable $callback = null, $options = [])
 */
class EquipamentosAdotadosTable extends Table
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

        $this->setTable('equipamentos_adotados');
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
            ->integer('resultados')
            ->requirePresence('resultados', 'create')
            ->notEmptyString('resultados');

        $validator
            ->dateTime('agenda_inspecao')
            ->allowEmptyDateTime('agenda_inspecao');

        $validator
            ->integer('codigo_equipamento_inspecao_tipo')
            ->allowEmptyString('codigo_equipamento_inspecao_tipo');

        $validator
            ->integer('codigo_unidade_medicao')
            ->allowEmptyString('codigo_unidade_medicao');

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
            ->integer('codigo_aprho')
            ->allowEmptyString('codigo_aprho');

        return $validator;
    }
}
