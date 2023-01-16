<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FontesGeradorasExposicao Model
 *
 * @method \App\Model\Entity\FontesGeradorasExposicao get($primaryKey, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicao findOrCreate($search, callable $callback = null, $options = [])
 */
class FontesGeradorasExposicaoTable extends Table
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

        $this->setTable('fontes_geradoras_exposicao');
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
            ->integer('codigo_risco_impacto_selecionado_descricao')
            ->requirePresence('codigo_risco_impacto_selecionado_descricao', 'create')
            ->notEmptyString('codigo_risco_impacto_selecionado_descricao');

        $validator
            ->integer('codigo_fonte_geradora_exposicao_tipo')
            ->requirePresence('codigo_fonte_geradora_exposicao_tipo', 'create')
            ->notEmptyString('codigo_fonte_geradora_exposicao_tipo');

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

        return $validator;
    }
}
