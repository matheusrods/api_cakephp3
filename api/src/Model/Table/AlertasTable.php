<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Alertas Model
 *
 * @method \App\Model\Entity\Alerta get($primaryKey, $options = [])
 * @method \App\Model\Entity\Alerta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Alerta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Alerta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Alerta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Alerta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Alerta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Alerta findOrCreate($search, callable $callback = null, $options = [])
 */
class AlertasTable extends Table
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

        $this->setTable('alertas');
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
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 170)
            ->allowEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_tratamento')
            ->allowEmptyDateTime('data_tratamento');

        $validator
            ->scalar('observacao_tratamento')
            ->maxLength('observacao_tratamento', 170)
            ->allowEmptyString('observacao_tratamento');

        $validator
            ->integer('codigo_usuario_tratamento')
            ->allowEmptyString('codigo_usuario_tratamento');

        $validator
            ->boolean('email_agendados')
            ->allowEmptyString('email_agendados');

        $validator
            ->boolean('sms_agendados')
            ->allowEmptyString('sms_agendados');

        $validator
            ->integer('codigo_alerta_tipo')
            ->requirePresence('codigo_alerta_tipo', 'create')
            ->notEmptyString('codigo_alerta_tipo');

        $validator
            ->scalar('descricao_email')
            ->allowEmptyString('descricao_email');

        $validator
            ->scalar('model')
            ->maxLength('model', 30)
            ->allowEmptyString('model');

        $validator
            ->integer('foreign_key')
            ->allowEmptyString('foreign_key');

        $validator
            ->boolean('ws_agendados')
            ->notEmptyString('ws_agendados');

        $validator
            ->scalar('assunto')
            ->maxLength('assunto', 255)
            ->allowEmptyString('assunto');

        return $validator;
    }
}
