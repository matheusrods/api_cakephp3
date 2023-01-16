<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * PosObsAnexos Model
 *
 * @method \App\Model\Entity\PosObsAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsAnexosTable extends Table
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

        $this->setTable('pos_obs_anexos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasOne('PosAnexos', [
            'bindingKey' => 'codigo_pos_anexo',
            'foreignKey' => 'codigo',
            'joinTable' => 'pos_anexos',
            'propertyName' => 'anexos',
        ]);

        $this->setEntityClass('App\Model\Entity\PosObsAnexo');
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_pos_anexo')
            ->requirePresence('codigo_pos_anexo', 'create')
            ->notEmptyString('codigo_pos_anexo');

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

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function obterPorCodigoObservacao(int $codigo_observacao = null)
    {
        try {
            $query = $this->find()
                ->select([
                    'codigo'       => 'PosObsAnexos.codigo',
                    'arquivo'      => 'PosAnexos.arquivo_url',
                ])
                ->where([
                    'PosObsAnexos.codigo_pos_obs_observacao' => $codigo_observacao,
                    'PosObsAnexos.ativo'                     => 1,
                    'PosAnexos.ativo'                        => 1,
                ])->contain([
                    'PosAnexos' => [
                        'queryBuilder' => function ($q) {
                            return $q->where(['PosAnexos.codigo_pos_ferramenta' => 3]);
                        }
                    ]
                ])
                ->toArray();

            return $query;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
