<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasLog Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasLog findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasLogTable extends Table
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

        $this->setTable('acoes_melhorias_log');
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
            ->integer('codigo_acao_melhoria')
            ->requirePresence('codigo_acao_melhoria', 'create')
            ->notEmptyString('codigo_acao_melhoria');

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->integer('codigo_origem_ferramenta')
            ->requirePresence('codigo_origem_ferramenta', 'create')
            ->notEmptyString('codigo_origem_ferramenta');

        $validator
            ->scalar('formulario_resposta')
            ->requirePresence('formulario_resposta', 'create')
            ->notEmptyString('formulario_resposta');

        $validator
            ->integer('codigo_cliente_observacao')
            ->requirePresence('codigo_cliente_observacao', 'create')
            ->notEmptyString('codigo_cliente_observacao');

        $validator
            ->integer('codigo_usuario_identificador')
            ->requirePresence('codigo_usuario_identificador', 'create')
            ->notEmptyString('codigo_usuario_identificador');

        $validator
            ->integer('codigo_usuario_responsavel')
            ->allowEmptyString('codigo_usuario_responsavel');

        $validator
            ->integer('codigo_acoes_melhorias_tipo')
            ->requirePresence('codigo_acoes_melhorias_tipo', 'create')
            ->notEmptyString('codigo_acoes_melhorias_tipo');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->requirePresence('codigo_acoes_melhorias_status', 'create')
            ->notEmptyString('codigo_acoes_melhorias_status');

        // $validator
        //     ->date('prazo')
        //     ->allowEmptyDate('prazo');

        $validator
            ->scalar('descricao_desvio')
            ->requirePresence('descricao_desvio', 'create')
            ->notEmptyString('descricao_desvio');

        $validator
            ->scalar('descricao_acao')
            ->requirePresence('descricao_acao', 'create')
            ->notEmptyString('descricao_acao');

        $validator
            ->scalar('descricao_local_acao')
            ->requirePresence('descricao_local_acao', 'create')
            ->notEmptyString('descricao_local_acao');

        // $validator
        //     ->dateTime('data_conclusao')
        //     ->allowEmptyDateTime('data_conclusao');

        $validator
            ->scalar('conclusao_observacao')
            ->allowEmptyString('conclusao_observacao');

        $validator
            ->boolean('analise_implementacao_valida')
            ->allowEmptyString('analise_implementacao_valida');

        $validator
            ->scalar('descricao_analise_implementacao')
            ->allowEmptyString('descricao_analise_implementacao');

        $validator
            ->integer('codigo_usuario_responsavel_analise_implementacao')
            ->allowEmptyString('codigo_usuario_responsavel_analise_implementacao');

        // $validator
        //     ->dateTime('data_analise_implementacao')
        //     ->allowEmptyDateTime('data_analise_implementacao');

        $validator
            ->boolean('analise_eficacia_valida')
            ->allowEmptyString('analise_eficacia_valida');

        $validator
            ->scalar('descricao_analise_eficacia')
            ->allowEmptyString('descricao_analise_eficacia');

        $validator
            ->integer('codigo_usuario_responsavel_analise_eficacia')
            ->allowEmptyString('codigo_usuario_responsavel_analise_eficacia');

        // $validator
        //     ->dateTime('data_analise_eficacia')
        //     ->allowEmptyDateTime('data_analise_eficacia');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        $validator
            ->integer('codigo_pos_criticidade')
            ->notEmptyString('codigo_pos_criticidade');

        $validator
            ->boolean('abrangente')
            ->allowEmptyString('abrangente');

        $validator
            ->boolean('necessario_abrangencia')
            ->allowEmptyString('necessario_abrangencia');

        $validator
            ->boolean('necessario_eficacia')
            ->allowEmptyString('necessario_eficacia');

        $validator
            ->boolean('necessario_implementacao')
            ->allowEmptyString('necessario_implementacao');

        return $validator;
    }
}
