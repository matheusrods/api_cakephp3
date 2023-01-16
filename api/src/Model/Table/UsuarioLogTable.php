<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuarioLog Model
 *
 * @property \App\Model\Table\UsuarioDadosTable&\Cake\ORM\Association\BelongsTo $UsuarioDados
 *
 * @method \App\Model\Entity\UsuarioLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioLog findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioLogTable extends Table
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

        $this->setTable('usuario_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsTo('UsuarioDados', [
            'foreignKey' => 'usuario_dados_id',
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
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('apelido')
            ->maxLength('apelido', 256)
            ->requirePresence('apelido', 'create')
            ->notEmptyString('apelido');

        $validator
            ->scalar('senha')
            ->maxLength('senha', 172)
            ->allowEmptyString('senha');

        //comentado para tentativa de aprovacao na loja da apple
        // $validator
        //     ->email('email')
        //     ->allowEmptyString('email');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_uperfil')
            ->allowEmptyString('codigo_uperfil');

        $validator
            ->boolean('alerta_portal')
            ->notEmptyString('alerta_portal');

        $validator
            ->boolean('alerta_email')
            ->notEmptyString('alerta_email');

        $validator
            ->boolean('alerta_sms')
            ->notEmptyString('alerta_sms');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 12)
            ->allowEmptyString('celular');

        $validator
            ->scalar('token')
            ->maxLength('token', 172)
            ->allowEmptyString('token');

        $validator
            ->integer('fuso_horario')
            ->allowEmptyString('fuso_horario');

        $validator
            ->boolean('horario_verao')
            ->allowEmptyString('horario_verao');

        $validator
            ->integer('cracha')
            ->allowEmptyString('cracha');

        $validator
            ->dateTime('data_senha_expiracao')
            ->allowEmptyDateTime('data_senha_expiracao');

        $validator
            ->boolean('admin')
            ->allowEmptyString('admin');

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
            ->integer('codigo_usuario_pai')
            ->allowEmptyString('codigo_usuario_pai');

        $validator
            ->allowEmptyString('restringe_base_cnpj');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->allowEmptyString('codigo_departamento');

        $validator
            ->integer('codigo_filial')
            ->allowEmptyString('codigo_filial');

        $validator
            ->integer('codigo_proposta_credenciamento')
            ->allowEmptyString('codigo_proposta_credenciamento');

        $validator
            ->integer('codigo_fornecedor')
            ->allowEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->boolean('usuario_multi_empresa')
            ->allowEmptyString('usuario_multi_empresa');

        $validator
            ->integer('codigo_corretora')
            ->allowEmptyString('codigo_corretora');

        // $validator
        //     ->integer('alerta_sm_usuario')
        //     ->allowEmptyString('alerta_sm_usuario');

        $validator
            ->allowEmptyString('acao_sistema');

        // $validator
        //     ->integer('codigo_gestor')
        //     ->allowEmptyString('codigo_gestor');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        // $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usuario_dados_id'], 'UsuarioDados'));

        return $rules;
    }
}
