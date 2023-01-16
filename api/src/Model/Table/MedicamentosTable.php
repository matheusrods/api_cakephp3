<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Medicamentos Model
 *
 * @property \App\Model\Table\FuncionariosTable&\Cake\ORM\Association\BelongsToMany $Funcionarios
 *
 * @method \App\Model\Entity\Medicamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\Medicamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Medicamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Medicamento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Medicamento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Medicamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Medicamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Medicamento findOrCreate($search, callable $callback = null, $options = [])
 */
class MedicamentosTable extends AppTable
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

        $this->setTable('medicamentos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Funcionarios', [
            'foreignKey' => 'medicamento_id',
            'targetForeignKey' => 'funcionario_id',
            'joinTable' => 'funcionarios_medicamentos'
        ]);
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
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('principio_ativo')
            ->maxLength('principio_ativo', 255)
            ->requirePresence('principio_ativo', 'create')
            ->notEmptyString('principio_ativo');

        $validator
            ->integer('codigo_laboratorio')
            ->requirePresence('codigo_laboratorio', 'create')
            ->notEmptyString('codigo_laboratorio');

        $validator
            ->scalar('codigo_barras')
            ->maxLength('codigo_barras', 50)
            ->allowEmptyString('codigo_barras');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_apresentacao')
            ->requirePresence('codigo_apresentacao', 'create')
            ->notEmptyString('codigo_apresentacao');

        $validator
            ->scalar('posologia')
            ->maxLength('posologia', 255)
            ->allowEmptyString('posologia');

        return $validator;
    }

    /**
     * Busca um medicamento por nome
     * @param $param string
     * @return Query Cake/Database
     */
    public function getMedicamentosNome($param){
        $select = ['codigo_apresentacao', 'descricao', 'posologia', 'medicamento' => 'CONCAT(descricao, \' \', posologia)', 'codigo_medicamento'=>'codigo'];
        $group = ['codigo_apresentacao',  'descricao', 'posologia','codigo'];
        $where = ['codigo_apresentacao IS NOT NULL'];
        if (!empty($param)) {
            $where[] = ['descricao LIKE ' => '%' . $param . '%'];
        }

        $dados = $this->find()
            ->select($select)
            ->where($where)
            ->group($group)
            ->limit(20);

        // debug($dados->sql());exit;

        return $dados;
    }

    /***
     * Busca os códigos de apresentação
     * @param string $descricao
     * @param string $posologia
     * @return Query
     */
    public function getApresentacoesId($descricao, $posologia = '') {
        $select = ['codigo_apresentacao'];
        $group = ['codigo_apresentacao'];
        if (empty($posologia)){
            $where = [
                'descricao' => $descricao
            ];
        } else {
            $where = [
                'descricao' => $descricao, 'posologia' => $posologia
            ];
        }

        $dados = $this->find()
            ->select($select)
            ->where($where)
            ->group($group);

        // debug($where);
        // debug($dados->sql());exit;

        return $dados;
    }


}
