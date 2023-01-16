<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RiscosImpactosSelecionadosAnexos Model
 *
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpactosSelecionadosAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class RiscosImpactosSelecionadosAnexosTable extends Table
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

        $this->setTable('riscos_impactos_selecionados_anexos');
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
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->allowEmptyString('arquivo_url');

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

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }
}
