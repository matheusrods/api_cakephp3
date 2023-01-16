<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RiscosImpactosSelecionadosDescricoes Model
 *
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico get($primaryKey, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosDescrico findOrCreate($search, callable $callback = null, $options = [])
 */
class RiscosImpactosSelecionadosDescricoesTable extends Table
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

        $this->setTable('riscos_impactos_selecionados_descricoes');
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
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->scalar('descricao_risco')
            ->maxLength('descricao_risco', 255)
            ->allowEmptyString('descricao_risco');

        $validator
            ->scalar('descricao_exposicao')
            ->maxLength('descricao_exposicao', 255)
            ->allowEmptyString('descricao_exposicao');

        $validator
            ->integer('pessoas_expostas')
            ->allowEmptyString('pessoas_expostas');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }
}
