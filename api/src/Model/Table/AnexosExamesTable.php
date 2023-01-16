<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * AnexosExames Model
 *
 * @method \App\Model\Entity\AnexosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnexosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnexosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnexosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnexosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnexosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnexosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnexosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class AnexosExamesTable extends AppTable
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

        $this->setTable('anexos_exames');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        //implementar log
        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_anexos_exames');
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
            ->integer('codigo_item_pedido_exame')
            ->requirePresence('codigo_item_pedido_exame', 'create')
            ->notEmptyString('codigo_item_pedido_exame');

        $validator
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 255)
            ->requirePresence('caminho_arquivo', 'create')
            ->notEmptyString('caminho_arquivo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->allowEmptyString('status');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * Verifica se o codigo item pedido exame existe
     * @param $codigo_item_pedido_exame
     * @return boolean
     */
    public function existsCodigoItemPedidoExame($codigo_item_pedido_exame){
        $anexo_exame = $this->find()->select(['codigo','codigo_item_pedido_exame'])
            ->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])
            ->first();
        return $anexo_exame;
    }

    /**
     * Altera o caminho do arquivo na tabela
     * @param $imagem_caminho_completo
     * @param $codigo_item_pedido_exame
     * @return \Cake\Database\StatementInterface
     */
    public function updateFoto($imagem_caminho_completo, $codigo_item_pedido_exame){
        return $this->query()->update()
            ->set(['caminho_arquivo' => $imagem_caminho_completo])
            ->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])
            ->execute();
    }

    public function deleteFoto($codigo_item_pedido_exame){
        /*$joins = [
            array(
                'table' => 'itens_pedidos_exames_baixa',
                'alias' => 'ItensPedidosExamesBaixa',
                'type' => 'INNER',
                'conditions' => ['ItensPedidosExamesBaixa.codigo_item_pedido_exame = AnexosExames.codigo_item_pedido_exame' ]
            ),
        ];
        $where = array(
            'AnexosExames.codigo_item_pedido_exame ='=>$codigo_item_pedido_exame,
        );
        $query = $this->find()
            ->where($where)
            ->join($joins)
            ->toArray();
        if(!empty($query)){*/
            return $this->query()->delete()
                ->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])
                ->execute();
        /*} else {
            return null;
        }*/

    }
}
