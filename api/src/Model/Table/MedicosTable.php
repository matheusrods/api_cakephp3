<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Medicos Model
 *
 * @property \App\Model\Table\FornecedoresTable&\Cake\ORM\Association\BelongsToMany $Fornecedores
 * @property \App\Model\Table\FuncionariosTable&\Cake\ORM\Association\BelongsToMany $Funcionarios
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 * @property \App\Model\Table\PropostasCredenciamentoTable&\Cake\ORM\Association\BelongsToMany $PropostasCredenciamento
 *
 * @method \App\Model\Entity\Medico get($primaryKey, $options = [])
 * @method \App\Model\Entity\Medico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Medico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Medico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Medico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Medico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Medico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Medico findOrCreate($search, callable $callback = null, $options = [])
 */
class MedicosTable extends Table
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

        $this->setTable('medicos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Fornecedores', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'fornecedore_id',
            'joinTable' => 'fornecedores_medicos'
        ]);
        $this->belongsToMany('Funcionarios', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'funcionario_id',
            'joinTable' => 'funcionarios_medicos'
        ]);
        $this->belongsToMany('Endereco', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'endereco_id',
            'joinTable' => 'medicos_endereco'
        ]);
        $this->belongsToMany('PropostasCredenciamento', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'propostas_credenciamento_id',
            'joinTable' => 'propostas_credenciamento_medicos'
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('numero_conselho')
            ->maxLength('numero_conselho', 25)
            ->requirePresence('numero_conselho', 'create')
            ->notEmptyString('numero_conselho');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('conselho_uf')
            ->maxLength('conselho_uf', 2)
            ->allowEmptyString('conselho_uf');

        $validator
            ->integer('codigo_conselho_profissional')
            ->allowEmptyString('codigo_conselho_profissional');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('especialidade')
            ->maxLength('especialidade', 100)
            ->allowEmptyString('especialidade');

        $validator
            ->scalar('nit')
            ->maxLength('nit', 25)
            ->allowEmptyString('nit');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->scalar('nis')
            ->maxLength('nis', 15)
            ->allowEmptyString('nis');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 14)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('rqe')
            ->maxLength('rqe', 250)
            ->allowEmptyString('rqe');

        $validator
            ->scalar('foto')
            ->maxLength('foto', 255)
            ->allowEmptyString('foto');

        return $validator;
    }

    function getConselhoProfissional(int $codigo = null){       

        if(!empty($codigo)){

            $fields = array(
                'codigo' => 'ConselhoProfissional.codigo',
                'descricao' => 'ConselhoProfissional.descricao',
            );

            $joins = [
                [
                    'table' => 'conselho_profissional',
                    'alias' => 'ConselhoProfissional',
                    'type' => 'INNER',
                    'conditions' => 'Medicos.codigo_conselho_profissional = ConselhoProfissional.codigo'
                ],
            ];

            $conditions[] = "Medicos.codigo = ".$codigo;

            try {

            //executa a query
            $dados = $this->find()
                        ->select($fields)
                        ->join($joins)
                        ->where($conditions)
                        ->first();

            } catch (\Exception $e) {
                $dados = ['error'=> 'Erro na consulta'];
            }

        }else{

            $query = "select codigo, descricao from conselho_profissional";

            //executa a query
            $conn = ConnectionManager::get('default');
            $dados =  $conn->execute($query)->fetchAll('assoc');
        }    

        return $dados;
    }
    
}
