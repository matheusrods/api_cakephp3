<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Ghe Model
 *
 * @method \App\Model\Entity\Ghe get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ghe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Ghe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ghe|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ghe saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ghe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ghe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ghe findOrCreate($search, callable $callback = null, $options = [])
 */
class GheTable extends Table
{
    const AGENTE_ABAIXO_DA_TOLERANCIA = 'Agente abaixo da tolerância';
    const AGENTE_ACIMA_DO_NIVEL_DE_ACAO = 'Agente acima do nível de ação';
    const AGENTE_ACIMA_DA_TOLERANCIA = 'Agente acima da tolerância';

    const GHE_APROVADA = 'GHE Aprovada';
    const DIVERGENCIA_APONTADA = 'Divergência Apontada';
    const AVALIAR_GHE = 'Avaliar GHE';

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('ghe');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasMany('ArrtpariGhe', [
            'foreignKey' => 'codigo_ghe'
        ]);

        $this->hasMany('CscGhe', [
            'foreignKey' => 'codigo_ghe'
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->scalar('chave_ghe')
            ->maxLength('chave_ghe', 255)
            ->requirePresence('chave_ghe', 'create')
            ->notEmptyString('chave_ghe');

        $validator
            ->scalar('aprho_parecer_tecnico')
            ->maxLength('aprho_parecer_tecnico', 50)
            ->requirePresence('aprho_parecer_tecnico', 'create')
            ->notEmptyString('aprho_parecer_tecnico');

        $validator
            ->integer('codigo_gerente_operacoes')
            ->allowEmptyString('codigo_gerente_operacoes');

        $validator
            ->integer('codigo_ehs_tecnico')
            ->allowEmptyString('codigo_ehs_tecnico');

        $validator
            ->integer('codigo_operador')
            ->allowEmptyString('codigo_operador');

        $validator
            ->dateTime('aprovacao_gerente_operacoes')
            ->allowEmptyDateTime('aprovacao_gerente_operacoes');

        $validator
            ->dateTime('aprovacao_ehs_tecnico')
            ->allowEmptyDateTime('aprovacao_ehs_tecnico');

        $validator
            ->scalar('descricao_divergencia')
            ->maxLength('descricao_divergencia', 255)
            ->allowEmptyString('descricao_divergencia');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_divergencia')
            ->allowEmptyDateTime('data_divergencia');

        $validator
            ->integer('divergencia_apontada_por')
            ->allowEmptyString('divergencia_apontada_por');

        return $validator;
    }

    public function getById(int $codigo_ghe)
    {
        $query = $this->find()
            ->select([
                'codigo',
                'codigo_cliente',
                'chave_ghe',
                'aprho_parecer_tecnico',
                'ghe_status',
                'codigo_gerente_operacoes',
                'codigo_ehs_tecnico',
                'codigo_operador',
                'aprovacao_gerente_operacoes',
                'aprovacao_ehs_tecnico',
                'descricao_divergencia',
                'data_divergencia',
                'divergencia_apontada_por',
                // 'codigo_usuario_inclusao',
                // 'codigo_usuario_alteracao',
                // 'data_inclusao',
                // 'data_alteracao',
                // 'ativo',
            ])
            // ->contain(['ArrtpariGhe', 'CscGhe'])
            ->where(["codigo" => $codigo_ghe, "ativo" => 1]);

        // debug($query->sql()); die;
        $dados = $query->first();

        // consulta os riscos impactos do GHE
        if ($dados) {
            $dados['riscos_impactos'] = TableRegistry::getTableLocator()->get('ArrtpariGhe')->getRiscosImpactos($codigo_ghe);
        }

        // consulta os cargos do GHE
        if ($dados) {
            $dados['cargos'] = TableRegistry::getTableLocator()->get('CscGhe')->getCargos($codigo_ghe);
        }

        return $dados;
    }

    public function getAll($codigo_cliente = null)
    {
        $where = ["ativo" => 1];

        if (!is_null($codigo_cliente)) {
            $where[] = ["codigo_cliente" => $codigo_cliente];
        }

        $query = $this->find()
            ->select([
                'codigo',
                'codigo_cliente',
                'chave_ghe',
                'aprho_parecer_tecnico',
                'ghe_status',
                'codigo_gerente_operacoes',
                'codigo_ehs_tecnico',
                'codigo_operador',
                'aprovacao_gerente_operacoes',
                'aprovacao_ehs_tecnico',
                'descricao_divergencia',
                'data_divergencia',
                'divergencia_apontada_por',
                // 'codigo_usuario_inclusao',
                // 'codigo_usuario_alteracao',
                // 'data_inclusao',
                // 'data_alteracao',
                // 'ativo',
            ])
            ->where($where);

        // debug($query->sql()); die;
        $dados = $query->all()->toArray();

        foreach ($dados as $key => $dado) {
            $dado['riscos_impactos'] = TableRegistry::getTableLocator()->get('ArrtpariGhe')->getRiscosImpactos($dado['codigo']);
            $dado['cargos'] = TableRegistry::getTableLocator()->get('CscGhe')->getCargos($dado['codigo']);
        }

        return $dados;
    }
}
