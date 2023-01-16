<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use App\Utils\Comum;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Cake\Http\Client;

use Cake\Datasource\ConnectionManager;

/**
 * PedidosExames Model
 *
 * @method \App\Model\Entity\PedidosExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\PedidosExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PedidosExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidosExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PedidosExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PedidosExame findOrCreate($search, callable $callback = null, $options = [])
 */
class PedidosExamesTable extends AppTable
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

        $this->setTable('pedidos_exames');
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
     * [consultas description]
     *
     * metodo com os dados do detalhes da consulta
     *
     * @param  [type] $codigo_item_pedido_exame [description]
     * @return [type]                           [description]
     */
    public function consultas( $codigo_item_pedido_exame )
    {

        //campos
        $fields = array(
            'codigo_pedido_exame'=>'PedidosExames.codigo',
            'codigo_exame' => 'Exame.codigo',
            'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'nome_fantasia_solicitante' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
            'razao_social_solicitante' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)',
            'cnpj_solicitante' => 'Cliente.codigo_documento',
            'tipo_exame' => '(CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno ao trabalho\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração pontual\'
                ELSE \'\'
            END)',
            'codigo_fornecedor' => 'Fornecedor.codigo',
            'nome_credenciado' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',


            'endereco' => 'RHHealth.dbo.ufn_decode_utf8_string(CONCAT(FornecedorEndereco.logradouro,\',\', FornecedorEndereco.numero,\' \', FornecedorEndereco.complemento,\' - \', FornecedorEndereco.bairro, \' - \', FornecedorEndereco.cidade, \' - \', FornecedorEndereco.estado_abreviacao))',
            'lat' => 'FornecedorEndereco.latitude',
            'long' => 'FornecedorEndereco.longitude',
            'avaliacao' => '0',
            'total_avaliacoes' => '0',
            'telefone' => '(SELECT TOP 1 CONCAT(\'(\',ddd, \') \', descricao) FROM fornecedores_contato WHERE codigo_tipo_retorno = 1 AND codigo_fornecedor = Fornecedor.codigo)',
            'email' => '(SELECT TOP 1 descricao FROM fornecedores_contato WHERE codigo_tipo_retorno = 2 AND codigo_fornecedor = Fornecedor.codigo)',

            // 'data_agendamento' => 'AgendamentoExame.data',
            // 'hora_agendamento' => 'AgendamentoExame.hora',

            'data_agendamento' => 'ItemPedidoExame.data_agendamento',
            'hora_agendamento' => 'ItemPedidoExame.hora_agendamento',

            'data_agendamento_itens' => 'ItemPedidoExame.data_agendamento',
            // 'de_atendimento_credenciado' => 'FornecedorHorario.de_hora',
            // 'ate_atendimento_credenciado' => 'FornecedorHorario.ate_hora',
            // 'dias_semana_credenciado' => 'FornecedorHorario.dias_semana',
            'data_realizacao_exames' => 'ItemPedidoExame.data_realizacao_exame',
            'codigo_item_pedido_exame' => 'ItemPedidoExame.codigo',
            'data_resultado' => 'ItemPedidoExameBaixa.data_realizacao_exame',
            'data_baixa' => 'ItemPedidoExameBaixa.data_inclusao',
            // 'data' => "CASE WHEN AgendamentoExame.data IS NOT NULL THEN CONCAT(AgendamentoExame.data,' ', AgendamentoExame.hora) ELSE NULL END",
            'data' => "(CASE
                           WHEN AgendamentoExame.data IS NOT NULL
                            THEN CONCAT(
                                    CONVERT(VARCHAR,AgendamentoExame.data,101), ' ',
                                    format(
                                        cast(
                                            (CASE WHEN len(AgendamentoExame.hora) < 4
                                                THEN CONCAT('0',CAST(AgendamentoExame.hora AS varchar(4)))
                                                ELSE CAST(AgendamentoExame.hora AS varchar(4))
                                            END) AS int)
                                            ,'##:##')
                                )
                           ELSE NULL
                       END)",

            'cpf' => 'Funcionarios.cpf',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'idade' => 'DATEDIFF(YEAR, Funcionarios.data_nascimento, GETDATE())',

            'setor' => 'RHHealth.dbo.ufn_decode_utf8_string(Setor.descricao)',
            'cargo' => 'RHHealth.dbo.ufn_decode_utf8_string(Cargo.descricao)',
            'medico_responsavel' => 'NULL',
            'grade_exames' => 'NULL',
            'preparo_exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.recomendacoes)',
            'imagem_exame' => 'AnexosExames.caminho_arquivo',
            'imagem_ficha_clinica' => 'AnexosFichasClinicas.caminho_arquivo',
            'resultado_pedido_exame' => 'ItemPedidoExameBaixa.resultado'
        );

        // debug($fields);

        //joins
        $joins = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo = ItemPedidoExame.codigo_pedidos_exames'
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_exame = Exame.codigo'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente = Cliente.codigo'
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'INNER',
                'conditions' => 'FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_funcionario = Funcionarios.codigo'
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_func_setor_cargo = FuncionarioSetorCargo.codigo'
            ),
            array(
                'table' => 'setores',
                'alias' => 'Setor',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetorCargo.codigo_setor = Setor.codigo'
            ),
            array(
                'table' => 'cargos',
                'alias' => 'Cargo',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetorCargo.codigo_cargo = Cargo.codigo'
            ),
            array(
                'table' => 'itens_pedidos_exames_baixa',
                'alias' => 'ItemPedidoExameBaixa',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = ItemPedidoExameBaixa.codigo_itens_pedidos_exames'
            ),
            // array(
            //     'table' => 'fornecedores_horario',
            //     'alias' => 'FornecedorHorario',
            //     'type' => 'LEFT',
            //     'conditions' => 'FornecedorHorario.codigo_fornecedor = Fornecedor.codigo'
            // ),
            array(
                'table' => 'agendamento_exames',
                'alias' => 'AgendamentoExame',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AgendamentoExame.codigo_itens_pedidos_exames AND AgendamentoExame.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'anexos_exames',
                'alias' => 'AnexosExames',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AnexosExames.codigo_item_pedido_exame'
            ),
            array(
                'table' => 'fichas_clinicas',
                'alias' => 'FichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'PedidosExames.codigo = FichasClinicas.codigo_pedido_exame'
            ),
            array(
                'table' => 'anexos_fichas_clinicas',
                'alias' => 'AnexosFichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'AnexosFichasClinicas.codigo_ficha_clinica = FichasClinicas.codigo'
            ),
        );

        //wheres
        $conditions = ['ItemPedidoExame.codigo'=> $codigo_item_pedido_exame];

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->first();

        // debug(array($dados,$codigo_item_pedido_exame));exit;

        return $dados;
    } //fim consulta detalhes


    public function consultaPedido( $codigo_pedido ){

        $params = ['codigo_pedido'=> $codigo_pedido];
        $strSql = 'SELECT * from pedidos_exames where codigo = :codigo_pedido';
        $connection = ConnectionManager::get('default');
        return $connection->execute($strSql, $params )->fetchAll('assoc');
    }

    /**
     * [getFieldsConsultas description]
     *
     * monta os fields das consutals
     *
     * @return [type] [description]
     */
    private function getFieldsConsultas()
    {
        //campos
        $fields = array(
            'codigo_pedido_exame'=>'PedidosExames.codigo',
            'id_agendamento' => 'ItemPedidoExame.codigo',
            'codigo_exame' => 'Exame.codigo',
            'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'nome_fantasia_solicitante' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
            'nome_cliente' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)',
            'tipo_agendamento' => '(CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno ao trabalho\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração pontual\'
                ELSE \'\'
            END)',
            'resultado' => '(
                        CASE WHEN FichaClinica.parecer = 1 THEN \'Apto\'
                            WHEN FichaClinica.parecer = 1 THEN \'Inapto\'
                        ELSE \'\' END)',
            'codigo_fornecedor' => 'Fornecedor.codigo',
            'nome_credenciado' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',

            'telefone' => '(SELECT TOP 1 CONCAT(\'(\',ddd, \') \', descricao) FROM fornecedores_contato WHERE codigo_tipo_retorno = 1 AND codigo_fornecedor = Fornecedor.codigo)',
            'email' => '(SELECT TOP 1 descricao FROM fornecedores_contato WHERE codigo_tipo_retorno = 2 AND codigo_fornecedor = Fornecedor.codigo)',

            'local_agendamento' => 'RHHealth.dbo.ufn_decode_utf8_string(CONCAT(FornecedorEndereco.logradouro,\',\', FornecedorEndereco.numero,\' \', FornecedorEndereco.complemento,\' - \', FornecedorEndereco.bairro, \' - \', FornecedorEndereco.cidade, \' - \', FornecedorEndereco.estado_abreviacao))',
            // 'data_agendamento' => 'AgendamentoExame.data',
            // 'hora_agendamento' => 'AgendamentoExame.hora',

            'data_agendamento' => 'ItemPedidoExame.data_agendamento',
            'hora_agendamento' => "(CASE WHEN len(ItemPedidoExame.hora_agendamento) < 4 THEN CONCAT('0',ItemPedidoExame.hora_agendamento) ELSE ItemPedidoExame.hora_agendamento END)",

            'data_agendamento_itens' => 'ItemPedidoExame.data_agendamento',
            'data_realizacao_exames' => 'ItemPedidoExame.data_realizacao_exame',
            'data_resultado' => 'ItemPedidoExameBaixa.data_realizacao_exame',
            'data_baixa' => 'ItemPedidoExameBaixa.data_inclusao',
            'data' => "(CASE
                           WHEN AgendamentoExame.data IS NOT NULL
                            THEN CONCAT(
                                    CONVERT(VARCHAR,AgendamentoExame.data,101), ' ',
                                    format(
                                        cast(
                                            (CASE WHEN len(AgendamentoExame.hora) < 4
                                                THEN CONCAT('0',CAST(AgendamentoExame.hora AS varchar(4)))
                                                ELSE CAST(AgendamentoExame.hora AS varchar(4))
                                            END) AS int)
                                            ,'##:##')
                                )
                           ELSE NULL
                       END)",
            'pendente_exames' => "(CASE
                                    WHEN ItemPedidoExameBaixa.data_inclusao IS NOT NULL THEN 0
                                    WHEN ItemPedidoExame.data_agendamento <= '".date('Y-m-d')."' AND AnexosExames.caminho_arquivo IS NULL THEN 1
                                    WHEN ItemPedidoExameBaixa.data_inclusao IS NULL AND ItemPedidoExame.data_agendamento IS NULL AND AnexosExames.caminho_arquivo IS NULL THEN 1
                                    ELSE 0 END)",

            'imagem_exame' => 'AnexosExames.caminho_arquivo'
        );


        return $fields;

    }//fim getFieldsConsultas

    /**
     * [getJoinsConsultas description]
     *
     * metodo para montar o join da consulta
     *
     * @param  string $type [description]
     * @return [type]       [description]
     */
    private function getJoinsConsultas($type = 'INNER')
    {
        //joins
        $joins = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo = ItemPedidoExame.codigo_pedidos_exames'
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_exame = Exame.codigo'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente = Cliente.codigo'
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'INNER',
                'conditions' => 'FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'itens_pedidos_exames_baixa',
                'alias' => 'ItemPedidoExameBaixa',
                'type' => $type,
                'conditions' => 'ItemPedidoExame.codigo = ItemPedidoExameBaixa.codigo_itens_pedidos_exames'
            ),
            array(
                'table' => 'fichas_clinicas',
                'alias' => 'FichaClinica',
                'type' => 'LEFT',
                'conditions' => 'FichaClinica.codigo_pedido_exame = PedidosExames.codigo'
            ),
            array(
                'table' => 'agendamento_exames',
                'alias' => 'AgendamentoExame',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AgendamentoExame.codigo_itens_pedidos_exames AND AgendamentoExame.codigo_fornecedor = Fornecedor.codigo'
            ),
            array(
                'table' => 'anexos_exames',
                'alias' => 'AnexosExames',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AnexosExames.codigo_item_pedido_exame'
            ),
            array(
                'table' => 'fichas_clinicas',
                'alias' => 'FichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'PedidosExames.codigo = FichasClinicas.codigo_pedido_exame'
            ),
            array(
                'table' => 'anexos_fichas_clinicas',
                'alias' => 'AnexosFichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'AnexosFichasClinicas.codigo_ficha_clinica = FichasClinicas.codigo'
            ),
        );

        return $joins;
    }//fim getJoinsConsultas


    /**
     * [ultimas_consultas description]
     *
     * metodo para pegar as ultimas consultas do funcionario
     *
     * @param  [type] $codigo_funcionario [description]
     * @param  [type] $limit              [description]
     * @return [type]                     [description]
     */
    public function ultimas_consultas(array $codigo_cliente, $codigo_funcionario,$limit = 10, array $filtro = [])
    {

        $fields = $this->getFieldsConsultas();
        // debug($fields);

        $joins = $this->getJoinsConsultas();

        //wheres
        $conditions = [
            'PedidosExames.codigo_funcionario'=> $codigo_funcionario,
            'Cliente.codigo IN (SELECT codigo_cliente FROM grupos_economicos_clientes WHERE codigo_grupo_economico IN (SELECT codigo FROM grupos_economicos WHERE codigo_cliente IN ('.implode(",",$codigo_cliente).')))'
        ];
        if(isset($filtro['nome'])){
            $nome = $filtro['nome'];
            array_push($conditions, ['Exame.descricao LIKE'=>"%{$nome}%"]);
        }

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);

        if(!empty($limit)){
            $dados->limit($limit);
        }

        return $dados;

    }//fim ultimas_consultas

    /**
     * [proximas_consultas description]
     *
     * metodo para pegar as ultimas consultas do funcionario
     *
     * @param  [type] $codigo_funcionario [description]
     * @param  [type] $limit              [description]
     * @return [type]                     [description]
     */
    public function proximas_consultas(array $codigo_cliente, $codigo_funcionario, $param_conditions=null, $limit = null)
    {

        $fields = $this->getFieldsConsultas();
        // debug($fields);

        $joins = $this->getJoinsConsultas('LEFT');

        //wheres
        $conditions = [ 'Cliente.codigo IN (SELECT codigo_cliente FROM grupos_economicos_clientes WHERE codigo_grupo_economico IN (SELECT codigo FROM grupos_economicos WHERE codigo_cliente IN ('.implode(",",$codigo_cliente).')))',
            'PedidosExames.codigo_status_pedidos_exames IN (1,2)',
            'PedidosExames.codigo_funcionario = '.$codigo_funcionario,
            'ItemPedidoExameBaixa.codigo IS NULL'
        ];
        // $conditions = ['PedidosExames.codigo_funcionario'=> $codigo_funcionario];

        //verifica se o param_condtions nao esta vazio
        if(!empty($param_conditions)) {
            $conditions[] =  $param_conditions;
        }

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->order(['ItemPedidoExame.data_agendamento']);
            // ->all();

        if(!empty($limit)){
            $dados->limit($limit);
        }

        $dados->all();
        // debug(array($codigo_cliente,$codigo_funcionario, $param_conditions));debug($this->find()->select($fields)->join($joins)->where($conditions)->order(['ItemPedidoExame.data_agendamento'])->sql());//exit;

        return $dados;

    }//fim proximas_consultas


    /**
     * [cte_posicao_exames_otimizada description]
     *
     * query cte_posicao_exames_otimizada da posicao de exames
     *
     * @param  [type] $codigo_cliente_alocacao [description]
     * @return [type]                          [description]
     */
    private function cte_posicao_exames($cpf,$codigos_clientes = null)
    {
        //filtro para na cte pegar somente os funcionarios do cliente que esta sendo processado
        $whereCteFuncionario = "";

        if(!is_null($codigos_clientes)) {
            $whereCteFuncionario = " AND [GrupoEconomicoCliente].[codigo_grupo_economico] IN (SELECT codigo
                                                                                FROM RHHealth.dbo.grupos_economicos
                                                                                WHERE codigo_cliente ".$codigos_clientes.")";
        }

        $query = "
            WITH cteFuncionario
                AS (SELECT
                  ClienteFuncionario.codigo AS codigo_cf,
                  ClienteFuncionario.matricula AS matricula,
                  ClienteFuncionario.ativo,
                  Funcionario.nome,
                  Funcionario.cpf,
                  Funcionario.codigo AS codigo_funcionario,
                  CAST(ClienteFuncionario.admissao AS date) AS admissao,
                  DATEDIFF(YEAR, Funcionario.data_nascimento, GETDATE()) AS idade,
                  FuncionarioSetorCargo.codigo_setor,
                  Setor.descricao AS setor,
                  FuncionarioSetorCargo.codigo_cargo,
                  Cargo.descricao AS cargo,
                  FuncionarioSetorCargo.codigo AS codigo_fsc,
                  fscx.codigo AS codigo_fscx,
                  GrupoEconomico.codigo_cliente AS codigo_matriz,
                  Cliente.codigo AS codigo_unidade,
                  Cliente.nome_fantasia,
                  CAST((SELECT E.CODIGO
                        FROM RHHealth.dbo.APLICACAO_EXAMES AE WITH (NOLOCK)
                            JOIN RHHealth.dbo.EXAMES E WITH (NOLOCK) ON AE.CODIGO_EXAME = E.CODIGO
                        WHERE AE.CODIGO_CARGO = FuncionarioSetorCargo.CODIGO_CARGO
                        GROUP BY E.CODIGO
                        FOR xml PATH ('')) AS text) EX_ATUAL,
                  CAST((SELECT R.CODIGO
                        FROM RHHealth.dbo.GRUPO_EXPOSICAO GE WITH (NOLOCK)
                            JOIN RHHealth.dbo.GRUPOS_EXPOSICAO_RISCO GER WITH (NOLOCK) ON GER.CODIGO_GRUPO_EXPOSICAO = GE.CODIGO
                            JOIN RHHealth.dbo.RISCOS R WITH (NOLOCK) ON GER.CODIGO_RISCO = R.CODIGO
                            JOIN RHHealth.dbo.clientes_setores CS WITH (NOLOCK) ON CS.codigo_setor = FuncionarioSetorCargo.codigo_setor
                                AND CS.codigo_cliente_alocacao = FuncionarioSetorCargo.codigo_cliente_alocacao
                            WHERE FuncionarioSetorCargo.CODIGO_CARGO = GE.codigo_cargo
                                AND CS.codigo = GE.codigo_cliente_setor
                            FOR xml PATH ('')) AS text) RIS_ATUAL,
                  CAST((SELECT E.CODIGO
                        FROM RHHealth.dbo.APLICACAO_EXAMES AE WITH (NOLOCK)
                            JOIN RHHealth.dbo.EXAMES E WITH (NOLOCK) ON AE.CODIGO_EXAME = E.CODIGO
                        WHERE AE.CODIGO_CARGO = fscx.CODIGO_CARGO
                        GROUP BY E.CODIGO
                        FOR xml PATH ('')) AS text) EX_ANTIGO,
                  CAST((SELECT R.CODIGO
                        FROM RHHealth.dbo.GRUPO_EXPOSICAO GE WITH (NOLOCK)
                            JOIN RHHealth.dbo.GRUPOS_EXPOSICAO_RISCO GER WITH (NOLOCK) ON GER.CODIGO_GRUPO_EXPOSICAO = GE.CODIGO
                            JOIN RHHealth.dbo.RISCOS R WITH (NOLOCK) ON GER.CODIGO_RISCO = R.CODIGO
                            JOIN RHHealth.dbo.clientes_setores CS WITH (NOLOCK) ON CS.codigo_setor = fscx.codigo_setor
                                AND CS.codigo_cliente_alocacao = fscx.codigo_cliente_alocacao
                        WHERE fscx.CODIGO_CARGO = GE.codigo_cargo
                            AND CS.codigo = GE.codigo_cliente_setor
                        FOR xml PATH ('')) AS text) RIS_ANTIGO
                FROM RHHealth.dbo.grupos_economicos_clientes AS [GrupoEconomicoCliente]
                    INNER JOIN RHHealth.dbo.grupos_economicos AS [GrupoEconomico] WITH (NOLOCK) ON ([GrupoEconomico].[codigo] = [GrupoEconomicoCliente].[codigo_grupo_economico])
                    INNER JOIN RHHealth.dbo.cliente_funcionario AS [ClienteFuncionario] WITH (NOLOCK) ON ([ClienteFuncionario].[codigo_cliente_matricula] = [GrupoEconomicoCliente].[codigo_cliente])
                    INNER JOIN RHHealth.dbo.funcionarios AS [Funcionario] WITH (NOLOCK) ON ([Funcionario].[codigo] = [ClienteFuncionario].[codigo_funcionario])
                    INNER JOIN RHHealth.dbo.funcionario_setores_cargos AS [FuncionarioSetorCargo] WITH (NOLOCK) ON ([FuncionarioSetorCargo].[codigo] = (SELECT TOP 1 codigo
                                                            FROM RHHealth.dbo.funcionario_setores_cargos x WITH (NOLOCK)
                                                            WHERE [x].[codigo_cliente_funcionario] = [ClienteFuncionario].[codigo]
                                                            ORDER BY codigo DESC))
                    LEFT JOIN RHHealth.dbo.funcionario_setores_cargos AS [fscx] WITH (NOLOCK)
                        ON ([fscx].[codigo] = (SELECT codigo
                                                FROM RHHealth.dbo.funcionario_setores_cargos x WITH (NOLOCK)
                                                WHERE [x].[codigo_cliente_funcionario] = [ClienteFuncionario].[codigo]
                                                ORDER BY codigo DESC
                                                OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY))
                    INNER JOIN RHHealth.dbo.cliente AS [Cliente] WITH (NOLOCK) ON ([Cliente].[codigo] = [FuncionarioSetorCargo].[codigo_cliente_alocacao])
                    INNER JOIN RHHealth.dbo.setores AS [Setor] WITH (NOLOCK) ON ([Setor].[codigo] = [FuncionarioSetorCargo].[codigo_setor] AND [Setor].[ativo] = 1)
                    INNER JOIN RHHealth.dbo.cargos AS [Cargo] WITH (NOLOCK) ON ([Cargo].[codigo] = [FuncionarioSetorCargo].[codigo_cargo] AND [Cargo].[ativo] = 1)
                WHERE Funcionario.cpf = '". $cpf. "' " . $whereCteFuncionario.")
                ,
                cteAplicacaoExames
                AS (SELECT
                  cteFuncionario.*,
                  AplicacaoExame.codigo as codigo_aplicacao,
                  AplicacaoExame.codigo_exame,
                  Exame.descricao AS descricao_exame,
                  AplicacaoExame.periodo_apos_demissao,
                  AplicacaoExame.periodo_idade,
                  AplicacaoExame.periodo_idade_2,
                  AplicacaoExame.periodo_idade_3,
                  AplicacaoExame.periodo_idade_4,
                  AplicacaoExame.periodo_meses,
                  AplicacaoExame.qtd_periodo_idade,
                  AplicacaoExame.qtd_periodo_idade_2,
                  AplicacaoExame.qtd_periodo_idade_3,
                  AplicacaoExame.qtd_periodo_idade_4,
                  Exame.codigo AS _codigo_exame,
                  AplicacaoExame.exame_admissional AS ae_exame_admissional,
                  AplicacaoExame.exame_monitoracao AS ae_exame_monitoracao,
                  AplicacaoExame.exame_periodico AS ae_exame_periodico,
                  fscx.codigo AS fscx
                FROM ctefuncionario AS [ctefuncionario]
                    INNER JOIN RHHealth.dbo.funcionario_setores_cargos AS [fscx] WITH (NOLOCK)
                      ON ([fscx].[codigo] = (CASE WHEN [cteFuncionario].[EX_ANTIGO] IS NOT NULL OR
                                                [cteFuncionario].[RIS_ANTIGO] IS NOT NULL THEN
                                                    CASE WHEN ([cteFuncionario].[EX_ANTIGO] LIKE [cteFuncionario].[EX_ATUAL] AND [cteFuncionario].[RIS_ANTIGO] LIKE [cteFuncionario].[RIS_ATUAL]) THEN [cteFuncionario].[codigo_fscx]
                                                    ELSE
                                                        CASE WHEN ([cteFuncionario].[RIS_ATUAL] LIKE '64' AND [cteFuncionario].[EX_ATUAL] LIKE '52') THEN [cteFuncionario].[codigo_fscx]
                                                        ELSE [cteFuncionario].[codigo_fsc]
                                                        END
                                                    END
                                            ELSE [cteFuncionario].[codigo_fsc]
                                            END))
                    INNER JOIN RHHealth.dbo.aplicacao_exames AS [AplicacaoExame] WITH (NOLOCK) ON (
                        ([AplicacaoExame].[codigo_cargo] = [cteFuncionario].[codigo_cargo]
                        AND [AplicacaoExame].[codigo_setor] = [cteFuncionario].[codigo_setor]
                        AND [cteFuncionario].[EX_ATUAL] LIKE (CASE
                                                                WHEN ([cteFuncionario].[RIS_ATUAL] NOT LIKE '64' OR [cteFuncionario].[RIS_ATUAL] NOT LIKE '4434') AND [cteFuncionario].[RIS_ANTIGO] LIKE [cteFuncionario].[RIS_ATUAL] THEN CONCAT('%>', [AplicacaoExame].[codigo_exame], '<%')
                                                                ELSE '%'
                                                            END)
                        /*AND [AplicacaoExame].[exame_admissional] = (CASE
                                                                        WHEN [cteFuncionario].[RIS_ATUAL] NOT LIKE '64' AND [cteFuncionario].[RIS_ANTIGO] NOT LIKE [cteFuncionario].[RIS_ATUAL] THEN 1
                                                                    ELSE (1 | 0)
                                                                    END)*/
                        )
                        AND (cteFuncionario.codigo_funcionario = AplicacaoExame.codigo_funcionario OR AplicacaoExame.codigo_funcionario IS NULL)
                        AND AplicacaoExame.codigo IN (select * from RHHealth.dbo.ufn_aplicacao_exames(fscx.codigo_cliente_alocacao,cteFuncionario.codigo_setor,cteFuncionario.codigo_cargo,cteFuncionario.codigo_funcionario))
                    )
                    INNER JOIN RHHealth.dbo.exames AS [Exame] WITH (NOLOCK) ON ([Exame].[codigo] = [AplicacaoExame].[codigo_exame])
                WHERE [AplicacaoExame].[codigo_cliente_alocacao] = [fscx].[codigo_cliente_alocacao])
                ,
                ctePedidosExames
                AS (SELECT
                  PedidoExame.codigo AS codigo_pedido,
                  cteAplicacaoExames.codigo_fsc,
                  cteAplicacaoExames.ae_exame_admissional,
                  cteAplicacaoExames.ae_exame_monitoracao,
                  cteAplicacaoExames.ae_exame_periodico,
                  cteAplicacaoExames.ativo,
                  cteAplicacaoExames.fscx,
                  PedidoExame.codigo_func_setor_cargo,
                  CAST(PedidoExame.data_inclusao AS date) AS ultimo_pedido,
                  cteAplicacaoExames.periodo_apos_demissao AS periodo_apos_demissao,
                  cteAplicacaoExames.nome,
                  cteAplicacaoExames.setor AS setor_descricao,
                  cteAplicacaoExames.cargo,
                  cteAplicacaoExames.cpf,
                  cteAplicacaoExames.codigo_funcionario,
                  cteAplicacaoExames.descricao_exame AS exame_descricao,
                  (CASE
                    WHEN ((PedidoExame.exame_admissional = 1 OR
                      PedidoExame.codigo IS NULL) AND
                      (cteAplicacaoExames.periodo_apos_demissao IS NOT NULL AND
                      cteAplicacaoExames.periodo_apos_demissao <> '')) THEN cteAplicacaoExames.periodo_apos_demissao
                    ELSE (CASE
                        WHEN ((cteAplicacaoExames.periodo_idade IS NOT NULL AND
                          cteAplicacaoExames.periodo_idade <> '') AND
                          ((cteAplicacaoExames.idade >= cteAplicacaoExames.periodo_idade AND
                          cteAplicacaoExames.idade < cteAplicacaoExames.periodo_idade_2) OR
                          (cteAplicacaoExames.periodo_idade_2 = ''))) THEN cteAplicacaoExames.qtd_periodo_idade
                        WHEN ((cteAplicacaoExames.periodo_idade_2 IS NOT NULL AND
                          cteAplicacaoExames.periodo_idade_2 <> '') AND
                          ((cteAplicacaoExames.idade >= cteAplicacaoExames.periodo_idade_2 AND
                          cteAplicacaoExames.idade < cteAplicacaoExames.periodo_idade_3) OR
                          (cteAplicacaoExames.periodo_idade_3 = ''))) THEN cteAplicacaoExames.qtd_periodo_idade_2
                        WHEN ((cteAplicacaoExames.periodo_idade_3 IS NOT NULL AND
                          cteAplicacaoExames.periodo_idade_3 <> '') AND
                          ((cteAplicacaoExames.idade >= cteAplicacaoExames.periodo_idade_3 AND
                          cteAplicacaoExames.idade < cteAplicacaoExames.periodo_idade_4) OR
                          (cteAplicacaoExames.periodo_idade_4 = ''))) THEN cteAplicacaoExames.qtd_periodo_idade_3
                        WHEN ((cteAplicacaoExames.periodo_idade_4 IS NOT NULL AND
                          cteAplicacaoExames.periodo_idade_4 <> '') AND
                          cteAplicacaoExames.idade >= cteAplicacaoExames.periodo_idade_4) THEN cteAplicacaoExames.qtd_periodo_idade_4
                        ELSE cteAplicacaoExames.periodo_meses
                      END)
                  END) AS periodicidade,
                  PedidoExame.codigo AS codigo_pedido_exame,
                  PedidoExame.exame_admissional,
                  cteAplicacaoExames.codigo_exame,
                  cteAplicacaoExames.EX_ANTIGO,
                  cteAplicacaoExames.EX_ATUAL,
                  cteAplicacaoExames.RIS_ANTIGO,
                  cteAplicacaoExames.RIS_ATUAL,
                  cteAplicacaoExames.codigo_matriz,
                  cteAplicacaoExames.codigo_unidade,
                  PedidoExame.exame_demissional AS exame_demissional,
                  PedidoExame.exame_retorno AS exame_retorno,
                  PedidoExame.exame_periodico AS exame_periodico,
                  PedidoExame.exame_mudanca AS exame_mudanca,
                  PedidoExame.exame_monitoracao AS exame_monitoracao,
                  cteAplicacaoExames.nome_fantasia AS unidade_descricao,
                  cteAplicacaoExames.ativo AS situacao,
                  (CASE
                    WHEN PedidoExame.exame_retorno = 1 THEN 'R'
                    WHEN PedidoExame.exame_demissional = 1 THEN 'D'
                    WHEN PedidoExame.exame_mudanca = 1 THEN 'M'
                    WHEN (PedidoExame.exame_admissional = 1 AND
                      PedidoExame.codigo IS NULL) THEN 'A'
                    ELSE 'P'
                  END) AS tipo_exame,
                  cteAplicacaoExames.matricula,
                  cteAplicacaoExames.codigo_cf,
                  cteAplicacaoExames.admissao,
                  cteAplicacaoExames.codigo_setor,
                  (CASE
                    WHEN PedidoExame.exame_retorno = 1 THEN 5
                    WHEN PedidoExame.exame_demissional = 1 THEN 2
                    WHEN PedidoExame.exame_mudanca = 1 THEN 3
                    WHEN (PedidoExame.exame_admissional = 1 AND
                      PedidoExame.codigo IS NULL) THEN 1
                    ELSE 4
                  END) AS codigo_tipo_exame
                FROM cteAplicacaoExames AS [cteAplicacaoExames]
                LEFT JOIN RHHealth.dbo.pedidos_exames AS [PedidoExame] WITH (NOLOCK)
                    ON ([PedidoExame].[codigo] = (SELECT TOP 1 [ped].[codigo]
                                                FROM RHHealth.dbo.pedidos_exames ped WITH (NOLOCK)
                                                    INNER JOIN RHHealth.dbo.itens_pedidos_exames ipe WITH (NOLOCK) on ped.codigo = ipe.codigo_pedidos_exames
                                                    INNER JOIN RHHealth.dbo.itens_pedidos_exames_baixa ipeb WITH (NOLOCK) ON ipe.codigo = ipeb.codigo_itens_pedidos_exames
                                                WHERE [ped].[pontual] = 0
                                                    AND [ped].[codigo_cliente_funcionario] = [cteAplicacaoExames].[codigo_cf]
                                                    AND [ped].[codigo_cliente] = [cteAplicacaoExames].[codigo_unidade]
                                                    AND [ipe].[codigo_exame] = [cteAplicacaoExames].[codigo_exame]
                                                    AND [ped].[codigo_status_pedidos_exames] <> 5
                                                ORDER BY [ipeb].[data_realizacao_exame] DESC, [ped].[codigo] DESC))
                WHERE 1 = 1)
                ,
                cetBaixaPedido
                AS (SELECT
                  ctePedidosExames.*,
                  ItemPedidoExameBaixa.codigo,
                  CASE
                    WHEN ctePedidosExames.codigo_pedido IS NULL THEN 1
                    WHEN ItemPedidoExameBaixa.codigo IS NULL THEN 1
                    ELSE 0
                  END AS pendente,
                  CASE
                    WHEN ctePedidosExames.codigo_pedido IS NULL THEN ctePedidosExames.codigo_funcionario
                    WHEN ItemPedidoExameBaixa.codigo IS NULL THEN ctePedidosExames.codigo_funcionario
                    ELSE NULL
                  END AS funcionario_pendente,
                  (CASE
                    WHEN ctePedidosExames.periodicidade <> '' THEN (DATEADD(MONTH, CAST(ctePedidosExames.periodicidade AS int), ItemPedidoExameBaixa.data_realizacao_exame))
                    ELSE NULL
                  END) AS vencimento,
                  ItemPedidoExameBaixa.data_realizacao_exame AS ultima_baixa,
                  ItemPedidoExameBaixa.resultado AS resultado,
                  (CASE
                    WHEN ItemPedidoExame.compareceu IS NULL THEN ''
                    ELSE CASE
                        WHEN ItemPedidoExame.compareceu = 0 THEN 'NÃO'
                        ELSE 'SIM'
                      END
                  END) AS compareceu,
                  (CASE
                    WHEN (CASE
                        WHEN (ctePedidosExames.exame_admissional = 1 AND
                          (ctePedidosExames.periodo_apos_demissao IS NOT NULL AND
                          ctePedidosExames.periodo_apos_demissao <> '')) THEN DATEADD(MONTH, CAST(ctePedidosExames.periodo_apos_demissao AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                        ELSE DATEADD(MONTH, CAST(ctePedidosExames.periodicidade AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                      END) < CAST(GETDATE() AS date) THEN 1
                    ELSE 0
                  END) AS vencido,
                  (CASE
                    WHEN (CASE
                        WHEN (ctePedidosExames.exame_admissional = 1 AND
                          (ctePedidosExames.periodo_apos_demissao IS NOT NULL AND
                          ctePedidosExames.periodo_apos_demissao <> '')) THEN DATEADD(MONTH, CAST(ctePedidosExames.periodo_apos_demissao AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                        ELSE DATEADD(MONTH, CAST(ctePedidosExames.periodicidade AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                      END) < CAST(GETDATE() AS date) THEN ctePedidosExames.codigo_funcionario
                    ELSE NULL
                  END) AS funcionario_vencido,
                  ItemPedidoExameBaixa.data_realizacao_exame AS data_realizacao_exame,
                  (CASE
                    WHEN
                      (CASE
                        WHEN (ctePedidosExames.exame_admissional = 1 AND
                          (ctePedidosExames.periodo_apos_demissao IS NOT NULL AND
                          ctePedidosExames.periodo_apos_demissao <> '')) THEN (DATEADD(MONTH, CAST(ctePedidosExames.periodo_apos_demissao AS int), ItemPedidoExameBaixa.data_realizacao_exame))
                        ELSE DATEADD(MONTH, CAST(ctePedidosExames.periodicidade AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                      END) >= CAST(GETDATE() AS date) THEN 1
                    ELSE 0
                  END) AS vencer,
                  (CASE
                    WHEN
                      (CASE
                        WHEN (ctePedidosExames.exame_admissional = 1 AND
                          (ctePedidosExames.periodo_apos_demissao IS NOT NULL AND
                          ctePedidosExames.periodo_apos_demissao <> '')) THEN (DATEADD(MONTH, CAST(ctePedidosExames.periodo_apos_demissao AS int), ItemPedidoExameBaixa.data_realizacao_exame))
                        ELSE DATEADD(MONTH, CAST(ctePedidosExames.periodicidade AS int), ItemPedidoExameBaixa.data_realizacao_exame)
                      END) >= CAST(GETDATE() AS date) THEN ctePedidosExames.codigo_funcionario
                    ELSE NULL
                  END) AS funcionario_vencer,
                  CASE
                    WHEN ctePedidosExames.tipo_exame = 'P' THEN 'Periódico'
                    WHEN ctePedidosExames.tipo_exame = 'A' THEN 'Admissional'
                    WHEN ctePedidosExames.tipo_exame = 'R' THEN 'Retorno'
                    WHEN ctePedidosExames.tipo_exame = 'D' THEN 'Demissional'
                    WHEN ctePedidosExames.tipo_exame = 'M' THEN 'Mudança de Função'
                  END AS tipo_exame_descricao
                FROM ctePedidosExames AS [ctePedidosExames]
                    INNER JOIN RHHealth.dbo.itens_pedidos_exames AS [ItemPedidoExame] WITH (NOLOCK) ON ([ItemPedidoExame].[codigo_pedidos_exames] = [ctePedidosExames].[codigo_pedido]
                        AND [ItemPedidoExame].[codigo_exame] = [ctePedidosExames].[codigo_exame])
                    LEFT JOIN RHHealth.dbo.itens_pedidos_exames_baixa AS [ItemPedidoExameBaixa] WITH (NOLOCK) ON ([ItemPedidoExameBaixa].[codigo_itens_pedidos_exames] = [ItemPedidoExame].[codigo])
                WHERE 1 = 1)
                ";

        // print $query;exit;

        return $query;

    } //fim cte_posicao_exames_otimizada


    /**
     * [getPosicaoExamesAVencer description]
     *
     * metodo para pegar os exames a vencer pelo cpf do funcionario
     *
     * @param  [type] $cpf [description]
     * @return [type]      [description]
     */
    public function getPosicaoExamesAVencer($cpf,$de=null, $ate=null)
    {
        //periodo
        if(empty($de)) {
            $de = date('Ymd');
        }

        if(empty($ate)) {
            $base_periodo = strtotime('+1 month', strtotime(Date('Y-m-d')));
            $ate = date('Ymd', $base_periodo);
        }

        //pega a query de posicao de exames
        $query_posicao_exames = $this->cte_posicao_exames($cpf);

        //query auxiliar de tratamento
        $query_a_vencer = " SELECT
                                analitico.*,
                                NULL AS tipo_exame_descricao_monitoracao
                            FROM cetBaixaPedido AS [analitico]
                            WHERE [analitico].[cpf] = '".$cpf."'
                                AND [analitico].[vencer] = 1
                                AND (((([analitico].[tipo_exame] = 'R')
                                        AND ([analitico].[codigo_pedido] IS NOT NULL)
                                        AND ([analitico].[data_realizacao_exame] IS NOT NULL)
                                        AND ([analitico].[ativo] <> 0)))
                                OR ((([analitico].[tipo_exame] = 'M')
                                    AND ([analitico].[codigo_pedido] IS NOT NULL)
                                    AND ([analitico].[data_realizacao_exame] IS NOT NULL)
                                    AND ([analitico].[ativo] <> 0)))

                                OR ((([analitico].[tipo_exame] = 'P')
                                    AND ([analitico].[codigo_pedido] IS NOT NULL)
                                    AND ([analitico].[ativo] <> 0))))

                                AND (([analitico].[vencimento] BETWEEN '".$de."' AND '".$ate."')
                                    OR ([analitico].[pendente] = '1')
                                    OR ([analitico].[vencimento] IS NULL))";

        //monta a query final
        $query = ($query_posicao_exames.$query_a_vencer);

        // print($query); exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        // debug($dados);exit;

        //verifica se tem exames a vencer
        if(!empty($dados)) {

            return $dados;
        }

        //nao tem exames a vencer
        return false;

    }//fim posicao_exames

    /**
     * [getPodeAgendar description]
     *
     * metodo para retornar se tem pedido em abert, caso tenha não pode agendar um novo pedido
     *
     * @param  [type] $codigo_funcionario [description]
     * @return [type]                     [description]
     */
    public function getPodeAgendar($codigo_funcionario,$codigos_clientes= null)
    {

        //verifica se existe o codigo_funcionario
        if(empty($codigo_funcionario)) {
            return false;
        }

        //monta as conditions
        $conditions = array(
            'codigo_funcionario' => $codigo_funcionario,
            'codigo_status_pedidos_exames IN (1,2)',
        );

        //verifica se o parametro codigos_clientes não está null
        if(!is_null($codigos_clientes)) {
            $conditions[] = array('codigo_cliente IN ' => $codigos_clientes);
        }

        //pega os pedidos em aberto
        $pedidos_exames = $this->find()
            ->where($conditions)
            ->hydrate(false)
            ->all()
            ->toArray();

        // debug($pedidos_exames);exit;

        //verifica se pode agendar um novo exame
        if(!empty($pedidos_exames)) {
            return false;
        }

        return true;

    }//fim getPodeAgendar

    /**
     * [getPodeAgendarPeriodico description]
     *
     * metodo para retornar se tem pedido periodico em abert,o caso tenha não pode agendar um novo pedido
     *
     * @param  [type] $codigo_funcionario [description]
     * @return [type]                     [description]
     */
    public function getPodeAgendarPeriodico($codigo_funcionario,$codigos_clientes= null)
    {

        //verifica se existe o codigo_funcionario
        if(empty($codigo_funcionario)) {
            return false;
        }

        //monta as conditions
        $conditions = array(
            'codigo_funcionario' => $codigo_funcionario,
            'codigo_status_pedidos_exames IN (1,2)',
            'exame_periodico' => 1
        );

        //verifica se o parametro codigos_clientes não está null
        if(!is_null($codigos_clientes)) {
            $conditions[] = array('codigo_cliente IN ' => $codigos_clientes);
        }

        //pega os pedidos em aberto
        $pedidos_exames = $this->find()
            ->where($conditions)
            ->hydrate(false)
            ->all()
            ->toArray();

        // debug($conditions);debug($pedidos_exames->sql());exit;

        //verifica se pode agendar um novo exame
        if(!empty($pedidos_exames)) {
            return false;
        }

        return true;

    }//fim getPodeAgendarPeriodico


    //Verifica se existe assinatura do exame na matriz
    /**
     * [verificaExameTemAssinatura description]
     *
     * metodo para verificar se tem assinatura da matriz
     *
     * @param  [type] $codigo_servico          [description]
     * @param  [type] $codigo_cliente_alocacao [description]
     * @param  [type] $codigo_matriz           [description]
     * @return [type]                          [description]
     */
    public function verificaExameTemAssinatura($codigo_servico, $codigo_cliente_alocacao, $codigo_matriz)
    {
        //instancia a tabela cliente produto servico2
        $ClienteProdutoServico2 = TableRegistry::get('ClienteProdutoServico2');

        //campos do select
        $fields = array(
            'ClienteProdutoServico2.codigo',
            'ClienteProdutoServico2.codigo_servico',
            'ClienteProdutoServico2.valor',
            'ClienteProduto.codigo_cliente'
        );

        $joins = array(
            array(
                'table' => 'cliente_produto',
                'alias' => 'ClienteProduto',
                'type' => 'INNER',
                'conditions' => 'ClienteProduto.codigo = ClienteProdutoServico2.codigo_cliente_produto',
            )
        );

        $conditions = array(
            "ClienteProdutoServico2.codigo_servico" => $codigo_servico,
            'ClienteProduto.codigo_cliente' => $codigo_cliente_alocacao
        );

        //executa a query para saber se tem assinaturas
        $assinatura = $ClienteProdutoServico2->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->first();

        //Se não encontrou assinatura no cliente de alocação (filial)
        if(empty($assinatura)){

            $conditions = array(
                "ClienteProdutoServico2.codigo_servico" => $codigo_servico,
                'ClienteProduto.codigo_cliente' => $codigo_matriz
            );

            //executa a query para saber se tem assinaturas na matriz
            $assinatura = $ClienteProdutoServico2->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->hydrate(false)
                ->first();
        }

        return $assinatura;
    }//fim verificaExameTemAssinatura

    //Verifica se existe fornecedor com o exame na lista de preços para o cliente de alocação do funcionario
    public function verificaExameTemFornecedor($codigo_servico, $codigo_cliente_alocacao)
    {

        //instancia a tabela cliente produto servico2
        $ClientesFornecedores = TableRegistry::get('ClientesFornecedores');

        $fields = array(
            'ClientesFornecedores.codigo_fornecedor'
        );

        $conditions = array(
            "ClientesFornecedores.codigo_cliente = {$codigo_cliente_alocacao} ",
            "ClientesFornecedores.ativo = 1",
            " EXISTS (SELECT top 1 *
                        FROM listas_de_preco lp
                        INNER JOIN listas_de_preco_produto lpp on lpp.codigo_lista_de_preco = lp.codigo
                        INNER JOIN listas_de_preco_produto_servico lpps on lpps.codigo_lista_de_preco_produto = lpp.codigo
                    WHERE lpps.codigo_servico = {$codigo_servico} and lp.codigo_fornecedor = ClientesFornecedores.codigo_fornecedor)"
        );

        $fornecedores = $ClientesFornecedores->find()
            ->select($fields)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        return $fornecedores;
    }

    /**
     * [retornaFornecedoresParaExamesListados description]
     *
     * metodo para pegar os fornecedores e os exames de cada fornecedor
     *
     * @param  [type] $exames_lista   [description]
     * @param  [type] $parametros     [description]
     * @param  [type] $codigo_cliente [description]
     * @return [type]                 [description]
     */
    public function retornaFornecedoresExames($exames_lista, $parametros, $codigo_cliente=null, $tipo_atendimento = null, $tipo_agendamento = null)
    {
        //instancia a model servico
        $this->Servico = TableRegistry::get('Servico');

        //monta os campos do select
        $fields = array(
            'Servico.codigo',
            'Servico.descricao',
            // 'Servico.telefone',
            'telefone' => '(SELECT TOP 1 CONCAT(\'(\',ddd, \') \', descricao) FROM fornecedores_contato WHERE codigo_tipo_retorno = 1 AND codigo_fornecedor = Fornecedor.codigo)',
            'ListaPrecoProdutoServico.codigo',
            'ListaPrecoProdutoServico.valor',
            'ListaPrecoProdutoServico.codigo_servico',
            'ListaPrecoProdutoServico.tipo_atendimento',
            'ListaPreco.codigo_fornecedor',
            'Fornecedor.codigo',
            'Fornecedor.razao_social',
            'Fornecedor.nome',
            'Fornecedor.utiliza_sistema_agendamento',
            'Fornecedor.tipo_atendimento',
            'FornecedorEndereco.numero',
            'FornecedorEndereco.complemento',
            'fornecedor_complemento' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.complemento)',
            'FornecedorEndereco.latitude',
            'FornecedorEndereco.longitude',
            'FornecedorEndereco.logradouro',
            'FornecedorEndereco.cidade',
            'FornecedorEndereco.estado_descricao',
            'Exame.codigo',
            'Exame.descricao',

            // dados com UTF8
            'utf8_fornecedor_endereco_logradouro' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.logradouro)',
            'utf8_fornecedor_endereco_complemento' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.complemento)',
            'utf8_fornecedor_endereco_cidade' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.cidade)',
            'utf8_fornecedor_endereco_estado_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.estado_descricao)',
            'utf8_exame_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'utf8_fornecedor_nome' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)'
        );
        //monta os relacionamentos da query
        $joins = array(
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'Exame.codigo_servico = Servico.codigo',
            ),
            array(
                'table' => 'listas_de_preco_produto_servico',
                'alias' => 'ListaPrecoProdutoServico',
                'type' => 'INNER',
                'conditions' => 'ListaPrecoProdutoServico.codigo_servico = Servico.codigo',
            ),
            array(
                'table' => 'listas_de_preco_produto',
                'alias' => 'ListaPrecoProduto',
                'type' => 'INNER',
                'conditions' => 'ListaPrecoProduto.codigo = ListaPrecoProdutoServico.codigo_lista_de_preco_produto',
            ),
            array(
                'table' => 'listas_de_preco',
                'alias' => 'ListaPreco',
                'type' => 'INNER',
                'conditions' => 'ListaPreco.codigo = ListaPrecoProduto.codigo_lista_de_preco',
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'Fornecedor.codigo = ListaPreco.codigo_fornecedor',
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'LEFT',
                'conditions' => 'FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo',
            ),
            array(
                'table' => 'clientes_fornecedores',
                'alias' => 'ClienteFornecedor',
                'type' => 'LEFT',
                'conditions' => 'ClienteFornecedor.codigo_fornecedor = Fornecedor.codigo AND ClienteFornecedor.ativo = 1',
            ),
        );

        // $options['recursive'] = '-1';
        //condições que a query vai executar
        $conditions = array("ListaPreco.codigo_fornecedor is not null
                            AND Servico.codigo IN (".$exames_lista.")
                            AND Servico.ativo  = 1
                            AND Fornecedor.ativo = 1");

        //condicoes para pegar o raio que deve procurar
        if((isset($parametros['latitude_min']) && !empty($parametros['latitude_min'])) && (isset($parametros['latitude_max']) && !empty($parametros['latitude_max'])) && (isset($parametros['longitude_min']) && !empty($parametros['longitude_min'])) && (isset($parametros['longitude_max']) && !empty($parametros['longitude_max'])) ) {

            $conditions = array_merge($conditions, array("FornecedorEndereco.latitude BETWEEN {$parametros['latitude_min']} and {$parametros['latitude_max']}"));
            $conditions = array_merge($conditions, array("FornecedorEndereco.longitude BETWEEN {$parametros['longitude_min']} and {$parametros['longitude_max']}"));
        }//fim verificacao da lat e long maxim

        //verifica se existe o codigo cliente
        if(isset($codigo_cliente) && !empty($codigo_cliente)) {
            $joins_cliente[] = array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'LEFT',
                'conditions' => 'Cliente.codigo = ClienteFornecedor.codigo_cliente',
            );
            $joins  = array_merge($joins, $joins_cliente);
            $conditions = array_merge($conditions, array('Cliente.codigo' => $codigo_cliente));
        }//fim codigo cliente


        //verifica se temos um filtro de tipo de atendimento
        if(!is_null($tipo_atendimento)) {
            $conditions = array_merge($conditions, array('Fornecedor.tipo_atendimento' => $tipo_atendimento));
        }//fim tipo_atendimento

        // if(!is_null($tipo_agendamento)) {
            // $conditions = array_merge($conditions, array('Fornecedor.tipo_agendamento' => $tipo_agendamento));
        // }//fim tipo_agendamento
        // $model_Servico->bindModel(array(
        //     'belongsTo' => array(
        //         'Fornecedor' => array(
        //             'alias' => 'Fornecedor',
        //             'foreignKey' => FALSE,
        //             )
        //         )
        //     ));

        // $model_Servico->virtualFields = array(
        //     'telefone' => 'SELECT TOP 1
        //     CONCAT( (CASE WHEN ddd IS NOT NULL THEN CONCAT(ddd, "-") ELSE "" END) , descricao)
        //     FROM fornecedores_contato
        //     WHERE codigo_fornecedor = Fornecedor.codigo
        //     AND codigo_tipo_retorno = 1
        //     ORDER BY codigo DESC',
        //     );

        $dados = $this->Servico->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        // debug($dados);exit;

        return $dados;
    }

    /**
     * metodo para verificar se existe pedidos de exames em aberto
     *
     * @param  int $codigo_func_setor_cargo
     * @return boolean
     */
    public function validaPedidoExameEmAberto(int $codigo_func_setor_cargo)
    {
        //verifica se já existe um pedido em aberto
        $conditions = [
            'codigo_func_setor_cargo' => $codigo_func_setor_cargo,
            'codigo_status_pedidos_exames' => 1
        ];

        $pedidos_aberto = $this->find()->where($conditions);

        return boolval((!empty($pedidos_aberto)));

    }//fim verifica_pedido_exame_aberto

    /**
     * [notificacaoExamesAVencer description]
     *
     * pega os usuarios
     *
     * @param  [type] $dias_a_vencer [description]
     * @return [type]                [description]
     */
    public function notificacaoExamesAVencer($dias_a_vencer)
    {
        //instancia a model de usuario
        $this->UsuariosDados = TableRegistry::get('UsuariosDados');

        //filtros
        $de = date('Y-m-d');
        $base_periodo = strtotime('+'.$dias_a_vencer.' days', strtotime(Date('Y-m-d')));
        $ate = date('Y-m-d', $base_periodo);

        //pega os cpfs para exames a vencer
        $usuarios = $this->UsuariosDados->getUsuariosPush();
        $usuarios_exames = array();

        //verifica se tem usuarios com notificacoes habilitadas
        if(!empty($usuarios)) {

            //varre os usuarios que querem receber notificação
            foreach($usuarios as $user) {

                //pega se tem exames a vencer daqui 30 dias
                $exames = $this->getPosicaoExamesAVencer($user['cpf'], $de, $ate);

                //verifica se tem registros de exames a vencer
                if(!empty($exames)) {
                    //seta os usuarios que tem exames a vencer
                    $usuarios_exames[] = $user;
                }//fim exames

            }//fim foreach

        }//fim usuarios

        return $usuarios_exames;

    }//fim notificacaoExamesAVencer($dias_a_vencer)

    /**
     * [historico_exames_ocupacional description]
     *
     * metodo para pegar os ultimos exames
     *
     * @param  [type] $condicoes [description]
     * @return [type]                     [description]
     */
    public function historico_exames_ocupacional($condicoes = [], $showAll = false)
    {
        if($showAll){
            $fields = array(
                'tipo' => '\'1\'',
                'titulo_tipo' => '\'Ocupacional\'',
                'codigo'=>'ItemPedidoExame.codigo',
                'codigo_exame' => 'Exame.codigo',
                'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
                'clinica' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',
                'data_realizacao' => 'ItemPedidoExame.data_realizacao_exame',
                'codigo_pedido_exame'=>'PedidosExames.codigo',
                'codigo_exame' => 'Exame.codigo',
                'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
                'nome_fantasia_solicitante' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
                'razao_social_solicitante' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)',
                'cnpj_solicitante' => 'Cliente.codigo_documento',
                'tipo_exame' => '(CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno ao trabalho\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração pontual\'
                ELSE \'\'
            END)',
                'codigo_fornecedor' => 'Fornecedor.codigo',
                'nome_credenciado' => 'Fornecedor.nome',


                'endereco' => 'RHHealth.dbo.ufn_decode_utf8_string(CONCAT(FornecedorEndereco.logradouro,\',\', FornecedorEndereco.numero,\' \', FornecedorEndereco.complemento,\' - \', FornecedorEndereco.bairro, \' - \', FornecedorEndereco.cidade, \' - \', FornecedorEndereco.estado_abreviacao))',
                'lat' => 'FornecedorEndereco.latitude',
                'long' => 'FornecedorEndereco.longitude',
            );
        } else {
            $fields = array(
                'tipo' => '\'1\'',
                'titulo_tipo' => '\'Ocupacional\'',
                'codigo'=>'ItemPedidoExame.codigo',
                'codigo_exame' => 'Exame.codigo',
                'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
                'clinica' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',
                'data_realizacao' => 'ItemPedidoExame.data_realizacao_exame'
            );
        }


        $joins = $this->getJoinsConsultas('LEFT');

        if(!empty($condicoes) && $showAll){

            if($showAll){ // <-- BUG-PC-271
                $fields['clinica_telefone'] = '(SELECT TOP 1 CONCAT(\'(\',ddd, \') \', descricao) FROM fornecedores_contato WHERE codigo_tipo_retorno = 1 AND codigo_fornecedor = Fornecedor.codigo)';
                $fields['clinica_email'] = '(SELECT TOP 1 descricao FROM fornecedores_contato WHERE codigo_tipo_retorno = 2 AND codigo_fornecedor = Fornecedor.codigo)';
                $fields['clinica_endereco'] = 'RHHealth.dbo.ufn_decode_utf8_string(CONCAT(FornecedorEndereco.logradouro,\',\', FornecedorEndereco.numero,\' \', FornecedorEndereco.complemento,\' - \', FornecedorEndereco.bairro, \' - \', FornecedorEndereco.cidade, \' - \', FornecedorEndereco.estado_abreviacao))';
                $fields['empresa'] = 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)';
                $fields['resultado'] = '(
                    CASE WHEN FichaClinica.parecer = 1 THEN \'Apto\'
                    WHEN FichaClinica.parecer = 0 THEN \'Inapto\'
                ELSE \'\' END)';
                $fields['codigo_fornecedor'] = 'Fornecedor.codigo';
                $fields['imagem_exame'] = 'AnexosExames.caminho_arquivo';
                $fields['id_agendamento'] = 'ItemPedidoExame.codigo';
            }
        }

        $conditions = [];

        if(!empty($condicoes)){
            $conditions = array_merge($condicoes, $conditions);
        }

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);
        // debug($dados->sql());exit;
        return $dados;

    }//fim historico_exames_ocupacional

    public function getPedidoExames(int $codigo_pedido)
    {

        $fields = array(
            'codigo_pedido'=>'PedidosExames.codigo',
            'data_solicitacao'=>'PedidosExames.data_inclusao',
            'data_agendado'=>'AgendamentoExame.data',

            'tipo_exame' => '(CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno ao trabalho\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração pontual\'
                ELSE \'\'
            END)',
            //'status_pedidos_exames'=>'RHHealth.dbo.ufn_decode_utf8_string(StatusPedidosExames.descricao)',
            'status_pedidos_exames'=> "(CASE
            WHEN StatusPedidosExames.descricao = 'PENDENTE DE BAIXA' THEN 'Aguardando baixa'
            WHEN StatusPedidosExames.descricao = 'BAIXADO PARCIALMENTE' THEN 'Em Andamento'
            WHEN StatusPedidosExames.descricao = 'BAIXADO TOTAL' THEN 'Concluído'
            WHEN StatusPedidosExames.descricao LIKE '%PENDENTE AGENDAMENTO%' THEN 'Pendente'
            WHEN StatusPedidosExames.descricao = 'CANCELADO' THEN 'Cancelado'
            ELSE ''
            END)",

            'codigo_cliente_funcionario'=>'PedidosExames.codigo_cliente_funcionario',
            'codigo_func_setor_cargo'=>'PedidosExames.codigo_func_setor_cargo',

            'codigo_item_pedido'=>'ItemPedidoExame.codigo',
            'data_inicio_triagem'=>'ItemPedidoExame.data_inicio_triagem',
            'data_fim_triagem'=>'ItemPedidoExame.data_fim_triagem',
            'data_inicio_realizacao_exame'=>'ItemPedidoExame.data_inicio_realizacao_exame',
            'hora_realizacao_exame'=>'ItemPedidoExame.hora_realizacao_exame',

            'codigo_exame'=>'Exame.codigo',
            'exame'=>'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'status_exame'=> '(CASE
            WHEN ItemPedidoExame.data_realizacao_exame IS NOT NULL THEN \'Realizado\'
            ELSE CASE WHEN ItemPedidoExame.compareceu = 0 THEN \'Não Compareceu\'
            ELSE \'Pendente\' END
            END)',

            'codigo_fornecedor'=>'Fornecedor.codigo',
            'nome_credenciado' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',
            'endereco' => 'RHHealth.dbo.ufn_decode_utf8_string(CONCAT(FornecedorEndereco.logradouro,\',\', FornecedorEndereco.numero,\' \', FornecedorEndereco.complemento,\' - \', FornecedorEndereco.bairro, \' - \', FornecedorEndereco.cidade, \' - \', FornecedorEndereco.estado_abreviacao))',
            'local'=>'Fornecedor.nome',
            'data'=>'AgendamentoExame.data',
            'hora'=>'AgendamentoExame.hora',
            'tipo_atendimento'=>'Fornecedor.tipo_atendimento',

            'data_realizacao_exame'=>'ItemPedidoExame.data_realizacao_exame',
            'medico'=>'Medicos.nome',
            'imagem_exame'=>'AnexosExames.caminho_arquivo',
            'imagem_ficha_clinica' => 'AnexosFichasClinicas.caminho_arquivo',
            'data_resultado_exame' => 'ItemPedidoExameBaixa.data_realizacao_exame',
            'codigo_item_pedido_exame_baixado' => 'ItemPedidoExameBaixa.codigo',
            'status_resultado' => "ItemPedidoExameBaixa.resultado",
            'laudo' => 'ItemPedidoExame.laudo',
            'imagem_laudo' => 'AnexosLaudos.caminho_arquivo',
        );

        $joins  = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_pedidos_exames = PedidosExames.codigo',
            ),
            array(
                'table' => 'status_pedidos_exames',
                'alias' => 'StatusPedidosExames',
                'type' => 'INNER',
                'conditions' => 'StatusPedidosExames.codigo = PedidosExames.codigo_status_pedidos_exames',
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_exame = Exame.codigo',
            ),
            array(
                'table' => 'agendamento_exames',
                'alias' => 'AgendamentoExame',
                'type' => 'LEFT',
                'conditions' => 'AgendamentoExame.codigo_itens_pedidos_exames = ItemPedidoExame.codigo',
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'LEFT',
                'conditions' => 'Fornecedor.codigo = ItemPedidoExame.codigo_fornecedor',
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'LEFT',
                'conditions' => 'FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo',
            ),
            array(
                'table' => 'anexos_exames',
                'alias' => 'AnexosExames',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AnexosExames.codigo_item_pedido_exame',
            ),
            array(
                'table' => 'itens_pedidos_exames_baixa',
                'alias' => 'ItemPedidoExameBaixa',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = ItemPedidoExameBaixa.codigo_itens_pedidos_exames',
            ),
            array(
                'table' => 'fichas_clinicas',
                'alias' => 'FichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'PedidosExames.codigo = FichasClinicas.codigo_pedido_exame AND FichasClinicas.codigo = (SELECT TOP 1 codigo FROM fichas_clinicas WHERE codigo_pedido_exame = PedidosExames.codigo ORDER BY codigo ASC)'
            ),
            array(
                'table' => 'anexos_fichas_clinicas',
                'alias' => 'AnexosFichasClinicas',
                'type' => 'LEFT',
                'conditions' => 'AnexosFichasClinicas.codigo_ficha_clinica = FichasClinicas.codigo'
            ),
            array(
                'table' => 'medicos',
                'alias' => 'Medicos',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo_medico = Medicos.codigo',
            ),
            array(
                'table' => 'anexos_laudos',
                'alias' => 'AnexosLaudos',
                'type' => 'LEFT',
                'conditions' => 'ItemPedidoExame.codigo = AnexosLaudos.codigo_item_pedido_exame',
            )
        );

        $conditions = "PedidosExames.codigo = ".$codigo_pedido;

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);

        // debug($dados->sql());exit;

        return $dados;

    }//fim getPedidoExames

    public function getFuncionarios(int $codigo_fornecedor = null, string $busca = null)
    {

        $fields = array(
            'codigo'=>'Funcionarios.codigo',
            'cpf'=>'Funcionarios.cpf',
            'nome'=>'Funcionarios.nome',
            'matricula'=>'ClienteFuncionario.matricula',
            'codigo_pedido'=>'PedidosExames.codigo',
            'tipo_pedido_exame' => 'CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração Pontual\'
                WHEN PedidosExames.qualidade_vida = 1 THEN \'Qualidade de vida\'
                END',
            'status' => 'StatusPedidosExames.descricao'
        );

        $joins  = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_pedidos_exames = PedidosExames.codigo',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = PedidosExames.codigo_funcionario',
            ),
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'LEFT',
                'conditions' => 'ClienteFuncionario.codigo = PedidosExames.codigo_cliente_funcionario',
            ),
            array(
                'table' => 'status_pedidos_exames',
                'alias' => 'StatusPedidosExames',
                'type' => 'LEFT',
                'conditions' => 'StatusPedidosExames.codigo = PedidosExames.codigo_status_pedidos_exames',
            )
        );

        $conditions = "
            ItemPedidoExame.codigo_fornecedor = ".$codigo_fornecedor."
            AND PedidosExames.codigo_status_pedidos_exames != 3
            AND PedidosExames.codigo_status_pedidos_exames != 5
            AND (PedidosExames.codigo LIKE '%".$busca."%'
                OR Funcionarios.cpf LIKE '%".$busca."%'
                OR Funcionarios.nome LIKE '%".$busca."%')";

        $group = "Funcionarios.codigo, Funcionarios.cpf, Funcionarios.nome, PedidosExames.codigo,
            PedidosExames.exame_admissional,
            PedidosExames.exame_periodico,
            PedidosExames.exame_demissional,
            PedidosExames.exame_retorno,
            PedidosExames.exame_mudanca,
            PedidosExames.exame_monitoracao,
            PedidosExames.qualidade_vida,
            StatusPedidosExames.descricao,
            ClienteFuncionario.matricula
        ";

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group);

        return $dados;

    }//fim getFuncionarios

    public function __getDadosFuncionarios(int $codigo_fornecedor = null, int $codigo_usuario = null, string $busca = null)
    {

        $fields = array(
            'codigo'=>'Funcionarios.codigo',
            'cpf'=>'Funcionarios.cpf',
            'nome'=>'Funcionarios.nome',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'codigo_cliente_funcionario' => 'cf.codigo',
            'matricula'=>'cf.matricula',
            'codigo_pedido'=>'pe.codigo',
            'tipo_pedido_exame' => 'CASE
                WHEN pe.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN pe.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN pe.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN pe.exame_retorno = 1 THEN \'Retorno\'
                WHEN pe.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN pe.exame_monitoracao = 1 THEN \'Monitoração Pontual\'
                WHEN pe.qualidade_vida = 1 THEN \'Qualidade de vida\'
                END',
            'status_pedido' => 'spe.descricao',
            'empresa' => 'c.codigo',
        );

        $joins  = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'cf',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = cf.codigo_funcionario',
            ),

            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'fsc',
                'type' => 'INNER',
                'conditions' => 'cf.codigo = fsc.codigo_cliente_funcionario and fsc.data_fim is null',
            ),

            array(
                'table' => 'clientes_fornecedores',
                'alias' => 'cfo',
                'type' => 'INNER',
                'conditions' => 'cfo.codigo_cliente = fsc.codigo_cliente',
            ),

            array(
                'table' => 'usuario_multi_fornecedor',
                'alias' => 'umf',
                'type' => 'INNER',
                'conditions' => 'umf.codigo_fornecedor = cfo.codigo_fornecedor',
            ),

            array(
                'table' => 'usuario_multi_cliente',
                'alias' => 'umc',
                'type' => 'INNER',
                'conditions' => 'umc.codigo_cliente = cfo.codigo_cliente',
            ),

            array(
                'table' => 'cliente',
                'alias' => 'c',
                'type' => 'INNER',
                'conditions' => 'cf.codigo_cliente = c.codigo',
            ),

            array(
                'table' => 'pedidos_exames',
                'alias' => 'pe',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = pe.codigo_funcionario and
                pe.codigo_cliente_funcionario = cf.codigo and
                fsc.codigo = pe.codigo_func_setor_cargo and pe.codigo_status_pedidos_exames NOT IN (3,5) and pe.pontual <> 1',
            ),

            array(
                'table' => 'status_pedidos_exames',
                'alias' => 'spe',
                'type' => 'LEFT',
                'conditions' => 'spe.codigo = pe.codigo_status_pedidos_exames',
            )
        );

        $conditions = "
            umf.codigo_usuario = ".$codigo_usuario."
            AND umc.codigo_usuario = ".$codigo_usuario." AND umc.codigo_cliente = fsc.codigo_cliente
            AND cfo.codigo_fornecedor = ".$codigo_fornecedor."
            AND (pe.codigo LIKE '%".$busca."%'
                OR Funcionarios.cpf LIKE '%".$busca."%'
                OR Funcionarios.nome LIKE '%".$busca."%')";

        $group = "Funcionarios.codigo, Funcionarios.cpf, Funcionarios.nome, Funcionarios.data_nascimento, pe.codigo,
            pe.exame_admissional,
            pe.exame_periodico,
            pe.exame_demissional,
            pe.exame_retorno,
            pe.exame_mudanca,
            pe.exame_monitoracao,
            pe.qualidade_vida,
            spe.descricao,
            cf.matricula,
            cf.codigo,
            c.codigo
        ";

        $funcionarios = TableRegistry::getTableLocator()->get('Funcionarios');

        $dados = $funcionarios->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group);

        return $dados;

    }//fim getFuncionarios

    public function getDadosFuncionarios(int $codigo_fornecedor = null, int $codigo_usuario = null, string $busca = null)
    {

        $fields = array(
            'codigo'=>'Funcionarios.codigo',
            'cpf'=>'Funcionarios.cpf',
            'nome'=>'RHHealth.dbo.ufn_decode_utf8_string(Funcionarios.nome)',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'codigo_cliente_funcionario' => 'cf.codigo',
            'matricula'=>'cf.matricula',
            'codigo_pedido'=>'pe.codigo',
            'tipo_pedido_exame' => 'CASE
                WHEN pe.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN pe.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN pe.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN pe.exame_retorno = 1 THEN \'Retorno\'
                WHEN pe.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN pe.exame_monitoracao = 1 THEN \'Monitoração Pontual\'
                WHEN pe.qualidade_vida = 1 THEN \'Qualidade de vida\'
                END',
            'status_pedido' => 'spe.descricao'
        );
        $joins  = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'cf',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = cf.codigo_funcionario',
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'fsc',
                'type' => 'INNER',
                'conditions' => 'cf.codigo = fsc.codigo_cliente_funcionario and fsc.data_fim is null',
            ),
            array(
                'table' => 'clientes_fornecedores',
                'alias' => 'cfo',
                'type' => 'INNER',
                'conditions' => 'cfo.codigo_cliente = fsc.codigo_cliente_alocacao',
            ),
            array(
                'table' => 'usuario',
                'alias' => 'u',
                'type' => 'INNER',
                'conditions' => 'u.codigo = '.$codigo_usuario.''
            ),
            array(
                'table' => 'usuario_multi_fornecedor',
                'alias' => 'umf',
                'type' => 'LEFT',
                'conditions' => 'umf.codigo_usuario = u.codigo and umf.codigo_fornecedor = '.$codigo_fornecedor.' ',
            ),
            array(
                'table' => 'usuario_multi_cliente',
                'alias' => 'umc',
                'type' => 'LEFT',
                'conditions' => 'umc.codigo_cliente = cfo.codigo_cliente',
            ),
            array(
                'table' => 'pedidos_exames',
                'alias' => 'pe',
                'type' => 'LEFT',
                'conditions' => 'Funcionarios.codigo = pe.codigo_funcionario and
                pe.codigo_cliente_funcionario = cf.codigo and
                fsc.codigo = pe.codigo_func_setor_cargo and pe.codigo_status_pedidos_exames <> 5 and pe.pontual <> 1',
            ),
            array(
                'table' => 'status_pedidos_exames',
                'alias' => 'spe',
                'type' => 'LEFT',
                'conditions' => 'spe.codigo = pe.codigo_status_pedidos_exames',
            )
        );
        $conditions = "
            umf.codigo_usuario = ".$codigo_usuario."
            AND cfo.codigo_fornecedor = ".$codigo_fornecedor."
            AND (pe.codigo LIKE '%".$busca."%'
                OR Funcionarios.cpf LIKE '%".$busca."%'
                OR Funcionarios.nome LIKE '%".$busca."%')";
        $group = "Funcionarios.codigo, Funcionarios.cpf, Funcionarios.nome, Funcionarios.data_nascimento, pe.codigo,
            pe.exame_admissional,
            pe.exame_periodico,
            pe.exame_demissional,
            pe.exame_retorno,
            pe.exame_mudanca,
            pe.exame_monitoracao,
            pe.qualidade_vida,
            spe.descricao,
            cf.matricula,
            cf.codigo
        ";

        $order = array('Funcionarios.nome ASC', 'pe.codigo DESC');

        $funcionarios = TableRegistry::getTableLocator()->get('Funcionarios');
        $dados = $funcionarios->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group)
            ->order($order)
            ->limit(50);

        // debug($dados->sql());exit;

        return $dados;


    }//fim getFuncionarios


    public function getDadosFuncionariosSimples(int $codigo_fornecedor = null, int $codigo_usuario = null, string $busca = null)
    {

        $fields = array(
            'codigo'=>'Funcionarios.codigo',
            'cpf'=>'Funcionarios.cpf',
            'nome'=>'RHHealth.dbo.ufn_decode_utf8_string(Funcionarios.nome)',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'codigo_cliente_funcionario' => 'cf.codigo',
            'matricula'=>'cf.matricula'
        );
        $joins  = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'cf',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = cf.codigo_funcionario',
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'fsc',
                'type' => 'INNER',
                'conditions' => 'cf.codigo = fsc.codigo_cliente_funcionario and fsc.data_fim is null',
            ),
            array(
                'table' => 'clientes_fornecedores',
                'alias' => 'cfo',
                'type' => 'INNER',
                'conditions' => 'cfo.codigo_cliente = fsc.codigo_cliente_alocacao',
            ),
            array(
                'table' => 'usuario',
                'alias' => 'u',
                'type' => 'INNER',
                'conditions' => 'u.codigo = '.$codigo_usuario.''
            ),
            array(
                'table' => 'usuario_multi_fornecedor',
                'alias' => 'umf',
                'type' => 'LEFT',
                'conditions' => 'umf.codigo_usuario = u.codigo and umf.codigo_fornecedor = '.$codigo_fornecedor.' ',
            ),
            array(
                'table' => 'usuario_multi_cliente',
                'alias' => 'umc',
                'type' => 'LEFT',
                'conditions' => 'umc.codigo_cliente = cfo.codigo_cliente',
            )

        );
        $conditions = "
            umf.codigo_usuario = ".$codigo_usuario."
            AND cfo.codigo_fornecedor = ".$codigo_fornecedor."
            AND (Funcionarios.cpf LIKE '%".$busca."%'
                OR Funcionarios.nome LIKE '%".$busca."%')";
        $group = "Funcionarios.codigo,
            Funcionarios.cpf,
            Funcionarios.nome,
            Funcionarios.data_nascimento,
            cf.matricula,
            cf.codigo
        ";

        $order = array('Funcionarios.nome ASC');

        $funcionarios = TableRegistry::getTableLocator()->get('Funcionarios');
        $dados = $funcionarios->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group)
            ->order($order)
            ->limit(50);

        return $dados;


    }//fim getFuncionarios

    /**
     * [obtemDadosComplementares pega os dados complementares para mandar na tela da ficha clinica]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                    [description]
     */
    public function obtemDadosComplementares($codigo_pedido_exame,$busca_aso = false)
    {
        //vai na tabela de configuração para pegar o valor config do exame clinico
        $this->Profissional = TableRegistry::get('Profissional');

        //verifica se deve buscar o aso
        if($busca_aso) {
            $Configuracao = TableRegistry::get('Configuracao');

            $codigo_exame_aso = $Configuracao->getChave('INSERE_EXAME_CLINICO');

            //verifica se tem codigo do aso cadastrado
            if(is_null($codigo_exame_aso)) {
                throw new Exception("Não existe uma configuração válida para a chave INSERE_EXAME_CLINICO em Administrativo > Cadastro > Configurações de Sistema!");
            }

            //esta query obtem todos os medicos disponiveis de todos os fornecedores utilizados no pedido de exame formando um unico grupo
            $values = $this->Profissional->getMedicosFornecedores($codigo_pedido_exame,$codigo_exame_aso);
        }
        else {
            //esta query obtem todos os medicos disponiveis de todos os fornecedores utilizados no pedido de exame formando um unico grupo
            $values = $this->Profissional->getMedicosFornecedores($codigo_pedido_exame);
        }

        $dados = $this->getDadosComplementaresFuncionario($codigo_pedido_exame);
        // debug($dados);exit;
        $dados['Medico'] = $values;
        unset($values);

        return $dados;

    }// fim obtemDadosComplementares


    /**
     * [getDadosComplementaresFuncionario
     * recupera os dados complementares do funcionario pelo pedido de exames
     * ]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function getDadosComplementaresFuncionario($codigo_pedido_exame)
    {

        $options['joins'][] = array(
            'table' => 'cliente_funcionario',
            'alias' => 'ClienteFuncionario',
            'type' => 'INNER',
            'conditions' => array(
                'ClienteFuncionario.codigo = PedidosExames.codigo_cliente_funcionario'
            )
        );
        $options['joins'][] = array(
            'table' => 'grupos_economicos_clientes',
            'alias' => 'GrupoEconomicoCliente',
            'type' => 'INNER',
            'conditions' => array(
                'GrupoEconomicoCliente.codigo_cliente = ClienteFuncionario.codigo_cliente_matricula'
            )
        );
        $options['joins'][] = array(
            'table' => 'cliente',
            'alias' => 'Unidade',
            'type' => 'INNER',
            'conditions' => array(
                'Unidade.codigo = GrupoEconomicoCliente.codigo_cliente'
            )
        );
        $options['joins'][] = array(
            'table' => 'grupos_economicos',
            'alias' => 'GrupoEconomico',
            'type' => 'INNER',
            'conditions' => array(
                'GrupoEconomico.codigo = GrupoEconomicoCliente.codigo_grupo_economico'
            )
        );
        $options['joins'][] = array(
            'table' => 'cliente',
            'alias' => 'Empresa',
            'type' => 'INNER',
            'conditions' => array(
                'Empresa.codigo = GrupoEconomico.codigo_cliente'
            )
        );
        $options['joins'][] = array(
            'table' => 'funcionarios',
            'alias' => 'Funcionario',
            'type' => 'INNER',
            'conditions' => array(
                'Funcionario.codigo = ClienteFuncionario.codigo_funcionario'
            )
        );

        $options['fields'] = array(
            'PedidosExames.codigo',
            'tipo_pedido_exame' => 'CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração Pontual\'
                WHEN PedidosExames.qualidade_vida = 1 THEN \'Qualidade de vida\'
                END',
            'idade' => '(SELECT FLOOR(DATEDIFF(DAY, Funcionario.data_nascimento, GETDATE()) / 365.25))',
            'sexo' => '(CASE Funcionario.sexo WHEN \'F\' THEN \'Feminino\' ELSE \'Masculino\' END)',
            'Funcionario.sexo',
            'Funcionario.nome',
            'Funcionario.cpf',
            'Funcionario.data_nascimento',
            'Funcionario.codigo',
            'ClienteFuncionario.codigo',
            'ClienteFuncionario.codigo_cliente_matricula',
            'ClienteFuncionario.admissao',
            'GrupoEconomicoCliente.codigo',
            'GrupoEconomicoCliente.codigo_cliente',
            'Empresa.razao_social',
            'GrupoEconomico.codigo',
            'GrupoEconomico.codigo_cliente',
            'Unidade.razao_social',
            'setor' => "(SELECT descricao FROM RHHealth.dbo.setores where codigo = (SELECT TOP 1 codigo_setor FROM RHHealth.dbo.funcionario_setores_cargos WHERE codigo_cliente_funcionario = ClienteFuncionario.codigo AND PedidosExames.codigo_func_setor_cargo = codigo  ORDER BY 1 DESC))",
            'cargo' => "(SELECT descricao FROM RHHealth.dbo.cargos where codigo = (SELECT TOP 1 codigo_cargo FROM RHHealth.dbo.funcionario_setores_cargos WHERE codigo_cliente_funcionario = ClienteFuncionario.codigo  AND PedidosExames.codigo_func_setor_cargo = codigo ORDER BY 1 DESC))"
        );

        $options['conditions'] = array(
            'PedidosExames.codigo' => $codigo_pedido_exame
        );

        $dados = $this->find()
            ->select($options['fields'])
            ->join($options['joins'])
            ->where($options['conditions'])
            ->first();
        // debug($dados->sql());exit;

        return $dados;

    }// fim getDadosComplementaresFuncionario($codigo_pedido_exame)

    public function getHistoricoFuncionario(int $codigo){

        $fields = array(
            'codigo_pedido'=>'PedidosExames.codigo',
            'empresa'=>'Cliente.razao_social',
            'tipo_pedido_exame' => 'CASE
                WHEN PedidosExames.exame_admissional = 1 THEN \'Exame admissional\'
                WHEN PedidosExames.exame_periodico = 1 THEN \'Exame periódico\'
                WHEN PedidosExames.exame_demissional = 1 THEN \'Exame demissional\'
                WHEN PedidosExames.exame_retorno = 1 THEN \'Retorno\'
                WHEN PedidosExames.exame_mudanca = 1 THEN \'Mudança de cargo\'
                WHEN PedidosExames.exame_monitoracao = 1 THEN \'Monitoração Pontual\'
                WHEN PedidosExames.qualidade_vida = 1 THEN \'Qualidade de vida\'
                END',
            'medico'=>'Medicos.nome',
            'data_agendamento'=>'AgendamentoExame.data'
        );

        $joins  = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItemPedidoExame',
                'type' => 'INNER',
                'conditions' => 'ItemPedidoExame.codigo_pedidos_exames = PedidosExames.codigo',
            ),
            array(
                'table' => 'agendamento_exames',
                'alias' => 'AgendamentoExame',
                'type' => 'LEFT',
                'conditions' => 'AgendamentoExame.codigo_itens_pedidos_exames = ItemPedidoExame.codigo',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.codigo = PedidosExames.codigo_funcionario',
            ),
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo = PedidosExames.codigo_cliente_funcionario',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = ClienteFuncionario.codigo_cliente',
            ),
            array(
                'table' => 'medicos',
                'alias' => 'Medicos',
                'type' => 'LEFT',
                'conditions' => 'Medicos.codigo = AgendamentoExame.codigo_medico',
            )
        );

        $conditions = "Funcionarios.cpf = '".$codigo."' OR Funcionarios.codigo = '".$codigo."'";

        $group = "  PedidosExames.codigo, Cliente.razao_social,
                    PedidosExames.exame_admissional,
                    PedidosExames.exame_periodico,
                    PedidosExames.exame_demissional,
                    PedidosExames.exame_retorno,
                    PedidosExames.exame_mudanca,
                    PedidosExames.exame_monitoracao,
                    PedidosExames.qualidade_vida,
                    Medicos.nome,
                    AgendamentoExame.data";

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group);

        return $dados;
    }

    /**
     * [baixaTotalPedidoExame verifica e se precisar troca o status do pedido de exame para baixado total]
     * @param  int    $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function baixaTotalPedidoExame(int $codigo_pedido_exame)
    {

        //pega os dados dos itens e os baixaidos
        $queryIPE = "select count(*) as total from itens_pedidos_exames ipe where codigo_pedidos_exames = {$codigo_pedido_exame}";
        $queryIPEB = "select count(*) as total from itens_pedidos_exames_baixa where codigo_itens_pedidos_exames IN (select codigo from itens_pedidos_exames where codigo_pedidos_exames = {$codigo_pedido_exame});";

        //eecuta as duas querys
        $conn = ConnectionManager::get('default');
        $ipe = $conn->execute($queryIPE)->fetchAll('assoc');
        $ipeb = $conn->execute($queryIPEB)->fetchAll('assoc');

        //verifica se todos os exames foram baixados
        $queryPed = "UPDATE pedidos_exames SET codigo_status_pedidos_exames = 2 WHERE codigo = {$codigo_pedido_exame}"; //baixado parcial
        if($ipe[0]['total'] == $ipeb[0]['total']) {

            $queryPed = "UPDATE pedidos_exames SET codigo_status_pedidos_exames = 3 WHERE codigo = {$codigo_pedido_exame}"; //baixado total

            // colocar o item pedido exame como 1 realizado
            $queryPed .= "UPDATE itens_pedidos_exames SET codigo_status_itens_pedidos_exames = 1 WHERE codigo_pedidos_exames = {$codigo_pedido_exame}"; //baixado total

        }

        $ped = $conn->execute($queryPed);

        return;

    }//fim baixatotalpedidoexame

    public function getUsuariosResponderExame($codigo_usuario = null, $notificacao = false)
    {

        //tabela de configuracao INSERE_EXAME_CLINICO
        $this->Configuracao = TableRegistry::get('Configuracao');
        $codigo_aso = $this->Configuracao->getChave('INSERE_EXAME_CLINICO');        

        $fields = array(
            'exame_admissional' => 'PedidosExames.exame_admissional',
            'exame_periodico' => 'PedidosExames.exame_periodico',
            'exame_demissional' => 'PedidosExames.exame_demissional',
            'exame_retorno' => 'PedidosExames.exame_retorno',
            'exame_mudanca' => 'PedidosExames.exame_mudanca',
            'pontual' => 'PedidosExames.pontual',
            'codigo_pedidos_exames' => 'PedidosExames.codigo',
            'codigo_func_setor_cargo' => 'PedidosExames.codigo_func_setor_cargo',
            'codigo_exame' => 'Exames.codigo',
            'codigo_medico' => 'ExamesGruposEconomicos.codigo_medico',
            'exame' => 'Exames.descricao',
            'exame_assinar_eletronicamente' => 'Exames.exame_assinar_eletronicamente',
            'exame_atraves_lyn' => 'Exames.exame_atraves_lyn',
            'codigo_servico' => 'Exames.codigo_servico',
            'servico' => 'Servico.descricao',
            'codigo_servico_lyn' => 'Exames.codigo_servico_lyn',
            'servico_lyn' => 'Servico2.descricao',
            'codigo_grupo_economico' => 'ExamesGruposEconomicos.codigo_grupo_economico',
            'codigo_cliente' => 'GruposEconomicosClientes.codigo_cliente',
            'codigo_cliente_funcionario' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario',
            'codigo_cliente_alocacao' => 'FuncionarioSetoresCargos.codigo_cliente_alocacao',
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_nome' => 'Usuario.nome',
            'login' => 'Usuario.apelido',
            'cpf' => 'Funcionarios.cpf',
            'codigo_funcionario' => 'Funcionarios.codigo',
            'funcionario_nome' => 'Funcionarios.nome',
            'setor' => 'RHHealth.dbo.ufn_decode_utf8_string(Setor.descricao)',
            'cargo' => 'RHHealth.dbo.ufn_decode_utf8_string(Cargo.descricao)',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'email' => 'Usuario.email',
            'notificacao' => 'UsuariosDados.notificacao',
            'codigo_fornecedor' => 'ItensPedidosExames.codigo_fornecedor',
            'codigo_fornecedor_aso' => '(select codigo_fornecedor from itens_pedidos_exames where codigo_pedidos_exames = PedidosExames.codigo AND codigo_exame = '.$codigo_aso.')',
            'valor_servico' => 'CAST(ClienteProdutoServico2.valor AS DECIMAL(10,2))'
        );

        $joins = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItensPedidosExames',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames',
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exames',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo_exame = Exames.codigo',
            ),
            array(
                'table' => 'servico',
                'alias' => 'Servico',
                'type' => 'INNER',
                'conditions' => 'Exames.codigo_servico = Servico.codigo',
            ),
            array(
                'table' => 'servico',
                'alias' => 'Servico2',
                'type' => 'INNER',
                'conditions' => 'Exames.codigo_servico_lyn = Servico2.codigo',
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetoresCargos',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_func_setor_cargo = FuncionarioSetoresCargos.codigo AND FuncionarioSetoresCargos.data_fim IS NULL',
            ),
            array(
                'table' => 'setores',
                'alias' => 'Setor',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_setor = Setor.codigo'
            ),
            array(
                'table' => 'cargos',
                'alias' => 'Cargo',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cargo = Cargo.codigo'
            ),
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND ClienteFuncionario.ativo <> 0',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
            ),
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
            ),

            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
            ),
            array(
                'table' => 'grupos_economicos',
                'alias' => 'GruposEconomicos',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_cliente = GruposEconomicos.codigo_cliente',
            ),
            array(
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GruposEconomicosClientes',
                'type' => 'INNER',
                'conditions' => 'GruposEconomicosClientes.codigo_grupo_economico = GruposEconomicos.codigo
                AND GruposEconomicosClientes.codigo_cliente = PedidosExames.codigo_cliente',
            ),
            array(
                'table' => 'exames_grupos_economicos',
                'alias' => 'ExamesGruposEconomicos',
                'type' => 'INNER',
                'conditions' => 'ExamesGruposEconomicos.codigo_exame = Exames.codigo AND ExamesGruposEconomicos.codigo_grupo_economico = GruposEconomicos.codigo',
            ),
            array(
                'table' => 'cliente_produto',
                'alias' => 'ClienteProduto',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente = ClienteProduto.codigo_cliente',
            ),
            array(
                'table' => 'cliente_produto_servico2',
                'alias' => 'ClienteProdutoServico2',
                'type' => 'INNER',
                'conditions' => "ClienteProdutoServico2.codigo_cliente_produto = ClienteProduto.codigo AND ClienteProdutoServico2.codigo_servico = Exames.codigo_servico_lyn",
            ),
            // array(
            //     'table' => 'fornecedores_contato',
            //     'alias' => 'FornecedoresContato',
            //     'type' => 'INNER',
            //     'conditions' => 'FornecedoresContato.codigo_fornecedor = ItensPedidosExames.codigo_fornecedor AND FornecedoresContato.codigo_tipo_retorno = 2',
            // ),
        );

        $where = array(
            'PedidosExames.codigo_status_pedidos_exames IN (1,2)',
            'ItensPedidosExames.codigo_exame' => 27, // AVALIACAO PSICOSSOCIAL
            'Exames.exame_assinar_eletronicamente' => 1,
            'Exames.exame_atraves_lyn' => 1,

            'ClienteProduto.codigo_produto in (59, 250)', // 59 - EXAMES COMPLEMENTARES e 250 - APP LYN - C

            // -- 'UsuariosDados.notificacao' => 1, //comentado porque não precisa ter aceitado notificacao no cadatro

//            'UsuarioSistema.codigo_sistema' => 1, // lyn
            // -- 'UsuarioSistema.platform IS NOT NULL', //comentado porque não precisa ter aceitado a notificacao
            // -- 'UsuarioSistema.token_push IS NOT NULL', //comentado porque não precisa ter aceitado a notificacao

            'Usuario.ativo' => 1,
            'GruposEconomicos.exame_atraves_lyn' => 1,
        );

        if($notificacao){

            $fields['celular'] =  "(CASE
                    WHEN UsuariosDados.celular <> '' THEN UsuariosDados.celular
                    WHEN UsuarioSistema.celular <> '' THEN UsuarioSistema.celular
                    WHEN UsuariosDados.telefone <> '' THEN UsuariosDados.telefone
                END)";
            $fields['token_push'] = 'UsuarioSistema.token_push';
            $fields['platform'] = 'UsuarioSistema.platform';

            $joins[] = array(
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = UsuarioSistema.codigo_usuario',
            );

            $joins[] = array(
                'table' => 'ficha_psicossocial',
                'alias' => 'FichaPsicossocial',
                'type' => 'LEFT',
                'conditions' => 'FichaPsicossocial.codigo_pedido_exame = PedidosExames.codigo',
            );

            $where[] = 'UsuarioSistema.codigo_sistema = 1'; //pega o usuario de notificacao do lyn
            $where[] = 'FichaPsicossocial.codigo IS NULL';
        }

        $order = array();
        if(!is_null($codigo_usuario)){
            $where['Usuario.codigo'] = $codigo_usuario;
            $order[] = 'PedidosExames.codigo DESC';
        }
        
        $group = $fields;

        unset($group['codigo_fornecedor_aso']);//retirar o fields do aso       

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($where)
            ->order($order)
            ->group($group);

        // debug($dados->sql());

        return (!is_null($codigo_usuario)) ? $dados->first() : $dados->toArray();
    }

    public function retornaPedidosFornecedor($codigo_fornecedor, $data_agendamento, $tipo_exame, $status, $especialidade, $especialista) {

        $fields = array(
            'ItensPedidoExame.codigo',
            'ItensPedidoExame.codigo_pedidos_exames',
            'ItensPedidoExame.codigo_exame',
            'ItensPedidoExame.codigo_fornecedor',
            'ItensPedidoExame.tipo_atendimento',
            'ItensPedidoExame.data_agendamento',
            'ItensPedidoExame.hora_agendamento',
            'ItensPedidoExame.tipo_agendamento',
            'ItensPedidoExame.data_realizacao_exame',
            'ItensPedidoExame.hora_realizacao_exame',
            'ItensPedidoExame.compareceu',
            'ItensPedidoExame.codigo_medico',
            'ItensPedidoExame.codigo_status_itens_pedidos_exames',

            'Funcionario.codigo',
            'Funcionario.nome',
            'Funcionario.cpf',
            'Funcionario.foto',

            'Cliente.codigo',
            'Cliente.razao_social',

            'PedidosExames.codigo',
            'PedidosExames.data_inclusao',
            'PedidosExames.exame_admissional',
            'PedidosExames.exame_periodico',
            'PedidosExames.exame_demissional',
            'PedidosExames.exame_retorno',
            'PedidosExames.exame_mudanca',
            'PedidosExames.exame_monitoracao',
            'PedidosExames.pontual',
            'PedidosExames.qualidade_vida',
            'PedidosExames.codigo_status_pedidos_exames',

            '_status_' => 'StatusPedidoExame.descricao',
            '_codigo_status_' => 'StatusPedidoExame.codigo',
            'Medicos.codigo',
            'Medicos.nome',
            'FuncionarioSetorCargo.codigo'
        );

        $order = array('ItensPedidoExame.hora_agendamento ASC');


        if ($especialidade != "null") {

            $condition_especialidade = "FornecedorMedicoEspecialidades.codigo_especialidade = " . $especialidade . " and FornecedorMedicoEspecialidades.codigo_fornecedor = " . $codigo_fornecedor . " and ItensPedidoExame.codigo_medico = FornecedorMedicoEspecialidades.codigo_medico";
        } else {
            $condition_especialidade = "FornecedorMedicoEspecialidades.codigo_fornecedor = " . $codigo_fornecedor . " ";
        }


        $joins = array(
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente_funcionario = FuncionarioSetorCargo.codigo_cliente_funcionario AND FuncionarioSetorCargo.data_fim is NULL'
            ),
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo = FuncionarioSetorCargo.codigo_cliente_funcionario AND FuncionarioSetorCargo.data_fim is NULL'
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'Funcionario.codigo = ClienteFuncionario.codigo_funcionario'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao'
            ),
            array(
                'table' => 'status_pedidos_exames',
                'alias' => 'StatusPedidoExame',
                'type' => 'INNER',
                'conditions' => 'StatusPedidoExame.codigo = PedidosExames.codigo_status_pedidos_exames'
            ),
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItensPedidoExame',
                'type' => 'INNER',
                'conditions' => 'ItensPedidoExame.codigo_pedidos_exames = PedidosExames.codigo'
            ),

            array(
                'table' => 'medicos',
                'alias' => 'Medicos',
                'type' => 'INNER',
                'conditions' => "ItensPedidoExame.codigo_medico = Medicos.codigo "
            ),

            array(
                'table' => 'fornecedores_medico_especialidades',
                'alias' => 'FornecedorMedicoEspecialidades',
                'type' => 'INNER',
                'conditions' => $condition_especialidade
            ),

        );

        $conditions = array(
            "PedidosExames.em_emissao IS NULL",
            "ItensPedidoExame.codigo_fornecedor" => $codigo_fornecedor,
            "ItensPedidoExame.data_agendamento" => $data_agendamento
        );

        if ($tipo_exame != "null") {

            $conditions["PedidosExames.{$tipo_exame}"] = 1;
        }

        if ($status != "null") {

            $conditions[] = array(
                "ItensPedidoExame.codigo_status_itens_pedidos_exames" => $status
            );
        }



        if ($especialista != "null") {

            $conditions[] = array(
                "ItensPedidoExame.codigo_medico IN ({$especialista})"
            );
        }

        $group = array(
            'ItensPedidoExame.codigo',
            'ItensPedidoExame.codigo_pedidos_exames',
            'ItensPedidoExame.codigo_exame',
            'ItensPedidoExame.codigo_fornecedor',
            'ItensPedidoExame.tipo_atendimento',
            'ItensPedidoExame.data_agendamento',
            'ItensPedidoExame.hora_agendamento',
            'ItensPedidoExame.tipo_agendamento',
            'ItensPedidoExame.data_realizacao_exame',
            'ItensPedidoExame.hora_realizacao_exame',
            'ItensPedidoExame.compareceu',
            'ItensPedidoExame.codigo_medico',
            'ItensPedidoExame.codigo_status_itens_pedidos_exames',

            'Funcionario.codigo',
            'Funcionario.nome',
            'Funcionario.cpf',
            'Funcionario.foto',

            'Cliente.codigo',
            'Cliente.razao_social',

            'PedidosExames.codigo',
            'PedidosExames.data_inclusao',
            'PedidosExames.exame_admissional',
            'PedidosExames.exame_periodico',
            'PedidosExames.exame_demissional',
            'PedidosExames.exame_retorno',
            'PedidosExames.exame_mudanca',
            'PedidosExames.exame_monitoracao',
            'PedidosExames.pontual',
            'PedidosExames.qualidade_vida',
            'PedidosExames.codigo_status_pedidos_exames',

            '_status_' => 'StatusPedidoExame.descricao',
            '_codigo_status_' => 'StatusPedidoExame.codigo',
            'Medicos.codigo',
            'Medicos.nome',
            'FuncionarioSetorCargo.codigo'
        );


        $query = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group)
            ->hydrate(false)
            ->order($order)
            ->toArray();

        return $query;
    }

    public function retornaEstrutura($codigo_funcionario_setor_cargo) {

        if(is_numeric($codigo_funcionario_setor_cargo)) {
            $FuncionarioSetoresCargos = TableRegistry::getTableLocator()->get('FuncionarioSetoresCargos');

            $fields = array(
                'Cliente.codigo',
                'Cliente.razao_social',
                'Cliente.nome_fantasia',
                'Cliente.codigo_documento',
                'Empresa.codigo',
                'Empresa.razao_social',
                'Empresa.nome_fantasia',
                'Empresa.codigo_documento',
                'ClienteContato.descricao',
                'Funcionario.codigo',
                'Funcionario.nome',
                'Funcionario.cpf',
                'Funcionario.data_nascimento',
                'Cargo.codigo',
                'Cargo.descricao',
                'Setor.codigo',
                'Setor.descricao',
                'ClienteEndereco.codigo',
            );

            $joins = array(
                array(
                    'table' => 'cliente_funcionario',
                    'alias' => 'ClienteFuncionario',
                    'type' => 'INNER',
                    'conditions' => 'ClienteFuncionario.codigo = FuncionarioSetoresCargos.codigo_cliente_funcionario',
                ),
                array(
                    'table' => 'funcionarios',
                    'alias' => 'Funcionario',
                    'type' => 'INNER',
                    'conditions' => 'Funcionario.codigo = ClienteFuncionario.codigo_funcionario',
                ),
                array(
                    'table' => 'cliente',
                    'alias' => 'Cliente',
                    'type' => 'INNER',
                    'conditions' => 'Cliente.codigo = FuncionarioSetoresCargos.codigo_cliente_alocacao',
                ),
                array(
                    'table' => 'grupos_economicos_clientes',
                    'alias' => 'GrupoEconomicoCliente',
                    'type' => 'INNER',
                    'conditions' => 'GrupoEconomicoCliente.codigo_cliente = FuncionarioSetoresCargos.codigo_cliente_alocacao',
                ),
                array(
                    'table' => 'grupos_economicos',
                    'alias' => 'GrupoEconomico',
                    'type' => 'INNER',
                    'conditions' => 'GrupoEconomico.codigo = GrupoEconomicoCliente.codigo_grupo_economico',
                ),
                array(
                    'table' => 'cliente',
                    'alias' => 'Empresa',
                    'type' => 'INNER',
                    'conditions' => 'Empresa.codigo = GrupoEconomico.codigo_cliente',
                ),
                array(
                    'table' => 'cliente_endereco',
                    'alias' => 'ClienteEndereco',
                    'type' => 'LEFT',
                    'conditions' => 'Cliente.codigo = ClienteEndereco.codigo_cliente',
                ),
                array(
                    'table' => 'cliente_contato',
                    'alias' => 'ClienteContato',
                    'type' => 'LEFT',
                    'conditions' => 'Cliente.codigo = ClienteContato.codigo_cliente AND ClienteContato.codigo_tipo_contato = 2 AND ClienteContato.codigo_tipo_retorno = 2',
                ),
                array(
                    'table' => 'cargos',
                    'alias' => 'Cargo',
                    'type' => 'INNER',
                    'conditions' => 'Cargo.codigo = FuncionarioSetoresCargos.codigo_cargo',
                ),
                array(
                    'table' => 'setores',
                    'alias' => 'Setor',
                    'type' => 'INNER',
                    'conditions' => 'Setor.codigo = FuncionarioSetoresCargos.codigo_setor',
                )
            );

            $conditions = array(
                "FuncionarioSetoresCargos.codigo" => $codigo_funcionario_setor_cargo
            );

            $query = $FuncionarioSetoresCargos->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->hydrate(false)
                ->first();

            return $query;

        } else {
            return false;
        }

    }

    /**
     * Description SOMENTE PARA PEDIDOS OCUPACIONAIS metodo para "tirar uma foto" do risco (ppra) e regra (pcmso) atual do funcionario, somente para exames ocupacionais
     * @param type $codigo_pedido_exame
     * @return type
     */
    public function setDadosRiscoRegraAso($codigo_pedido_exame)
    {

        $conn = ConnectionManager::get('default');

        //busca o pcmso do funcionario
        $query_dados_pcmso = "
                            INSERT INTO RHHealth.dbo.pedidos_exames_pcmso_aso
                            SELECT
                                pe.codigo,
                                pe.codigo_cliente,
                                ae.codigo_setor,
                                ae.codigo_cargo,
                                ae.codigo_funcionario,
                                ae.codigo_exame,
                                fsc.codigo as codigo_func_setor_cargo,
                                pe.codigo_usuario_inclusao,
                                pe.data_inclusao,
                                pe.codigo_usuario_inclusao,
                                pe.data_inclusao
                            FROM RHHealth.dbo.pedidos_exames  pe
                                INNER JOIN RHHealth.dbo.itens_pedidos_exames ipe ON (ipe.codigo_pedidos_exames = pe.codigo)
                                inner join RHHealth.dbo.funcionario_setores_cargos fsc on pe.codigo_func_setor_cargo = fsc.codigo
                                INNER join RHHealth.dbo.aplicacao_exames ae on (ae.exame_excluido_aso = 1
                                    AND (ae.codigo_cliente_alocacao = fsc.codigo_cliente_alocacao
                                    AND ae.codigo_setor = fsc.codigo_setor
                                    AND ae.codigo_cargo = fsc.codigo_cargo
                                    AND ((ae.codigo_funcionario = pe.codigo_funcionario) OR (ae.codigo_funcionario IS NULL))))
                            WHERE ae.codigo_exame = ipe.codigo_exame
                                AND ae.codigo IN (select * from dbo.ufn_aplicacao_exames(fsc.codigo_cliente_alocacao,fsc.codigo_setor,fsc.codigo_cargo,pe.codigo_funcionario))
                                AND pe.codigo = " . $codigo_pedido_exame;
        $dados_pcmso = $conn->execute($query_dados_pcmso);

        //busca o ppra do funcionario
        $query_dados_ppra = "
                            INSERT INTO RHHealth.dbo.pedidos_exames_ppra_aso
                            SELECT
                                pe.codigo,
                                pe.codigo_cliente,
                                cs.codigo_setor,
                                ge.codigo_cargo,
                                ge.codigo_funcionario,
                                gr.codigo as codigo_grupo_risco,
                                ri.codigo as codigo_risco,
                                ger.codigo_tipo_medicao,
                                ger.valor_medido,
                                ri.nivel_acao,
                                fsc.codigo as codigo_func_setor_cargo,
                                pe.codigo_usuario_inclusao,
                                pe.data_inclusao,
                                pe.codigo_usuario_inclusao,
                                pe.data_inclusao
                            FROM RHHealth.dbo.pedidos_exames pe
                                INNER JOIN RHHealth.dbo.funcionario_setores_cargos fsc ON fsc.codigo = pe.codigo_func_setor_cargo
                                INNER JOIN RHHealth.dbo.cliente_funcionario cf ON cf.codigo = pe.codigo_cliente_funcionario
                                INNER JOIN RHHealth.dbo.cargos cg ON cg.codigo = fsc.codigo_cargo
                                INNER JOIN RHHealth.dbo.setores st ON st.codigo = fsc.codigo_setor
                                INNER JOIN RHHealth.dbo.clientes_setores cs ON (cs.codigo_setor = st.codigo AND cs.codigo_cliente_alocacao = fsc.codigo_cliente_alocacao)
                                INNER JOIN RHHealth.dbo.grupo_exposicao ge  ON (ge.codigo_cargo = cg.codigo AND ge.codigo_cliente_setor = cs.codigo AND ((ge.codigo_funcionario = pe.codigo_funcionario) OR (ge.codigo_funcionario IS NULL)))
                                INNER JOIN RHHealth.dbo.grupos_exposicao_risco ger ON (ger.codigo_grupo_exposicao = ge.codigo)
                                INNER JOIN RHHealth.dbo.riscos ri ON (ri.codigo = ger.codigo_risco)
                                INNER JOIN RHHealth.dbo.grupos_riscos gr ON (gr.codigo = ri.codigo_grupo)
                            WHERE ge.codigo IN (select * from dbo.ufn_grupo_exposicao(fsc.codigo_cliente_alocacao,fsc.codigo_setor,fsc.codigo_cargo,pe.codigo_funcionario))
                                AND pe.codigo = " . $codigo_pedido_exame;
        $dados_ppra = $conn->execute($query_dados_ppra);

    }//fim setDadosRiscoRegra

    /**
     * [set_gerar_pedidos_exames
     *     metodo para verificar e saber se todos os exames foram agendados pelo lyn onde irá gerar o pedido de exames ]
     * @param [type] $codigo_pedido_exame [codigo do pre pedido de exames]
     */
    public function set_pre_pedido_exame_para_pedido_exame($codigo_pre_pedido_exame,$codigo_func_setor_cargo,$codigo_cliente_alocacao)
    {

        //registra a model preItensPedidosExames
        $this->PreItensPedidosExames = TableRegistry::get('PreItensPedidosExames');
        //carre os dados do pre pedido de exames
        $pre_item_pedido_exame = $this->PreItensPedidosExames->find()->select(['codigo_exame'])->where(['codigo_pedidos_exames' => $codigo_pre_pedido_exame])->hydrate(false)->all();

        //verfica se tem dados de exames pre registrados
        if(!empty($pre_item_pedido_exame)) {
            //seta como array
            $pre_item_pedido_exame = $pre_item_pedido_exame->toArray();

            ####ocupacionais pega os exames do pcmso
            //pega a lista de pcmso da configuracao do funcionario setores cargos
            $dados_pcmso = $this->lista_exames_pcmso($codigo_func_setor_cargo,$codigo_cliente_alocacao);

            //verfica se tenho a mesma quantidade de exames com o pcmso aplicado
            if(count($dados_pcmso) == count($pre_item_pedido_exame)) {
                $exames_pcmso = array();
                //varre os pcmso para pegar os exames
                foreach($dados_pcmso AS $pcmso) {
                    $exames_pcmso[$pcmso['codigo_exame']]['codigo_exame'] = $pcmso['codigo_exame'];
                    $exames_pcmso[$pcmso['codigo_exame']]['verificado'] = 0;
                }//fim foreach

                // varre os exames do pre itens pedidos exames
                foreach($pre_item_pedido_exame AS $pre_exames) {

                    //verifica se tem o exame agendado tem dentro do pcmso
                    if(isset($exames_pcmso[$pre_exames['codigo_exame']])) {
                        //seta que foi verificado existe o exame no pre
                        $exames_pcmso[$pre_exames['codigo_exame']]['verificado'] = 1;
                    }

                }//fim foreach pre_exames

                //verificar se tem algum exame do pcmso que não foi verificado
                $gerar_pedido_exame = true;
                foreach($exames_pcmso AS $epcmso) {
                    //verifica se algum exame não foi verificado
                    if($epcmso['verificado'] == 0) {
                        $gerar_pedido_exame = false;
                    }
                }//fim foreach

                //verifica se pode gerar o pedido de exames
                if($gerar_pedido_exame) {

                    $this->PrePedidosExames = TableRegistry::get('PrePedidosExames');
                    $dpp = $this->PrePedidosExames->find()->where(['codigo' => $codigo_pre_pedido_exame])->first();

                    //para gravar os dados e depois atualizar
                    $dados_pre_pedido = $dpp->toArray();
                    unset($dados_pre_pedido['codigo']);
                    unset($dados_pre_pedido['codigo_pedido_exame']);

                    //gera o pedido
                    $pedido = $this->set_pedidos_exames($dados_pre_pedido);

                    //verfica se retornou erro para dar rollback
                    if (is_array($pedido)) {
                        throw new Exception($pedido);
                    }

                    //seta o codigo do pedido
                    $codigo_pedido_exame = $pedido;

                    //atualiza o pre_pedidos_exames com o codigo do pedido_exame
                    $update_pre_pedido_exame['codigo_pedido_exame'] = $codigo_pedido_exame;
                    $up_ppp = $this->PrePedidosExames->patchEntity($dpp,$update_pre_pedido_exame);
                    //atualiza a tabela de pre pedido de exames com o codigo do pedido exame
                    if (!$this->PrePedidosExames->save($up_ppp)) {
                        throw new Exception('Ocorreu algum erro! Precisamos reagendar ou entre contato com a clínica! '.print_r($up_ppp->getValidationErrors(),1));
                    }


                    //pega todos os dados do pre_itens_pedidos_exames para criar o itens_pedidos_exames
                    $dados_pipe = $this->PreItensPedidosExames->find()->where(['codigo_pedidos_exames' => $codigo_pre_pedido_exame])->hydrate(false)->all()->toArray();

                    //criar os itens e agendamentos
                    $this->ItensPedidosExames = TableRegistry::get('ItensPedidosExames');
                    $this->AgendamentoExames = TableRegistry::get('AgendamentoExames');
                    $this->PreAgendamentoExames = TableRegistry::get('PreAgendamentoExames');

                    foreach($dados_pipe AS $key_pipe => $pipe) {

                        $codigo_pre_itens_pedidos_exames = $pipe['codigo'];
                        unset($pipe['codigo']);

                        $pipe['codigo_pedidos_exames'] = $codigo_pedido_exame;

                        // debug($pipe);

                        //verifica se existe um pedido de exame, caso exista e tem o exame do laço irá atualziar
                        $ipe = $this->ItensPedidosExames->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame,'codigo_exame' => $pipe['codigo_exame']])->first();

                        //verifica se existe o item
                        if(!empty($ipe)) {
                            $registro_item = $this->ItensPedidosExames->patchEntity($ipe,$pipe);
                        }
                        else {
                            $registro_item = $this->ItensPedidosExames->newEntity($pipe);
                        }

                        //cria um item ou atualiza o item
                        if (!$this->ItensPedidosExames->save($registro_item)) {
                            throw new Exception('Ocorreu algum erro! Ao Gerar Pedido de exame! '.print_r($registro_item->getValidationErrors(),1));
                        }

                        //verfica se tem pre_agendamento e gerar os dados na tabela de agendamento
                        $pre_agendamentos = $this->PreAgendamentoExames->find()->where(['codigo_itens_pedidos_exames' => $codigo_pre_itens_pedidos_exames])->hydrate(false)->all();

                        if(!empty($pre_agendamentos)) {
                            $pre_agendamentos = $pre_agendamentos->toArray();
                            //varre os pre agendamento
                            foreach($pre_agendamentos AS $prea) {
                                unset($prea['codigo']);

                                $prea['codigo_itens_pedidos_exames'] = $registro_item->codigo;

                                $agenda_item = $this->AgendamentoExames->newEntity($prea);
                                if(!$this->AgendamentoExames->save($agenda_item)) {
                                    throw new Exception("Houve um erro ao gerar o pedido de exames e salvar o Agendamento!");
                                }

                            }//fim foreach
                        }//fim agendamento

                    } //fim foreach dos exames

                    return $codigo_pedido_exame;


                }//fim $gerar_pedido_exame

            }// fim dados_pcmso

            // debug($gerar_pedido_exame);
            // exit;

        }//fim verificacao se tem os dados dos exames na pre_itens_pedidos_exames

        return false;

    }//fim set_pre_pedido_exame_para_pedido_exame

     /**
     * [atualiza_lista_exames_grupo description]
     *
     * metodo para validar os exames de pcmso do funcionario setor e cargo
     *
     * @param  [type] $codigo_funcionario_setor_cargo [description]
     * @return [type]                                 [description]
     */
    public function lista_exames_pcmso($codigo_funcionario_setor_cargo, $codigo_cliente_matriz)
    {
        $this->FuncionarioSetoresCargos = TableRegistry::get('FuncionarioSetoresCargos');
        $arr_exames = array();

        //pega onde o funcionario esta alocado
        $codigo_cliente_alocacao = $this->FuncionarioSetoresCargos->find()->select(['codigo_cliente_alocacao'])->where(['codigo' => $codigo_funcionario_setor_cargo])->first();
        $codigo_cliente = $codigo_cliente_alocacao['codigo_cliente_alocacao'];

        //Recupera os exames do PCMSO aplicados para unidade + setor + cargo de alocação do funcionário
        $itens_exames = $this->FuncionarioSetoresCargos->retornaExamesNecessarios($codigo_funcionario_setor_cargo);

        // adiciona exames na lista
        if(count($itens_exames)) {
            //varre os itens de exames
            foreach($itens_exames as $key => $item) {
                /**
                 * Verifica se existe assinatura e recupera o valor do exame
                 * Inicialmente consulta a unidade de alocação se não encontrar consulta a matriz (Grupo Econômico)
                 */
                $item['assinatura'] = $this->verificaExameTemAssinatura($item['codigo_servico'],$codigo_cliente, $codigo_cliente_matriz);

                //Verifica se existe fornecedor no cliente de alocação (exame na lista de preços do fornecedor)
                $fornecedores = $this->verificaExameTemFornecedor($item['codigo_servico'],$codigo_cliente);

                //verifica se tem fornecedor
                if(count($fornecedores) > 0) {
                    $item['fornecedores'] = 1;
                }
                else {
                    $item['fornecedores'] = 0;
                }
                //grava sessao com todos os exames do PCMSO (até os sem valor de assinatura)
                $arr_exames[] = $item;
            }//fim foreach dos itens de exames
        }//fim count itens_exames
        return $arr_exames;
    }//FINAL FUNCTION atualiza_lista_exames_grupo

    /**
     * [set_pedidos_exames metodo para criar os pedidos de exames]
     * @param [type] $dados [description]
     */
    public function set_pedidos_exames($dados)
    {
        //cria os relacionamentos para criar o pedido de exame
        $registro = $this->newEntity($dados);
        if (!$this->save($registro)) {
            $error[] = $registro->getValidationErrors();
            return $error;
        }

        $codigo_pedido_exame = isset($registro->codigo) ? $registro->codigo : null;

        return $codigo_pedido_exame;

    }//fim set_pedidos_exames

    /**
     * [enviarKit metodo para disparar o kit para o funcionario, cliente e fornecedor que tenham o email cadastrado corretamente]
     * @return [type] [description]
     */
    public function enviarKit($codigo_pedido_exame)
    {


        //variavel para configurar os emails que vai ser disparado e o que do kit
        $var_data = array();

        //pega os dados do pedido com o cliente, funcioanrio e fornecedores
        $joinsPedido = array(
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItensPedidosExames',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames',
            ),
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente_funcionario = ClienteFuncionario.codigo',
            ),
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_usuario_inclusao = Usuario.codigo',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo_cliente = Cliente.codigo',
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo_fornecedor = Fornecedor.codigo',
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo_exame = Exame.codigo',
            ),
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedorEndereco',
                'type' => 'LEFT',
                'conditions' => array('FornecedorEndereco.codigo_fornecedor = Fornecedor.codigo')
            )

        );
        $fields = array(
            'PedidosExames.codigo',
            'PedidosExames.codigo_cliente',
            'PedidosExames.codigo_cliente_funcionario',
            'PedidosExames.codigo_func_setor_cargo',
            'PedidosExames.codigo_funcionario',
            'PedidosExames.codigo_usuario_inclusao',
            'PedidosExames.exame_demissional',
            'PedidosExames.aso_embarcados',
            'ItensPedidosExames.codigo_exame',
            'ItensPedidosExames.codigo_fornecedor',
            'ItensPedidosExames.data_agendamento',
            'ItensPedidosExames.hora_agendamento',
            'ItensPedidosExames.tipo_agendamento',
            'ClienteFuncionario.codigo_cliente_matricula',
            'Usuario.nome',
            'Cliente_nome_fantasia' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
            'Fornecedor.codigo',

            'Fornecedor_razao_social' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.razao_social)',

            'FornecedorEndereco_logradouro' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.logradouro)',
            'FornecedorEndereco.numero',
            'FornecedorEndereco_cidade' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.cidade)',
            'FornecedorEndereco.estado_descricao',
            'FornecedorEndereco_bairro' => 'RHHealth.dbo.ufn_decode_utf8_string(FornecedorEndereco.bairro)',
            'FornecedorEndereco.complemento',
            'Exame.exame_audiometria',
            'Exame_descricao'  => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            // '(SELECT TOP 1 descricao FROM fornecedores_contato WHERE fornecedores_contato.codigo_fornecedor = Fornecedor.codigo AND fornecedores_contato.codigo_tipo_retorno = 2)' => 'email_fornecedor'
        );
        $dados_pedido = $this->find()->select($fields)->join($joinsPedido)->where(['PedidosExames.codigo' => $codigo_pedido_exame])->hydrate(false)->all()->toArray();
        //arruma os indices
        foreach($dados_pedido AS $keyDadosPedido => $valDadosPedido) {
            $dados_pedido[$keyDadosPedido]['Cliente']['nome_fantasia'] = $valDadosPedido['Cliente_nome_fantasia'];
            $dados_pedido[$keyDadosPedido]['Fornecedor']['razao_social'] = $valDadosPedido['Fornecedor_razao_social'];
            $dados_pedido[$keyDadosPedido]['FornecedorEndereco']['logradouro'] = $valDadosPedido['FornecedorEndereco_logradouro'];
            $dados_pedido[$keyDadosPedido]['FornecedorEndereco']['cidade'] = $valDadosPedido['FornecedorEndereco_cidade'];
            $dados_pedido[$keyDadosPedido]['FornecedorEndereco']['bairro'] = $valDadosPedido['FornecedorEndereco_bairro'];
            $dados_pedido[$keyDadosPedido]['Exame']['descricao'] = $valDadosPedido['Exame_descricao'];
        }

        // debug($dados_pedido);exit;

        //Recupera o campo vias_aso da tabela grupos_economicos
        $this->GrupoEconomico = TableRegistry::get('GruposEconomicos');
        $dados_matriz = $this->GrupoEconomico->find()->select(['codigo_cliente','vias_aso'])->where(['codigo_cliente' => $dados_pedido[0]['ClienteFuncionario']['codigo_cliente_matricula']])->first();
        //pega a quantidade de vias de aso que deve ser impressa
        $vias_aso = $dados_matriz->vias_aso;

        //variavel para comportar todos os emails para onde vai ser enviado o kit
        $email = array();

        //pega o email do funcionario
        $this->Usuario = TableRegistry::get('Usuario');
        $dados_usuario = $this->Usuario->getUsuariosDadosFuncionario($dados_pedido[0]['codigo_usuario_inclusao']);

        $email_funcionario = array();
        $email_funcionario[$dados_pedido[0]['codigo_funcionario']]['email'] = 'lyn';
        if(!empty($dados_usuario)) {
            $email_funcionario[$dados_pedido[0]['codigo_funcionario']]['email'] = $dados_usuario->email;
            $email_funcionario[$dados_pedido[0]['codigo_funcionario']]['nome'] = $dados_usuario->nome;

            $email['Email']['Funcionario'] = $email_funcionario;
            $email['funcionario_nome'] = $dados_usuario->nome;
        }

        //pega o email do cliente
        $this->ClienteContato = TableRegistry::get('ClienteContato');
        $cliente_contato = $this->ClienteContato->find()->select(['descricao'])->where(['codigo_cliente' => $dados_pedido[0]['codigo_cliente'],'codigo_tipo_retorno' => '2'])->first();
        $email_cliente = array();
        $email_cliente[$dados_pedido[0]['codigo_cliente']]['email'] = 'lyn';
        if(!empty($cliente_contato)) {
            $email_cliente[$dados_pedido[0]['codigo_cliente']]['email'] = $cliente_contato->descricao;
        }
        $email_cliente[$dados_pedido[0]['codigo_cliente']]['nome'] = $dados_pedido[0]['Cliente']['nome_fantasia'];
        $email['Email']['Cliente'] = $email_cliente;
        $email['cliente_nome'] = $dados_pedido[0]['Cliente']['nome_fantasia'];


        //pega o email do fornecedor
        $this->FornecedoresContato = TableRegistry::get('FornecedoresContato');
        $email_fornecedor = array();
        //varre os dados dos itens
        foreach($dados_pedido AS $key_dados => $itens) {
            $fornecedor_contato = $this->FornecedoresContato->find()->select(['descricao'])->where(['codigo_fornecedor' => $itens['ItensPedidosExames']['codigo_fornecedor'],'codigo_tipo_retorno' => '2'])->first();
            $dados_pedido[$key_dados]['Fornecedor']['email'] = 'lyn';
            if(!empty($fornecedor_contato)) {
                $email_fornecedor[$itens['ItensPedidosExames']['codigo_fornecedor']]['email'] = $fornecedor_contato->descricao;
                $email_fornecedor[$itens['ItensPedidosExames']['codigo_fornecedor']]['nome'] = $itens['Fornecedor']['razao_social'];
                $email['Email']['Fornecedor'] = $email_fornecedor;

                $dados_pedido[$key_dados]['Fornecedor']['email'] = $fornecedor_contato->descricao;
            }
        }

        //grava os emails que vai ser enviados
        $this->PedidoExameNotificacao = TableRegistry::get('PedidosExamesNotificacao');
        $this->PedidoExameNotificacao->gravaDados($email,$codigo_pedido_exame);

        ########################NOTIFICACOES
        //pega os tipos de notifiacao padrao
        $this->TipoNotificacao = TableRegistry::get('TipoNotificacao');
        $this->TipoNotificacaoValores = TableRegistry::get('TipoNotificacaoValores');

        $tipos_notificacoes = $this->TipoNotificacaoValores->find()->where('codigo_pedidos_exames IS NULL')->hydrate(false)->all()->toArray();

        //apaga os registros anteriores das notificações
        $this->TipoNotificacaoValores->deleteAll($codigo_pedido_exame);

        //Grava as novas configurações de notificações
        foreach($tipos_notificacoes as $k => $tipo) {

            unset($tipo['codigo']);
            $tipo['codigo_pedidos_exames'] = $codigo_pedido_exame;
            $tipo['data_inclusao'] = date('Y-m-d H:i:s');
            if($tipo['codigo_tipo_notificacao'] == 2) {
                $tipo['vias_aso'] = $vias_aso;
            }

            $tipoNotificacaoValor = $this->TipoNotificacaoValores->newEntity($tipo);
            $this->TipoNotificacaoValores->save($tipoNotificacaoValor);

        }//FINAL FOREACH $dados_tipo_notificacao

        //mota o que vai enviar para a impressao
        $dados_tipo_notificacao = $this->TipoNotificacaoValores->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame])->hydrate(false)->all()->toArray();
        $dados_tipo_notificacao_for_save = array();
        $dados_impressao = array();
        foreach($dados_tipo_notificacao AS $not) {

            $dados_tipo_notificacao_for_save[$not['codigo_tipo_notificacao']] = $not;

            if($not['campo_funcionario']) {
                $dados_impressao['PedidosExames']['funcionario'][$not['codigo_tipo_notificacao']] = 1;
            }

            if($not['campo_cliente']) {
                $dados_impressao['PedidosExames']['cliente'][$not['codigo_tipo_notificacao']] = 1;
            }

            if($not['campo_fornecedor']) {
                $dados_impressao['PedidosExames']['fornecedor'][$not['codigo_tipo_notificacao']] = 1;
            }

            $dados_impressao['PedidosExames']['vias_aso'] = $vias_aso;
            $dados_impressao['PedidosExames']['sugestao'][$codigo_pedido_exame] = 0;
            $dados_impressao['PedidosExames']['tem_sugestao'] = 0;

        }//fim foreach tipos_notificacao

        // debug($dados_impressao);
        // debug($email);
        // exit;
        $dados_impressao = array_merge($dados_impressao,$email);

        if($dados_pedido[0]['exame_demissional'] == 1){
            unset($dados_impressao['Email']['Funcionario']);
            unset($dados_impressao['PedidosExames']['funcionario']);
        }

        //seta a variavel itens
        $dados_itens_pedido = $dados_pedido;


        $notificado = false;
        if($this->__enviaRelatorios($dados_impressao, $dados_itens_pedido, $dados_pedido[0]['codigo_cliente_funcionario'], $codigo_pedido_exame, $dados_tipo_notificacao_for_save)) {
            $notificado = true;
        }
        // debug('aqui');
        // exit;

        //atualiza o codigo do pedido exame
        $ped = $this->find()->where(['codigo' => $dados_pedido[0]['codigo']])->first();

        $up_dados_PedidoExame['PedidoExame']['em_emissao'] = "";
        $up_dados_PedidoExame['PedidoExame']['data_notificacao'] = (isset($notificado) && $notificado) ? date('Y-m-d H:i:s') : "";

        //atualiza o pedidos_exames com o codigo do pedido_exame
        $up_ped = $this->patchEntity($ped,$up_dados_PedidoExame);
        //atualiza a tabela de pedido de exames com o codigo do pedido exame
        $this->save($up_ped);

        // limpa array tipos de notificação
        unset($array_organiza_inclusao);


    }//fim enviaKit

    /**
     * Description metodo responsavel por pegar exatamente os relatorios no jasper
     * @param type $dados_post -> dados com os emails e codigos dos relatorios que vai ser executado
     * @param type $dados_itens  -> dados do exame
     * @param type $codigo_cliente_funcionario -> codigo do cliente funcionario / matricula
     * @param type $codigo_pedido  -> codigo do pedido de exame
     * @param type $tipos_relatorio -> os relatorios que irão ser anexos
     */
    public function __enviaRelatorios($dados_post, $dados_itens, $codigo_cliente_funcionario, $codigo_pedido, $tipos_relatorio)
    {

        $this->FornecedoresHorario = TableRegistry::get('FornecedoresHorario');
        $codigo_empresa = 1;

        $this->comum = new Comum();

        $this->Configuracao = TableRegistry::get('Configuracao');
        $codigo_exame_aso = $this->Configuracao->getChave('INSERE_EXAME_CLINICO');
        if(is_null($codigo_exame_aso) || empty($codigo_exame_aso) || $codigo_exame_aso == 0) {
            $codigo_exame_aso = 52;
        }

        $exibe_nome_fantasia_aso = 'false';
        $exibe_rqe_aso = 'false';
        $exibe_aso_embarcado = 'false';

        //verifica se existe relatorio espeficico (audiometria), para enviar somente para os fornecedores que vão,atender o exame de audiometria.
        $fornecedores_audiometria = array();
        if(isset($dados_post['relatorio_especifico'][$codigo_pedido]) && $dados_post['relatorio_especifico'][$codigo_pedido] == '6') {
            foreach($dados_itens as $item) {
                if($item['Exame']['exame_audiometria'] == '1') {
                    $fornecedores_audiometria[$item['Fornecedor']['codigo']] = '6';
                    $dados['fornecedor'][$item['Fornecedor']['codigo']]['dados'] = array();
                    $dados_post['PedidosExames']['fornecedor'][6] = 1;
                }
            }
        }

        $nome_relatorio['1'] = 'pedidos_exame';
        $nome_relatorio['2'] = 'ASO';
        $nome_relatorio['3'] = 'ficha_clinica_1';
        $nome_relatorio['4'] = 'laudo_pcd';
        $nome_relatorio['5'] = 'Recomendacoes';
        $nome_relatorio['6'] = 'audiometria_1';
        $nome_relatorio['7'] = 'ficha_assistencial_exame';
        $nome_relatorio['8'] = 'psicossocial';

        //Relatórios que recebem o parâmetro de fornecedor
        $relatorio_tem_fornecedor = array('1' => '1','6' => '6');

        $conta_arquivo = 0;

        //Merge de todos os relatórios selecionados
        $rel_funcionario = array();
        $rel_cliente = array();
        $rel_fornecedor = array();

        if(!empty($dados_post['PedidosExames']['funcionario'])) $rel_funcionario = array_keys($dados_post['PedidosExames']['funcionario']);
        if(!empty($dados_post['PedidosExames']['cliente'])) $rel_cliente = array_keys($dados_post['PedidosExames']['cliente']);
        if(!empty($dados_post['PedidosExames']['fornecedor'])) $rel_fornecedor = array_keys($dados_post['PedidosExames']['fornecedor']);

        //variaveis para identificar os relatorio nos emails o nome do funcionario e o nome do cliente conforme solicitado
        $nome_funcionario = strtr($dados_post['funcionario_nome'], ' ', '_');
        $nome_cliente = strtr($dados_post['cliente_nome'], ' ', '_');

        $rel_totais = array_unique(array_merge($rel_funcionario, $rel_cliente, $rel_fornecedor));

        //Utilizado para criar apenas os relatórios solicitados
        $relatorios_total = array_fill_keys($rel_totais, 1);

        //seta o codigo do funcionario setor e cargo
        $codigo_func_setor_cargo = $dados_itens[0]['codigo_func_setor_cargo'];

        // retorna dados do cliente e do funcionario
        $contatosClienteFuncionario = $this->retornaContatosClienteFuncionario($codigo_func_setor_cargo);
        // debug($contatosClienteFuncionario);exit;

        $itens_pedido = array();
        $dados_exames = array();
        $tipo_ocupacional_pedido = 'PERIÓDICO';

        $arr_aso_ficha = array();
        $arr_audiometria = array();
        $arr_psicossocial = array();
        $arr_pcd = array();

        $existe_psicossocial = 0;
        $existe_pcd = 0;
        $clienteNome = strtr($nome_cliente,'.','_');
        $clienteNome = str_replace("/", "", $clienteNome);

        foreach ($dados_itens as $key => $value) {
            $itens_pedido[$value['ItensPedidosExames']['codigo_fornecedor']][] = array('codigo_fornecedor' => $value['ItensPedidosExames']['codigo_fornecedor'],
                                                                                        'codigo_exame' => $value['ItensPedidosExames']['codigo_exame'],
                                                                                        'fornecedor_razao_social' => $value['Fornecedor']['razao_social'],
                                                                                        'exame_descricao' => $value['Exame']['descricao'],
                                                                                        'codigo_pedido_exame' => $value['codigo'],
                                                                                        'email_fornecedor' => $value['Fornecedor']['email']);
            $complemento = '';
            $complemento = (!empty($value['FornecedorEndereco']['complemento']) &&  $value['FornecedorEndereco']['complemento'] != ' ') ? ' - '.$value['FornecedorEndereco']['complemento']: '';

            //Se não é horario marcado
            //será necessário recuperar horario de atendimento
            $horario_fornecedor = array();
            if(empty($value['ItensPedidosExames']['data_agendamento'])){
                $horario_fornecedor = $this->FornecedoresHorario->find()->where(['codigo_fornecedor' => $value['ItensPedidosExames']['codigo_fornecedor']])->all();
            }

            //seta as variaveis de data e hora do agendamento do exame
            $ipe_data_agendamento = '';
            $ipe_hora_agendamento = '';

            //verifica se é agendamento => 1 ou ordem de chegada => 0 no tipo_agendamento para tirar a data de agendamento quando for ordem de chegada e não enviar para o email
            if($value['ItensPedidosExames']['tipo_agendamento'] == 1) {
                $ipe_data_agendamento = !empty($value['ItensPedidosExames']['data_agendamento']) ? $value['ItensPedidosExames']['data_agendamento'] : '';
                $ipe_hora_agendamento = !empty($value['ItensPedidosExames']['hora_agendamento']) ? $value['ItensPedidosExames']['hora_agendamento'] : '';
            }

            $dados_exames[] = array('empresa_nome' => $value['Fornecedor']['razao_social'],
                        'empresa_endereco' => $value['FornecedorEndereco']['logradouro'].', '.$value['FornecedorEndereco']['numero'].$complemento.' - '.$value['FornecedorEndereco']['bairro'].' - '.$value['FornecedorEndereco']['cidade'].'/'.$value['FornecedorEndereco']['estado_descricao'],
                        'exame' => $value['Exame']['descricao'],
                        'data' => $ipe_data_agendamento,
                        'hora' => $ipe_hora_agendamento,
                        'tipo_ocupacional' => $tipo_ocupacional_pedido,
                        'horario_fornecedor' => $horario_fornecedor
            );

            // monta os relatorios que devem ser enviados
            $relatorios_post['PedidosExames']['fornecedor'][$value['ItensPedidosExames']['codigo_fornecedor']] = $dados_post['PedidosExames']['fornecedor'];
            //audiometria
            if(isset($dados_post['relatorio_especifico']) && $dados_post['relatorio_especifico'][$codigo_pedido] == '6') {
                $relatorios_post['PedidosExames']['fornecedor'][$value['ItensPedidosExames']['codigo_fornecedor']][6] = 1;
            }

            //variavel auxiliar para saber se tem aso
            if(!isset($arr_aso_ficha[$value['ItensPedidosExames']['codigo_fornecedor']])) {
                $arr_aso_ficha[$value['ItensPedidosExames']['codigo_fornecedor']] = 0;
            }
            //verifica se tem aso
            if($value['ItensPedidosExames']['codigo_exame'] == $codigo_exame_aso) {
                //seta como verdadeiro se tem aso
                $arr_aso_ficha[$value['ItensPedidosExames']['codigo_fornecedor']] = 1;
            }//fim verificacao aso

            //para ficha psicossocial
            //variavel auxiliar para saber se tem exame psicossocial
            if(!isset($arr_psicossocial[$value['ItensPedidosExames']['codigo_fornecedor']])) {
                $arr_psicossocial[$value['ItensPedidosExames']['codigo_fornecedor']] = 0;
            }
            if($value['ItensPedidosExames']['codigo_exame'] == 27) {
                $arr_psicossocial[$value['ItensPedidosExames']['codigo_fornecedor']] = 1;

                //variavel para falar que existe o exame psicossocial
                $existe_psicossocial = 1;

            }//fim exame psicossocial

            //verifica se existe a avaliacao pcd
            if(!isset($arr_pcd[$value['ItensPedidosExames']['codigo_fornecedor']])) {
                $arr_pcd[$value['ItensPedidosExames']['codigo_fornecedor']] = 0;
            }
            if($value['ItensPedidosExames']['codigo_exame'] == 25) {
                $arr_pcd[$value['ItensPedidosExames']['codigo_fornecedor']] = 1;
                //variavel para falar que existe a avaliacao pcd
                $existe_pcd = 1;
            }

            //variavel auxiliar para saber se tem exame audiometria
            if(!isset($arr_audiometria[$value['ItensPedidosExames']['codigo_fornecedor']])) {
                // die('tem');
                $arr_audiometria[$value['ItensPedidosExames']['codigo_fornecedor']] = 0;
            }
            if($value['ItensPedidosExames']['codigo_exame'] == 130 || $value['ItensPedidosExames']['codigo_exame'] == 4240) {
                // die('nao tem');
                $arr_audiometria[$value['ItensPedidosExames']['codigo_fornecedor']] = 1;
            }//fim exame audiometria

        }//FINAL FOREACH $dados['fornecedor']$dados_itens

        // debug($arr_aso_ficha);
        // debug($arr_aso_ficha);
        // debug($arr_audiometria);
        // debug($arr_psicossocial);
        // debug($arr_pcd);
        // debug($itens_pedido);
        // exit;

        //varre os itens de pedidos para saber se ira retirar o aso e ficha clinica para enviar somente para quem irá fazer
        foreach($itens_pedido as $key_codigo_fornecedor => $val) {

            //verifica se o fornecedor irá ter aso para executar ou nao
            if(!$arr_aso_ficha[$key_codigo_fornecedor]) {
                //retira o aso e ficha clinica
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][2])) {
                    unset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][2]);
                }
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][3])) {
                    unset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][3]);
                }
            }//fim if aso existe

            //verifica se o fornecedor irá ter aso para executar ou nao
            if(!$arr_psicossocial[$key_codigo_fornecedor]) {
                //retira o aso e ficha clinica
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][8])) {
                    unset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][8]);
                }
            }//fim if arr_psicossocial

            //verifica se o fornecedor irá ter pcd para executar ou nao
            if(!$arr_pcd[$key_codigo_fornecedor]) {
                //retira o aso e ficha clinica
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][4])) {
                    unset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][4]);
                }
            }//fim if arr_psicossocial

            //verifica se o fornecedor irá ter aso para executar ou nao
            if(!$arr_audiometria[$key_codigo_fornecedor]) {
                //retira o aso e ficha clinica
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][6])) {
                    unset($relatorios_post['PedidosExames']['fornecedor'][$key_codigo_fornecedor][6]);
                }
            }//fim if arr_psicossocial

        }//fim foreach itens

        //verifica se existe exame psicossocial para enviar para o solicitante
        if(!$existe_psicossocial) {
            //solicitante
            if(isset($dados_post['PedidosExames']['cliente'][8])){
                unset($dados_post['PedidosExames']['cliente'][8]);
            }
            //funcionario
            if(isset($dados_post['PedidosExames']['funcionario'][8])){
                unset($dados_post['PedidosExames']['funcionario'][8]);
            }
        }//fim existe psicossocial

        //verifica se existe exame pcd
        if(!$existe_pcd) {
            //solicitante
            if(isset($dados_post['PedidosExames']['cliente'][4])){
                unset($dados_post['PedidosExames']['cliente'][4]);
            }
            //funcionario
            if(isset($dados_post['PedidosExames']['funcionario'][4])){
                unset($dados_post['PedidosExames']['funcionario'][4]);
            }
        }//fim existe psicossocial

        // debug($tipos_relatorio);
        // debug($relatorios_total);
        // debug($relatorio_tem_fornecedor);
        // debug($dados_post);
        // debug($dados_itens);
        // debug($relatorios_post);
        // exit;

        $this->GrupoEconomico = TableRegistry::get('GruposEconomicos');
        $this->GrupoEconomicoCliente = TableRegistry::get('GruposEconomicosClientes');
        $this->Cliente = TableRegistry::get('Cliente');

        $http = new Client();
        $base_portal = BASE_URL_PORTAL;
        //$base_portal = "https://tstportal.rhhealth.com.br";
        
        $path_api = ROOT .DS. 'tmp' .DS. 'pdf' .DS;
        $path_portal = "/home/sistemas/rhhealth/c-care/c-care/app/tmp/pdf/";

        foreach($tipos_relatorio as $key_relatorio => $item_relatorio) {

            $allAttachments = array();
            $params = array();

            $codigo_ge = $this->GrupoEconomicoCliente->find()->select(['codigo_grupo_economico'])->where(['codigo_cliente' => $dados_itens[0]['codigo_cliente']])->first();
            $codigo_ge = $codigo_ge->codigo_grupo_economico;

            ################### Criação dos relatórios sem fornecedor ###################
            //Se o relatório foi solicitado e não possui parametro de fornecedor
            if(isset($relatorios_total[$key_relatorio]) && !isset($relatorio_tem_fornecedor[$key_relatorio])){

                $opcoes = array(
                    'REPORT_NAME'=>'/reports/RHHealth/' . $nome_relatorio[$key_relatorio] // especificar qual relatório
                );

                if($key_relatorio == 2){//ASO

                    if(!empty($codigo_pedido) && !is_null($codigo_pedido)){

                        $codigo_cliente = $dados_itens[0]['codigo_cliente'];

                        if(!is_null($codigo_cliente)){

                            $return = $this->GrupoEconomico->getCampoPorCliente('exibir_nome_fantasia_aso', $codigo_cliente);
                            $exibe_nome_fantasia_aso = ($return ? 'true' : 'false');

                            $retorno_rqe = $this->GrupoEconomico->getCampoPorClienteRqe('exibir_rqe_aso', $codigo_cliente);
                            $exibe_rqe_aso = ($retorno_rqe ? 'true' : 'false');
                        }

                        //buscar no pedido exame se ele foi flegado como aso embarcado
                        if($dados_itens[0]['aso_embarcados'] == 1){
                            $exibe_aso_embarcado = 'true';
                        }

                    }
                }

                $parametros = array(
                    'CODIGO_CLIENTE_FUNCIONARIO' => $codigo_cliente_funcionario,
                    'CODIGO_PEDIDO_EXAME' => $codigo_pedido,
                    'CODIGO_FUNC_SETOR_CARGO' => $codigo_func_setor_cargo,
                    'CODIGO_EXAME_ASO' => $codigo_exame_aso,
                    'EXIBE_NOME_FANTASIA_ASO' => $exibe_nome_fantasia_aso,
                    'EXIBE_RQE_ASO' => $exibe_rqe_aso,
                    'EXIBE_ASO_EMBARCADO' => $exibe_aso_embarcado,
                );

                $grupo_economico_codigo_cliente = $this->GrupoEconomico->find()->where(['codigo' => $codigo_ge])->first();
                $dados_logo_cliente = $this->Cliente->find()->select(['caminho_arquivo_logo'])->where(['codigo' => $grupo_economico_codigo_cliente->codigo_cliente])->first();
                if(!empty($dados_logo_cliente->caminho_arquivo_logo)) {
                    $parametros['URL_MATRIZ_LOGOTIPO'] = "https://api.rhhealth.com.br".$dados_logo_cliente->caminho_arquivo_logo;
                }

                //se o codigo_ge existir e tiver com valor ele faz o tratamento e mmonta com os parametros para traduzir o relatorio aso
                if(isset($codigo_ge) && !empty($codigo_ge)){
                    //Mergeia parametros de tradução se houver
                    $parametros = array_merge($parametros, $this->traducao($codigo_ge));
                }


                $params['parametro'] = $parametros;
                $params['opcoes'] = $opcoes;

                $encode = base64_encode(json_encode($params));
                //Comprimir parametro para não não estourar o limite da url
                $params = strtr(base64_encode(addslashes(gzcompress(serialize($encode),9))), '+/=', '-_,');

                $url_relatorio = "{$base_portal}/portal/impressoes/get_relatorio/{$params}";
                // debug($url_relatorio);

                $url = file_get_contents($url_relatorio);
    
                //define o numero de relatorios asos
                $conta = 1;
                //Se ASO
                if( $key_relatorio == 2 ) {

                    $vias = !empty($dados_post['PedidosExames']['vias_aso']) ? $dados_post['PedidosExames']['vias_aso'] : 1;

                    for($i = 0;$i < $vias; $i++){
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_ASO_'.$conta.'_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                        $key_aso = 'aso_'.$i;

                        //grava os dados do relatorio para criação de novos relatorios
                        $allAttachments[$key_aso]['data'] = $url;
                        $allAttachments[$key_aso]['nome_arquivo'] = $nome_arquivo;
                        $conta++;

                    }
                }
                else if ($key_relatorio == 1 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_PEDIDOS_EXAMES_'.$nome_funcionario.'_'.$clienteNome.'.pdf');

                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 3 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_FICHA_CLINICA_'.$nome_funcionario.'_'.$clienteNome.'.pdf');

                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 5 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_RECOMENDACOES_'.$nome_funcionario.'_'.$clienteNome.'.pdf');

                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 6 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_AUDIOMETRIA_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 4 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_LAUDO_PCD_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 7 ) {
                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_FICHA_ASSISTENCIAL_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                else if ($key_relatorio == 8 ) {

                    $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_AVALIACAO_PSICOSSOCIAL_'.$nome_funcionario.'_'.$clienteNome.'.pdf');

                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;
                }
                $conta_arquivo++;
            }//FINAL SE isset($relatorios_total[$key_relatorio]) && !isset($relatorio_tem_fornecedor[$key_relatorio]

            // debug($allAttachments);exit;

            foreach ($itens_pedido as $key => $item_pedido) {
                // $this->log(print_r($item_pedido,1),'debug');

                $params = array();

                ################### Criação dos relatórios com fornecedor ###################
                if (isset($relatorios_total[$key_relatorio]) && isset($relatorio_tem_fornecedor[$key_relatorio]) && (($key_relatorio != '6') || isset($fornecedores_audiometria[$key]))) {

                    $opcoes = array(
                        'REPORT_NAME'=>'/reports/RHHealth/' . $nome_relatorio[$key_relatorio] // especificar qual relatório
                    );

                    if($key_relatorio == 2){//ASO

                        if(!empty($codigo_pedido) && !is_null($codigo_pedido)){

                            $codigo_cliente = $dados_itens[0]['codigo_cliente'];

                            if(!is_null($codigo_cliente)){

                                $return = $this->GrupoEconomico->getCampoPorCliente('exibir_nome_fantasia_aso', $codigo_cliente);
                                $exibe_nome_fantasia_aso = ($return ? 'true' : 'false');

                                $retorno_rqe = $this->GrupoEconomico->getCampoPorClienteRqe('exibir_rqe_aso', $codigo_cliente);
                                $exibe_rqe_aso = ($retorno_rqe ? 'true' : 'false');
                            }

                            //buscar no pedido exame se ele foi flegado como aso embarcado
                            if($dados_itens[0]['aso_embarcados'] == 1){
                                $exibe_aso_embarcado = 'true';
                            }
                        }
                    }

                    $parametros = array(
                        'CODIGO_FORNECEDOR' => $key,
                        'CODIGO_CLIENTE_FUNCIONARIO' => $codigo_cliente_funcionario,
                        'CODIGO_PEDIDO_EXAME' => $codigo_pedido,
                        'CODIGO_FUNC_SETOR_CARGO' => $codigo_func_setor_cargo,
                        'CODIGO_EXAME_ASO' => $codigo_exame_aso,
                        'EXIBE_NOME_FANTASIA_ASO' => $exibe_nome_fantasia_aso,
                        'EXIBE_RQE_ASO' => $exibe_rqe_aso,
                        'EXIBE_ASO_EMBARCADO' => $exibe_aso_embarcado,
                    );

                    $grupo_economico_codigo_cliente = $this->GrupoEconomico->find()->where(['codigo' => $codigo_ge])->first();
                    $dados_logo_cliente = $this->Cliente->find()->select(['caminho_arquivo_logo'])->where(['codigo' => $grupo_economico_codigo_cliente->codigo_cliente])->first();
                    if(!empty($dados_logo_cliente->caminho_arquivo_logo)) {
                        $parametros['URL_MATRIZ_LOGOTIPO'] = "https://api.rhhealth.com.br".$dados_logo_cliente->caminho_arquivo_logo;
                    }

                    //se o codigo_ge existir e tiver com valor ele faz o tratamento e mmonta com os parametros para traduzir o relatorio aso
                    if(isset($codigo_ge) && !empty($codigo_ge)){
                        //Mergeia parametros de tradução se houver
                        $parametros = array_merge($parametros, $this->traducao($codigo_ge));
                    }

                    $params['parametro'] = $parametros;
                    $params['opcoes'] = $opcoes;

                    $encode = base64_encode(json_encode($params));

                    //Comprimir parametro para não não estourar o limite da url
                    $params = strtr(base64_encode(addslashes(gzcompress(serialize($encode),9))), '+/=', '-_,');

                    $url_relatorio = "{$base_portal}/portal/impressoes/get_relatorio/{$params}";
                    // Log::debug('[URL RELATORIO PARAMS]:'.$url_relatorio);
                    // debug($url_relatorio);

                    $url = file_get_contents($url_relatorio);
                    // Log::debug('[URL RELATORIO]:'.$url);

                    // debug($key_relatorio);

                    if($key_relatorio == 1) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_PEDIDOS_EXAMES_'.$nome_funcionario.'_'.$key.'_'.$clienteNome.'.pdf');
                    } else if ($key_relatorio == 2) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido .'_ASO_'.$nome_funcionario.'_' .$clienteNome.'.pdf');
                    } else if($key_relatorio == 3) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_FICHA_CLINICA_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    } else if($key_relatorio == 4) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_LAUDO_PCD_'.$nome_funcionario.'_' .$clienteNome.'.pdf');
                    } else if ($key_relatorio == 5) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_RECOMENDACOES_'.$nome_funcionario.'_'.$key.'_'.$clienteNome.'.pdf');
                    } else if($key_relatorio == 6){
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_AUDIOMETRIA_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    } else if ($key_relatorio == 7) {
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_FICHA_ASSISTENCIAL_'.$nome_funcionario.'_'.$clienteNome.'.pdf');
                    } else if($key_relatorio == 8){
                        $nome_arquivo  = $this->comum->tirarAcentos('PEDIDO_'.$codigo_pedido.'_AVALIACAO_PSICOSSOCIAL_'.$nome_funcionario.'_' .$clienteNome.'.pdf');
                    }

                    // grava os dados do relatorio para criação de novos relatorios
                    $allAttachments[$conta_arquivo]['data'] = $url;
                    $allAttachments[$conta_arquivo]['nome_arquivo'] = $nome_arquivo;


                    $conta_arquivo++;
                }

                ############################# relatorio do fornecedor ###################################
                if(isset($relatorios_post['PedidosExames']['fornecedor'][$key][$key_relatorio]) && isset($nome_relatorio[$key_relatorio])) {

                    if($key_relatorio == 2 && $item_pedido[0]['codigo_exame'] == 52){
                        $vias = !empty($dados_post['PedidosExames']['vias_aso']) ? $dados_post['PedidosExames']['vias_aso'] : 1;

                            //Gera o número de vias do ASO
                        for ($i = 0; $i < $vias; $i++) {

                            $nome_arquivo_fisico = date('YmdHis') . '_' . $nome_relatorio[$key_relatorio] . '_f' . $i . '_cf' . $codigo_cliente_funcionario .'_p' . $codigo_pedido . '_' . $conta_arquivo . "_for.pdf";

                            $path = $path_api . $nome_arquivo_fisico;

                            $nome_arquivo = $allAttachments['aso_'.$i]['nome_arquivo'];

                            // debug($nome_arquivo);

                            // grava os arquivos em disco
                            file_put_contents($path, $allAttachments['aso_'.$i]['data']);

                            //deve trocar o caminho do arquivo pois o servidor de email para anexar precisa do caminho dele
                            $path = $path_portal . $nome_arquivo_fisico;

                            $attachment[][$nome_arquivo] = $path;
                            $dados['fornecedor'][$key]['attachment'][][$nome_arquivo] = $path;
                            // debug($dados['fornecedor']);

                        }//FINAL FOR $i

                    }
                    else {

                        $nome_arquivo_fisico = date('YmdHis') . '_' . $nome_relatorio[$key_relatorio] . '_f' . $key . '_cf' . $codigo_cliente_funcionario .'_p' . $codigo_pedido . '_' . $conta_arquivo . "_for.pdf";

                        $path = $path_api . $nome_arquivo_fisico;

                        $arr_nome_arquivo = explode("_",$nome_arquivo);

                        // grava os arquivos em disco
                        file_put_contents($path, $url);

                        //deve trocar o caminho do arquivo pois o servidor de email para anexar precisa do caminho dele
                        $path = $path_portal . $nome_arquivo_fisico;

                        $attachment[][$nome_arquivo] = $path;
                        $dados['fornecedor'][$key]['attachment'][][$nome_arquivo] = $path;
                        // debug($dados);
                    }

                    //verifica para preencher somente uma vez
                    if(empty($dados['fornecedor'][$key]['dados'])) {

                        $dados['fornecedor'][$key]['dados'] = array(
                            'tipo_notificacao' => $item_relatorio,
                            'pedido_exame' => $codigo_pedido,
                        );

                            //Valida de acordo com a origem da chamada,  se veio da função notificacao ou notificacao_grupo
                        $email_forn =  !empty($dados_post['EmailFornecedor'][$key]['fornecedor']) ?  $dados_post['EmailFornecedor'][$key]['fornecedor'] : $dados_post['Email']['Fornecedor'][$key]['email'];

                        if(isset($dados_post['PedidosExames']['fornecedor'][$key_relatorio])) {
                            if(!empty($item_pedido[0]['email_fornecedor']) || !empty($email_forn)) {
                                $dados['fornecedor'][$key]['dados'][ucwords($item_pedido[0]['fornecedor_razao_social'])] = !empty($email_forn) ? $email_forn : $item_pedido[0]['email_fornecedor'];
                            }
                        }

                    }//fim verificacao dados do fornecedor

                }
            }//FINAL FOREACH $itens_pedido

            ##########################################################################################
            // debug($allAttachments);
            ############################# relatorio do solicitante ###################################
            if(isset($dados_post['PedidosExames']['cliente'][$key_relatorio])  && isset($nome_relatorio[$key_relatorio]) && !empty($allAttachments)) {

                // debug('solicitante');

                //Valida de acordo com a origem da chamada,  se veio da função notificacao ou notificacao_grupo
                $email_sol = !empty($dados_post['EmailCliente']['email']) ? $dados_post['EmailCliente']['email'] : $dados_post['Email']['Cliente'][$contatosClienteFuncionario['FuncionarioSetoresCargos']['cliente_codigo']]['email'];

                if(!empty($contatosClienteFuncionario['FuncionarioSetoresCargos']['cliente_email']) || !empty($email_sol)) {

                    $attachments = array();

                    foreach ($allAttachments as $key => $value) {

                        $nome_arquivo_fisico = date('YmdHis') . '_' . $nome_relatorio[$key_relatorio] . '_f_' . $key . '_cf' . $codigo_cliente_funcionario .'_p' . $codigo_pedido . "_cli.pdf";

                        // debug($value['nome_arquivo']);
                        $path = $path_api . $nome_arquivo_fisico;
                        $nome_arquivo   = $value['nome_arquivo'];

                        // grava os arquivos em disco
                        file_put_contents($path, $value['data']);
                        //=============

                        //deve trocar o caminho do arquivo pois o servidor de email para anexar precisa do caminho dele
                        $path = $path_portal . $nome_arquivo_fisico;

                        $attachments[$nome_arquivo] = $path;
                    }//FINAL FOREACH $allAttachments

                    $dados['solicitante'][$key]['dados'] = array(
                        'tipo_notificacao' => $item_relatorio,
                        'pedido_exame' => $codigo_pedido,
                        'nome' => $contatosClienteFuncionario['FuncionarioSetoresCargos']['cliente_razao_social'],
                        'email' => !empty($email_sol) ? $email_sol : $contatosClienteFuncionario['FuncionarioSetoresCargos']['cliente_email'],
                        'dados_exames' => $dados_exames,
                        'funcionario_nome' => $dados_post['funcionario_nome'],
                        'cliente_nome' => $dados_post['cliente_nome'],
                    );

                    $dados['solicitante'][$key]['attachments'] = $attachments;
                    // debug($dados['solicitante']);
                }
            }
            ##########################################################################################
            // debug($dados_post);
            ############################# relatorio do funcionario ###################################
            if(isset($dados_post['PedidosExames']['funcionario'][$key_relatorio]) && !empty($allAttachments)) {

                // debug('funcionario');

                //Valida de acordo com a origem da chamada,  se veio da função notificacao ou notificacao_grupo
                $email_func = !empty($dados_post['EmailFuncionario']['email']) ? $dados_post['EmailFuncionario']['email'] :  $dados_post['Email']['Funcionario'][$contatosClienteFuncionario['FuncionarioSetoresCargos']['funcionario_codigo']]['email'];

                if(!empty($contatosClienteFuncionario['FuncionarioSetoresCargos']['funcionario_email']) || !empty($email_func)) {

                    $attachments = array();

                    // debug($allAttachments);

                    foreach ($allAttachments as $key => $value) {

                        // debug($value['nome_arquivo']);
                        $nome_arquivo_fisico = date('YmdHis') . '_' . $nome_relatorio[$key_relatorio] . '_f_' . $key . '_cf' . $codigo_cliente_funcionario .'_p' . $codigo_pedido . "_fun.pdf";
                        $path = $path_api . $nome_arquivo_fisico;

                        $nome_arquivo   = $value['nome_arquivo'];
                        // debug($nome_arquivo);

                        // grava os arquivos em disco
                        file_put_contents($path, $value['data']);
                        //=============

                        //deve trocar o caminho do arquivo pois o servidor de email para anexar precisa do caminho dele
                        $path = $path_portal . $nome_arquivo_fisico;

                        $attachments[$nome_arquivo] = $path;
                        // debug('dpois do file put');
                        // debug($attachments);
                    }

                    $dados['funcionario'][$key]['dados'] = array(
                        'tipo_notificacao' => $item_relatorio,
                        'pedido_exame' => $codigo_pedido,
                        'nome' => $contatosClienteFuncionario['FuncionarioSetoresCargos']['funcionario_nome'],
                        'email' => !empty($email_func) ? $email_func : $contatosClienteFuncionario['FuncionarioSetoresCargos']['funcionario_email'],
                        'dados_exames' => $dados_exames,
                        'funcionario_nome' => $dados_post['funcionario_nome'],
                        'cliente_nome' => $dados_post['cliente_nome'],
                        'tipo_ocupacional_pedido' => $tipo_ocupacional_pedido,
                    );

                    $dados['funcionario'][$key]['attachments'] = $attachments;
                    // debug($dados['funcionario']);
                }
            }//FINAL SE isset($dados_post['PedidosExames']['funcionario'][$key_relatorio])
            ##########################################################################################

        }//FINAL FOREACH $tipos_relatorio

        // debug($dados);
        // exit;


        #########################################################################################################################
        // debug($dados);exit;
        #########################################################################################################################

        // DISPARA OS E-MAILS PARA FUNCIONARIOS COM OS DOCS AGRUPADOS
        if(isset($dados['funcionario']) && count($dados['funcionario'])) {
            $attachment = array();
            // debug($dados['funcionario']);
            foreach ($dados['funcionario'] as $key => $dado) {
                $data = $dado['dados'];
                // debug($dado);
                $attachment = $attachment + $dado['attachments'];
                // debug($attachment);
            }
            $assunto_email_funcionario = 'RH HEALTH - '.$dados_post['funcionario_nome'].' - '.'EXAME '.$tipo_ocupacional_pedido.' - '.$dados_post['cliente_nome'];

            $this->disparaEmail($data, $assunto_email_funcionario, 'agendamento_funcionario', $data['email'], json_encode($attachment));
        }
        //===============================================


        // DISPARA OS EMAILS PARA FORNECEDORES COM OS DOCUMENTOS AGRUPADOS
        if(isset($dados['fornecedor']) && count($dados['fornecedor'])) {
            foreach ($dados['fornecedor'] as $key => $dado) {
                $data = array(
                    'tipo_notificacao' => $dado['dados']['tipo_notificacao'],
                    'pedido_exame' => $dado['dados']['pedido_exame'],
                    'nome' => key(array_slice($dado['dados'], -1)),
                    'email' => end($dado['dados']),
                    'funcionario_nome' => $dados_post['funcionario_nome'],
                    'cliente_nome' => $dados_post['cliente_nome']
                );

                $attachment = array();
                foreach ($dado['attachment'] as $key => $value) {
                    $attachment[key($value)] = end($value);
                }

                $assunto_email_credenciado = 'RH HEALTH - '.$dados_post['funcionario_nome'].' - '.'EXAME '.$tipo_ocupacional_pedido.' - '.$dados_post['cliente_nome'].' - '.key(array_slice($dado['dados'], -1));

                $this->disparaEmail($data, $assunto_email_credenciado, 'agendamento_credenciado', $data['email'], json_encode($attachment));
            }//FINAL FOREACH $dados['fornecedor']
        }
        //===============================================

        // DISPARA OS E-MAILS PARA SOLICITANTES COM OS DOCS AGRUPADOS
        if(isset($dados['solicitante']) && count($dados['solicitante'])) {
            $attachment = array();
            foreach ($dados['solicitante'] as $key => $dado) {
                $data = $dado['dados'];
                $attachment = $attachment + $dado['attachments'];
            }

            $assunto_email_cliente = 'RH HEALTH - '.$dados_post['funcionario_nome'].' - '.'EXAME '.$tipo_ocupacional_pedido.' - '. $dados_post['cliente_nome'];

            $this->disparaEmail($data, $assunto_email_cliente, 'agendamento_cliente', $data['email'], json_encode($attachment));
        }

        //===============================================
        return true;
    }//FINAL FUNCTION __enviaRelatorios

    /**
     * [traducao pega os campos de tradução caso seja necessário]
     * @param  [type] $codigo_ge [description]
     * @return [type]            [description]
     */
    public function traducao($codigo_ge){

        $parametros = array();

        if(!empty($codigo_ge)){

            $this->GrupoEconomico = TableRegistry::get('GruposEconomicos');

            $descricao_idioma = $this->GrupoEconomico->find()->select(['descricao_idioma','codigo_idioma'])->where(['codigo' => $codigo_ge])->first();
            if(!is_null($descricao_idioma->descricao_idioma)) {
                $parametros['DESCRICAO_IDIOMA'] = $descricao_idioma->descricao_idioma;
            }
            $codigo_idioma = trim($descricao_idioma->codigo_idioma);

            if(!empty($codigo_idioma)) {
                if($codigo_idioma != "1"){

                    $this->CamposIdiomasAso = TableRegistry::get('CamposIdiomasAso');
                    foreach ($this->CamposIdiomasAso->listar($codigo_idioma) as $key => $v){
                        if($codigo_idioma == 2){
                            if(
                                $v['campo'] == "apto" || $v['campo'] == "inapto" ||
                                $v['campo'] == "apto_altura" || $v['campo'] == "inapto_altura" ||
                                $v['campo'] == "apto_confinado" || $v['campo'] == "inapto_confinado"
                            ){
                                $v['titulo'] = "[  ] - ".$v['titulo'];
                            }
                        }

                        $parametros[strtoupper($v['campo'])] = $v['titulo'];
                    }

                }
            }

        }

        return $parametros;

    }//fim traducao

    public function retornaContatosClienteFuncionario($codigo_funcionario_setor_cargo) {

        $FuncionarioSetoresCargos = TableRegistry::get('FuncionarioSetoresCargos');

        $options['conditions'] = array(
            'FuncionarioSetoresCargos.codigo' => $codigo_funcionario_setor_cargo
            );
        $options['joins'] = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => array('ClienteFuncionario.codigo = FuncionarioSetoresCargos.codigo_cliente_funcionario')
                ),
            array(
                'table'         => 'cliente',
                'alias'         => 'Cliente',
                'type'          => 'INNER',
                'conditions'    => 'Cliente.codigo = FuncionarioSetoresCargos.codigo_cliente_alocacao'
                ),
            array(
                'table'         => 'cliente_contato',
                'alias'         => 'ClienteContato',
                'type'          => 'LEFT',
                'conditions'    => 'ClienteContato.codigo_cliente = Cliente.codigo AND ClienteContato.codigo_tipo_retorno = 2'
                ),
            array(
                'table'         => 'funcionarios',
                'alias'         => 'Funcionario',
                'type'          => 'INNER',
                'conditions'    => 'Funcionario.codigo = ClienteFuncionario.codigo_funcionario'
                ),
            array(
                'table'         => 'funcionarios_contatos',
                'alias'         => 'FuncionarioContato',
                'type'          => 'LEFT',
                'conditions'    => 'FuncionarioContato.codigo_funcionario = Funcionario.codigo AND FuncionarioContato.codigo_tipo_retorno = 2'
                ),
            );

        $options['fields'] = array(
            'cliente_codigo'=>'Cliente.codigo',
            'cliente_razao_social'=>'Cliente.razao_social',
            'cliente_email'=>'ClienteContato.descricao',
            'funcionario_codigo'=>'ClienteFuncionario.codigo_funcionario',
            'funcionario_nome'=>'Funcionario.nome',
            'funcionario_email'=>'FuncionarioContato.descricao'
            );

        $retorno['FuncionarioSetoresCargos'] = $FuncionarioSetoresCargos->find()->select($options['fields'])->join($options['joins'])->where($options['conditions'])->first()->toArray();

        return $retorno;

    }//fim retornaContatosClientesFuncionario


    public function disparaEmail($dados, $assunto, $template, $to, $attachment = null)
    {


        //consome a url do portal para gerar os emails
        $params = array();
        $params['dados'] = $dados;
        $params['assunto'] = $assunto;
        $params['template'] = $template;
        $params['to'] = $to;
        $params['attachment'] = $attachment;
        // debug($params);exit;
        $params = base64_encode(json_encode($params));
        $base_portal = BASE_URL_PORTAL;
        $url_email = "{$base_portal}/portal/impressoes/get_dispara_email/{$params}";

        // debug($url_email);

        $url = file_get_contents($url_email);
        return true;

    }//fim disparaEmail
}
