<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
//use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Model\Table\AppTable;


/**
 * ClienteContato Model
 *
 * @method \App\Model\Entity\ClienteContato get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteContato newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteContato[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteContato|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteContato saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteContato patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteContato[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteContato findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteContatoTable extends AppTable
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

        $this->setTable('cliente_contato');
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->requirePresence('codigo_tipo_retorno', 'create')
            ->notEmptyString('codigo_tipo_retorno');

        $validator
            ->allowEmptyString('ddi');

        $validator
            ->allowEmptyString('ddd');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->allowEmptyString('ramal');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
