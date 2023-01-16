<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MotivosConclusaoParcial Model
 *
 * @method \App\Model\Entity\MotivosConclusaoParcial get($primaryKey, $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MotivosConclusaoParcial findOrCreate($search, callable $callback = null, $options = [])
 */
class MotivosConclusaoParcialTable extends Table
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

        $this->setTable('motivos_conclusao_parcial');
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
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
