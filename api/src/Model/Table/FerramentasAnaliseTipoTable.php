<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FerramentasAnaliseTipo Model
 *
 * @method \App\Model\Entity\FerramentasAnaliseTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FerramentasAnaliseTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class FerramentasAnaliseTipoTable extends Table
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

        $this->setTable('ferramentas_analise_tipo');
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
            ->integer('codigo_metodo_tipo')
            ->requirePresence('codigo_metodo_tipo', 'create')
            ->notEmptyString('codigo_metodo_tipo');

        $validator
            ->scalar('ferramenta_analise_categoria')
            ->maxLength('ferramenta_analise_categoria', 255)
            ->allowEmptyString('ferramenta_analise_categoria');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('versao')
            ->maxLength('versao', 255)
            ->allowEmptyString('versao');

        $validator
            ->scalar('ferramenta_analise_form')
            ->allowEmptyString('ferramenta_analise_form');

        $validator
            ->scalar('ferramenta_analise_regras')
            ->allowEmptyString('ferramenta_analise_regras');

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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        return $validator;
    }
}
