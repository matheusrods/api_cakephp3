<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PacientesCategoria Model
 *
 * @method \App\Model\Entity\PacientesCategorium get($primaryKey, $options = [])
 * @method \App\Model\Entity\PacientesCategorium newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PacientesCategorium[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PacientesCategorium|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PacientesCategorium saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PacientesCategorium patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PacientesCategorium[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PacientesCategorium findOrCreate($search, callable $callback = null, $options = [])
 */
class PacientesCategoriaTable extends Table
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

        $this->setTable('pacientes_categoria');
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
            ->maxLength('descricao', 50)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->requirePresence('codigo_usuario_alteracao', 'create')
            ->notEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        return $validator;
    }

    public function getPacientesCategoria($codigoEmpresa)
    {
        //Condições para retornar os dados das empresas
        $fields = array(
            'codigo'         => 'PacientesCategoria.codigo',
            'descricao'      => 'PacientesCategoria.descricao'
        );

        $conditions = "PacientesCategoria.codigo_empresa = " . $codigoEmpresa . "";
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->limit(20);

        return $dados;
    }
}
