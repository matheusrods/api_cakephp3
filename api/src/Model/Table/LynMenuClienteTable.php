<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LynMenuCliente Model
 *
 * @method \App\Model\Entity\LynMenuCliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\LynMenuCliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LynMenuCliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LynMenuCliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMenuCliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMenuCliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LynMenuCliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LynMenuCliente findOrCreate($search, callable $callback = null, $options = [])
 */
class LynMenuClienteTable extends Table
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

        $this->setTable('lyn_menu_cliente');
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
            ->integer('codigo_lyn_menu')
            ->allowEmptyString('codigo_lyn_menu');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * [getMenuCliente pega o menu do cliente que pode ter sido desabilitado]
     * @param  [type] $codigo_cliente [description]
     * @param  [type] $codigo_sistema [codigo do sistema padrao lyn]
     * @return [type]                 [description]
     */
    public function getMenuCliente($codigo_cliente, $codigo_sistema = 1)
    {

        //monta o select
        $select = [
            'codigo' => 'LynMenu.codigo',
            'descricao' => 'LynMenu.descricao' 
        ];

        //monta os joins
        $joins = [
            [
                'table' => 'lyn_menu',
                'alias' => 'LynMenu',
                'type' => 'INNER',
                'conditions' => 'LynMenu.codigo = LynMenuCliente.codigo_lyn_menu'
            ],
        ];

        //pega os dados do menu
        $dados = $this->find()
            ->select($select)
            ->join($joins)
            ->where([
                'LynMenuCliente.codigo_cliente' => $codigo_cliente,
                'LynMenu.codigo_sistema' => $codigo_sistema
            ])
            ->hydrate(false)
            ->all();

        //verifica se tem dados de retorno da query
        if(!empty($dados)) {
            $dados = $dados->toArray();
        }//fim dados

        return $dados;

    }//fim getMenuCliente


}
