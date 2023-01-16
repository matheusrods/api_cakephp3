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
 * ItensPedidosExames Model
 *
 * @method \App\Model\Entity\ItensPedidosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\ItensPedidosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ItensPedidosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ItensPedidosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class ItensPedidosExamesTable extends AppTable
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

        $this->setTable('itens_pedidos_exames');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        //implementar o log
        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_itens_pedidos_exames');
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
            ->integer('codigo_medico')
            ->allowEmptyString('codigo_medico');

        $validator
            ->integer('codigo_status_itens_pedidos_exames')
            ->allowEmptyString('codigo_status_itens_pedidos_exames');

        $validator
            ->scalar('hora_realizacao_exame')
            ->allowEmptyString('hora_realizacao_exame');

        $validator
            ->scalar('laudo')
            ->allowEmptyString('laudo');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        // $validator
        //     ->dateTime('data_inicio_triagem')
        //     ->allowEmptyDateTime('data_inicio_triagem');

        // $validator
        //     ->dateTime('data_fim_triagem')
        //     ->allowEmptyDateTime('data_fim_triagem');

        // $validator
        //     ->dateTime('data_inicio_realizacao_exame')
        //     ->allowEmptyDateTime('data_inicio_realizacao_exame');

        return $validator;
    }

    function getItemPedidoExame($codigo){
        $select=[
            "UsuariosDados.codigo_usuario",
            "ItensPedidosExames.codigo_fornecedor",
            "ItensPedidosExames.data_agendamento",
            "ItensPedidosExames.hora_agendamento",
            "ItensPedidosExames.codigo_tipos_exames_pedidos",
            "PedidosExames.codigo_funcionario",
            "ItensPedidosExames.codigo"
        ];
        $joins = [
            array(
                'table' => 'pedidos_exames',
                'alias' => 'PedidosExames',
                'type' => 'INNER',
                'conditions' => ['PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames' ]
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = PedidosExames.codigo_funcionario'
            ),
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            )
        ];
        $where = ["ItensPedidosExames.codigo"=>$codigo];
        return $this->find()->select($select)->where($where)->join($joins);
    }

    /**
     * Recebe a lista de usuários que precisam ser notificados
     *
     * @param string $model nome da model que esta notificação pertence
     * @param Time $time horários que buscará os pedidos de exame [Ex: new Time('3 hours ago')]
     * @return array|Query Lista de usuários que precisam ser notificados
     */
    function getUsuarioNotificarAnexoExame(string $model, Time $time){

        // formatando a data
        $data = $time->i18nFormat('yyyy-MM-dd');
        $hora = $time->i18nFormat('HHmm');

        $where = array(
            'AnexosExames.caminho_arquivo IS NULL',
            'ItensPedidosExames.data_agendamento =' => $data,
            'ItensPedidosExames.hora_agendamento <=' => $hora, //
            'PushOutbox.codigo IS NULL',
            'Usuario.ativo =' => 1,
            'UsuariosDados.notificacao =' => 1,
            "UsuarioSistema.platform IS NOT NULL"
        );

        $join = array(
            array(
                'table' => 'anexos_exames',
                'alias' => 'AnexosExames',
                'type' => 'LEFT',
                'conditions' => 'AnexosExames.codigo_item_pedido_exame = ItensPedidosExames.codigo'
            ),
            array(
                'table' => 'pedidos_exames',
                'alias' => 'PedidosExames',
                'type' => 'INNER',
                'conditions' => ['PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames',
                    'PedidosExames.codigo_status_pedidos_exames IN (1,2)']
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = PedidosExames.codigo_funcionario'
            ),
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            ),
            array(
                 'table' => 'usuario_sistema',
                 'alias' => 'UsuarioSistema',
                 'type' => 'INNER',
                 'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosDados.codigo_usuario'
            ),
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario'
            ),

            array(
                'table' => 'push_outbox',
                'alias' => 'PushOutbox',
                'type' => 'LEFT',
                'conditions' => [
                    'PushOutbox.model = ' => $model,
                    'PushOutbox.foreign_key = ItensPedidosExames.codigo',
                ]
            ),
        );

        return $this->find()
            ->select([
                "PushOutbox.codigo",
                "Funcionarios.nome",
                "Usuario.nome",
                "UsuariosDados.telefone",
                "UsuariosDados.codigo_usuario",
                "ItensPedidosExames.codigo",
                "UsuarioSistema.token_push",
                "UsuarioSistema.platform",
            ])
            ->join($join)
            ->where($where);
    }

    function notificarExamesAgendados($tipo = null) {

        $data = null;

        if($tipo == 'vespera') {
            $time = new Time('+1 day');
        } else {
            $time = new Time();
            // $time = new Time("2019-10-31");
        }

        $data = $time->i18nFormat('yyyy-MM-dd');

        $where = array(
            'UsuariosDados.notificacao =' => 1,
            'Usuario.ativo =' => 1,
            'PushOutbox.codigo IS NULL',
            'UsuarioSistema.platform IS NOT NULL'
        );

        if($tipo == 'vespera') {
            $where['ItensPedidosExames.data_agendamento ='] = $data;
        } else {
            $where['CAST(ItensPedidosExames.data_inclusao as Date) ='] = $data;
        }

        $join = array(
            array(
                'table' => 'pedidos_exames',
                'alias' => 'PedidosExames',
                'type' => 'INNER',
                'conditions' => ['PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames',
                    'PedidosExames.codigo_status_pedidos_exames IN (1,2)'
                ]
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = PedidosExames.codigo_funcionario'
            ),
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            ),
            array(
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'INNER',
//                 'type' => 'LEFT',
                'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosDados.codigo_usuario'
            ),
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario'
            ),
            array(
                'table' => 'push_outbox',
                'alias' => 'PushOutbox',
                'type' => 'LEFT',
                'conditions' => [
                    'PushOutbox.model = ' => 'NotificaExamesAgendados'.$tipo,
                    'PushOutbox.foreign_key = ItensPedidosExames.codigo',
                ]
            ),
        );

        $dados =  $this->find()
            ->select([
                "Funcionarios.nome",
                "UsuariosDados.telefone",
                "ItensPedidosExames.codigo",
                "UsuarioSistema.token_push",
                "UsuarioSistema.platform",
                "Usuario.nome",
                "ItensPedidosExames.data_agendamento",
                "ItensPedidosExames.hora_agendamento",
                "UsuariosDados.codigo_usuario",
            ])
            ->join($join)
            ->where($where);

        // debug($join);
        // debug($where);
        // debug($dados->sql());exit;

        return $dados;

    }

    /**
     * [Método para montar o endereço do fornecedor baseado em item pedido]
     * @param  [int] $codigoPedidoExame [Código do Pedido de Exame]
     * @return [array]                  [Retorna um array com os dados obrigatorios para serem incluindo junto ao atestdo]
     */
    public function getMontaEnderecoFornecedor($codigoPedidoExame)
    {

        $fields_ipe = array(
            'endereco'=>"FornecedoresEndereco.logradouro",
            'numero'=>'FornecedoresEndereco.numero',
            'complemento'=>'FornecedoresEndereco.complemento',
            'bairro'=>'FornecedoresEndereco.bairro',
            'cep'=>'FornecedoresEndereco.cep',
            'estado'=>'FornecedoresEndereco.estado_descricao',
            'cidade'=>'FornecedoresEndereco.cidade',
            'longitude'=>'FornecedoresEndereco.longitude',
            'latitude'=>'FornecedoresEndereco.latitude'
        );

        $joins_ipe = array(
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedoresEndereco',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo_fornecedor = FornecedoresEndereco.codigo_fornecedor'
            )
        );
        //monta
        $conditions_ipe = array('ItensPedidosExames.codigo_pedidos_exames' => $codigoPedidoExame);

        //pega o retorno do endereco do fornecedor
        $retorno_endereco_fornecedor = $this->find()->select($fields_ipe)->join($joins_ipe)->where($conditions_ipe)->hydrate(false)->toArray();

        $endereco_atestado = array();

        if(!empty($retorno_endereco_fornecedor)){
            $endereco_atestado   = $retorno_endereco_fornecedor['0'];
        }

        return $endereco_atestado;
    }//FINAL FUNCTION getMontaEnderecoFornecedor


    /**
     * [setItensPedidosExames description]
     *
     * metodo para gravar os exames que foram selecionados
     *
     * @param [type] $codigo_pedido_exame [description]
     * @param [array] $exames              [description]
     */
    public function setItensPedidosExames($codigo_usuario,$codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_exame_tipo, $exames)
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
                    $this->AgendamentoExames = TableRegistry::get('AgendamentoExames');
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
                    $agenda_item = $this->AgendamentoExames->newEntity($array_incluir);
                    if(!$this->AgendamentoExames->save($agenda_item)) {
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

    /**
     * [retornaItensDoPedidoExame metodo para retornar os dados dos itens a partir de um codigo de pedido]
     * @param  [type] $codigo_pedido [description]
     * @return [type]                [description]
     */
    public function retornaItensDoPedidoExame($codigo_pedido) {

        $options['conditions'] = array("ItemPedidoExame.codigo_pedidos_exames = {$codigo_pedido}");
        $options['fields'] = array(
            'Fornecedor.codigo', 
            'Fornecedor.razao_social', 
            'Exame.descricao', 
            'Exame.codigo_servico',
            'Exame.exame_audiometria',
            'PedidoExame.codigo', 
            'PedidoExame.codigo_cliente_funcionario',
            "CASE WHEN PedidoExame.exame_admissional = 1 THEN 'ADMISSIONAL'
                  WHEN PedidoExame.exame_periodico = 1  THEN 'PERIÓDICO' 
                  WHEN PedidoExame.exame_demissional = 1  THEN 'DEMISSIONAL' 
                  WHEN PedidoExame.exame_retorno = 1  THEN 'RETORNO AO TRABALHO' 
                  WHEN PedidoExame.exame_mudanca = 1  THEN 'MUDANCA DE FUNCAO'
                  WHEN PedidoExame.exame_monitoracao = 1  THEN 'MONITORAÇÃO PONTUAL'
            ELSE 'PONTUAL' END" => 'tipo_ocupacional_pedido',
            'ItemPedidoExame.valor', 
            'ItemPedidoExame.*',
            'FornecedorEndereco.logradouro',
            'FornecedorEndereco.numero',
            'FornecedorEndereco.cidade',
            'FornecedorEndereco.estado_descricao',
            'FornecedorEndereco.bairro',
            'FornecedorEndereco.complemento',       
            '(SELECT TOP 1 descricao 
            FROM fornecedores_contato 
            WHERE fornecedores_contato.codigo_fornecedor = Fornecedor.codigo AND fornecedores_contato.codigo_tipo_retorno = 2
            )' => 'email_fornecedor'          
            );
        $options['joins'] = array(
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_fornecedor = Fornecedor.codigo',
                ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'Exame.codigo = ItemPedidoExame.codigo_exame',
                ),
            array(
                'table' => 'pedidos_exames',
                'alias' => 'PedidoExame',
                'type' => 'INNER',
                'conditions' => 'PedidoExame.codigo = ItemPedidoExame.codigo_pedidos_exames',
                ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'LEFT',
                'conditions' => array('FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo')
                )
            );

        $dados = $this->find()->select($options['fields'])->join($options['joins'])->where($options['conditions'])->all();

        debug($dados);exit;

        return $dados;
    }   

}
