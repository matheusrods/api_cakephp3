<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FornecedoresTipoAvaliacao Model
 *
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresTipoAvaliacao findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresTipoAvaliacaoTable extends AppTable
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

        $this->setTable('fornecedores_tipo_avaliacao');
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
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        return $validator;
    }
}
