<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RiscosImpactos Model
 *
 * @method \App\Model\Entity\RiscosImpacto get($primaryKey, $options = [])
 * @method \App\Model\Entity\RiscosImpacto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RiscosImpacto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpacto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpacto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosImpacto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpacto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosImpacto findOrCreate($search, callable $callback = null, $options = [])
 */
class RiscosImpactosTable extends Table
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

        $this->setTable('riscos_impactos');
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
            ->integer('codigo_perigo_aspecto')
            ->requirePresence('codigo_perigo_aspecto', 'create')
            ->notEmptyString('codigo_perigo_aspecto');

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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_risco_impacto_tipo')
            ->allowEmptyString('codigo_risco_impacto_tipo');

        return $validator;
    }

    public function getRiscosImpactos($codigo_risco_impacto=null)
    {

        if(is_null($codigo_risco_impacto)) {
            return null;
        }

        //campos do select
        $fields = [
            'codigo',
            'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
        ];

        $conditions = "RiscosImpactos.codigo = {$codigo_risco_impacto}";
        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->hydrate(false)
            ->first();

        return $dados;
    }
}
