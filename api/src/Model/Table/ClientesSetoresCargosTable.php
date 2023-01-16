<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClientesSetoresCargos Model
 *
 * @method \App\Model\Entity\ClientesSetoresCargos get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClientesSetoresCargos findOrCreate($search, callable $callback = null, $options = [])
 */
class ClientesSetoresCargosTable extends Table
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

        $this->setTable('clientes_setores_cargos');
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
            ->integer('codigo_setor')
            ->requirePresence('codigo_setor', 'create')
            ->notEmptyString('codigo_setor');

        $validator
            ->integer('codigo_cargo')
            ->requirePresence('codigo_cargo', 'create')
            ->notEmptyString('codigo_cargo');

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
}
