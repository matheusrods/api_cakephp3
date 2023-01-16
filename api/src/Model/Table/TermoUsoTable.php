<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TermoUso Model
 *
 * @method \App\Model\Entity\TermoUso get($primaryKey, $options = [])
 * @method \App\Model\Entity\TermoUso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TermoUso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TermoUso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TermoUso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TermoUso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TermoUso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TermoUso findOrCreate($search, callable $callback = null, $options = [])
 */
class TermoUsoTable extends Table
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

        $this->setTable('termo_uso');
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
            ->scalar('link_html')
            ->maxLength('link_html', 250)
            ->allowEmptyString('link_html');

        $validator
            ->scalar('link_pdf')
            ->maxLength('link_pdf', 250)
            ->allowEmptyString('link_pdf');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 250)
            ->allowEmptyString('nome');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }
}
