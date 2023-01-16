<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;
use Cake\Log\Log;


/**
 * PedidosExamesNotificacao Model
 *
 * @method \App\Model\Entity\PedidosExamesNotificacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExamesNotificacao findOrCreate($search, callable $callback = null, $options = [])
 */
class PedidosExamesNotificacaoTable extends AppTable
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

        $this->setTable('pedidos_exames_notificacao');
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
            ->integer('codigo_pedido_exame')
            ->allowEmptyString('codigo_pedido_exame');

        $validator
            ->scalar('funcionario_email')
            ->maxLength('funcionario_email', 255)
            ->allowEmptyString('funcionario_email');

        $validator
            ->scalar('clinica_email')
            ->maxLength('clinica_email', 255)
            ->allowEmptyString('clinica_email');

        $validator
            ->scalar('cliente_email')
            ->maxLength('cliente_email', 255)
            ->allowEmptyString('cliente_email');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_fornecedor')
            ->allowEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_pedido_exame_log')
            ->allowEmptyString('codigo_pedido_exame_log');

        return $validator;
    }


    /**
     * [gravaDados description]
     * 
     * pega os dados da tela de notificacao e guarda os emails digitados
     * 
     * @return [type] [description]
     */
    public function gravaDados($dados,$codigo_pedido_exame)
    {

        // debug($dados);exit;

        //variaveis auxilizares
        $codigo_funcionario = '';
        $funcionario_email = '';
        $codigo_cliente = '';
        $cliente_email = '';
        $codigo_fornecedor = '';
        $clinica_email = '';

        //pega os dados
        if(isset($dados['Email'])) {
            //verifica se tem indice de funcionarios
            if(isset($dados['Email']['Funcionario'])) {
                //pega os dados
                $codigo_funcionario = key($dados['Email']['Funcionario']);
                $funcionario_email = $dados['Email']['Funcionario'][$codigo_funcionario]['email'];
            }

            //verifica se tem indice de cliente
            if(isset($dados['Email']['Cliente'])) {
                //seta os dados
                $codigo_cliente = key($dados['Email']['Cliente']);
                $cliente_email = $dados['Email']['Cliente'][$codigo_cliente]['email'];
            }
            //verifica se tem indice de fornecedor
            if(isset($dados['Email']['Fornecedor'])) {

                foreach($dados['Email']['Fornecedor'] as $key_for => $for) {                    
                    $fornecedor_email[$key_for] = $for['email'];
                }

            }

        }//fim indice email

        //pode haver mais de um fornecedor para enviar os emails
        if (!empty($fornecedor_email)) {
            foreach ($fornecedor_email as $codigo_fornecedor => $email) {
                //seta os dados
                $dados_incluir = array(
                    'codigo_pedido_exame' => $codigo_pedido_exame,
                    'codigo_funcionario' => $codigo_funcionario,
                    'funcionario_email' => $funcionario_email,
                    'codigo_cliente' => $codigo_cliente,
                    'cliente_email' => $cliente_email,
                    'codigo_fornecedor' => $codigo_fornecedor,
                    'clinica_email' => $email
                );
                
                //monta o array para gravar os dados
                $registros = $this->newEntity($dados_incluir);
                //grava os dados na tabela
                $this->save($registros);
            
            }//fim fornecedor email
        }
        
        return true;

    }//fim gravaDados
}
