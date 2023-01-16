<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * EnderecoBairro Model
 *
 * @method \App\Model\Entity\EnderecoBairro get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoBairro newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\EnderecoBairro[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoBairro|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoBairro saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoBairro patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoBairro[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoBairro findOrCreate($search, callable $callback = null, $options = [])
 */
class EnderecoBairroTable extends AppTable
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

        $this->setTable('endereco_bairro');
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
            ->integer('codigo_endereco_cidade')
            ->requirePresence('codigo_endereco_cidade', 'create')
            ->notEmptyString('codigo_endereco_cidade');

        $validator
            ->integer('codigo_correio')
            ->allowEmptyString('codigo_correio');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_endereco_distrito')
            ->allowEmptyString('codigo_endereco_distrito');

        $validator
            ->scalar('abreviacao')
            ->maxLength('abreviacao', 128)
            ->allowEmptyString('abreviacao');

        return $validator;
    }
}
