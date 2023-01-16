<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasAssociada Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasAssociada get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAssociada findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasAssociadasTable extends Table
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

        $this->setTable('acoes_melhorias_associadas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria_associada');
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
            ->integer('codigo_acao_melhoria_principal')
            ->requirePresence('codigo_acao_melhoria_principal', 'create')
            ->notEmptyString('codigo_acao_melhoria_principal');

        $validator
            ->integer('codigo_acao_melhoria_relacionada')
            ->requirePresence('codigo_acao_melhoria_relacionada', 'create')
            ->notEmptyString('codigo_acao_melhoria_relacionada');

        $validator
            ->integer('tipo_relacao')
            ->requirePresence('tipo_relacao', 'create')
            ->notEmptyString('tipo_relacao');

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
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }
}
