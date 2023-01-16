<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * GruposEconomicosClientes Model
 *
 * @method \App\Model\Entity\GruposEconomicosCliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomicosCliente findOrCreate($search, callable $callback = null, $options = [])
 */
class GruposEconomicosClientesTable extends Table
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

        $this->setTable('grupos_economicos_clientes');
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
            ->integer('codigo_grupo_economico')
            ->requirePresence('codigo_grupo_economico', 'create')
            ->notEmptyString('codigo_grupo_economico');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

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
            ->boolean('bloqueado')
            ->allowEmptyString('bloqueado');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getCodigoClienteMatriz($codigo_cliente)
    {
        //monta o select
        $fields = [
            'codigo_cliente_matriz' => 'GrupoEconomico.codigo_cliente',
        ];
        //monta os joins
        $joins = [
            [
                'table' => 'grupos_economicos',
                'alias' => 'GrupoEconomico',
                'type' => 'INNER',
                'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
            ],
        ];

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where(['GruposEconomicosClientes.codigo_cliente' => $codigo_cliente])
            ->first();

        return $dados;
    }

    public function getClients($economicGroups = [], $userCode, $clientUserCode = null)
    {
        if (count($economicGroups) > 0) {
            foreach ($economicGroups as $key => $economicGroup) {
                $conditions = [
                    'GruposEconomicosClientes.codigo_grupo_economico' => $economicGroup['codigo'],
                    'Cliente.ativo' => 1,
                    'Cliente.e_tomador <> 1',
                ];

                if (!is_null($clientUserCode)) {
                    array_push($conditions, "Cliente.codigo = $clientUserCode");
                }

                $joins = [
                    [
                        'table' => 'cliente',
                        'alias' => 'Cliente',
                        'type' => 'INNER',
                        'conditions' => [
                            'Cliente.codigo = GruposEconomicosClientes.codigo_cliente',
                        ],
                    ],
                    [
                        'table' => 'cliente_endereco',
                        'alias' => 'ClienteEndereco',
                        'type' => 'INNER',
                        'conditions' => [
                            'ClienteEndereco.codigo_cliente = Cliente.codigo',
                        ],
                    ],
                ];

                $economicGroups[$key]['unidades'] = $this->find()
                    ->select([
                        'codigo' => 'GruposEconomicosClientes.codigo',
                        'codigo_cliente' => 'Cliente.codigo',
                        'razao_social' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)',
                        'nome_fantasia' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
                        'cep' => 'ClienteEndereco.cep',
                        'logradouro' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.logradouro)',
                        'numero' => 'ClienteEndereco.numero',
                        'bairro' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.bairro)',
                        'cidade' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.cidade)',
                        'estado_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.estado_descricao)',
                        'complemento' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.complemento)',
                    ])
                    ->join($joins)
                    ->where($conditions)
                    ->orderAsc('GruposEconomicosClientes.codigo')
                    ->hydrate(false)
                    ->all()
                    ->toArray();

                if (count($economicGroups[$key]['unidades']) > 0) {
                    $CentroResultado = TableRegistry::getTableLocator()->get('CentroResultado');

                    foreach ($economicGroups[$key]['unidades'] as $unityKey => $unity) {
                        $clientId = (int) $unity['codigo_cliente'];

                        $unformattedData = $CentroResultado->find()
                            ->select([
                                'CentroResultado.codigo_cliente_bu',
                                'cliente_bu_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteBu.descricao)',
                                'CentroResultado.codigo_cliente_opco',
                                'cliente_opco_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteOpco.descricao)'
                            ])
                            ->join([
                                [
                                    'table' => 'cliente_bu',
                                    'alias' => 'ClienteBu',
                                    'type' => 'INNER',
                                    'conditions' => [
                                        'ClienteBu.codigo = CentroResultado.codigo_cliente_bu',
                                    ],
                                ],
                                [
                                    'table' => 'cliente_opco',
                                    'alias' => 'ClienteOpco',
                                    'type' => 'INNER',
                                    'conditions' => [
                                        'ClienteOpco.codigo = CentroResultado.codigo_cliente_opco',
                                    ],
                                ]
                            ])
                            ->where([
                                'CentroResultado.codigo_cliente_bu IS NOT NULL',
                                'CentroResultado.ativo = 1',
                                "CentroResultado.codigo_cliente_alocacao = $clientId",
                            ])
                            ->hydrate(false)
                            ->group([
                                'CentroResultado.codigo_cliente_bu',
                                'CentroResultado.codigo_cliente_opco',
                                'ClienteBu.descricao',
                                'ClienteOpco.descricao'
                            ])
                            ->all()
                            ->toArray();

                        $formattedData = [];

                        foreach ($unformattedData as $row) {
                            if (!isset($formattedData[$row['codigo_cliente_bu']])) {
                                $formattedData[$row['codigo_cliente_bu']] = [
                                    'codigo' => (int) $row['codigo_cliente_bu'],
                                    'descricao' => $row['cliente_bu_descricao'],
                                    'opcos' => [],
                                ];
                            }

                            if (!isset($formattedData[$row['codigo_cliente_bu']]['opcos'][$row['codigo_cliente_opco']])) {
                                $formattedData[$row['codigo_cliente_bu']]['opcos'][$row['codigo_cliente_opco']] = [
                                    'codigo' => (int) $row['codigo_cliente_opco'],
                                    'descricao' => $row['cliente_opco_descricao']
                                ];
                            }
                        }

                        $formattedData = array_values($formattedData);

                        foreach ($formattedData as $resultKey => $result) {
                            $formattedOpcos = array_values($result['opcos']);

                            $formattedData[$resultKey]['opcos'] = $formattedOpcos;
                        }

                        $economicGroups[$key]['unidades'][$unityKey]['centro_resultado'] = $formattedData;
                    }
                }
            }

            return $economicGroups;
        } else {
            return [];
        }
    }

    public function retorna_lista_de_unidades_do_grupo_economico($codigo_cliente) {

        $unidades = $this->find()
            ->select([
                'Cliente.codigo'
            ])
            ->join([
                [
                    'table' => 'grupos_economicos',
                    'alias' => 'GrupoEconomico',
                    'type' => 'INNER',
                    'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
                ],
                [
                    'table' => 'cliente',
                    'alias' => 'Cliente',
                    'type' => 'INNER',
                    'conditions' => 'Cliente.codigo = GruposEconomicosClientes.codigo_cliente',
                ]
            ])
            ->where([
                'GrupoEconomico.codigo_cliente' => $codigo_cliente,
                'Cliente.ativo' => 1,
                'Cliente.e_tomador' => 0
            ])
            ->order(['Cliente.nome_fantasia ASC'])
            ->hydrate(false)
            ->toArray();

        $arr = array();

        foreach ($unidades as $unidade) {
            $arr[] = $unidade['Cliente']['codigo'];
        };

        $arr = implode(",", $arr);  

		return $arr;    
	}
}
