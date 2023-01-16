<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MedicoCalendario Model
 *
 * @method \App\Model\Entity\MedicoCalendario get($primaryKey, $options = [])
 * @method \App\Model\Entity\MedicoCalendario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MedicoCalendario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedicoCalendario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MedicoCalendario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MedicoCalendario findOrCreate($search, callable $callback = null, $options = [])
 */
class MedicoCalendarioTable extends Table
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

        $this->setTable('medico_calendario');
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
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_medico')
            ->requirePresence('codigo_medico', 'create')
            ->notEmptyString('codigo_medico');

        $validator
            ->integer('codigo_especialidade')
            ->allowEmptyString('codigo_especialidade');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    /**
     * [getMedicosCalendario metodo para buscar os dados dos medicos que tem calendario configurado]
     * @param  int    $codigo_fornecedor [description]
     * @return [type]                    [description]
     */
    public function getMedicosCalendario(int $codigo_fornecedor)
    {

        //campos
        $fields = array(
            "codigo_medico" => "Medico.codigo",
            "nome" => "RHHealth.dbo.ufn_decode_utf8_string(Medico.nome)",
            "codigo_fornecedor" => "Fornecedor.codigo",
            "unidade" => "RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)",
        );

        //relacionamentos
        $joins = array(
            array(
                'table' => 'medicos',
                'alias' => 'Medico',
                'type' => 'INNER',
                'conditions' => 'MedicoCalendario.codigo_medico = Medico.codigo',
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'MedicoCalendario.codigo_fornecedor = Fornecedor.codigo',
            ),
        );

        //monta os filtros
        $where = array(
            'MedicoCalendario.codigo_fornecedor' => $codigo_fornecedor
        );

        $dados = $this->find()
                      ->select($fields)
                      ->join($joins)
                      ->where($where)
                      ->hydrate(false)
                      ->toArray();

        return $dados;

    }//fim getMedicosCalendario

}
