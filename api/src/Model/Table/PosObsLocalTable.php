<?php

namespace App\Model\Table;

use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Error;

/**
 * PosObsLocal Model
 *
 * @method \App\Model\Entity\PosObsLocal get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsLocal newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsLocal[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocal|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocal saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocal patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocal[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocal findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsLocalTable extends Table
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

        $this->setTable('pos_obs_local');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->scalar('descricao')
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function buscarLocaisPeloCodigoCliente(int $codigo_cliente = null)
    {
        if (!$codigo_cliente) {
            throw new Error('Código cliente não informado');
        }

        $locais = $this->find()
            ->select([
                'codigo',
                'codigo_empresa',
                'codigo_cliente',
                'descricao' => 'RHHealth.publico.Ufn_decode_utf8_string(descricao)'
            ])
            ->where([
                'codigo_cliente' => $codigo_cliente,
                'ativo'          => 1
            ])
            ->all();

        return $locais;
    }
}
