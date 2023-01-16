<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Setores Model
 *
 * @property \App\Model\Table\CaracteristicasTable&\Cake\ORM\Association\BelongsToMany $Caracteristicas
 *
 * @method \App\Model\Entity\Setore get($primaryKey, $options = [])
 * @method \App\Model\Entity\Setore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Setore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Setore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Setore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Setore findOrCreate($search, callable $callback = null, $options = [])
 */
class SetoresTable extends Table
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

        $this->setTable('setores');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Caracteristicas', [
            'foreignKey' => 'setore_id',
            'targetForeignKey' => 'caracteristica_id',
            'joinTable' => 'setores_caracteristicas',
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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('codigo_rh')
            ->maxLength('codigo_rh', 50)
            ->allowEmptyString('codigo_rh');

        $validator
            ->scalar('descricao_setor')
            ->allowEmptyString('descricao_setor');

        $validator
            ->scalar('observacao_aso')
            ->allowEmptyString('observacao_aso');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getSetor($codigoSetor, $codigoCliente)
    {
        $query = $this->find()
            ->select([
                'codigo',
                'descricao',
                'codigo_cliente',
            ])
            ->where(["codigo" => $codigoSetor, "codigo_cliente" => $codigoCliente]);

        // debug($query->sql()); die;
        return $query->first();
    }

    public function getSetoresPorEmpresas($codigoCliente)
    {
        //Condições para retornar os dados das empresas
        $fields = array(
            'codigo'         => 'Setores.codigo',
            'descricao'      => 'Setores.descricao',
            'codigo_cliente' => 'Setores.codigo_cliente',
        );

        $conditions = "Setores.codigo_cliente = " . $codigoCliente . "";
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->limit(20);

        return $dados;
    }
}
