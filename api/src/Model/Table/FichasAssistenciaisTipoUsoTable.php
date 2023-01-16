<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FichasAssistenciaisTipoUso Model
 *
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisTipoUso findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasAssistenciaisTipoUsoTable extends Table
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

        $this->setTable('fichas_assistenciais_tipo_uso');
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
            ->maxLength('descricao', 20)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        return $validator;
    }
}
