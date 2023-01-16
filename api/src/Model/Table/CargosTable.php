<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cargos Model
 *
 * @property \App\Model\Table\ClientesSetoresTable&\Cake\ORM\Association\BelongsToMany $ClientesSetores
 *
 * @method \App\Model\Entity\Cargo get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cargo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cargo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cargo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cargo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cargo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cargo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cargo findOrCreate($search, callable $callback = null, $options = [])
 */
class CargosTable extends Table
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

        $this->setTable('cargos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('ClientesSetores', [
            'foreignKey' => 'cargo_id',
            'targetForeignKey' => 'clientes_setore_id',
            'joinTable' => 'clientes_setores_cargos',
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
            ->maxLength('descricao', 60)
            ->allowEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->scalar('codigo_cbo')
            ->maxLength('codigo_cbo', 7)
            ->allowEmptyString('codigo_cbo');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('codigo_rh')
            ->maxLength('codigo_rh', 60)
            ->allowEmptyString('codigo_rh');

        $validator
            ->scalar('descricao_ppp')
            ->maxLength('descricao_ppp', 100)
            ->allowEmptyString('descricao_ppp');

        $validator
            ->scalar('requisito')
            ->allowEmptyString('requisito');

        $validator
            ->scalar('descricao_cargo')
            ->allowEmptyString('descricao_cargo');

        $validator
            ->scalar('educacao')
            ->allowEmptyString('educacao');

        $validator
            ->scalar('treinamento')
            ->allowEmptyString('treinamento');

        $validator
            ->scalar('habilidades')
            ->allowEmptyString('habilidades');

        $validator
            ->scalar('experiencias')
            ->allowEmptyString('experiencias');

        $validator
            ->scalar('descricao_local')
            ->allowEmptyString('descricao_local');

        $validator
            ->scalar('observacao_aso')
            ->allowEmptyString('observacao_aso');

        $validator
            ->scalar('material_utilizado')
            ->allowEmptyString('material_utilizado');

        $validator
            ->scalar('mobiliario_utilizado')
            ->allowEmptyString('mobiliario_utilizado');

        $validator
            ->scalar('local_trabalho')
            ->allowEmptyString('local_trabalho');

        $validator
            ->integer('codigo_gfip')
            ->allowEmptyString('codigo_gfip');

        $validator
            ->integer('codigo_funcao')
            ->allowEmptyString('codigo_funcao');

        $validator
            ->integer('codigo_cargo_similar')
            ->allowEmptyString('codigo_cargo_similar');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getCargosPorEmpresas($codigoCliente)
    {
        //Condições para retornar os dados das empresas
        $fields = array(
            'codigo'         => 'Cargos.codigo',
            'descricao'      => 'Cargos.descricao',
            'codigo_cliente' => 'Cargos.codigo_cliente',
        );

        $conditions = "Cargos.codigo_cliente = " . $codigoCliente . "";
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->limit(20);

        return $dados;
    }

    public function getCargo($codigoCargo, $codigoCliente)
    {
        $query = $this->find()
            ->select([
                'codigo',
                'descricao',
                'codigo_cliente',
                'codigo_setor' => 'ClientesSetoresCargos.codigo_setor',
            ])
            ->join([
                'ClientesSetoresCargos' => [
                    'table' => 'clientes_setores_cargos',
                    'type' => 'LEFT',
                    'conditions' => [
                        'ClientesSetoresCargos.codigo_cargo = Cargos.codigo'
                    ],
                ],
            ])
            ->where(["Cargos.codigo" => $codigoCargo, "Cargos.codigo_cliente" => $codigoCliente]);

        // debug($query->sql()); die;
        return $query->first();
    }

    public function getCargosPorEmpresaSetor($codigoCliente, $codigoSetor)
    {
        $query = $this->find()
            ->select([
                'codigo'                                => 'distinct Cargos.codigo',
                'descricao'                             => 'Cargos.descricao',
                'codigo_cliente'                        => 'Cargos.codigo_cliente',
                'codigo_setor'                          => 'ClientesSetoresCargos.codigo_setor',
                // 'codigo_clientes_setores_cargos'     => 'ClientesSetoresCargos.codigo',
            ])
            ->join([
                'ClientesSetoresCargos' => [
                    'table' => 'clientes_setores_cargos',
                    'type' => 'INNER',
                    'conditions' => ['ClientesSetoresCargos.codigo_cargo = Cargos.codigo'],
                ],
            ])
            ->where([
                "ClientesSetoresCargos.codigo_cliente" => $codigoCliente,
                "ClientesSetoresCargos.codigo_setor" => $codigoSetor
            ]);

        // debug($query->sql()); die;

        return $query->all()->toArray();
    }
}
