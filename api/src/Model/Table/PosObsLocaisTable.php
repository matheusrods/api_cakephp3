<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;

/**
 * PosObsLocais Model
 *
 * @method \App\Model\Entity\PosObsLocai get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsLocai newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsLocai[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocai|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocai saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsLocai patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocai[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsLocai findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsLocaisTable extends Table
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

        $this->setTable('pos_obs_locais');
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_local_empresa')
            ->allowEmptyString('codigo_local_empresa');

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


    public function salvarPorCodigoObservacao(int $codigo_observacao = null, array $data = [])
    {

        if (empty($codigo_observacao)) {
            throw new \Exception("Código de Observação não fornecido para Salvar Locais", 1);
        }

        try {

            $findData = $this->find()->where([
                'codigo_pos_obs_observacao' => $codigo_observacao
            ])->first();

            // atualiza
            if (!empty($findData)) {
                return $this->salvar($findData['codigo'], $data);
            }

            // insere
            return $this->salvar(null, $data);
        } catch (\Exception $e) {

            throw $e;
        }
    }

    public function conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_bu IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_bu' => $codigoClienteBuConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    public function conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_opco IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_opco' => $codigoClienteOpcoConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
