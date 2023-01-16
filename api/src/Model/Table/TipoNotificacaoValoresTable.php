<?php
namespace App\Model\Table;


use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

use App\Utils\EncodingUtil;
use Cake\Log\Log;

/**
 * TipoNotificacaoValores Model
 *
 * @method \App\Model\Entity\TipoNotificacaoValore get($primaryKey, $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacaoValore findOrCreate($search, callable $callback = null, $options = [])
 */

class TipoNotificacaoValoresTable extends AppTable
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

        $this->setTable('tipo_notificacao_valores');
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
            ->boolean('campo_funcionario')
            ->allowEmptyString('campo_funcionario');

        $validator
            ->boolean('campo_cliente')
            ->allowEmptyString('campo_cliente');

        $validator
            ->boolean('campo_fornecedor')
            ->allowEmptyString('campo_fornecedor');

        $validator
            ->integer('codigo_tipo_notificacao')
            ->requirePresence('codigo_tipo_notificacao', 'create')
            ->notEmptyString('codigo_tipo_notificacao');

        $validator
            ->integer('codigo_pedidos_exames')
            ->allowEmptyString('codigo_pedidos_exames');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('vias_aso')
            ->allowEmptyString('vias_aso');

        return $validator;
    }


    public function tiposRelatoriosPorPedido($codigo_pedidos_exames)
    {
        $fields = array(
            'codigo_tipo_notificacao'=> 'TipoNotificacaoValores.codigo_tipo_notificacao',
            'tipo' => 'TipoNotificacao.tipo',
            'qtd_vias' => 'TipoNotificacaoValores.vias_aso'
        );

        $joins  = array(
            array(
                'table' => 'tipo_notificacao',
                'alias' => 'TipoNotificacao',
                'type' => 'INNER',
                'conditions' => 'TipoNotificacao.codigo = TipoNotificacaoValores.codigo_tipo_notificacao',
            )
        );

        $conditions = array(
            "TipoNotificacaoValores.codigo_pedidos_exames" => $codigo_pedidos_exames
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        return $dados;
    }

    public function deleteAll($codigo_pedido_exame)
    {
        $query = "DELETE FROM RHHealth.dbo.tipo_notificacao_valores WHERE codigo_pedidos_exames = {$codigo_pedido_exame}";
        $conn = ConnectionManager::get('default');
        $conn->execute($query);

        return true;

    }
}
