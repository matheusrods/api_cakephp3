<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioEnderecoTipo Model
 *
 * @method \App\Model\Entity\UsuarioEnderecoTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioEnderecoTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioEnderecoTipoTable extends AppTable
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

        $this->setTable('usuario_endereco_tipo');
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
            ->integer('tipo')
            ->allowEmptyString('tipo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 256)
            ->allowEmptyString('descricao');

        return $validator;
    }
}
