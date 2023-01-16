<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Datasource\ConnectionManager;

/**
 * CentroResultado Model
 *
 * @method \App\Model\Entity\CentroResultado get($primaryKey, $options = [])
 * @method \App\Model\Entity\CentroResultado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CentroResultado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CentroResultado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CentroResultado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CentroResultado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CentroResultado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CentroResultado findOrCreate($search, callable $callback = null, $options = [])
 */
class CentroResultadoTable extends Table
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

        $this->setTable('centro_resultado');
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
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente_matriz')
            ->allowEmptyString('codigo_cliente_matriz');

        $validator
            ->integer('codigo_cliente_alocacao')
            ->allowEmptyString('codigo_cliente_alocacao');

        $validator
            ->scalar('codigo_externo_centro_resultado')
            ->maxLength('codigo_externo_centro_resultado', 255)
            ->allowEmptyString('codigo_externo_centro_resultado');

        $validator
            ->scalar('nome_centro_resultado')
            ->maxLength('nome_centro_resultado', 255)
            ->allowEmptyString('nome_centro_resultado');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

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

    public function conciliarDuplicatasClienteDs($codigoClienteDsConciliador, $arrCodigosDuplicatas)
    {


        try {

            $this->addBehavior('Loggable');


            $this->find()
                ->where(['codigo_cliente_ds IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_ds' => $codigoClienteDsConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
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
