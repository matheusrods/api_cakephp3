<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArrtpaRi Model
 *
 * @method \App\Model\Entity\ArrtpaRi get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArrtpaRi newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArrtpaRi[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpaRi|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtpaRi saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtpaRi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpaRi[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpaRi findOrCreate($search, callable $callback = null, $options = [])
 */
class ArrtpaRiTable extends Table
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

        $this->setTable('arrtpa_ri');
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
            ->integer('codigo_arrt_pa')
            ->allowEmptyString('codigo_arrt_pa');

        $validator
            ->integer('codigo_risco_impacto')
            ->allowEmptyString('codigo_risco_impacto');

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
            ->integer('e_hazop')
            ->allowEmptyString('e_hazop');

        $validator
            ->scalar('acao_requerida')
            ->maxLength('acao_requerida', 255)
            ->allowEmptyString('acao_requerida');

        $validator
            ->integer('codigo_agente_risco')
            ->allowEmptyString('codigo_agente_risco');

        return $validator;
    }

    public function getRiscosImpactos($perigos_aspectos)
    {
        //Condições para retornar os dados dos funcionários
        $fields = array(
            'codigo'  => 'Arrtpari.codigo',
            'codigo_arrt_pa'     => 'Arrtpari.codigo_arrt_pa',
            'codigo_risco_impacto'    => 'Arrtpari.codigo_risco_impacto',
            'codigo_risco_impacto_tipo'    => 'RiscosImpactos.codigo_risco_impacto_tipo',
            'risco_impacto_tipo'    => 'RiscosImpactosTipo.descricao',

        );

        $joins  = array(
            array(
                'table' => 'riscos_impactos',
                'alias' => 'RiscosImpactos',
                'type'  => 'INNER',
                'conditions' => "Arrtpari.codigo_risco_impacto = RiscosImpactos.codigo and Arrtpari.codigo_arrt_pa = {$perigos_aspectos}",
            ),
            array(
                'table' => 'riscos_impactos_tipo',
                'alias' => 'RiscosImpactosTipo',
                'type'  => 'INNER',
                'conditions' => "RiscosImpactos.codigo_risco_impacto_tipo = RiscosImpactosTipo.codigo",
            )
        );

        //Condições para retornar os dados dos pacientes
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->toArray();

        return $dados;
    }
}
