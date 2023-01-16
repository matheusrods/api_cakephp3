<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GruposEconomicos Model
 *
 * @method \App\Model\Entity\GruposEconomico get($primaryKey, $options = [])
 * @method \App\Model\Entity\GruposEconomico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GruposEconomico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GruposEconomico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GruposEconomico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GruposEconomico findOrCreate($search, callable $callback = null, $options = [])
 */
class GruposEconomicosTable extends Table
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

        $this->setTable('grupos_economicos');
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
            ->scalar('descricao')
            ->maxLength('descricao', 50)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('vias_aso')
            ->allowEmptyString('vias_aso');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_medico_pcmso_padrao')
            ->allowEmptyString('codigo_medico_pcmso_padrao');

        $validator
            ->integer('exames_dias_a_vencer')
            ->allowEmptyString('exames_dias_a_vencer');

        $validator
            ->boolean('exibir_centro_custo_per_capita')
            ->notEmptyString('exibir_centro_custo_per_capita');

        $validator
            ->boolean('exibir_nome_fantasia_aso')
            ->notEmptyString('exibir_nome_fantasia_aso');

        $validator
            ->boolean('exibir_rqe_aso')
            ->allowEmptyString('exibir_rqe_aso');

        return $validator;
    }

    public function getCampoPorCliente($campo = "exibir_nome_fantasia_aso", $codigo_cliente){
        $joins = array(
            array (
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GrupoEconomicoCliente',
                'type' => 'INNER',
                'conditions' => array (
                    'GrupoEconomicoCliente.codigo_grupo_economico = GruposEconomicos.codigo',
                ),
            ),
        );
        $fields = array($campo);
        $conditions = array('GrupoEconomicoCliente.codigo_cliente' => $codigo_cliente);

        $grupo_economico = $this->find()->select($fields)->join($joins)->where($conditions)->first()->toArray();

        return $grupo_economico[$campo];
    }

    public function getCampoPorClienteRqe($campo = "exibir_rqe_aso", $codigo_cliente){
        $joins = array(
            array (
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GrupoEconomicoCliente',
                'type' => 'INNER',
                'conditions' => array (
                    'GrupoEconomicoCliente.codigo_grupo_economico = GruposEconomicos.codigo',
                ),
            ),
        );
        $fields = array($campo);
        $conditions = array('GrupoEconomicoCliente.codigo_cliente' => $codigo_cliente);

        $grupo_economico = $this->find()->select($fields)->join($joins)->where($conditions)->first()->toArray();

        return $grupo_economico[$campo];
    }

    public function getEconomicGroups($userCode, $accessesReleasedUser, $clientUserCode = null) {
        $fields = [
            'codigo' => 'GruposEconomicos.codigo',
            'codigo_cliente' => 'GruposEconomicos.codigo_cliente',
            'descricao' => 'GruposEconomicos.descricao'
        ];

        $joins = [
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GEC',
                'type' => 'INNER',
                'conditions' => [
                    'GEC.codigo_grupo_economico = GruposEconomicos.codigo',
                ]
            ],
            [
                'table' => 'cliente',
                'alias' => 'C',
                'type' => 'INNER',
                'conditions' => [
                    'C.codigo = GEC.codigo_cliente',
                ]
            ]
        ];

        try {
            if (count($accessesReleasedUser) > 0) {
                $economicGroupsByUserAccess = $this->find()
                    ->select($fields)
                    ->join($joins)
                    ->where([
                        'C.e_tomador <> 1',
                        'C.ativo = 1',
                    ])
                    ->whereInList('C.codigo', $accessesReleasedUser)
                    ->group(['GruposEconomicos.codigo', 'GruposEconomicos.descricao', 'GruposEconomicos.codigo_cliente'])
                    ->orderAsc('GruposEconomicos.codigo')
                    ->all()
                    ->toArray();

                return $economicGroupsByUserAccess;
            } else {
                $conditions = [
                    'C.e_tomador <> 1',
                    'C.ativo = 1',
                ];

                if (!is_null($clientUserCode)) {
                    array_push($conditions,
                        "(GruposEconomicos.codigo_cliente IN (
                            SELECT UMC.codigo_cliente FROM usuario U LEFT JOIN usuario_multi_cliente UMC ON UMC.codigo_usuario = U.codigo WHERE U.codigo = $userCode
                        ) OR GruposEconomicos.codigo_cliente = $clientUserCode)"
                    );
                } else {
                    array_push($conditions,
                        "GruposEconomicos.codigo_cliente IN (
                            SELECT UMC.codigo_cliente FROM usuario U LEFT JOIN usuario_multi_cliente UMC ON UMC.codigo_usuario = U.codigo WHERE U.codigo = $userCode
                        )"
                    );
                }

                $data = $this->find()
                    ->select($fields)
                    ->join($joins)
                    ->where($conditions)
                    ->group(['GruposEconomicos.codigo', 'GruposEconomicos.descricao', 'GruposEconomicos.codigo_cliente'])
                    ->orderAsc('GruposEconomicos.codigo')
                    ->all()
                    ->toArray();

                return $data;
            }
        } catch (\Exception $exception) {
            return [];
        }
    }
}
