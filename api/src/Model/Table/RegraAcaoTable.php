<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * RegraAcao Model
 *
 * @method \App\Model\Entity\RegraAcao get($primaryKey, $options = [])
 * @method \App\Model\Entity\RegraAcao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RegraAcao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RegraAcao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RegraAcao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RegraAcao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RegraAcao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RegraAcao findOrCreate($search, callable $callback = null, $options = [])
 */
class RegraAcaoTable extends Table
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

        $this->setTable('regra_acao');
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('dias_rejeitar')
            ->requirePresence('dias_rejeitar', 'create')
            ->notEmptyString('dias_rejeitar');

        $validator
            ->integer('dias_encaminhar')
            ->requirePresence('dias_encaminhar', 'create')
            ->notEmptyString('dias_encaminhar');

        $validator
            ->integer('dias_prazo')
            ->requirePresence('dias_prazo', 'create')
            ->notEmptyString('dias_prazo');

        $validator
            ->integer('status_acao_sem_prazo')
            ->requirePresence('status_acao_sem_prazo', 'create')
            ->notEmptyString('status_acao_sem_prazo');

        $validator
            ->integer('dias_analise_implementacao')
            ->requirePresence('dias_analise_implementacao', 'create')
            ->notEmptyString('dias_analise_implementacao');

        $validator
            ->integer('dias_analise_eficacia')
            ->requirePresence('dias_analise_eficacia', 'create')
            ->notEmptyString('dias_analise_eficacia');

        $validator
            ->integer('dias_analise_abrangencia')
            ->requirePresence('dias_analise_abrangencia', 'create')
            ->notEmptyString('dias_analise_abrangencia');

        $validator
            ->integer('dias_analise_cancelamento')
            ->requirePresence('dias_analise_cancelamento', 'create')
            ->notEmptyString('dias_analise_cancelamento');

        $validator
            ->integer('dias_a_vencer')
            ->requirePresence('dias_a_vencer', 'create')
            ->notEmptyString('dias_a_vencer');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('dias_a_aceitar')
            ->allowEmptyString('dias_a_aceitar');

        return $validator;
    }

    public function getUserClientActionRule($userCode = null)
    {
        $this->Usuario = TableRegistry::get('Usuario');

        $conditions = [
            'Usuario.codigo' => $userCode,
        ];

        $fields = [
            'codigo' => 'RegraAcao.codigo',
            'codigo_cliente' => 'RegraAcao.codigo_cliente',
            'dias_rejeitar' => 'RegraAcao.dias_rejeitar',
            'dias_encaminhar' => 'RegraAcao.dias_encaminhar',
            'dias_prazo' => 'RegraAcao.dias_prazo',
            'status_acao_sem_prazo' => 'RegraAcao.status_acao_sem_prazo',
            'dias_analise_implementacao' => 'RegraAcao.dias_analise_implementacao',
            'dias_analise_eficacia' => 'RegraAcao.dias_analise_eficacia',
            'dias_analise_abrangencia' => 'RegraAcao.dias_analise_abrangencia',
            'dias_analise_cancelamento' => 'RegraAcao.dias_analise_cancelamento',
            'dias_a_vencer' => 'RegraAcao.dias_a_vencer',
            'codigo_usuario_inclusao' => 'RegraAcao.codigo_usuario_inclusao',
            'codigo_usuario_alteracao' => 'RegraAcao.codigo_usuario_alteracao',
            'data_inclusao' => 'RegraAcao.data_inclusao',
            'data_alteracao' => 'RegraAcao.data_alteracao',
            'dias_a_aceitar' => 'RegraAcao.dias_a_aceitar',
        ];

        $joins = [
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = (CASE WHEN UsuariosDados.cpf IS NULL THEN Usuario.apelido ELSE UsuariosDados.cpf END)',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo AND ClienteFuncionario.codigo_cliente = Usuario.codigo_cliente',
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetorCargo.codigo = (
                    SELECT TOP 1 _fsc.codigo FROM funcionario_setores_cargos _fsc
                        INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                    WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                        AND _fsc.data_fim IS NULL
                    ORDER BY _fsc.codigo desc
                )',
            ],
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GruposEconomicosClientes',
                'type' => 'INNER',
                'conditions' => 'GruposEconomicosClientes.codigo_cliente = FuncionarioSetorCargo.codigo_cliente_alocacao',
            ],
            [
                'table' => 'grupos_economicos',
                'alias' => 'GrupoEconomico',
                'type' => 'INNER',
                'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
            ],
            [
                'table' => 'regra_acao',
                'alias' => 'RegraAcao',
                'type' => 'INNER',
                'conditions' => 'RegraAcao.codigo_cliente = GrupoEconomico.codigo_cliente',
            ],
        ];

        try {
            $actionRule = $this->Usuario
                ->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->first();

            return (empty($actionRule)) ? null : $actionRule;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
