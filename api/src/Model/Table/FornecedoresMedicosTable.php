<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresMedicos Model
 *
 * @method \App\Model\Entity\FornecedoresMedico get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresMedico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresMedico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresMedico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresMedico findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresMedicosTable extends Table
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

        $this->setTable('fornecedores_medicos');
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
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    public function getMedicos(int $codigo_fornecedor, string $especialidade=null, $limit = null){
        
        $fields = array(
            'codigo'=>'Medicos.codigo',
            'nome'=>'Medicos.nome',
            'numero_conselho'=>'Medicos.numero_conselho',
            'conselho_uf'=>'Medicos.conselho_uf',
            'especialidade' => 'RHHealth.dbo.ufn_decode_utf8_string(Especialidades.descricao)',
            'foto'=>'Medicos.foto'
        );

        $joins  = array(
            array(
                'table' => 'medicos',
                'alias' => 'Medicos',
                'type' => 'INNER',
                'conditions' => 'Medicos.codigo = FornecedoresMedicos.codigo_medico',
            ),
            array(
                'table' => 'fornecedores_medico_especialidades',
                'alias' => 'FME',
                'type' => 'INNER',
                'conditions' => 'Medicos.codigo = FME.codigo_medico AND FornecedoresMedicos.codigo_fornecedor = FME.codigo_fornecedor',
            ),
            array(
                'table' => 'especialidades',
                'alias' => 'Especialidades',
                'type' => 'INNER',
                'conditions' => 'FME.codigo_especialidade = Especialidades.codigo',
            ),
        );

        $conditions = "FornecedoresMedicos.codigo_fornecedor = ".$codigo_fornecedor;

        if(!empty($especialidade)){
            $conditions .= " AND Medicos.especialidade = '".$especialidade."'";
        }

        $order = "Medicos.nome ASC";

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->order($order);

        if($limit) {
            $dados->limit(20);

        }

        return $dados;
    }

    public function getEspecialidades(int $codigo_fornecedor){
        
        $fields = array(
            'codigo'=>'Especialidades.codigo',
            'especialidade'=>'RHHealth.dbo.ufn_decode_utf8_string(Especialidades.descricao)',
        );

        $joins  = array(
            array(
                'table' => 'fornecedores_medico_especialidades',
                'alias' => 'FME',
                'type' => 'INNER',
                'conditions' => 'FornecedoresMedicos.codigo_medico = FME.codigo_medico AND FornecedoresMedicos.codigo_fornecedor = FME.codigo_fornecedor',
            ),
            array(
                'table' => 'especialidades',
                'alias' => 'Especialidades',
                'type' => 'INNER',
                'conditions' => 'Especialidades.codigo = FME.codigo_especialidade',
            )
        );

        $conditions = "FornecedoresMedicos.codigo_fornecedor = ".$codigo_fornecedor;
        $group = array('Especialidades.codigo','Especialidades.descricao');
        $order = "Especialidades.descricao ASC";        

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group)
            ->order($order);

        return $dados;
    }
}
