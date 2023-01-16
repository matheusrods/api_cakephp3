<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;

/**
 * UsuarioFornecedorPermissoes Model
 *
 * @method \App\Model\Entity\UsuarioFornecedorPermisso get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFornecedorPermisso findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioFornecedorPermissoesTable extends AppTable
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

        $this->setTable('usuario_fornecedor_permissoes');
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
            ->integer('codigo_fornecedor_permissoes')
            ->requirePresence('codigo_fornecedor_permissoes', 'create')
            ->notEmptyString('codigo_fornecedor_permissoes');

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
