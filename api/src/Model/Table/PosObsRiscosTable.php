<?php

namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * PosObsRiscos Model
 *
 * @method \App\Model\Entity\PosObsRisco get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsRisco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsRisco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRisco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsRisco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsRisco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRisco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsRisco findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsRiscosTable extends Table
{
    private $connect;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->connect = ConnectionManager::get('default');
        $this->setTable('pos_obs_riscos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
        $this->setEntityClass('App\Model\Entity\PosObsRisco');

        $this->hasOne('RiscosImpactos', [
            'bindingKey'   => 'codigo_arrtpa_ri',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'riscos_impactos',
            'propertyName' => 'impactos',
        ]);

        $this->hasOne('PerigosAspectos', [
            'bindingKey'   => 'codigo_arrt_pa',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'perigos_aspectos',
            'propertyName' => 'aspectos',
        ]);

        $this->hasOne('RiscosTipo', [
            'bindingKey'   => 'codigo_arrt',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'riscos_tipo',
            'propertyName' => 'tipo',
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_arrt_pa')
            ->requirePresence('codigo_arrt_pa', 'create')
            ->notEmptyString('codigo_arrt_pa');

        $validator
            ->integer('codigo_arrt')
            ->requirePresence('codigo_arrt', 'create')
            ->notEmptyString('codigo_arrt');

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

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    /**
     * Salvar Riscos de uma Observação
     *
     * ex. payload recebido em $riscos
     *
     *   {  "codigo_risco: 3,
     *      "codigo_risco_tipo": 1,
     *      "codigo_perigo_aspecto": 6,
     *      "codigo_risco_impacto": 1
     *    }
     *
     * @param integer $codigo_observacao
     * @param array $riscos
     * @return bool|Exception
     */
    public function salvarPorCodigoObservacao(int $codigo_observacao, array $riscos = [])
    {
        $codigo_usuario_inclusao = $this->obterCodigoUsuarioAutenticado();

        if (empty($codigo_observacao)) {
            throw new Exception("Código de Observação não fornecido para Salvar Riscos", 1);
        }
        try {
            $this->connect->begin();
            $this->deletarPorCodigoObservacao($codigo_observacao);

            foreach ($riscos as $risco) {

                $this->salvar(null, [
                    'codigo_pos_obs_observacao' => $codigo_observacao,
                    'codigo_arrtpa_ri'          => $risco['codigo_risco_impacto'],
                    'codigo_arrt_pa'            => $risco['codigo_perigo_aspecto'],
                    'codigo_arrt'               => $risco['codigo_risco_tipo'],
                    'codigo_usuario_inclusao'   => $codigo_usuario_inclusao,
                ]);
            }
            $this->connect->commit();
            return true;
        } catch (\Throwable $e) {
            $this->connect->rollback();
            Log::debug($e->getMessage());
            throw $e;
        }
    }

    /**
     * Obter Riscos passando o código da observação
     *
     * @param integer $codigo_observacao
     * @return array|Exception
     */
    public function obterPorCodigoObservacao(int $codigo_observacao)
    {
        try {
            $riscos = $this->find()
                ->select([
                    'PosObsRiscos.codigo',
                ])
                ->where([
                    'codigo_pos_obs_observacao' => $codigo_observacao,
                    'PosObsRiscos.ativo'        => 1
                ])
                ->contain([
                    'RiscosTipo' => [
                        'queryBuilder' => function ($q) {
                            return $q->select([
                                'codigo_risco_tipo'    => 'RiscosTipo.codigo',
                                'risco_tipo_icone'     => 'RiscosTipo.icone',
                                'risco_tipo_cor'       => 'RiscosTipo.cor',
                                'descricao_risco_tipo' => 'RHHealth.dbo.ufn_decode_utf8_string(RiscosTipo.descricao)'
                            ]);
                        }
                    ],
                    'RiscosImpactos' => [
                        'queryBuilder' => function ($q) {
                            return $q->select([
                                'codigo_impacto'    => 'RiscosImpactos.codigo',
                                'descricao_impacto' => 'RHHealth.dbo.ufn_decode_utf8_string(RiscosImpactos.descricao)'
                            ]);
                        }
                    ],
                    'PerigosAspectos' => [
                        'queryBuilder' => function ($q) {
                            return $q->select([
                                'codigo_aspecto'    => 'PerigosAspectos.codigo',
                                'descricao_aspecto' => 'RHHealth.dbo.ufn_decode_utf8_string(PerigosAspectos.descricao)'
                            ]);
                        }
                    ]
                ])
                ->toArray();

            return $riscos;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Deletar Riscos passando o código da observação
     *
     * @param integer $codigo_observacao
     * @return bool|Exception
     */
    public function deletarPorCodigoObservacao(int $codigo_observacao)
    {
        try {
            $riscos = $this->find()
                ->where(['codigo_pos_obs_observacao' => $codigo_observacao])
                ->all();

            $this->connect->begin();

            foreach ($riscos as $risco) {
                $dados['codigo_usuario_alteracao'] = $this->obterCodigoUsuarioAutenticado();
                $dados["data_alteracao"]           = date('Y-m-d H:i:s');
                $dados["ativo"]                    = 0;

                $entity = $this->patchEntity($risco, $dados);

                if (!$this->save($entity)) {
                    throw new Exception('Erro ao remover em AgentesRiscos');
                }
            }

            $this->connect->commit();
            return true;
        } catch (Exception $e) {
            $this->connect->rollback();
            Log::debug($e->getMessage());
            throw $e;
        }
    }
}
