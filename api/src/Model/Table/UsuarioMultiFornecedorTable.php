<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;

/**
 * UsuarioMultiFornecedor Model
 *
 * @method \App\Model\Entity\UsuarioMultiFornecedor get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioMultiFornecedor findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioMultiFornecedorTable extends AppTable
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

        $this->setTable('usuario_multi_fornecedor');
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
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

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
