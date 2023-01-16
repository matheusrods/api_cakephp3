<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ArrtpariGhe Model
 *
 * @method \App\Model\Entity\ArrtpariGhe get($primaryKey, $options = [])
 * @method \App\Model\Entity\ArrtpariGhe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ArrtpariGhe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpariGhe|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtpariGhe saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ArrtpariGhe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpariGhe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ArrtpariGhe findOrCreate($search, callable $callback = null, $options = [])
 */
class ArrtpariGheTable extends Table
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

        $this->setTable('arrtpari_ghe');
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
            ->integer('codigo_ghe')
            ->requirePresence('codigo_ghe', 'create')
            ->notEmptyString('codigo_ghe');

        $validator
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

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

    public function getRiscosImpactos($codigo_ghe)
    {
        $query = $this->find()
            ->select([
                // 'ArrtpariGhe.codigo',
                // 'ArrtpariGhe.codigo_ghe',

                // 'ArrtpaRi.codigo',
                // 'ArrtpaRi.codigo_arrt_pa',
                // 'ArrtpaRi.codigo_risco_impacto',

                'codigo'                => 'RiscoImpacto.codigo',
                'descricao'             => 'RHHealth.dbo.ufn_decode_utf8_string(RiscoImpacto.descricao)',
                'codigo_perigo_aspecto' => 'RiscoImpacto.codigo_perigo_aspecto',
                'codigo_metodo_tipo'    => 'RiscoImpacto.codigo_metodo_tipo',
                'codigo_arrtpa_ri'      => 'ArrtpariGhe.codigo_arrtpa_ri',
                'codigo_risco_impacto_tipo' => 'RiscoImpacto.codigo_risco_impacto_tipo',
                'risco_impacto_tipo'      => 'RiscoImpactoTipo.descricao',
            ])
            ->join([
                'ArrtpaRi' => [
                    'table' => 'arrtpa_ri',
                    'type' => 'INNER',
                    'conditions' => 'ArrtpaRi.codigo = ArrtpariGhe.codigo_arrtpa_ri',
                ],
                'RiscosImpactos' => [
                    'table' => 'riscos_impactos',
                    'alias' => 'RiscoImpacto',
                    'type' => 'INNER',
                    'conditions' => 'RiscoImpacto.codigo = ArrtpaRi.codigo_risco_impacto',
                ],

                'RiscosImpactosTipo' => [
                    'table' => 'riscos_impactos_tipo',
                    'alias' => 'RiscoImpactoTipo',
                    'type'  => 'INNER',
                    'conditions' => "RiscoImpacto.codigo_risco_impacto_tipo = RiscoImpactoTipo.codigo",
                ]

            ])
            ->where(['ArrtpariGhe.codigo_ghe' => $codigo_ghe]);

        // debug($query->sql()); die;

        return $query->all()->toArray();
    }
}
