<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresUnidades Model
 *
 * @method \App\Model\Entity\FornecedoresUnidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresUnidade findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresUnidadesTable extends Table
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

        $this->setTable('fornecedores_unidades');
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
            ->integer('codigo_fornecedor_matriz')
            ->requirePresence('codigo_fornecedor_matriz', 'create')
            ->notEmptyString('codigo_fornecedor_matriz');

        $validator
            ->integer('codigo_fornecedor_unidade')
            ->requirePresence('codigo_fornecedor_unidade', 'create')
            ->notEmptyString('codigo_fornecedor_unidade');

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
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }

    public function getUnidadesByFornecedor($codigo_fornecedor)
    {
        $fields = array(
            'codigo_fornecedor_unidade'  => 'FornecedoresUnidades.codigo_fornecedor_unidade',
            'codigo_fornecedor_matriz'   => 'FornecedoresUnidades.codigo_fornecedor_matriz',
            'codigo_fornecedor_endereco' => 'FornecedoresEndereco.codigo',
            'nome'                       => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedores.nome)',
            'razao_social'               => 'Fornecedores.razao_social',
            'logradoro'                  => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.logradouro)',
            'numero'                     => 'FornecedoresEndereco.numero',
            'bairro'                     => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.bairro)',
            'cidade'                     => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.cidade)',
            'estado_descricao'           => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.estado_descricao)',
            'estado_abreviacao'          => 'FornecedoresEndereco.estado_abreviacao',
        );

        $joins  = array(
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedores',
                'type'  => 'INNER',
                'conditions' => 'Fornecedores.codigo = FornecedoresUnidades.codigo_fornecedor_unidade and FornecedoresUnidades.codigo_fornecedor_matriz = ' . $codigo_fornecedor . ' ',
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedoresEndereco',
                'type'  => 'INNER',
                'conditions' => 'Fornecedores.codigo = FornecedoresEndereco.codigo_fornecedor',
            )
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins);

        return $dados;
    }

    public function getUnidadeByFornecedor($codigo_fornecedor, $codigo_unidade)
    {
        $fields = array(
            'codigo_fornecedor_unidade'  => 'FornecedoresUnidades.codigo_fornecedor_unidade',
            'codigo_fornecedor_matriz'   => 'FornecedoresUnidades.codigo_fornecedor_matriz',
            'codigo_fornecedor_endereco' => 'FornecedoresEndereco.codigo',
            'nome'                       => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedores.nome)',
            'razao_social'               => 'Fornecedores.razao_social',
            'logradoro'                  => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.logradouro)',
            'numero'                     => 'FornecedoresEndereco.numero',
            'bairro'                     => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.bairro)',
            'cidade'                     => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.cidade)',
            'estado_descricao'           => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedoresEndereco.estado_descricao)',
            'estado_abreviacao'          => 'FornecedoresEndereco.estado_abreviacao',
        );

        $joins  = array(
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedores',
                'type'  => 'INNER',
                'conditions' => 'Fornecedores.codigo = FornecedoresUnidades.codigo_fornecedor_unidade and FornecedoresUnidades.codigo_fornecedor_matriz = ' . $codigo_fornecedor . ' ',
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedoresEndereco',
                'type'  => 'INNER',
                'conditions' => 'Fornecedores.codigo = FornecedoresEndereco.codigo_fornecedor',
            )
        );

        $condition = "FornecedoresUnidades.codigo_fornecedor_unidade = " . $codigo_unidade . " ";

        $dados = $this->find()
            ->select($fields)
            ->where($condition)
            ->join($joins)
            ->first();

        return $dados;
    }
}
