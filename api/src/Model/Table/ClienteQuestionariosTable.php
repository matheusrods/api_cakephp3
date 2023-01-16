<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Log\Log;
use Cake\Validation\Validator;

/**
 * ClienteQuestionarios Model
 *
 * @method \App\Model\Entity\ClienteQuestionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteQuestionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteQuestionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteQuestionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteQuestionario findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteQuestionariosTable extends Table
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

        $this->setTable('cliente_questionarios');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_cliente_questionario');
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
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyString('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('data_alteracao')
            ->allowEmptyString('data_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    function QuestionarioPermissao($codigo_cliente, $questionario = null){
        $dados = null;
        try {

            $joins  = array(
                array(
                    'table' => 'questionarios',
                    'alias' => 'Questionarios',
                    'type' => 'INNER',
                    'conditions' => 'Questionarios.codigo = ClienteQuestionarios.codigo_questionario and Questionarios.status = 1',
                )
            );

            //monta as condicoes
            $conditions = [
                            'codigo_questionario'=> $questionario,
                            'codigo_cliente IN ' => $codigo_cliente,
                            'ativo' => 1
                        ];

            $dados = $this->find()
                    ->join($joins)
                    ->where($conditions)
                    //->hydrate(false)
                    ->first();
        
        // debug(array($questionario,$codigo_cliente));
        // debug($dados->sql());
        // exit;
        
        // debug($dados);

        } catch (\Exception $e) {
            $error = ['error'=> 'Erro na consulta'];
        }

        return $dados;

    }


    public function questionarioRetiraPermissao($codigo_cliente, $cliente_inativo = null){
        $dados = null;
        try {

            $joins  = array(
                array(
                    'table' => 'questionarios',
                    'alias' => 'Questionarios',
                    'type' => 'INNER',
                    'conditions' => 'Questionarios.codigo = ClienteQuestionarios.codigo_questionario and Questionarios.status = 1',
                )
            );
                           
            $conditions = [                            
                        'ClienteQuestionarios.codigo_cliente IN ' => $codigo_cliente,
                        'ClienteQuestionarios.inativar_cliente' => 1,
                        'ClienteQuestionarios.ativo' => 1
                    ];

            $dados = $this->find()
                    ->join($joins)
                    ->where($conditions)
                    ->all()
                    ->toArray();
        
        // debug($codigo_cliente);
        // debug($dados->sql());
        // exit;
        
        // debug($dados);

        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            // debug($e->getMessage());
            $error = ['error'=> 'Erro na consulta'];
        }

        return $dados;

    }//fim questionario_recuso_permissao

}
