<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * PrePedidosExames Model
 *
 * @method \App\Model\Entity\PrePedidosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\PrePedidosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PrePedidosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PrePedidosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PrePedidosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PrePedidosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PrePedidosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PrePedidosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class PrePedidosExamesTable extends AppTable
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

        $this->setTable('pre_pedidos_exames');
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
            ->integer('codigo_cliente_funcionario')
            ->allowEmptyString('codigo_cliente_funcionario');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->scalar('endereco_parametro_busca')
            ->maxLength('endereco_parametro_busca', 255)
            ->allowEmptyString('endereco_parametro_busca');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->integer('exame_admissional')
            ->allowEmptyString('exame_admissional');

        $validator
            ->integer('exame_periodico')
            ->allowEmptyString('exame_periodico');

        $validator
            ->integer('exame_demissional')
            ->allowEmptyString('exame_demissional');

        $validator
            ->integer('exame_retorno')
            ->allowEmptyString('exame_retorno');

        $validator
            ->integer('exame_mudanca')
            ->allowEmptyString('exame_mudanca');

        $validator
            ->integer('qualidade_vida')
            ->allowEmptyString('qualidade_vida');

        $validator
            ->integer('codigo_status_pedidos_exames')
            ->allowEmptyString('codigo_status_pedidos_exames');

        $validator
            ->integer('portador_deficiencia')
            ->allowEmptyString('portador_deficiencia');

        $validator
            ->integer('pontual')
            ->allowEmptyString('pontual');

        $validator
            ->dateTime('data_notificacao')
            ->allowEmptyDateTime('data_notificacao');

        $validator
            ->date('data_solicitacao')
            ->allowEmptyDate('data_solicitacao');

        $validator
            ->integer('codigo_pedidos_lote')
            ->allowEmptyString('codigo_pedidos_lote');

        $validator
            ->integer('em_emissao')
            ->allowEmptyString('em_emissao');

        $validator
            ->integer('codigo_motivo_cancelamento')
            ->allowEmptyString('codigo_motivo_cancelamento');

        $validator
            ->integer('codigo_func_setor_cargo')
            ->allowEmptyString('codigo_func_setor_cargo');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('exame_monitoracao')
            ->allowEmptyString('exame_monitoracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * [set_pedidos_exames metodo para criar os pedidos de exames]
     * @param [type] $dados [description]
     */
    public function set_pre_pedidos_exames($dados) 
    {

        $registro = $this->newEntity($dados);
        if (!$this->save($registro)) {
            $error[] = $registro->getValidationErrors();
            return $error;
        }

        $codigo_pedido_exame = isset($registro->codigo) ? $registro->codigo : null;

        return $codigo_pedido_exame;

    }//fim set_pedidos_exames

}
