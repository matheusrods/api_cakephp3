<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FontesGeradorasExposicaoTipo Model
 *
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FontesGeradorasExposicaoTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class FontesGeradorasExposicaoTipoTable extends Table
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

        $this->setTable('fontes_geradoras_exposicao_tipo');
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
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

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
