<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\ORM\TableRegistry;

/**
 * PosQtdParticipantes Model
 *
 * @method \App\Model\Entity\PosQtdParticipante get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosQtdParticipante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosQtdParticipante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosQtdParticipante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosQtdParticipante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosQtdParticipante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosQtdParticipante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosQtdParticipante findOrCreate($search, callable $callback = null, $options = [])
 */
class PosQtdParticipantesTable extends AppTable
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

        $this->setTable('pos_qtd_participantes');
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
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->allowEmptyString('codigo_pos_ferramenta');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('quantidade')
            ->requirePresence('quantidade', 'create')
            ->notEmptyString('quantidade');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

     /**
     * [getQtdParticipante metodo para realizar a busca no banco com os dados de parametros passados e retornar o valor de qtd de participantes]
     * @param  [type] $codigo_unidade [codigo da unidade do funcionario]
     * @return [type]                 [description]
     */
    public function getQtdParticipante(int $codigo_unidade) 
    {
        $dados = array();      
        
        try {
            //verifica se tem a configuracao do formulário
            $this->GruposEconomicos = TableRegistry::get('GruposEconomicos');
            $codigo_cliente_matriz = $this->GruposEconomicos->getCampoPorClienteRqe("codigo_cliente", $codigo_unidade);

            $fields = array(
                'codigo',
                'codigo_cliente',
                'quantidade'
            );
            $conditions['codigo_cliente'] = $codigo_cliente_matriz;

            //pega os valores de participantes
            $dados = $this->find()
                ->select($fields)
                ->where($conditions)
                ->hydrate(false)
                ->toArray();

            if(empty($dados)) {
                throw new Exception("Não existe qtd de participantes configurada, favor entrar em contato com o Administrador!");
            }

        }
        catch(Exception $e) {

            $dados['error'] = $e->getMessage();
        }

        return (array)$dados;

    }//fim getQtdParticipante($codigo_unidade,$form_tipo)

}
