<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Qualificacao Model
 *
 * @method \App\Model\Entity\Qualificacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\Qualificacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Qualificacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Qualificacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Qualificacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Qualificacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Qualificacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Qualificacao findOrCreate($search, callable $callback = null, $options = [])
 */
class QualificacaoTable extends Table
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

        $this->setTable('qualificacao');
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
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('qualitativo')
            ->allowEmptyString('qualitativo');

        $validator
            ->integer('quantitativo')
            ->allowEmptyString('quantitativo');

        $validator
            ->integer('codigo_metodo_tipo')
            ->allowEmptyString('codigo_metodo_tipo');

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
            ->integer('acidente_registrado')
            ->allowEmptyString('acidente_registrado');

        $validator
            ->scalar('partes_afetadas')
            ->allowEmptyString('partes_afetadas');

        $validator
            ->scalar('resultado_ponderacao')
            ->maxLength('resultado_ponderacao', 255)
            ->allowEmptyString('resultado_ponderacao');

        return $validator;
    }
}
