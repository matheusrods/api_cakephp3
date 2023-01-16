<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * ClientesFornecedores Model
 *
 * @method \App\Model\Entity\ClientesFornecedore get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClientesFornecedore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClientesFornecedore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClientesFornecedore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientesFornecedore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClientesFornecedore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClientesFornecedore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClientesFornecedore findOrCreate($search, callable $callback = null, $options = [])
 */
class ClientesFornecedoresTable extends AppTable
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

        $this->setTable('clientes_fornecedores');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
    }

    public function listarFornecedorPorCodigoCliente($codigo_cliente){
        return $this->find()
            ->select(['fornecedores.nome','fornecedores.codigo', 'fornecedores.razao_social', 'fornecedores.tipo_atendimento',
                'fornecedores_endereco.latitude','fornecedores_endereco.longitude',
                'cidade'=>'RHHealth.dbo.ufn_decode_utf8_string(fornecedores_endereco.cidade)',
                'nome'=>'RHHealth.dbo.ufn_decode_utf8_string(fornecedores.nome)',
                'logradouro'=>'RHHealth.dbo.ufn_decode_utf8_string(fornecedores_endereco.logradouro)',
                'bairro'=>'RHHealth.dbo.ufn_decode_utf8_string(fornecedores_endereco.bairro)',
                'fornecedores_endereco.bairro',
                'fornecedores_endereco.estado_abreviacao', 'fornecedores_endereco.numero','fornecedores_endereco.complemento','fornecedores_endereco.logradouro'])
            ->join([
                'fornecedores'=>[
                    'table' => 'fornecedores',
                    'type' => 'INNER',
                    'conditions' => 'fornecedores.codigo = ClientesFornecedores.codigo_fornecedor',
                ],
                'fornecedores_endereco'=>[
                    'table' => 'fornecedores_endereco',
                    'type' => 'INNER',
                    'conditions' => 'fornecedores_endereco.codigo_fornecedor = ClientesFornecedores.codigo_fornecedor'
                ]
            ])
            ->where(['ClientesFornecedores.codigo_cliente'=>$codigo_cliente]);
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
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function getEmpresasPorFornecedor($codigo_fornecedor)
    {
        //Condições para retornar os dados das empresas
        $fields = array(
            'codigo_cliente' => 'GruposEconomicos.codigo_cliente',
            'descricao'      => 'RHHealth.dbo.ufn_decode_utf8_string(GruposEconomicos.descricao)',
        );

        $joins  = array(
            array(
                'table' => 'grupos_economicos',
                'alias' => 'GruposEconomicos',
                'type' => 'INNER',
                'conditions' => 'ClientesFornecedores.codigo_fornecedor = '.$codigo_fornecedor.' and GruposEconomicos.codigo_cliente = ClientesFornecedores.codigo_cliente',
            )
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins);

        return $dados;
    }
}
