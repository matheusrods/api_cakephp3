<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuariosMedicamentosStatus Model
 *
 * @method \App\Model\Entity\UsuariosMedicamentosStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosMedicamentosStatusTable extends AppTable
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

        $this->setTable('usuarios_medicamentos_status');
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
            ->integer('codigo_usuario_medicamento')
            ->allowEmptyString('codigo_usuario_medicamento');

        $validator
            ->dateTime('data_hora_uso')
            ->requirePresence('data_hora_uso', 'create')
            ->notEmptyDateTime('data_hora_uso');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    public function getQuantidadeDeDoses($codigo_usuario_medicamento ) {
        $select = array(
            'quantidade_de_doses' => 'COUNT(UsuariosMedicamentosStatus.codigo_usuario_medicamento)'
        );

        //monta os filtros
        $conditions = array(
            'UsuariosMedicamentosStatus.codigo_usuario_medicamento' => $codigo_usuario_medicamento,
            'CAST(UsuariosMedicamentosStatus.data_hora_uso as date) =' => date('Y-m-d')
        );


        $dados = $this->find()
//            ->select($select)
            ->where($conditions)
            ->enableHydration(false)
            ->orderAsc('UsuariosMedicamentosStatus.data_hora_uso')
            ->toArray()
        ;

        return $dados;
    }
}
