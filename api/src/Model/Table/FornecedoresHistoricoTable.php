<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresHistorico Model
 *
 * @method \App\Model\Entity\FornecedoresHistorico get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresHistorico findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresHistoricoTable extends Table
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

        $this->setTable('fornecedores_historico');
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
            ->allowEmptyString('codigo_fornecedor');

        $validator
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 255)
            ->allowEmptyString('caminho_arquivo');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        return $validator;
    }

    public function listar(int $codigo_fornecedor) {
        $query = "SELECT
                    fh.observacao
                , u.apelido
                , fh.codigo
                , fh.codigo_fornecedor
                , fh.codigo_usuario_inclusao
                , fh.codigo_usuario_alteracao
                , fh.ativo
                , fh.codigo_empresa
                , fh.data_inclusao
                , fh.data_alteracao
                , fh.caminho_arquivo
            FROM
                dbo.fornecedores_historico fh
                INNER JOIN usuario u ON fh.codigo_usuario_inclusao = u.codigo
            WHERE
                fh.codigo_fornecedor = {$codigo_fornecedor}";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }
}
