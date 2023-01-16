<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresLog Model
 *
 * @method \App\Model\Entity\FornecedoresLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresLog findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresLogTable extends Table
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

        $this->setTable('fornecedores_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_inclusao' => 'new',
                    'data_alteracao' => 'always',
                ]
            ]
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
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->scalar('codigo_documento')
            ->maxLength('codigo_documento', 14)
            ->allowEmptyString('codigo_documento');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

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
            ->allowEmptyString('ativo');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->allowEmptyString('razao_social');

        $validator
            ->scalar('responsavel_administrativo')
            ->maxLength('responsavel_administrativo', 255)
            ->allowEmptyString('responsavel_administrativo');

        $validator
            ->integer('tipo_atendimento')
            ->allowEmptyString('tipo_atendimento');

        $validator
            ->integer('acesso_portal')
            ->allowEmptyString('acesso_portal');

        $validator
            ->integer('exames_local_unico')
            ->allowEmptyString('exames_local_unico');

        $validator
            ->scalar('numero_banco')
            ->maxLength('numero_banco', 255)
            ->allowEmptyString('numero_banco');

        $validator
            ->scalar('tipo_conta')
            ->maxLength('tipo_conta', 255)
            ->allowEmptyString('tipo_conta');

        $validator
            ->scalar('favorecido')
            ->maxLength('favorecido', 255)
            ->allowEmptyString('favorecido');

        $validator
            ->scalar('agencia')
            ->maxLength('agencia', 255)
            ->allowEmptyString('agencia');

        $validator
            ->scalar('numero_conta')
            ->maxLength('numero_conta', 255)
            ->allowEmptyString('numero_conta');

        $validator
            ->integer('interno')
            ->allowEmptyString('interno');

        $validator
            ->scalar('atendente')
            ->maxLength('atendente', 255)
            ->allowEmptyString('atendente');

        $validator
            ->scalar('data_contratacao')
            ->allowEmptyString('data_contratacao');

        $validator
            ->scalar('data_cancelamento')
            ->allowEmptyString('data_cancelamento');

        $validator
            ->integer('contrato_ativo')
            ->allowEmptyString('contrato_ativo');

        $validator
            ->integer('codigo_soc')
            ->allowEmptyString('codigo_soc');

        $validator
            ->integer('dia_do_pagamento')
            ->allowEmptyString('dia_do_pagamento');

        $validator
            ->integer('disponivel_para_todas_as_empresas')
            ->allowEmptyString('disponivel_para_todas_as_empresas');

        $validator
            ->scalar('especialidades')
            ->maxLength('especialidades', 255)
            ->allowEmptyString('especialidades');

        $validator
            ->scalar('tipo_de_pagamento')
            ->maxLength('tipo_de_pagamento', 255)
            ->allowEmptyString('tipo_de_pagamento');

        $validator
            ->scalar('texto_livre')
            ->allowEmptyString('texto_livre');

        $validator
            ->allowEmptyString('codigo_status_contrato_fornecedor');

        $validator
            ->scalar('responsavel_tecnico')
            ->maxLength('responsavel_tecnico', 255)
            ->allowEmptyString('responsavel_tecnico');

        $validator
            ->integer('codigo_conselho_profissional')
            ->allowEmptyString('codigo_conselho_profissional');

        $validator
            ->scalar('responsavel_tecnico_conselho_numero')
            ->maxLength('responsavel_tecnico_conselho_numero', 25)
            ->allowEmptyString('responsavel_tecnico_conselho_numero');

        $validator
            ->scalar('responsavel_tecnico_conselho_uf')
            ->maxLength('responsavel_tecnico_conselho_uf', 2)
            ->allowEmptyString('responsavel_tecnico_conselho_uf');

        $validator
            ->boolean('utiliza_sistema_agendamento')
            ->allowEmptyString('utiliza_sistema_agendamento');

        $validator
            ->scalar('tipo_unidade')
            ->maxLength('tipo_unidade', 1)
            ->allowEmptyString('tipo_unidade');

        $validator
            ->integer('codigo_fornecedor_fiscal')
            ->allowEmptyString('codigo_fornecedor_fiscal');

        $validator
            ->scalar('codigo_documento_real')
            ->maxLength('codigo_documento_real', 14)
            ->allowEmptyString('codigo_documento_real');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->allowEmptyString('cobranca_boleto');

        $validator
            ->scalar('cnes')
            ->maxLength('cnes', 10)
            ->allowEmptyString('cnes');

        $validator
            ->integer('codigo_fornecedor_recebedor')
            ->allowEmptyString('codigo_fornecedor_recebedor');

        $validator
            ->integer('prestador_qualificado')
            ->allowEmptyString('prestador_qualificado');

        $validator
            ->integer('modalidade_pagamento')
            ->allowEmptyString('modalidade_pagamento');

        $validator
            ->integer('faturamento_dias')
            ->allowEmptyString('faturamento_dias');

        $validator
            ->scalar('faturamento_detalhes')
            ->allowEmptyString('faturamento_detalhes');

        $validator
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 250)
            ->allowEmptyString('caminho_arquivo');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        $validator
            ->scalar('dia_pagamento')
            ->maxLength('dia_pagamento', 4)
            ->allowEmptyString('dia_pagamento');

        $validator
            ->allowEmptyString('ambulatorio');

        $validator
            ->integer('ambulatorio_codigo_cliente')
            ->allowEmptyString('ambulatorio_codigo_cliente');

        $validator
            ->boolean('prestador_particular')
            ->notEmptyString('prestador_particular');

        return $validator;
    }
}
