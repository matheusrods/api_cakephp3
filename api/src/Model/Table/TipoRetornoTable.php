<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TipoRetorno Model
 *
 * @method \App\Model\Entity\TipoRetorno get($primaryKey, $options = [])
 * @method \App\Model\Entity\TipoRetorno newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TipoRetorno[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TipoRetorno|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoRetorno saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoRetorno patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TipoRetorno[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TipoRetorno findOrCreate($search, callable $callback = null, $options = [])
 */
class TipoRetornoTable extends Table
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

        $this->setTable('tipo_retorno');
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
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao')
            ->add('descricao', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('cliente')
            ->allowEmptyString('cliente');

        $validator
            ->boolean('proprietario')
            ->allowEmptyString('proprietario');

        $validator
            ->boolean('profissional')
            ->allowEmptyString('profissional');

        $validator
            ->boolean('usuario_interno')
            ->allowEmptyString('usuario_interno');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['descricao']));

        return $rules;
    }
}
