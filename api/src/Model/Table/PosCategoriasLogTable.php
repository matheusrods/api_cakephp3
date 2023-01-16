<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosCategoriasLog Model
 *
 * @method \App\Model\Entity\PosCategoriasLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosCategoriasLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosCategoriasLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosCategoriasLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosCategoriasLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosCategoriasLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosCategoriasLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosCategoriasLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosCategoriasLogTable extends Table
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

        $this->setTable('pos_categorias_log');
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
            ->integer('codigo_pos_categoria')
            ->requirePresence('codigo_pos_categoria', 'create')
            ->notEmptyString('codigo_pos_categoria');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->requirePresence('codigo_pos_ferramenta', 'create')
            ->notEmptyString('codigo_pos_ferramenta');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->allowEmptyString('acao_sistema');

        return $validator;
    }
}
