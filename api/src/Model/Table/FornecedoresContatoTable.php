<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use phpDocumentor\Reflection\Types\Integer;

/**
 * FornecedoresContato Model
 *
 * @method \App\Model\Entity\FornecedoresContato get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresContato newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresContato[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContato|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresContato saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresContato patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContato[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresContato findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresContatoTable extends AppTable
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

        $this->setTable('fornecedores_contato');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_fornecedor_contato');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_inclusao' => 'new',
                    'data_alteracao' => 'always',
                ]
            ]
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
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->requirePresence('codigo_tipo_retorno', 'create')
            ->notEmptyString('codigo_tipo_retorno');

        $validator
            ->allowEmptyString('ddi');

        $validator
            ->allowEmptyString('ddd');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 256)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->allowEmptyString('nome');

        $validator
            ->allowEmptyString('ramal');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * @param int $codigo_fornecedor
     * @return array
     */
    public function getContatos(int $codigo_fornecedor)
    {
        $query = "SELECT 
                    fc.codigo
                    , fc.codigo_tipo_contato
                    , fc.codigo_tipo_retorno
                    , tr.descricao retorno
                    , CONCAT(fc.ddi , fc.ddd , fc.descricao) contato
                    , tc.descricao tipo
                    , fc.nome representante
                FROM
                    dbo.fornecedores_contato fc
                    INNER JOIN dbo.tipo_contato tc ON fc.codigo_tipo_contato = tc.codigo
                    INNER JOIN dbo.tipo_retorno tr ON fc.codigo_tipo_retorno = tr.codigo
                WHERE
                    fc.codigo_fornecedor = {$codigo_fornecedor}
                ORDER BY fc.data_inclusao ASC;";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }

    /**
     * @param int $codigo_fornecedor
     * @param int $codigo_contato
     * @return array
     */
    public function getContato(int $codigo_fornecedor, int $codigo_contato)
    {
        $query = "SELECT 
                    fc.codigo
                    , fc.codigo_tipo_contato
                    , fc.codigo_tipo_retorno
                    , tr.descricao retorno
                    , CONCAT(fc.ddi , fc.ddd , fc.descricao) contato
                    , tc.descricao tipo
                    , fc.nome representante
                FROM
                    dbo.fornecedores_contato fc
                    INNER JOIN dbo.tipo_contato tc ON fc.codigo_tipo_contato = tc.codigo
                    INNER JOIN dbo.tipo_retorno tr ON fc.codigo_tipo_retorno = tr.codigo
                WHERE
                    fc.codigo_fornecedor = {$codigo_fornecedor}
                    AND fc.codigo = {$codigo_contato}
                ORDER BY fc.data_inclusao ASC;";

        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }

}
