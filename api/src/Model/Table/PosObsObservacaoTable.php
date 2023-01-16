<?php

namespace App\Model\Table;

use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsObservacao Model
 *
 * @method \App\Model\Entity\PosObsObservacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsObservacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsObservacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsObservacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsObservacao findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsObservacaoTable extends Table
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

        $this->setTable('pos_obs_observacao');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasOne('PosObsParticipantes', [
            'bindingKey' => 'codigo',
            'foreignKey' => 'codigo_pos_obs_observacao',
            'joinTable' => 'pos_obs_participantes',
            'propertyName' => 'participantes',
        ]);

        $this->hasOne('PosObsAnexos', [
            'bindingKey' => 'codigo',
            'foreignKey' => 'codigo_pos_obs_observacao',
            'joinTable' => 'pos_obs_anexos',
            'propertyName' => 'anexos',
        ]);

        $this->hasOne('PosObsRiscos', [
            'bindingKey' => 'codigo',
            'foreignKey' => 'codigo_pos_obs_observacao',
            'joinTable' => 'pos_obs_riscos',
            'propertyName' => 'riscos',
        ]);

        $this->hasOne('Cliente', [
            'bindingKey' => 'codigo_cliente',
            'foreignKey' => 'codigo',
            'joinTable' => 'cliente',
            'propertyName' => 'localidade',
        ]);

        $this->hasOne('UsuarioResponsavel', [
            'className' => 'Usuario',
            'bindingKey' => 'codigo_usuario_status',
            'foreignKey' => 'codigo',
            'joinTable' => 'usuario',
            'propertyName' => 'responsavel',
        ]);

        $this->hasOne('AcoesMelhoriasStatus', [
            'bindingKey' => 'codigo_status',
            'foreignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_status',
            'propertyName' => 'status',
        ]);

        $this->hasOne('AcoesMelhoriasStatus', [
            'bindingKey' => 'codigo_status_responsavel',
            'foreignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_status',
            'propertyName' => 'status_responsavel',
        ]);

        $this->hasOne('PosObsLocal', [
            'bindingKey' => 'codigo_pos_obs_local',
            'foreignKey' => 'codigo',
            'joinTable' => 'pos_obs_local',
            'propertyName' => 'local_observacao',
        ]);

        $this->belongsTo('PosObsLocais', [
            'bindingKey' => 'codigo_pos_obs_observacao',
            'foreignKey' => 'codigo',
            'joinTable' => 'pos_obs_locais',
            'propertyName' => 'pos_obs_locais',
        ]);

        $this->belongsToMany('AcoesMelhorias')
            ->setThrough('PosObsObservacaoAcaoMelhoria')
            ->setForeignKey('obs_observacao_id')
            ->setTargetForeignKey('acoes_melhoria_id');

        $this->setEntityClass('App\Model\Entity\PosObsObservacao');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_unidade')
            ->allowEmptyString('codigo_unidade');

        $validator
            ->integer('codigo_pos_categoria')
            ->requirePresence('codigo_pos_categoria', 'create')
            ->notEmptyString('codigo_pos_categoria');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('codigo_status')
            ->requirePresence('codigo_status', 'create')
            ->notEmptyString('codigo_status');

        $validator
            ->integer('codigo_status_responsavel')
            ->requirePresence('codigo_status_responsavel', 'create')
            ->notEmptyString('codigo_status_responsavel');

        $validator
            ->dateTime('data_observacao')
            ->requirePresence('data_observacao', 'create')
            ->notEmptyDateTime('data_observacao');

        $validator
            ->scalar('descricao_usuario_observou')
            ->requirePresence('descricao_usuario_observou', 'create')
            ->notEmptyString('descricao_usuario_observou');

        $validator
            ->scalar('descricao_usuario_acao')
            ->allowEmptyString('descricao_usuario_acao');

        $validator
            ->scalar('descricao_usuario_sugestao')
            ->allowEmptyString('descricao_usuario_sugestao');

        $validator
            ->integer('codigo_local_descricao')
            ->allowEmptyString('codigo_local_descricao');

        $validator
            ->integer('codigo_pos_obs_local')
            ->requirePresence('codigo_pos_obs_local', 'create')
            ->notEmptyString('codigo_pos_obs_local');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->integer('observacao_criticidade')
            ->allowEmptyString('observacao_criticidade');

        $validator
            ->integer('qualidade_avaliacao')
            ->allowEmptyString('qualidade_avaliacao');

        $validator
            ->scalar('qualidade_descricao_complemento')
            ->allowEmptyString('qualidade_descricao_complemento');

        $validator
            ->scalar('qualidade_descricao_participantes_tratativa')
            ->allowEmptyString('qualidade_descricao_participantes_tratativa');

        $validator
            ->integer('codigo_usuario_status')
            ->requirePresence('codigo_usuario_status', 'create')
            ->notEmptyString('codigo_usuario_status');

        $validator
            ->dateTime('data_status')
            ->requirePresence('data_status', 'create')
            ->notEmptyDateTime('data_status');

        $validator
            ->scalar('descricao_status')
            ->allowEmptyString('descricao_status');

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
     * [getDadosSwtObs metodo para buscar os dados da area do observador swt]
     * @param  [type] $codigo_unidade [description]
     * @return [type]                 [description]
     */
    public function getDadosSwtObs($codigo_unidade)
    {
        $dados = array();

        try {
            //verifica se tem a configuracao do formulário
            // $this->GruposEconomicos = TableRegistry::get('GruposEconomicos');
            // $codigo_cliente_matriz = $this->GruposEconomicos->getCampoPorClienteRqe("codigo_cliente", $codigo_unidade);

            $fields = array(
                'codigo' => 'Categoria.codigo',
                'classificacao' => 'Categoria.descricao',
                'cor' => 'Categoria.cor',
                'categoria' => 'RiscoImpacto.descricao',
                'total' => 'COUNT(PosObsObservacao.codigo)',
            );

            $joins = array(
                array(
                    'table' => 'pos_categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array('PosObsObservacao.codigo_pos_categoria = Categoria.codigo'),
                ),
                array(
                    'table' => 'pos_obs_riscos',
                    'alias' => 'PosObsRisco',
                    'type' => 'INNER',
                    'conditions' => array('PosObsObservacao.codigo = PosObsRisco.codigo_pos_obs_observacao'),
                ),
                array(
                    'table' => 'riscos_impactos',
                    'alias' => 'RiscoImpacto',
                    'type' => 'INNER',
                    'conditions' => array('PosObsRisco.codigo_arrtpa_ri = RiscoImpacto.codigo'),
                )
            );

            $conditions = [];
            $conditions['PosObsObservacao.codigo_cliente'] = $codigo_unidade;
            $conditions[] = array(
                'PosObsObservacao.codigo_status <> 6'
            );

            $group = array(
                'Categoria.codigo',
                'Categoria.descricao',
                'Categoria.cor',
                'RiscoImpacto.descricao',
            );

            $order = array('Categoria.descricao ASC');

            $dados_obs = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->group($group)
                ->order($order)
            // ->sql();
                ->hydrate(false)
                ->all()
                ->toArray();

            if (!empty($dados_obs)) {
                $dados['classificacao'] = [];

                foreach ($dados_obs as $key => $dado) {

                    $dado['classificacao'] = utf8_decode($dado['classificacao']);
                    $dado['categoria'] = utf8_decode($dado['categoria']);
                    $dados_obs[$key]['classificacao'] = $dado['classificacao'];
                    $dados_obs[$key]['categoria'] = $dado['categoria'];

                    $dados['classificacao'][$dado['codigo']]['codigo'] = $dado['codigo'];
                    $dados['classificacao'][$dado['codigo']]['descricao'] = $dado['classificacao'];
                    $dados['classificacao'][$dado['codigo']]['cor'] = $dado['cor'];

                    if (!isset($dados['classificacao'][$dado['codigo']]['total'])) {
                        $dados['classificacao'][$dado['codigo']]['total'] = 0;
                    }

                    $dados['classificacao'][$dado['codigo']]['total'] += $dado['total'];
                }

                $dados['classificacao'] = array_values($dados['classificacao']);
                $dados['categoria'] = $dados_obs;
            } //fim dados_obs
        } catch (\Exception $e) {
            $dados['error'] = $e->getMessage();
        }

        // debug($dados);exit;

        return $dados;
    } //fim getDadosSwtObs($codigo_unidade)

    public function getObservationById(int $observationId)
    {
        $data = $this->find()
            ->where([
                'codigo' => $observationId,
                'ativo' => 1
            ])
            ->enableHydration(false)
            ->first();

        return $data;
    }

    public function getObservationsWithoutAnalysis()
    {
        $data = $this->find()
            ->where([
                'PosObsObservacao.status' => 1,
                'PosObsObservacao.codigo_status' => 1,
                'PosObsObservacao.ativo' => 1
            ])
            ->contain(['PosObsLocais'])
            ->enableHydration(false)
            ->toArray();

        return $data;
    }
}
