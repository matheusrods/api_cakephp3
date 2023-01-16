<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResultadoCovidLog Model
 *
 * @method \App\Model\Entity\ResultadoCovidLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovidLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ResultadoCovidLogTable extends Table
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

        $this->setTable('resultado_covid_log');
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
            ->integer('codigo_resultado_covid')
            ->allowEmptyString('codigo_resultado_covid');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_grupo_covid')
            ->allowEmptyString('codigo_grupo_covid');

        $validator
            ->integer('passaporte')
            ->allowEmptyString('passaporte');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->allowEmptyString('acao_sistema');

        return $validator;
    }
}
