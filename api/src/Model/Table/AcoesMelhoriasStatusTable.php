<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasStatus Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasStatusTable extends Table
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

        $this->setTable('acoes_melhorias_status');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria_status');
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
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('cor')
            ->maxLength('cor', 255)
            ->requirePresence('cor', 'create')
            ->notEmptyString('cor');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

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

    public function getAll()
    {
        try {
            $data = $this->find()
                ->all()
                ->toArray();

            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }
}
