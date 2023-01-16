<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PdaConfigRegraCondicao Model
 *
 * @method \App\Model\Entity\PdaConfigRegraCondicao get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaConfigRegraCondicao findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaConfigRegraCondicaoTable extends Table
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

        $this->setTable('pda_config_regra_condicao');
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pda_config_regra')
            ->requirePresence('codigo_pda_config_regra', 'create')
            ->notEmptyString('codigo_pda_config_regra');

        $validator
            ->integer('codigo_pda_tema_condicao')
            ->allowEmptyString('codigo_pda_tema_condicao');

        $validator
            ->integer('codigo_pda_tema_acoes')
            ->allowEmptyString('codigo_pda_tema_acoes');

        $validator
            ->integer('codigo_cliente_opco')
            ->allowEmptyString('codigo_cliente_opco');

        $validator
            ->integer('codigo_cliente_bu')
            ->allowEmptyString('codigo_cliente_bu');

        $validator
            ->integer('codigo_acoes_melhorias_status')
            ->allowEmptyString('codigo_acoes_melhorias_status');

        $validator
            ->integer('codigo_origem_ferramentas')
            ->allowEmptyString('codigo_origem_ferramentas');

        $validator
            ->integer('codigo_pos_criticidade')
            ->allowEmptyString('codigo_pos_criticidade');

        $validator
            ->integer('qtd_dias')
            ->allowEmptyString('qtd_dias');

        $validator
            ->scalar('condicao')
            ->maxLength('condicao', 10)
            ->allowEmptyString('condicao');

        $validator
            ->allowEmptyString('ativo');

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
