<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Exception;

/**
 * PosConfiguracoes Model
 *
 * @method \App\Model\Entity\PosConfiguraco get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosConfiguraco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosConfiguraco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosConfiguraco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosConfiguraco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosConfiguraco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosConfiguraco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosConfiguraco findOrCreate($search, callable $callback = null, $options = [])
 */
class PosConfiguracoesTable extends Table
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

        $this->setTable('pos_configuracoes');
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
            ->integer('codigo_pos_ferramenta')
            ->requirePresence('codigo_pos_ferramenta', 'create')
            ->notEmptyString('codigo_pos_ferramenta');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('chave')
            ->maxLength('chave', 255)
            ->requirePresence('chave', 'create')
            ->notEmptyString('chave');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('valor')
            ->maxLength('valor', 255)
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->scalar('observacao')
            ->maxLength('observacao', 255)
            ->allowEmptyString('observacao');

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

    public function buscarConfig($codigo_ferramenta, $codigo_cliente, $chave)
    {
        try {
            $data = $this->find()
                ->select([
                    'codigo',
                    'codigo_ferramenta' => 'codigo_pos_ferramenta',
                    'codigo_cliente',
                    'chave',
                    'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
                    'valor',
                    'observacao',
                    "PosSwtRegras.dias_registro_retroativo"
                ])
                ->join(
                    array(
                        'table' => 'pos_swt_regras',
                        'alias' => 'PosSwtRegras',
                        'type' => 'LEFT',
                        'conditions' => array('PosSwtRegras.codigo_cliente = PosConfiguracoes.codigo_cliente')
                    )
                )
                ->where([
                    'PosConfiguracoes.codigo_pos_ferramenta' => $codigo_ferramenta,
                    'PosConfiguracoes.codigo_cliente'        => $codigo_cliente,
                    'PosConfiguracoes.chave'                 => $chave,
                    'PosConfiguracoes.ativo'                 => 1
                ])
                ->first();

            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
