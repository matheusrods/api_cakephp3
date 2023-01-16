<?php
namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;


/**
 * PreItensPedidosExames Model
 *
 * @method \App\Model\Entity\PreItensPedidosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PreItensPedidosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class PreItensPedidosExamesTable extends AppTable
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

        $this->setTable('pre_itens_pedidos_exames');
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
            ->integer('codigo_pedidos_exames')
            ->allowEmptyString('codigo_pedidos_exames');

        $validator
            ->integer('codigo_exame')
            ->allowEmptyString('codigo_exame');

        $validator
            ->decimal('valor')
            ->allowEmptyString('valor');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->allowEmptyString('tipo_atendimento');

        // $validator
        //     ->date('data_agendamento')
        //     ->allowEmptyDate('data_agendamento');

        $validator
            ->scalar('hora_agendamento')
            ->maxLength('hora_agendamento', 5)
            ->allowEmptyString('hora_agendamento');

        $validator
            ->integer('codigo_tipos_exames_pedidos')
            ->allowEmptyString('codigo_tipos_exames_pedidos');

        $validator
            ->integer('tipo_agendamento')
            ->allowEmptyString('tipo_agendamento');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_cliente_assinatura')
            ->allowEmptyString('codigo_cliente_assinatura');

        $validator
            ->date('data_realizacao_exame')
            ->allowEmptyDate('data_realizacao_exame');

        $validator
            ->integer('compareceu')
            ->allowEmptyString('compareceu');

        $validator
            ->boolean('recebimento_digital')
            ->notEmptyString('recebimento_digital');

        $validator
            ->boolean('recebimento_enviado')
            ->notEmptyString('recebimento_enviado');

        $validator
            ->dateTime('data_notificacao_nc')
            ->allowEmptyDateTime('data_notificacao_nc');

        $validator
            ->decimal('valor_custo')
            ->allowEmptyString('valor_custo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_status_itens_pedidos_exames')
            ->allowEmptyString('codigo_status_itens_pedidos_exames');

        $validator
            ->scalar('hora_realizacao_exame')
            ->maxLength('hora_realizacao_exame', 5)
            ->allowEmptyString('hora_realizacao_exame');

        $validator
            ->scalar('laudo')
            ->allowEmptyString('laudo');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        return $validator;
    }

    /**
     * [setItensPedidosExames description]
     *
     * metodo para gravar os exames que foram selecionados
     *
     * @param [type] $codigo_pedido_exame [description]
     * @param [array] $exames              [description]
     */
    public function setPreItensPedidosExames($codigo_usuario,$codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_exame_tipo, $exames)
    {
        // obter codigo servico
        $this->Exames = TableRegistry::get('Exames');
        $this->PedidosExames = TableRegistry::get('PedidosExames');
        
        /**
         * array:6 [▼
        "codigo_fornecedor" => 444
        "codigo_exame" => 77
        "tipo_atendimento" => null
        "tipo_agendamento" => null
        "data" => "2019-10-31"
        "horario" => "1735"
        ]
         */
        $exames_salvos = [];
        $codigo_fornecedor = null;
        foreach($exames as $key => $item) {
            if(!empty($item['codigo_fornecedor'])) {
                $codigo_fornecedor = $item['codigo_fornecedor'];
            }
            $codigo_exame = $item['codigo_exame'];
            $tipo_atendimento = $item['tipo_atendimento'];
            $tipo_agendamento = $item['tipo_agendamento'];
            $data_agendamento = $item['data'];
            $hora_agendamento = $item['horario'];
            // obter codigo servico
            $exame = $this->Exames->find()->select(['codigo_servico', 'descricao'=>'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])->where(['codigo'=>$codigo_exame])->first();
            $codigo_servico = $exame->codigo_servico;
            $item_exame_descricao = $exame->descricao;

            //pega o valor do custo do serviço/exame para aquele fornecedor
            //$valor_custo = $this->ItemPedidoExame->ObterFornecedorCusto($codigo_fornecedor, $codigo_exame);
            
            // codigos de serviços dos exames para retornar preço
            $d = $this->PedidosExames->retornaFornecedoresExames($codigo_servico, null, $codigo_cliente, null);
            $valor_custo = $d[0]['ListaPrecoProdutoServico']['valor'];
            
            // $codigo_cliente_alocacao = $codigo_cliente; //todo
            $codigo_matriz = $codigo_cliente;
            $assinatura = $this->PedidosExames->verificaExameTemAssinatura($codigo_servico, $codigo_cliente_alocacao, $codigo_matriz);
            $dados_salvar_item = array(
                'codigo_pedidos_exames' => $codigo_pedido_exame,
                'codigo_exame' => $codigo_exame,
                'codigo_fornecedor' => $codigo_fornecedor,
                'tipo_atendimento' => $tipo_atendimento,
                'tipo_agendamento' => $tipo_agendamento,
                'data_agendamento' => $data_agendamento,
                'hora_agendamento' => $hora_agendamento,
                'codigo_tipos_exames_pedidos' => $codigo_exame_tipo,
                'valor_custo' => $valor_custo,
                'valor' => $assinatura['valor'],
                'codigo_cliente_assinatura' => $assinatura['codigo'],
                'codigo_usuario_inclusao' => $codigo_usuario
            );
            // debug($dados_salvar_item);
            // salvar item
            
            //verifica se existe um pedido de exame, caso exista e tem o exame do laço irá atualziar
            $ipe = $this->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame,'codigo_exame' => $codigo_exame])->first();
            //verifica se existe o item
            if(!empty($ipe)) {
                $registro_item = $this->patchEntity($ipe,$dados_salvar_item);
            }
            else {
                $registro_item = $this->newEntity($dados_salvar_item);
            }

            //cria um item ou atualiza o item
            if (!$this->save($registro_item)) {
                throw new Exception('Ocorreu algum erro! Precisamos reagendar ou entre contato com a clínica! '.print_r($registro_item->getValidationErrors(),1));
            }

            //verifica se é alteracao e tem que ser inclusao
            if(empty($ipe)) {
                //verifica se tem data de agendamento
                if(!empty($data_agendamento) && !empty($hora_agendamento)) {
                    $this->PreAgendamentoExames = TableRegistry::get('PreAgendamentoExames');
                    $array_incluir = array(
                        'data' => $data_agendamento,
                        'hora' => (int) str_replace(":", "", $hora_agendamento),
                        'codigo_fornecedor' => $codigo_fornecedor,
                        'codigo_itens_pedidos_exames' => $registro_item->codigo,
                        'ativo' => '1',
                        'data_inclusao' => date('Y-m-d H:i:s'),
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'codigo_empresa' => 1,
                        'codigo_lista_de_preco_produto_servico' => null
                    );
                    $agenda_item = $this->PreAgendamentoExames->newEntity($array_incluir);
                    if(!$this->PreAgendamentoExames->save($agenda_item)) {
                        throw new Exception("Houve um erro ao salvar o Agendamento!");
                    }
                }
            }//fim empty ipe
            $item_exame_resposta['codigo'] = $registro_item->codigo;
            $item_exame_resposta['codigo_tipo_exame'] = $codigo_exame_tipo;
            $item_exame_resposta['descricao'] = $item_exame_descricao;
            $item_exame_resposta['agendado'] = true;
            $exames_salvos[] = $item_exame_resposta;
        } // foreach
        // exit;
        return $exames_salvos;
    
    }//fim setItensPedidosExames

}
