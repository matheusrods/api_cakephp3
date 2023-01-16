<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SistemaValidarTokenTipo Model
 *
 * @method \App\Model\Entity\SistemaValidarTokenTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SistemaValidarTokenTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class SistemaValidarTokenTipoTable extends Table
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

        $this->setTable('sistema_validar_token_tipo');
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
            ->integer('codigo_validar_token_tipo')
            ->requirePresence('codigo_validar_token_tipo', 'create')
            ->notEmptyString('codigo_validar_token_tipo');

        $validator
            ->integer('tempo_expiracao')
            ->requirePresence('tempo_expiracao', 'create')
            ->notEmptyString('tempo_expiracao');

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
