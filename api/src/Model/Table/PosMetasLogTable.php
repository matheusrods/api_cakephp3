<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PosMetasLog Model
 *
 * @method \App\Model\Entity\PosMetasLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosMetasLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosMetasLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosMetasLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosMetasLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosMetasLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosMetasLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosMetasLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosMetasLogTable extends Table
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

        $this->setTable('pos_metas_log');
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
            ->integer('codigo_pos_meta')
            ->allowEmptyString('codigo_pos_meta');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->allowEmptyString('codigo_pos_ferramenta');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

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
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->allowEmptyString('acao_sistema');

        $validator
            ->integer('dia_follow_up')
            ->allowEmptyString('dia_follow_up');

        return $validator;
    }
}
