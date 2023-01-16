<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Aprho Model
 *
 * @method \App\Model\Entity\Aprho get($primaryKey, $options = [])
 * @method \App\Model\Entity\Aprho newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Aprho[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Aprho|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Aprho saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Aprho patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Aprho[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Aprho findOrCreate($search, callable $callback = null, $options = [])
 */
class AprhoTable extends Table
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

        $this->setTable('aprho');
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
            ->integer('codigo_qualificacao')
            ->requirePresence('codigo_qualificacao', 'create')
            ->notEmptyString('codigo_qualificacao');

        $validator
            ->decimal('exposicao_duracao')
            ->allowEmptyString('exposicao_duracao');

        $validator
            ->scalar('exposicao_frequencia')
            ->maxLength('exposicao_frequencia', 255)
            ->allowEmptyString('exposicao_frequencia');

        $validator
            ->integer('codigo_fonte_geradora_exposicao_tipo')
            ->allowEmptyString('codigo_fonte_geradora_exposicao_tipo');

        $validator
            ->integer('codigo_fonte_geradora_exposicao')
            ->allowEmptyString('codigo_fonte_geradora_exposicao');

        $validator
            ->integer('codigo_agente_exposicao')
            ->allowEmptyString('codigo_agente_exposicao');

        $validator
            ->integer('qualitativo')
            ->allowEmptyString('qualitativo');

        $validator
            ->scalar('relevancia')
            ->maxLength('relevancia', 255)
            ->allowEmptyString('relevancia');

        $validator
            ->scalar('aceitabilidade')
            ->maxLength('aceitabilidade', 255)
            ->allowEmptyString('aceitabilidade');

        $validator
            ->integer('conselho_tecnico_resultado')
            ->allowEmptyString('conselho_tecnico_resultado');

        $validator
            ->dateTime('conselho_tecnico_agenda')
            ->allowEmptyDateTime('conselho_tecnico_agenda');

        $validator
            ->scalar('conselho_tecnico_descricao')
            ->maxLength('conselho_tecnico_descricao', 255)
            ->allowEmptyString('conselho_tecnico_descricao');

        $validator
            ->integer('codigo_conselho_tecnico_arquivo')
            ->allowEmptyString('codigo_conselho_tecnico_arquivo');

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
            ->integer('medicoes_resultado')
            ->allowEmptyString('medicoes_resultado');

        $validator
            ->dateTime('medicoes_agenda')
            ->allowEmptyDateTime('medicoes_agenda');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->allowEmptyString('arquivo_url');


        return $validator;
    }
}
