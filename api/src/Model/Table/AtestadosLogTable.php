<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AtestadosLog Model
 *
 * @method \App\Model\Entity\AtestadosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AtestadosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AtestadosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AtestadosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AtestadosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AtestadosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class AtestadosLogTable extends Table
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

        $this->setTable('atestados_log');
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
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_atestado')
            ->requirePresence('codigo_atestado', 'create')
            ->notEmptyString('codigo_atestado');

        $validator
            ->integer('codigo_cliente_funcionario')
            ->requirePresence('codigo_cliente_funcionario', 'create')
            ->notEmptyString('codigo_cliente_funcionario');

        $validator
            ->integer('codigo_medico')
            ->allowEmptyString('codigo_medico');

        $validator
            ->date('data_afastamento_periodo')
            ->allowEmptyDate('data_afastamento_periodo');

        $validator
            ->date('data_retorno_periodo')
            ->allowEmptyDate('data_retorno_periodo');

        $validator
            ->scalar('afastamento_em_horas')
            ->maxLength('afastamento_em_horas', 10)
            ->allowEmptyString('afastamento_em_horas');

        $validator
            ->date('data_afastamento_hr')
            ->allowEmptyDate('data_afastamento_hr');

        $validator
            ->time('hora_afastamento')
            ->allowEmptyTime('hora_afastamento');

        $validator
            ->time('hora_retorno')
            ->allowEmptyTime('hora_retorno');

        $validator
            ->integer('codigo_motivo_esocial')
            ->allowEmptyString('codigo_motivo_esocial');

        $validator
            ->integer('codigo_motivo_licenca')
            ->allowEmptyString('codigo_motivo_licenca');

        $validator
            ->scalar('restricao')
            ->maxLength('restricao', 1)
            ->allowEmptyString('restricao');

        $validator
            ->integer('codigo_cid_contestato')
            ->allowEmptyString('codigo_cid_contestato');

        $validator
            ->allowEmptyString('imprimi_cid_atestado');

        $validator
            ->allowEmptyString('acidente_trajeto');

        $validator
            ->scalar('endereco')
            ->maxLength('endereco', 1)
            ->allowEmptyString('endereco');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 1)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 1)
            ->allowEmptyString('complemento');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 1)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 1)
            ->allowEmptyString('cep');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_estado')
            ->allowEmptyString('codigo_estado');

        $validator
            ->integer('codigo_cidade')
            ->allowEmptyString('codigo_cidade');

        $validator
            ->integer('codigo_tipo_local_atendimento')
            ->allowEmptyString('codigo_tipo_local_atendimento');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->integer('afastamento_em_dias')
            ->allowEmptyString('afastamento_em_dias');

        $validator
            ->boolean('habilita_afastamento_em_horas')
            ->allowEmptyString('habilita_afastamento_em_horas');

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 2)
            ->allowEmptyString('estado');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->allowEmptyString('cidade');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
