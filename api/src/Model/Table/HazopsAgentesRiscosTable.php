<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HazopsAgentesRiscos Model
 *
 * @method \App\Model\Entity\HazopsAgentesRisco get($primaryKey, $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HazopsAgentesRisco findOrCreate($search, callable $callback = null, $options = [])
 */
class HazopsAgentesRiscosTable extends Table
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

        $this->setTable('hazops_agentes_riscos');
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
            ->scalar('causa')
            ->maxLength('causa', 255)
            ->allowEmptyString('causa');

        $validator
            ->scalar('consequencia')
            ->maxLength('consequencia', 255)
            ->allowEmptyString('consequencia');

        $validator
            ->scalar('codigo_severidade')
            ->maxLength('codigo_severidade', 255)
            ->allowEmptyString('codigo_severidade');

        $validator
            ->scalar('codigo_dimensao_risco')
            ->maxLength('codigo_dimensao_risco', 255)
            ->allowEmptyString('codigo_dimensao_risco');

        $validator
            ->integer('interacao_pessoas')
            ->allowEmptyString('interacao_pessoas');

        $validator
            ->integer('historico_ocorrencia')
            ->allowEmptyString('historico_ocorrencia');

        $validator
            ->scalar('controle_fisico')
            ->maxLength('controle_fisico', 255)
            ->allowEmptyString('controle_fisico');

        $validator
            ->scalar('controle_fisico_opcao')
            ->maxLength('controle_fisico_opcao', 255)
            ->allowEmptyString('controle_fisico_opcao');

        $validator
            ->integer('controle_dependencia_comportamental')
            ->allowEmptyString('controle_dependencia_comportamental');

        $validator
            ->scalar('controle_dependencia')
            ->maxLength('controle_dependencia', 255)
            ->allowEmptyString('controle_dependencia');

        $validator
            ->scalar('hazop_level_risco')
            ->maxLength('hazop_level_risco', 255)
            ->allowEmptyString('hazop_level_risco');

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
            ->integer('codigo_arrtpa_ri')
            ->allowEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_hazop_keyword_tipo')
            ->allowEmptyString('codigo_hazop_keyword_tipo');

        return $validator;
    }
}
