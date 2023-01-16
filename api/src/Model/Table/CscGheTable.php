<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CscGhe Model
 *
 * @method \App\Model\Entity\CscGhe get($primaryKey, $options = [])
 * @method \App\Model\Entity\CscGhe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CscGhe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CscGhe|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CscGhe saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CscGhe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CscGhe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CscGhe findOrCreate($search, callable $callback = null, $options = [])
 */
class CscGheTable extends Table
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

        $this->setTable('csc_ghe');
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
            ->integer('codigo_ghe')
            ->requirePresence('codigo_ghe', 'create')
            ->notEmptyString('codigo_ghe');

        $validator
            ->integer('codigo_clientes_setores_cargos')
            ->requirePresence('codigo_clientes_setores_cargos', 'create')
            ->notEmptyString('codigo_clientes_setores_cargos');

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

    public function getCargos($codigo_ghe)
    {
        $query = $this->find()
            ->select([
                'codigo_cargo'                      => 'Cargos.codigo',
                'descricao'                         => 'Cargos.descricao',
                'codigo_clientes_setores_cargos'    => 'CscGhe.codigo_clientes_setores_cargos',
            ])
            ->join([
                'ClientesSetoresCargos' => [
                    'table' => 'clientes_setores_cargos',
                    'type' => 'INNER',
                    'conditions' => 'ClientesSetoresCargos.codigo = CscGhe.codigo_clientes_setores_cargos',
                ],
                'Cargos' => [
                    'table' => 'cargos',
                    'alias' => 'Cargos',
                    'type' => 'INNER',
                    'conditions' => 'Cargos.codigo = ClientesSetoresCargos.codigo_cargo',
                ],
            ])
            ->where(['CscGhe.codigo_ghe' => $codigo_ghe]);

        // debug($query->sql()); die;

        return $query->all()->toArray();
    }
}
