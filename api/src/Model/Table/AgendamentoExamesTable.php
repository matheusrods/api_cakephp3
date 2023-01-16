<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * AgendamentoExames Model
 *
 * @method \App\Model\Entity\AgendamentoExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\AgendamentoExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AgendamentoExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AgendamentoExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgendamentoExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgendamentoExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AgendamentoExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AgendamentoExame findOrCreate($search, callable $callback = null, $options = [])
 */
class AgendamentoExamesTable extends AppTable
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

        $this->setTable('agendamento_exames');
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
            ->requirePresence('hora', 'create')
            ->notEmptyString('hora');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_itens_pedidos_exames')
            ->requirePresence('codigo_itens_pedidos_exames', 'create')
            ->notEmptyString('codigo_itens_pedidos_exames');

        $validator
            ->date('data')
            ->requirePresence('data', 'create')
            ->notEmptyDate('data');

        $validator
            ->integer('codigo_lista_de_preco_produto_servico')
            ->allowEmptyString('codigo_lista_de_preco_produto_servico');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_medico')
            ->allowEmptyString('codigo_medico');

        return $validator;
    }

    /**
     * [retorna_agenda description]
     *
     * metodo para pegar o que ja tem agendado para o fornecedor
     *
     * @param  [type] $codigo_fornecedor [description]
     * @param  [type] $codigo_servico    [description]
     * @param  [type] $data_inicial      [description]
     * @param  [type] $data_final        [description]
     * @return [type]                    [description]
     */
    public function retorna_agenda($codigo_fornecedor, $codigo_servico, $data_inicial, $data_final)
    {

        $fields = array(
            'AgendamentoExames.data',
            'AgendamentoExames.hora',
            'AgendamentoExames.codigo_fornecedor',
            'Exame.codigo_servico',
            'ItemPedidoExame.codigo'
        );

        $joins  = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo = AgendamentoExames.codigo_itens_pedidos_exames',
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'Exame.codigo = ItemPedidoExame.codigo_exame',
            )
        );

        $order = array('AgendamentoExames.data ASC', 'AgendamentoExames.hora ASC');

        $group = array(
            'AgendamentoExames.data',
            'AgendamentoExames.hora',
            'AgendamentoExames.codigo_fornecedor',
            'Exame.codigo_servico',
            'ItemPedidoExame.codigo'
        );

        $conditions = array(
            'AgendamentoExames.codigo_fornecedor' => $codigo_fornecedor,
            'AgendamentoExames.ativo' => '1',
            "AgendamentoExames.data BETWEEN '".$data_inicial."' AND '".$data_final."'"
        );


        if($codigo_servico) {
            $conditions['Exame.codigo_servico'] = $codigo_servico;
        } else {
            $conditions['AgendamentoExames.codigo_lista_de_preco_produto_servico'] = $codigo_servico;
        }

        //executa
        $dados = $this->find()
                      ->select($fields)
                      ->join($joins)
                      ->where($conditions)
                      ->group($group)
                      ->order($order)
                      ->hydrate(false)
                      ->toArray();

        // debug($dados->sql());

        return $dados;

    }//fim retorna_agenda

    /**
     * [getCodigoLPPS metodo para achar o codigo da lista de preco produto servico para colocar na tabela de agendmento quando precisar]
     * @param  [type] $codigo_itens_pedidos_exames [description]
     * @return [type]                              [description]
     */
    public function getCodigoLPPS($codigo_itens_pedidos_exames)
    {

      $query = "
        SELECT 
            lpps.codigo as codigo_lpps
        FROM itens_pedidos_exames ipe
            INNER JOIN exames e ON ipe.codigo_exame = e.codigo
            INNER JOIN listas_de_preco lp ON ipe.codigo_fornecedor = lp.codigo_fornecedor
            INNER JOIN listas_de_preco_produto lpp ON lp.codigo = lpp.codigo_lista_de_preco
            INNER JOIN listas_de_preco_produto_servico lpps ON lpp.codigo = lpps.codigo_lista_de_preco_produto AND lpps.codigo_servico = e.codigo_servico
        WHERE ipe.codigo = {$codigo_itens_pedidos_exames};
      ";

      $connection = ConnectionManager::get('default');
      $dados = $connection->execute($query)->fetchAll('assoc');

      return isset($dados[0]) ? $dados[0] : null;

    }//fim getCodfigoLPPS

}
