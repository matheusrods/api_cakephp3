<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedorPermissoes Model
 *
 * @property \App\Model\Table\UsuarioTable&\Cake\ORM\Association\BelongsToMany $Usuario
 *
 * @method \App\Model\Entity\FornecedorPermisso get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedorPermisso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedorPermisso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorPermisso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedorPermisso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedorPermisso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorPermisso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedorPermisso findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedorPermissoesTable extends Table
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

        $this->setTable('fornecedor_permissoes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Usuario', [
            'foreignKey' => 'fornecedor_permisso_id',
            'targetForeignKey' => 'usuario_id',
            'joinTable' => 'usuario_fornecedor_permissoes'
        ]);
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
            ->maxLength('descricao', 250)
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

        return $validator;
    }
}
