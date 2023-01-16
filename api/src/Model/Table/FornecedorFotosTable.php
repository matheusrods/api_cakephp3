<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

use App\Exception\ModelException;
use App\Utils\ArrayUtil;

/**
 * FornecedorFotos Model
 *
 * @method \App\Model\Entity\FornecedorFoto get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedorFoto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedorFoto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorFoto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedorFoto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedorFoto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorFoto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorFoto findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedorFotosTable extends AppTable
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

        $this->setTable('fornecedores_fotos');
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
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 255)
            ->requirePresence('caminho_arquivo', 'create')
            ->notEmptyString('caminho_arquivo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 50)
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
    

    /**
     * Obter Imagens de um fornecedor por codigo_fornecedor(opcional) e filtros por conditions
     *
     * @param int $codigo_fornecedor
     * @param array $conditions
     * @param array $fields
     * @param array $order
     * @return array|null
     */
    public function obterImagens(int $codigo_fornecedor = null, array $fields = [], array $conditions = [], array $order = []){
        
        // obter campos
        if(isset($fields) && empty($fields)){
            $fields = ['codigo', 'descricao', 'caminho_arquivo'];
        }

        // obter condições
        if(isset($conditions) && empty($conditions)){
            $conditions = ArrayUtil::mergePreserveKeys($conditions,  ['status' => 1]);
        }

        // obter por codigo
        if(isset($codigo_fornecedor) && !empty($codigo_fornecedor)){
            $conditions = ArrayUtil::mergePreserveKeys($conditions, ['codigo_fornecedor'=>(int)$codigo_fornecedor]);
        }
        
        try {
            
            // monta consulta
            $query = $this->find();

            $query->select($fields)
                        ->where($conditions)
                        ->limit(self::TABLE_QUERY_LIMIT_DEFAULT)
                        ->order($order);
            
            // formatando um campo na instancia da ORM\Query 
            // antes de chegar na controller
            $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
                return $results->map(function ($row) {
                    $row['caminho_arquivo'] = FILE_SERVER.$row['caminho_arquivo'];
                    return $row;
                });
            });

            return $query;

        } catch (\Exception $e) {
            
            throw new ModelException( $e->getMessage() );

            return ['error' => $e->getMessage()];    
        }
    }
}
