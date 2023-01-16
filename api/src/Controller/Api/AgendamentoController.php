<?php

namespace App\Controller\Api;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use DateTime;
use DateTimeZone;
use App\Controller\Api\ApiController;

/**
 * Agendamento Controller
 *
 * @property \App\Model\Table\AgendamentoExamesTable AgendamentoExamesTable
 *
 * @method \App\Model\Entity\AgendamentoExame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */

class AgendamentoController extends ApiController
{
    public $connection;
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');

        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow(['updateDataExame', 'conferirExame', 'listaAgendamentoExames', 'add']);

        $this->loadModel("TipoNotificacao");
        $this->loadModel("TipoNotificacaoValores");
        $this->loadModel("GruposEconomicos");
        $this->loadModel("PedidosExames");
        $this->loadModel("Fornecedores");
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        try {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //seta para o retorno do objeto
            $data = array();

            $data = $this->getAgendamentoList($dados);

            $this->set(compact('data'));
            //$this->set('data', $data);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    /**
     * [getAgendamentoList para montar o agendamento]
     * @param  [type] $dados [description]
     * @return [type]        [description]
     */
    private function getAgendamentoList($dados)
    {

        $data = array();

        //parametro tipo_data: dia => 1, semana => 2, mes => 3
        $query_days = '';
        $array_datas = array();
        switch ($dados['tipo_data']) {
            case 1:

                $array_datas = $this->montaDataPorDia($dados['data_por_dia'],$dados['data_por_dia']);

                $query_days = " and ae.data = '" . $dados['data_por_dia'] . "' ";
                break;
            case 2:

                $array_datas = $this->montaDataPorDia($dados['data_inicio'],$dados['data_fim']);

                $query_days = " and ae.data >= '" . $dados['data_inicio'] . "' and ae.data <= '" . $dados['data_fim'] . "' ";
                break;
            case 3:

                $array_datas = $this->montaDataPorDia($dados['data_inicio'],$dados['data_fim']);

                $query_days = " and ae.data >= '" . $dados['data_inicio'] . "' and ae.data <= '" . $dados['data_fim'] . "' ";
                break;
        }

        $query_days_compromisso = '';
        switch ($dados['tipo_data']) {
            case 1:
                $query_days_compromisso = " c.data = '" . $dados['data_por_dia'] . "' ";
                break;
            case 2:
                $query_days_compromisso = " c.data >= '" . $dados['data_inicio'] . "' and c.data <= '" . $dados['data_fim'] . "' ";
                break;
            case 3:
                $query_days_compromisso = " c.data >= '" . $dados['data_inicio'] . "' and c.data <= '" . $dados['data_fim'] . "' ";
                break;
        }

        //Verifica se tem especialidade
        $query_especialidade = '';
        $query_especialidade_compromisso = '';

        //correcao de um chamado pois o modulo de agendamento esta passando o paramentro especialidades e não especialidade
        if (!empty($dados['especialidades'])) {
            $query_especialidade = "
                inner join fornecedores_medico_especialidades fme on ipe.codigo_fornecedor = fme.codigo_fornecedor
                    and fme.codigo_medico = m.codigo
                inner join especialidades espec on fme.codigo_especialidade = espec.codigo
                    and espec.descricao = '" . $dados['especialidades'] . "' ";

            $query_especialidade_compromisso = "
                inner join fornecedores_medico_especialidades fme on fme.codigo_medico = m.codigo
                inner join especialidades espec on fme.codigo_especialidade = espec.codigo
                    and espec.descricao = '" . $dados['especialidades'] . "' ";
        }

        //Busca por codigo_medico
        $query_medico = '';
        $codigo_medico = null;
        if (!empty($dados['codigo_medico'])) {
            $codigo_medico = $dados['codigo_medico'];
            $query_medico = " and m.codigo IN(" . $dados['codigo_medico'] . ") ";
        }

        //Buscar por codigo_pedidos_exames
        $query_codigo_pedidos_exames = "";
        if (!empty($dados['codigo_pedidos_exames'])) {
            $query_codigo_pedidos_exames = " and pe.codigo = " . $dados['codigo_pedidos_exames'];
        }

        //Buscar por codigo_itens_pedidos_exames
        $query_codigo_itens_pedidos_exames = "";
        if (!empty($dados['codigo_itens_pedidos_exames'])) {
            $query_codigo_itens_pedidos_exames = " and ipe.codigo_itens_pedidos_exames = " . $dados['codigo_itens_pedidos_exames'];
        }

        //Buscar por codigo_status_itens_pedidos_exames
        $query_codigo_status_itens_pedidos_exames = "";
        if (!empty($dados['codigo_status_itens_pedidos_exames'])) {
            $query_codigo_status_itens_pedidos_exames = " and ipe.codigo_status_itens_pedidos_exames = {$dados['codigo_status_itens_pedidos_exames']} ";
        }

        //Buscar por codigo_status_pedidos_exames
        $query_codigo_status_pedidos_exames = "";
        if (!empty($dados['codigo_status_pedidos_exames'])) {
            $query_codigo_status_pedidos_exames = " and pe.codigo_status_pedidos_exames = {$dados['codigo_status_pedidos_exames']} ";
        }

        //Buscar por cpf
        $query_cpf = '';
        $query_cpf_paciente = '';
        if (!empty($dados['cpf'])) {
            $query_cpf = " and f.cpf = '{$dados['cpf']}' ";
            $query_cpf_paciente = " and p.cpf = '{$dados['cpf']}' ";
        }

        //Buscar por rg
        $query_rg = '';
        $query_rg_paciente = '';
        if (!empty($dados['rg'])) {
            $query_rg = " and f.rg = '{$dados['rg']}' ";
            $query_rg_paciente = " and p.rg = '{$dados['rg']}' ";
        }

        //Buscar por nome
        $query_nome = '';
        $query_nome_paciente = '';
        if (!empty($dados['nome'])) {
            $query_nome = " and f.nome LIKE '%" . $dados['nome'] . "%' ";
            $query_nome_paciente = " and p.nome LIKE '%" . $dados['nome'] . "%' ";
        }

        //Buscar por matricula
        $query_matricula = '';
        if (!empty($dados['matricula'])) {

            $query_matricula = " inner join cliente_funcionario cf
                                on cf.codigo = pe.codigo_cliente_funcionario
                                and cf.matricula = '{$dados['matricula']}' ";

        }else{

            $query_matricula = " left join cliente_funcionario cf
                                on cf.codigo = pe.codigo_cliente_funcionario ";
        }

        //Buscar por empresa
        $query_empresa = '';
        if (!empty($dados['empresa'])) {

            $query_empresa = " inner join grupos_economicos ge
                                on ge.codigo_cliente = cf.codigo_cliente
                                and ge.descricao like '%{$dados['empresa']}%' ";
        }else{

            $query_empresa = " left join grupos_economicos ge
                                on ge.codigo_cliente = cf.codigo_cliente ";
        }

        //Busca por Fornecedor unidade
        $query_fornecedore_unidade = '';
        if (!empty($dados['codigo_fornecedor_unidade'])) {

            //Se o codigo_fornecedor_unidade for igual ao codigo_fornecedor, executar a query usando o left join na tabela de fornecedores_unidades
            if ($dados['codigo_fornecedor_unidade'] == $dados['codigo_fornecedor']) {
                $query_fornecedore_unidade = " left join fornecedores_unidades fu
                                on fu.codigo_fornecedor_matriz = forn.codigo or fu.codigo_fornecedor_unidade = forn.codigo ";
            } else {
                $query_fornecedore_unidade = " inner join fornecedores_unidades fu
                                on fu.codigo_fornecedor_matriz = {$dados['codigo_fornecedor']} and fu.codigo_fornecedor_unidade = {$dados['codigo_fornecedor_unidade']} ";
            }

        }else{

            $query_fornecedore_unidade = " left join fornecedores_unidades fu
                                on fu.codigo_fornecedor_matriz = forn.codigo or fu.codigo_fornecedor_unidade = forn.codigo ";
        }

        //Verificação para saber qual o é dia da semana referente a data enviada
        $dia_semana_por_data = "";
        if (isset($dados['data_por_dia']) and isset($dados['data_inicio'])) {
            // debug('aqui');
            $dia_semana_por_data = $this->verificaDiaSemana($dados['data_inicio']);
        } else if (isset($dados['data_por_dia'])) {
            // debug('aqui');
            $dia_semana_por_data = $this->verificaDiaSemana($dados['data_por_dia']);
        } else {
            // debug('aqui');
            $dia_semana_por_data = $this->verificaDiaSemana($dados['data_inicio']);
        }

        //RETORNA A GRADE DE FORNECEDORES HORARIOS
        $fornecedoresGradeHorario = $this->getFornecesoresHorarios($dados['codigo_fornecedor'],null,$codigo_medico);
        // debug($fornecedoresGradeHorario); die;
        //./END

        //Verificação para saber qual é o codigo do dia da semana referente ao parametro: data_por_dia ou data_inicio
        $dia_semana_codigo = $this->convertToWeekDayCode($dia_semana_por_data);
        //Verificação para saber qual é o dia da semana referente ao parametro: dia_semana
        $dia_semana = $this->convertToWeekDay($dia_semana_codigo);

        //pega somente quanfdo o tipo for = 1 que é por dia
        $queryHoras = '';
        $params = ['codigo_fornecedor' => $dados['codigo_fornecedor']];
        if($dados['tipo_data'] == 1) {
            $queryHoras = " and dia_semana = :dia_semana ";
            //Seta as variaveis do request para bindar os dados na query
            $params = ['codigo_fornecedor' => $dados['codigo_fornecedor'], 'dia_semana' => $dia_semana_codigo];
        }

        $strSql = 'select dia_semana, hora
                    from fornecedores_grade_agenda
                    where codigo_fornecedor = :codigo_fornecedor
                    '.$queryHoras.'
                    group by dia_semana, hora
                    order by hora, dia_semana';
        //Retorna os dados da consulta ao banco
        $result = $this->connection->execute($strSql, $params)->fetchAll('assoc');

        //pega as configuracoes do sistema para os exames
        $this->loadModel('Configuracao');
        $arrConfig = $this->Configuracao->getConfiguracaoTiposExames();

        //variavel para determinar se o calendario esta disponivel ou nao
        $indisponivel = false;

        //verifica se tem horarios para o dia da semana se não tiver tem que trazer indisponivel como true
        if(empty($result)) {
            $indisponivel = true;

            //Seta as variaveis do request para bindar os dados na query
            $params = ['codigo_fornecedor' => $dados['codigo_fornecedor']];
            $strSql = 'select hora
                        from fornecedores_grade_agenda
                        where codigo_fornecedor = :codigo_fornecedor
                        group by hora
                        order by hora';
            //Retorna os dados da consulta ao banco
            $result = $this->connection->execute($strSql, $params)->fetchAll('assoc');

        } //fim result

        $strSqlMedicos = "SELECT
                                ipe.codigo_fornecedor,
                                fu.codigo_fornecedor_unidade,
                                RHHealth.dbo.ufn_decode_utf8_string(CONCAT(forn.nome,' / ',forn.razao_social)) as fornecedor_unidade,
                                ae.codigo_itens_pedidos_exames,
                                (CASE WHEN spe.codigo = 3 THEN 'Realizado' ELSE RHHealth.dbo.ufn_decode_utf8_string(spe.descricao) END) as status_exame,
                                spe.codigo as codigo_status_exame,
                                ae.data,
                                ae.hora,
                                ipe.codigo_pedidos_exames,
                                ipe.codigo_exame,
                                ipe.codigo_medico,
                                ipe.data_realizacao_exame AS data_realizacao,
                                RHHealth.dbo.ufn_decode_utf8_string(m.nome) as nome_medico,
                                RHHealth.dbo.ufn_decode_utf8_string(m.especialidade) as especialidade,
                                CAST(ipe.observacao AS NVARCHAR(MAX)) observacao,
                                (CASE WHEN f.codigo IS NOT NULL THEN f.codigo ELSE p.codigo END) as codigo_funcionario,
                                (CASE WHEN f.codigo IS NOT NULL THEN RHHealth.dbo.ufn_decode_utf8_string(f.nome) ELSE p.nome END) as nome_funcionario,
                                (CASE WHEN f.cpf IS NOT NULL THEN f.cpf ELSE p.cpf END) as cpf,
                                (CASE WHEN f.rg IS NOT NULL THEN f.rg ELSE p.rg END) as rg,
                                (CASE WHEN f.foto IS NOT NULL THEN f.foto ELSE f.foto END) as foto,
                                cf.matricula,
                                ge.codigo as codigo_empresa,
                                RHHealth.dbo.ufn_decode_utf8_string(ge.descricao) as empresa,
                                (CASE WHEN pe.pontual =1 THEN RHHealth.dbo.ufn_decode_utf8_string(e.descricao)
                                ELSE
                                    CASE
                                        WHEN pe.exame_admissional = 1 THEN 'Exame admissional'
                                        WHEN pe.exame_periodico = 1 THEN 'Exame periódico'
                                        WHEN pe.exame_demissional = 1 THEN 'Exame demissional'
                                        WHEN pe.exame_retorno = 1 THEN 'Retorno ao trabalho'
                                        WHEN pe.exame_mudanca = 1 THEN 'Mudança de cargo'
                                        WHEN pe.exame_monitoracao = 1 THEN 'Monitoração pontual'
                                        ELSE ''
                                    END
                                END ) as tipo_exame,
                                ipe.codigo_status_itens_pedidos_exames,
                                '' as compromisso_codigo,
                                '' as compromisso_titulo,
                                '' as compromisso_descricao,
                                '' as compromisso_ativo,
                                '' as compromisso_hora_fim
                            from agendamento_exames ae

                            join itens_pedidos_exames ipe
                                on ipe.codigo_fornecedor = :codigo_fornecedor
                                " . $query_days . "
                                and ipe.codigo_fornecedor = ae.codigo_fornecedor
                                and ipe.data_agendamento = ae.data
                                and ipe.codigo = ae.codigo_itens_pedidos_exames
                                " . $query_codigo_itens_pedidos_exames . "

                            inner join medicos m
                                on m.codigo = ipe.codigo_medico
                                " . $query_medico . "
                                " . $query_especialidade . "

                            inner join exames e on e.codigo = ipe.codigo_exame

                            inner join pedidos_exames pe
                                on pe.codigo = ipe.codigo_pedidos_exames
                                " . $query_codigo_pedidos_exames . "
                                " . $query_codigo_status_pedidos_exames . "

                            inner join status_pedidos_exames spe
                                on pe.codigo_status_pedidos_exames = spe.codigo

                            " . $query_matricula . "

                            " . $query_empresa . "

                            inner join fornecedores forn
                                on forn.codigo = ipe.codigo_fornecedor

                            " . $query_fornecedore_unidade . "

                            inner join funcionarios f
                                on f.codigo = pe.codigo_funcionario
                                " . $query_nome . "
                                " . $query_cpf . "
                                " . $query_rg . "

                            left join pacientes p
                                on p.codigo = pe.codigo_paciente
                                " . $query_nome_paciente . "
                                " . $query_cpf_paciente . "
                                " . $query_rg_paciente . "

                            where ipe.codigo_status_itens_pedidos_exames <> '6' {$query_codigo_status_itens_pedidos_exames}

                            UNION

                            SELECT
                                '' as codigo_fornecedor,
                                '' as codigo_fornecedor_unidade,
                                '' as fornecedor_unidade,
                                '' as codigo_itens_pedidos_exames,
                                '' as status_exame,
                                '' as codigo_status_exame,
                                c.data,
                                c.hora_inicio as hora,
                                '' as codigo_pedidos_exames,
                                '' as codigo_exame,
                                c.codigo_medico,
                                '' AS data_realizacao,
                                RHHealth.dbo.ufn_decode_utf8_string(m.nome) as nome_medico,
                                RHHealth.dbo.ufn_decode_utf8_string(m.especialidade) AS especialidade,
                                '' as observacao,
                                '' as codigo_funcionario,
                                '' as nome_funcionario,
                                '' as cpf,
                                '' as rg,
                                '' as foto,
                                '' as matricula,
                                '' as codigo_empresa,
                                '' as empresa,
                                '' as tipo_exame,
                                '' as codigo_status_itens_pedidos_exames,
                                c.codigo as compromisso_codigo,
                                c.titulo as compromisso_titulo,
                                c.descricao as compromisso_descricao,
                                c.ativo as compromisso_ativo,
                                c.hora_fim as compromisso_hora_fim
                            from compromisso c
                            inner join medicos m on m.codigo = c.codigo_medico
                            " . $query_medico . "
                            " . $query_especialidade_compromisso . "
                            where " . $query_days_compromisso . "

                            order by ae.data, ae.hora";

        //Retorna os dados da consulta ao banco para buscar os medicos
        $agendamento_params = ['codigo_fornecedor' => $dados['codigo_fornecedor']];

        $result_medicos = $this->connection->execute($strSqlMedicos, $agendamento_params)->fetchAll('assoc');

        //verifica se o filtro é de mes para nao montar a grade de indisponivel pois no front nao monta isso
        if($dados['tipo_data'] == 3) {
            foreach ($result as $key => $row) { //percorre datas

                $var_dia_semana = (isset($row['dia_semana'])) ? $row['dia_semana'] : $dia_semana_codigo;
                $hora = $this->formatHour($row['hora']);

                $item['dia_semana'] = $this->convertToWeekDay($var_dia_semana); //Formata para dia da semana
                $item['hora'] = $hora; //Formata a string para o formato de horas
                $item['indisponivel'] = $indisponivel; //deixa a variavel indisponivel setar se vai estar disponivel ou nao
                $item['agendamento'] = array();

                foreach ($result_medicos as $resultado_key => $v) {

                    $dia_semana_exame = date('w', strtotime($v['data']));
                    if (($v["hora"] == $row["hora"] && $dia_semana_exame == $var_dia_semana) || ($row['hora'] >= $v["hora"] && $row['hora'] < $v['compromisso_hora_fim'])) { //a hora retornada na query (hora = hora_inicio) é igual a hora de fornecedores_grade_agenda?

                        if ($dia_semana_por_data == $dia_semana) {
                            $v['status_itens_pedidos_exames_cor'] = $v['codigo_status_itens_pedidos_exames'];

                            //agendamento
                            if ($v['codigo_itens_pedidos_exames'] != "0") {
                                //seta o tipo
                                $v['tipo'] = "agendamento";
                                if (!empty($v['codigo_exame'])) {
                                    //verifica se tem que passar algum tipo de configuracao
                                    if (isset($arrConfig[$v['codigo_exame']])) {
                                        $v['tipo'] = $arrConfig[$v['codigo_exame']];
                                    } //fim verificacao
                                } //fim verificacao se tem exame
                                if (!empty($v['matricula'])) {
                                    $v['vinculo'] = 1; //tipo do vinculo do paciente é funcionario
                                    $v['vinculo_descricao'] = "Colaborador";
                                } else {
                                    $v['vinculo'] = 2; //tipo do vinculo do paciente é terceirizado
                                    $v['vinculo_descricao'] = "Terceirizado";
                                }
                                unset($v['compromisso_titulo']);
                                unset($v['compromisso_descricao']);
                                unset($v['compromisso_ativo']);
                                $item['agendamento'][] = $v;
                            } else {
                                //compromisso
                                $item['agendamento'][] = array(
                                    "tipo"  => "compromisso",
                                    "codigo" => $v['compromisso_codigo'],
                                    "data" => $v['data'],
                                    "hora_inicio" => $v['hora'],
                                    "hora_fim" => $v['compromisso_hora_fim'],
                                    "titulo" => $v['compromisso_titulo'],
                                    "descricao" => $v['compromisso_descricao'],
                                    "ativo" => $v['compromisso_ativo'],
                                    "codigo_medico" => $v['codigo_medico'],
                                    "especialidade" => $v['especialidade']
                                );
                            }
                        }
                    }
                }

                $data[] = $item;
            }
        }//fim if tipo data filtro mes = 3
        else {

            $codigo_ipe = array(); //Codigos itens pedido exames

            foreach($array_datas AS $param_data) {

                foreach ($result as $key => $row) { //percorre datas

                    $var_dia_semana = (isset($row['dia_semana'])) ? $row['dia_semana'] : $dia_semana_codigo;
                    $hora = $this->formatHour($row['hora']);

                    $item['dia_semana'] = $this->convertToWeekDay($var_dia_semana); //Formata para dia da semana
                    $item['hora'] = $hora; //Formata a string para o formato de horas
                    $item['indisponivel'] = $indisponivel; //deixa a variavel indisponivel setar se vai estar disponivel ou nao
                    $item['agendamento'] = array();

                    $foraDeGrade = array();
                    // $hora = new DateTime($this->formatHour($v["hora"]));
                    if ( !empty($fornecedoresGradeHorario[$var_dia_semana])) {

                        // $itemGrade = $fornecedoresGradeHorario[$var_dia_semana][$v['codigo_medico']];
                        foreach ($fornecedoresGradeHorario[$var_dia_semana] as $codigo_medico => $itemGrade) {

                            $hora_inicio_manha  = $itemGrade['hora_inicio_manha'];
                            $hora_fim_manha  = $itemGrade['hora_fim_manha'];
                            $hora_inicio_tarde  = $itemGrade['hora_inicio_tarde'];
                            $hora_fim_tarde  = $itemGrade['hora_fim_tarde'];

                            // debug(array($var_dia_semana, $v['codigo_medico'], $hora,$hora_inicio_manha,$hora_fim_manha,$hora_inicio_tarde,$hora_fim_tarde));

                            $disponivel = 0;
                            //verifica se esta denro da hora de disponibulidade
                            if($hora >= $hora_inicio_manha && $hora <= $hora_fim_manha ) {
                                $disponivel = 1;
                            }

                            //verifica se esta denro da hora de disponibulidade
                            if($hora >= $hora_inicio_tarde && $hora <= $hora_fim_tarde ) {
                                $disponivel= 1;
                            }

                            //verifica se esta INDISPONIVEL
                            if(!$disponivel) {
                                $foraDeGrade[$codigo_medico][$hora] = array(
                                    'codigo_medico' => $codigo_medico,
                                    'nome_medico'   => $itemGrade['nome'],
                                    'data'          => $param_data,
                                    'hora'          => $hora,
                                    'indisponivel'  => true,
                                );

                            }//fim fisponivel

                        }//fim foreach grade

                    }// fornecedores grade medico

                    foreach ($result_medicos as $resultado_key => $v) {


                        $dia_semana_exame = date('w', strtotime($v['data']));
                        if (($v["hora"] == $row["hora"] && $dia_semana_exame == $var_dia_semana) || ($row['hora'] >= $v["hora"] && $row['hora'] < $v['compromisso_hora_fim'])) { //a hora retornada na query (hora = hora_inicio) é igual a hora de fornecedores_grade_agenda?

                            if ($dia_semana_por_data == $dia_semana) {
                                $v['status_itens_pedidos_exames_cor'] = $v['codigo_status_itens_pedidos_exames'];

                                //agendamento
                                if ($v['codigo_itens_pedidos_exames'] != "0") {

                                    //seta o tipo
                                    $v['tipo'] = "agendamento";
                                    if (!empty($v['codigo_exame'])) {
                                        //verifica se tem que passar algum tipo de configuracao
                                        if (isset($arrConfig[$v['codigo_exame']])) {
                                            $v['tipo'] = $arrConfig[$v['codigo_exame']];
                                        } //fim verificacao
                                    } //fim verificacao se tem exame
                                    if (!empty($v['matricula'])) {
                                        $v['vinculo'] = 1; //tipo do vinculo do paciente é funcionario
                                        $v['vinculo_descricao'] = "Colaborador";
                                    } else {
                                        $v['vinculo'] = 2; //tipo do vinculo do paciente é terceirizado
                                        $v['vinculo_descricao'] = "Terceirizado";
                                    }
                                    unset($v['compromisso_titulo']);
                                    unset($v['compromisso_descricao']);
                                    unset($v['compromisso_ativo']);

                                    //Verifica se o exame já foi inserido no objeto de agendamento, para não inserir novamente.
                                    if (!in_array($v['codigo_itens_pedidos_exames'], $codigo_ipe)) {

                                        $codigo_ipe[] = $v['codigo_itens_pedidos_exames'];
                                        $item['agendamento'][] = $v;
                                    }

                                } else {
                                    //compromisso
                                    $item['agendamento'][] = array(
                                        "tipo"  => "compromisso",
                                        "codigo" => $v['compromisso_codigo'],
                                        "data" => $v['data'],
                                        "hora_inicio" => $v['hora'],
                                        "hora_fim" => $v['compromisso_hora_fim'],
                                        "titulo" => $v['compromisso_titulo'],
                                        "descricao" => $v['compromisso_descricao'],
                                        "ativo" => $v['compromisso_ativo'],
                                        "codigo_medico" => $v['codigo_medico'],
                                        "especialidade" => $v['especialidade']
                                    );
                                }
                            }
                        }
                    }

                    if(!empty($foraDeGrade)) {

                        foreach ($foraDeGrade as $key => $med_indisponivel) {
                            $var_indisponivel = array_shift($med_indisponivel);
                            $item['agendamento'][] = $var_indisponivel;
                        }
                    }

                    $data[] = $item;
                }

//                $data = array_unique($data);
            }
//            $data = array_unique($data);
        }//fim else filtro mes

        return $data;
    } //fim getAgendamentoList

    /**
     * Description metodo par amontar as datas nos intervalos passados
     * @param type $data_inicio
     * @param type $data_fim
     * @return type
     */
    public function montaDataPorDia($data_inicio,$data_fim)
    {

        $start = new \DateTime($data_inicio);
        $end = new \DateTime($data_fim);
        $periodArr = new \DatePeriod($start , new \DateInterval('P1D') , $end);

        $dados = array();
        foreach($periodArr as $period) {
            $dados[] = $period->format('Y-m-d');
            // echo $period->format('d/m/Y H:i:s').'<br />';
        }
        $dados[] = $end->format('Y-m-d');

        return $dados;

    }// fim montaDataPorDIa



    public function unique_multi_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
      }

    public function colorStatus($codigo_status_itens_pedidos_exames)
    {

        $color = '';
        switch ($codigo_status_itens_pedidos_exames) {
            case 1:
                $color = 'verde';
                break;
            case 2:
                $color = 'vermelho';
                break;
            case 3:
                $color = 'amarelo';
                break;
            case 4:
                $color = 'laranja';
                break;
            case 5:
                $color = 'azul';
                break;
            default:
                $color = 'cinza claro';
        }

        return $color;
    }

    public function formatHour($hora)
    {
        //Se o tamanho da campo 'hora' for igual a 3, adiciona um '0'
        // a frente para poder fazer a conversão do intereiro em horas
        if (strlen($hora) == 3) {
            $hora = 0 . $hora;
        } else {
            $hora = $hora;
        }
        //Formata a string para o formato de horas
        return  substr($hora, 0, 2) . ":" . substr($hora, 2);
    }

    public function convertToWeekDay($dia_semana)
    {
        $converte_dia = array(
            "Segunda" => 1,
            "Terça"   => 2,
            "Quarta"  => 3,
            "Quinta"  => 4,
            "Sexta"   => 5,
            "Sábado"  => 6,
            "Sabado"  => 6,
            "Domingo" => 7,
        );

        $result = array_keys($converte_dia, $dia_semana);

        if(!empty($result)) {
            $return = $result[0];
        }
        else {
            $return = $converte_dia[$dia_semana];
        }

        return $return;
    }

    public function convertToWeekDayCode($dia_semana)
    {
        $converte_dia = array(
            1 => "Segunda",
            2 => "Terça",
            3 => "Quarta",
            4 => "Quinta",
            5 => "Sexta",
            6 => "Sábado",
            6 => "Sabado",
            7 => "Domingo",
        );
        $result = array_keys($converte_dia, $dia_semana);

        // debug($result);exit;

        if(!empty($result)) {
            $return = $result[0];
        }
        else {
            $return = $converte_dia[$dia_semana];
        }

        return $return;

    }

    public function verificaDiaSemana($data)
    {
        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

        $diasemanaNumero = date('w', strtotime($data));

        return $diasemana[$diasemanaNumero];
    }
    /**
     * View method
     *
     * @param string|null $id Medico id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        try {
            $data = array(); //Retorno do resultado de inserção

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //            $this->loadModel('PedidosExames');
            //            $pedidoExame = $this->PedidosExames->newEntity($dados);
            //
            //            if ($this->PedidosExames->save($pedidoExame)) {
            //                $data = $pedidoExame;
            //
            //                //Inicia verificacao para criar itens na tabela de itens_pedidos_exames
            //
            //            }

            $exames = [
                "codigo_fornecedor" => 8360,
                "codigo_exame" => 27,
                "tipo_atendimento" => null,
                "tipo_agendamento" => null,
                "data" => "2019-10-31",
                "horario" => "1735",
            ];

            $data = $this->setItensPedidosExames(63031, 175812, 79930, 79930, 1, $exames);

            //$this->set('dados', $data);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Edit method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
    }
    /**
     * Delete method
     *
     * @param string|null $id Medico id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
    }

    public function updateDataExame()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        //Declara transação
        $conn = $this->connection;

        try {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            if (empty($dados['codigo_itens_pedidos_exames'])) {
                throw new Exception("Favor passar o codigo da itens pedidos exames!");
            }
            $codigo_itens_pedidos_exames = $dados['codigo_itens_pedidos_exames'];


            //verifica se o exame que está reagendando esta baixado
            $this->loadModel('ItensPedidosExamesBaixa');
            $baixado = $this->ItensPedidosExamesBaixa->find()->where(['codigo_itens_pedidos_exames' => $codigo_itens_pedidos_exames])->first();

            if(!empty($baixado)) {
                throw new Exception("Exame já baixado não podendo reagendar!");
            }


            if (empty($dados['codigo_usuario'])) {
                throw new Exception("Favor passar o codigo do usuario!");
            }
            $codigo_usuario = $dados['codigo_usuario'];


            if (empty($dados['codigo_medico'])) {
                throw new Exception("Favor passar o codigo do medico!");
            }
            $codigo_medico = $dados['codigo_medico'];

            if (empty($dados['codigo_fornecedor'])) {
                throw new Exception("Favor passar o codigo do fornecedor!");
            }
            $codigo_fornecedor = $dados['codigo_fornecedor'];

            if (empty($dados['data_agendamento'])) {
                throw new Exception("Favor passar a nova data do agendamento!");
            }

            if (empty($dados['hora_agendamento'])) {
                throw new Exception("Favor passar a nova hora do agendamento!");
            }
            $hora_agendamento = str_replace(":", '', $dados['hora_agendamento']);


            $itensPedidosExamesTable = TableRegistry::getTableLocator()->get('ItensPedidosExames');
            $itensPedidosExames = $itensPedidosExamesTable->get($codigo_itens_pedidos_exames);

            $itensPedidosExames->data_agendamento = $dados['data_agendamento'];
            $itensPedidosExames->hora_agendamento = $hora_agendamento;
            $itensPedidosExames->codigo_medico = $codigo_medico;
            $itensPedidosExames->codigo_fornecedor = $codigo_fornecedor;
            $itensPedidosExames->codigo_usuarios_alteracao = $codigo_usuario;
            $itensPedidosExames->data_alteracao = date('Y-m-d H:i:s');

            if(isset($dados['observacao'])) {
                $itensPedidosExames->observacao = $dados['observacao'];
            }

            //inicia transacao
            $conn->begin();

            if ($result = $itensPedidosExamesTable->save($itensPedidosExames)) {

                $agendamentoExamesTable = TableRegistry::getTableLocator()->get('AgendamentoExames');
                $codigoItensPedidosExames = $agendamentoExamesTable->findByCodigoItensPedidosExames($codigo_itens_pedidos_exames);

                $codigoAgendamentoExame = null;
                foreach ($codigoItensPedidosExames as $row) {
                    $codigoAgendamentoExame = $row->codigo;
                }

                if (is_null($codigoAgendamentoExame)) {
                    //pega o codigo da lista de preco produto servico
                    $codigo_lpps = $agendamentoExamesTable->getCodigoLPPS($codigo_itens_pedidos_exames);

                    //monta para inserir na tabela de agendamento exames
                    $agendamentoExames['codigo_itens_pedidos_exames'] = $codigo_itens_pedidos_exames;
                    $agendamentoExames['codigo_fornecedor'] = $itensPedidosExames->codigo_fornecedor;
                    $agendamentoExames['codigo_listas_de_preco_produto_servico'] = $codigo_lpps;
                    $agendamentoExames['codigo_medico'] = $codigo_medico;
                    $agendamentoExames['codigo_empresa'] = 1;
                    $agendamentoExames['ativo'] = 1;
                    $agendamentoExames['data'] = $dados['data_agendamento'];
                    $agendamentoExames['hora'] = $hora_agendamento;
                    $agendamentoExames['codigo_usuario_inclusao'] = $codigo_usuario;
                    $agendamentoExames['data_inclusao'] = date('Y-m-d H:i:s');

                    $agendamentoExames = $agendamentoExamesTable->newEntity($agendamentoExames);
                } else {
                    $agendamentoExames = $agendamentoExamesTable->get($codigoAgendamentoExame);

                    $agendamentoExames->data = $dados['data_agendamento'];
                    $agendamentoExames->hora = $hora_agendamento;
                    $agendamentoExames->codigo_medico = $codigo_medico;
                }

                if ($resultAgendamentoExames = $agendamentoExamesTable->save($agendamentoExames)) {
                    $data = array(
                        'codigo' => $result->codigo,
                        'data_agendamento' => $result->data_agendamento,
                        'hora_agendamento' => Comum::formataHora($result->hora_agendamento)
                    );
                } else {
                    // debug($agendamentoExames->getValidationErrors());exit;
                    throw new Exception("Error ao atualizar os dados do exame na agenda exame!");
                }
            } else {
                throw new Exception("Error ao editar a data do exame na item!");
            }

            //finaliza a transacao
            $conn->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {

            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção

            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function getAgendamentoOcupacional($codigo_usuario, $codigo_documento = null, $codigo_fornecedor)
    {

        //variavel auxiliar
        $data = array();

        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_usuario)) {
            $error[] = 'Parametro codigo_usuario inválido.';
        } else {

            //valida se o usuario pode emitir um pedido de exames
            $validar = $this->validaUsuarioPedidosExames($codigo_usuario, $codigo_documento);

            // debug($validar);exit;

            if (!empty($validar)) {

                //verifica se é terceiro
                $this->loadModel('PacientesDadosTrabalho');
                $conditions[] = "codigo_paciente = ".$codigo_usuario." and codigo_pacientes_categoria = '2' ";
                $terceiro = $this->PacientesDadosTrabalho->find()->where($conditions)->first();

                if($terceiro){

                    $this->loadModel('Pacientes');
                    $data['terceiro'] = true;
                    $data['consulta_pontual'] = $this->Pacientes->getExamePacienteTerceiro();

                }else{

                    $error = $validar;
                }

            } else {

                if (!empty($codigo_documento)) {

                    $this->loadModel('Funcionarios');

                    //Pega código do funcionário
                    $codigo_funcionario = $this->Funcionarios->getCodigoFuncionario($codigo_documento);
                    // debug($codigo_funcionario);exit;
                    if (!empty($codigo_funcionario)) {
                        $codigo_funcionario = $codigo_funcionario[0]->codigo;
                        $clientes = $this->getCodigosClientesFuncionario($codigo_funcionario);
                    }
                } else {

                    //carrega os dados do usuario
                    $this->getDadosUsuario($codigo_usuario);

                    //carrega os dados do usuario
                    if (!empty($this->usuario->codigo_funcionario)) {
                        $codigo_funcionario = $this->usuario->codigo_funcionario;
                        $clientes = $this->getCodigosClientesVinculados($codigo_usuario);
                    }
                }

                $this->loadModel('ClienteFuncionario');
                //pega a função do funcionario na funcionario_setores_cargo
                $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($codigo_funcionario, $clientes);

                //verifica se existe alguma matricula para este usuario
                if (!empty($dados_matricula_funcao)) {

                    $this->loadModel('PedidosExames');
                    $this->loadModel('Configuracao');

                    //lista pcmso
                    $lista_pcmso = array();
                    $retorno_exames = array();

                    //varre as matriculas do funcionario
                    foreach ($dados_matricula_funcao as $dados) {

                        //verifica se tem ppra
                        if (!$this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo'])) {

                            //monta a lista do exames pcmso
                            $lista_pcmso['cliente'][] = array(
                                'msg' => "Não existe PPRA para este funcionário, setor, cargo para esta unidade!",
                                'codigo_cliente' => $dados['Cliente']['codigo'],
                                'nome_cliente' => $dados['Cliente']['nome_fantasia'],
                                'codigo_cliente_matricula' => $dados['codigo_cliente_matricula'],
                                'codigo_func_setor_cargo' => $dados['FuncionarioSetorCargo']['codigo'],
                                'codigo_funcionario' => $codigo_funcionario,
                                'codigo_cliente_alocacao' => $dados['FuncionarioSetorCargo']['codigo_cliente_alocacao'],
                                'exames_disponiveis' => [],
                                "consulta_pontual" => []
                            );

                            continue;

                        } else {

                            //seta o codigo cliente
                            $codigo_cliente = $dados['Cliente']['codigo'];

                            //variaveis auxiliares
                            $cliente_pcmso = array();
                            $exames_pcmso_periodico = array();
                            $exames_necessarios_periodico = array();
                            $exames_necessarios_periodico_aso = array();
                            $exames_pcmso_retorno = array();
                            $exames_necessarios_retorno = array();
                            $exames_pcmso_mudanca = array();
                            $exames_necessarios_mudanca = array();

                            $exames_pcmso_admissional = array();
                            $exames_necessarios_admissional = array();

                            $exames_pcmso_demissional = array();
                            $exames_necessarios_demissional = array();

                            $exames_periodicos = array();
                            $exames_retorno = array();
                            $exames_mudanca = array();
                            $exames_admissional = array();
                            $exames_demissional = array();


                            //monta retorno de exames pmcso
                            $cliente_pcmso[$dados['Cliente']['codigo']] = array(
                                'codigo_cliente' => $dados['Cliente']['codigo'],
                                'nome_cliente' => $dados['Cliente']['nome_fantasia'],
                                'codigo_cliente_matricula' => $dados['codigo_cliente_matricula'],
                                'codigo_func_setor_cargo' => $dados['FuncionarioSetorCargo']['codigo'],
                                'codigo_cliente_alocacao' => $dados['FuncionarioSetorCargo']['codigo_cliente_alocacao'],
                                'codigo_funcionario' => $dados['Funcionario']['codigo'],
                            );

                            //verifica se tem pedido ocupacional aberto
                            //$pedido_periodico_aberto = $this->PedidosExames->getPodeAgendarPeriodico($this->usuario->codigo_funcionario);
                            $pedido_periodico_aberto = $this->PedidosExames->getPodeAgendarPeriodico($codigo_funcionario,$codigo_cliente);

                            // debug(array($codigo_cliente, $pedido_periodico_aberto));

                            //se retornar true significa que não tem periodico aberto
                            if ($pedido_periodico_aberto) {

                                //ocupacionais pega os exames do pcmso
                                //pega a lista de pcmso da configuracao do funcionario setores cargos
                                $dados_pcmso = $this->lista_exames_pcmso($dados['FuncionarioSetorCargo']['codigo'], $dados['Cliente']['codigo']);

                                //varre os dados de pcmso com os exames por cliente
                                foreach ($dados_pcmso as $dpcmso) {

                                    //pega os dados dos tipos de exames
                                    if ($dpcmso['exame_periodico'] == '1') {

                                        //monta o array de periodico
                                        $exames_pcmso_periodico[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 1,
                                            "descricao" => "Periódico"
                                        );

                                        //pega as configuracoes do codigo do aso
                                        $codigo_aso = $this->Configuracao->getChave('INSERE_EXAME_CLINICO');
                                        if(empty($codigo_aso)) {
                                            $codigo_aso = 52;
                                        }

                                        if ($dpcmso['codigo_exame'] == $codigo_aso) {

                                            $exames_necessarios_periodico_aso = array(
                                                "codigo_exame" => $dpcmso['codigo_exame'],
                                                "exame" => $dpcmso['exame'],
                                                "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor),
                                                "aso" => true
                                            );
                                        } else {
                                            $exames_necessarios_periodico[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                                "codigo_exame" => $dpcmso['codigo_exame'],
                                                "exame" => $dpcmso['exame'],
                                                "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor),
                                                "aso" => false
                                            );
                                        }
                                    } //fim periodico

                                    // //exame retorno
                                    if ($dpcmso['exame_retorno'] == '1') {
                                        //monta o array de retorno
                                        $exames_pcmso_retorno[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 2,
                                            "descricao" => "Retorno ao Trabalho",
                                        );

                                        $exames_necessarios_retorno[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                            "codigo_exame" => $dpcmso['codigo_exame'],
                                            "exame" => $dpcmso['exame'],
                                            "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor)
                                        );
                                    } //fim exame retorno

                                    //verifica se tem mudanca de funcao
                                    if ($dpcmso['exame_mudanca'] == '1') {
                                        //monta o array de mudança de função
                                        $exames_pcmso_mudanca[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 3,
                                            "descricao" => "Mudança de cargo",
                                        );

                                        $exames_necessarios_mudanca[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                            "codigo_exame" => $dpcmso['codigo_exame'],
                                            "exame" => $dpcmso['exame'],
                                            "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor)
                                        );
                                    } //fim mudanca

                                    //verifica se tem exame admissional
                                    if ($dpcmso['exame_admissional'] == '1') {
                                        //monta o array de mudança de função
                                        $exames_pcmso_admissional[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 4,
                                            "descricao" => "Admissional",

                                        );

                                        $exames_necessarios_admissional[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                            "codigo_exame" => $dpcmso['codigo_exame'],
                                            "exame" => $dpcmso['exame'],
                                            "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor)
                                        );
                                    } //fim admissional

                                    //verifica se tem exame demissional
                                    if ($dpcmso['exame_demissional'] == '1') {
                                        //monta o array de mudança de função
                                        $exames_pcmso_demissional[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 5,
                                            "descricao" => "Demissional",
                                        );

                                        $exames_necessarios_demissional[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                            "codigo_exame" => $dpcmso['codigo_exame'],
                                            "exame" => $dpcmso['exame'],
                                            "tipo_atendimento" => $this->verificaTipoAtendimento($dpcmso['codigo_servico'], $codigo_fornecedor)
                                        );
                                    } //fim demissional

                                } //fim foreach

                                $exames_necessarios_periodico[$dados['Cliente']['codigo']]["exames_necessarios"][] = $exames_necessarios_periodico_aso;

                                //junta os arrays para deixar corretamente a saida da lista de exames por empresa
                                if (isset($exames_pcmso_periodico[$dados['Cliente']['codigo']]) && isset($exames_necessarios_periodico[$dados['Cliente']['codigo']])) {
                                    $exames_periodicos = array_merge($exames_pcmso_periodico[$dados['Cliente']['codigo']], $exames_necessarios_periodico[$dados['Cliente']['codigo']]);
                                }

                                if (isset($exames_pcmso_retorno[$dados['Cliente']['codigo']]) && isset($exames_necessarios_retorno[$dados['Cliente']['codigo']])) {
                                    $exames_retorno   = array_merge($exames_pcmso_retorno[$dados['Cliente']['codigo']], $exames_necessarios_retorno[$dados['Cliente']['codigo']]);
                                }

                                if (isset($exames_pcmso_mudanca[$dados['Cliente']['codigo']]) && isset($exames_necessarios_mudanca[$dados['Cliente']['codigo']])) {
                                    $exames_mudanca    = array_merge($exames_pcmso_mudanca[$dados['Cliente']['codigo']], $exames_necessarios_mudanca[$dados['Cliente']['codigo']]);
                                }

                                if (isset($exames_pcmso_admissional[$dados['Cliente']['codigo']]) && isset($exames_necessarios_admissional[$dados['Cliente']['codigo']])) {
                                    $exames_admissional    = array_merge($exames_pcmso_admissional[$dados['Cliente']['codigo']], $exames_necessarios_admissional[$dados['Cliente']['codigo']]);
                                }

                                if (isset($exames_pcmso_demissional[$dados['Cliente']['codigo']]) && isset($exames_necessarios_demissional[$dados['Cliente']['codigo']])) {
                                    $exames_demissional    = array_merge($exames_pcmso_demissional[$dados['Cliente']['codigo']], $exames_necessarios_demissional[$dados['Cliente']['codigo']]);
                                }
                            }

                            //verifica se tem dados na configuração
                            if (isset($cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente'])) {

                                $consulta_pontual = $this->getPontual($codigo_funcionario, $codigo_cliente);

                                //verifica se existe exames disponiveis pcmso
                                $exames_disponiveis = array();
                                if (!empty($exames_periodicos)) {
                                    $exames_disponiveis[] = $exames_periodicos;
                                }

                                if (!empty($exames_retorno)) {
                                    $exames_disponiveis[] = $exames_retorno;
                                }

                                if (!empty($exames_mudanca)) {
                                    $exames_disponiveis[] = $exames_mudanca;
                                }

                                if (!empty($exames_admissional)) {
                                    $exames_disponiveis[] = $exames_admissional;
                                }

                                if (!empty($exames_demissional)) {
                                    $exames_disponiveis[] = $exames_demissional;
                                }

                                //monta a lista do exames pcmso
                                $lista_pcmso['cliente'][] = array(
                                    'msg' => '',
                                    'codigo_cliente' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente'],
                                    'nome_cliente' => $cliente_pcmso[$dados['Cliente']['codigo']]['nome_cliente'],
                                    'codigo_cliente_matricula' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente_matricula'],
                                    'codigo_func_setor_cargo' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_func_setor_cargo'],
                                    'codigo_funcionario' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_funcionario'],
                                    'codigo_cliente_alocacao' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente_alocacao'],
                                    'exames_disponiveis' => $exames_disponiveis,
                                    "consulta_pontual" => ((isset($consulta_pontual['consulta_pontual'])) ? $consulta_pontual['consulta_pontual'] : [])
                                );
                            }
                        } //fim if existe ppra

                    } //fim foreach dados matricula funcao

                    //retorna a lista de exames do pcmso
                    $data = $lista_pcmso;

                    // debug($data);exit;

                } //fim dados_matricula_funcao
                else {
                    $error[] = "Não encontramos nenhuma matricula/função as empresa(s) vinculada(s)!";
                }
            } //fim validausuariopedidosexames

        } //fim else codigo_usuario

        if (!empty($error)) {
            $this->set(compact('error'));
        } else {
            $this->set(compact('data'));
        }
    } //fim getCriarAgendamento

    /**
     * [setItensPedidosExames description]
     *
     * metodo para gravar os exames que foram selecionados
     *
     * @param [type] $codigo_pedido_exame [description]
     * @param [array] $exames              [description]
     */
    public function setItensPedidosExames($codigo_usuario, $codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_tipo_exames_pedidos, $exames, $codigo_medico, $observacao)
    {

        // obter codigo servico
        $this->loadModel('Exames');

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

        // $exame = $this->Exames->find()->select(['codigo_servico', 'descricao'])->where(['codigo' => 27])->first();

        //        $this->set('parou', $d);
        //        return;
        foreach ($exames as $key => $item) {

            if (!empty($item['codigo_fornecedor'])) {
                $codigo_fornecedor = $item['codigo_fornecedor'];
            }

            $codigo_exame = $item['codigo_exame'];
            $tipo_atendimento = $item['tipo_atendimento'];
            $tipo_agendamento = $item['tipo_agendamento'];
            $data_agendamento = $item['data'];
            $hora_agendamento = $item['horario'];

            // obter codigo servico
            $exame = $this->Exames->find()->select(['codigo_servico', 'descricao'])->where(['codigo' => $codigo_exame])->first();

            $codigo_servico = $exame->codigo_servico;
            $item_exame_descricao = $exame->descricao;

            //pega o valor do custo do serviço/exame para aquele fornecedor
            //$valor_custo = $this->ItemPedidoExame->ObterFornecedorCusto($codigo_fornecedor, $codigo_exame);
            // codigos de serviços dos exames para retornar preço
            $d = $this->PedidosExames->retornaFornecedoresExames($codigo_servico, null, $codigo_cliente, null);
            if(empty($d)) {
                throw new Exception("Esse exame não está configurado para este fornecedor, verificar com o administrador.");
            }

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
                'codigo_tipos_exames_pedidos' => $codigo_tipo_exames_pedidos,
                'valor_custo' => $valor_custo,
                'valor' => $assinatura['valor'],
                'codigo_cliente_assinatura' => $assinatura['codigo'],
                'codigo_usuario_inclusao' => $codigo_usuario,
                'codigo_status_itens_pedidos_exames' => 5,
                'codigo_medico' => $codigo_medico,
                'observacao' => $observacao
            );

            // salvar item
            $this->loadModel('ItensPedidosExames');

            //verifica se existe um pedido de exame, caso exista e tem o exame do laço irá atualziar
            $ipe = $this->ItensPedidosExames->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame, 'codigo_exame' => $codigo_exame])->first();

            //verifica se existe o item
            if (!empty($ipe)) {
                $registro_item = $this->ItensPedidosExames->patchEntity($ipe, $dados_salvar_item);
            } else {
                $registro_item = $this->ItensPedidosExames->newEntity($dados_salvar_item);
            }

            //cria um item ou atualiza o item
            if (!$this->ItensPedidosExames->save($registro_item)) {
                throw new Exception('Ocorreu algum erro! Precisamos reagendar ou entre contato com a clínica! ' . print_r($registro_item->getValidationErrors(), 1));
            }

            //verifica se é alteracao e tem que ser inclusao
            if (empty($ipe)) {
                //verifica se tem data de agendamento
                if (!empty($data_agendamento) && !empty($hora_agendamento)) {

                    $this->loadModel('AgendamentoExames');

                    $array_incluir = array(
                        'data' => $data_agendamento,
                        'hora' => (int) str_replace(":", "", $hora_agendamento),
                        'codigo_fornecedor' => $codigo_fornecedor,
                        'codigo_itens_pedidos_exames' => $registro_item->codigo,
                        'ativo' => '1',
                        'data_inclusao' => date('Y-m-d H:i:s'),
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'codigo_empresa' => 1,
                        'codigo_lista_de_preco_produto_servico' => null,
                        'codigo_medico' => $codigo_medico
                    );

                    $agenda_item = $this->AgendamentoExames->newEntity($array_incluir);

                    if (!$this->AgendamentoExames->save($agenda_item)) {
                        throw new Exception("Houve um erro ao salvar o Agendamento!");
                    }
                }
            } //fim empty ipe

            $item_exame_resposta['codigo'] = $registro_item->codigo;
            $item_exame_resposta['codigo_tipo_exames_pedidos'] = $codigo_tipo_exames_pedidos;
            $item_exame_resposta['descricao'] = $item_exame_descricao;
            $item_exame_resposta['agendado'] = true;

            $exames_salvos[] = $item_exame_resposta;
        } // foreach

        // exit;

        return $exames_salvos;
    } //fim setItensPedidosExames

    /**
     * Criar um agendamento
     *
     * exemplo payload recebido
     * {
     *   "codigo_usuario": 63035,
     *   "codigo_empresa": 79,
     *   "codigo_exame_tipo": 1,
     *   "codigo_consulta_pontual":0,
     *   "exames": [
     *       {
     *           "codigo_fornecedor": 444,
     *           "codigo_exame":77,
     *           "tipo_atendimento":null,
     *           "tipo_agendamento":null,
     *           "data": "2019-10-31",
     *           "horario": "1735"
     *       }
     *   ]
     * }
     *
     * @return array
     */
    public function criarAgendamento()
    {
        $data = [];
        $payload = $this->request->getData();

        // validar se dados corretos necessários foram enviados no payload
        if (empty($payload)) {
            $error = 'Payload não encontrado';
            $this->set(compact('error'));
            return;
        }

        if (!isset($payload['codigo_usuario']) && empty($payload['codigo_usuario'])) {
            $error = 'codigo_usuario requerido';
            $this->set(compact('error'));
            return;
        }

        // obter codigo_usuario
        $codigo_usuario = $payload['codigo_usuario'];

        // obter codigo_cliente
        $codigo_cliente = $payload['codigo_cliente'];

        // obter codigo_exame_tipo
        if (!isset($payload['codigo_exame_tipo'])) {
            $error = 'codigo_exame_tipo requerido';
            $this->set(compact('error'));
            return;
        }

        $codigo_exame_tipo = $payload['codigo_exame_tipo'];

        // $codigo_consulta_pontual = (isset($payload['codigo_consulta_pontual'])) ? $payload['codigo_consulta_pontual'] : 0;

        // // não podem ser iguais
        // if ($codigo_exame_tipo == $codigo_consulta_pontual) {
        //     //|| $codigo_exame_tipo > 5
        //     //|| $codigo_consulta_pontual > 2){
        //     $error = 'Corrija a configuração codigo_exame_tipo e codigo_consulta_pontual';
        //     $this->set(compact('error'));
        //     return;
        // }

        // obter codigo_funcionario
        $codigo_usuario = $payload['codigo_usuario'];

        //verifica se tem o codigo_pedido_exame
        $codigo_pedido_exame = '';
        if (isset($payload['codigo_pedido_exame'])) {
            if (!empty($payload['codigo_pedido_exame'])) {
                $codigo_pedido_exame = $payload['codigo_pedido_exame'];
            }
        }

        // $this->loadModel('Usuario');
        // $usuario = $this->Usuario->obterDadosDoUsuario($codigo_usuario);

        // if(empty($usuario)){
        //     $error = 'Usuário não encontrado';
        //     $this->set(compact('error'));
        //     return;
        // }

        /**
         *  {
         *  "codigo_fornecedor": 444,
         *  "codigo_exame":77,
         *  "tipo_atendimento":1, //0 ordem chegada 1 hora marcada
         *  "tipo_agendamento":1,
         *  "data": "2019-10-31",
         *  "horario": "1735"
         * }
         */
        if (!isset($payload['exames']) && empty($payload['exames']) || !is_array($payload['exames']) || count($payload['exames']) == 0) {
            $error = 'exames requerido';
            $this->set(compact('error'));
            return;
        }

        $exames = $payload['exames'];
        $campos_exames = array("codigo_fornecedor" => "codigo_fornecedor", "codigo_exame" => "codigo_exame", "tipo_atendimento" => "tipo_atendimento", "tipo_agendamento" => "tipo_agendamento", "data" => "data", "horario" => "horario");

        $valida_campos = array();

        //valida os indices do objeto se tem todos os campos
        foreach ($exames as $chaves => $campos_indices) {

            //varre os objetos para saber se tem todos os obrigatorios
            foreach ($campos_exames as $obj => $val) {
                //verifica os campos de indices
                if (!isset($campos_indices[$obj])) {

                    $valida_campos[] = $obj;
                }
            } //fim foreach campos exames

        } //fim foreach

        //verifica os campos
        if (!empty($valida_campos)) {
            $error = 'campos requeridos: ' . implode(",", $valida_campos);
            $this->set(compact('error'));
            return;
        } //fim validacao


        //abrir transacao
        $conn = ConnectionManager::get('default');

        try {
            //abre a transacao
            $conn->begin();

            // obter codigo_cliente_funcionario
            $this->loadModel('ClienteFuncionario');

            $codigo_consulta_pontual = ($codigo_exame_tipo == 9) ? 1 : 0;

            $exame_periodico = ($codigo_exame_tipo == 1) ? 1 : 0;
            $exame_retorno = ($codigo_exame_tipo == 2) ? 1 : 0;
            $exame_mudanca = ($codigo_exame_tipo == 3) ? 1 : 0;
            $exame_monitoracao = 0;
            $exame_admissional = ($codigo_exame_tipo == 4) ? 1 : 0;
            $exame_demissional = ($codigo_exame_tipo == 5) ? 1 : 0;

            $codigo_tipo_exames_pedidos = 1;
            if($codigo_consulta_pontual == 1) {
                $codigo_tipo_exames_pedidos = 3;
            }

            if ($exame_periodico > 0 && $codigo_consulta_pontual > 0) {
                $error = 'Não pode passar um exame Periódico e Pontual para cadastrar ao mesmo tempo, ou é um Pedido Ocupacional ou Pontual para ser cadastrado!';
                $this->set(compact('error'));
                return;
            }

            $this->loadModel('PedidosExames');

            $codigo_cliente_alocacao = "";

            if(!is_null($codigo_cliente)){

                //verifica se existe o codigo do cliente funcionario
                $var_codigo_cliente_funcionario = $this->ClienteFuncionario->find()->where(['codigo' => $payload['codigo_cliente_funcionario']])->first();
                if(is_null($var_codigo_cliente_funcionario)) {
                    throw new Exception("Código passado de cliente_funcionario não existe na tabela, favor verificar");
                }

                $this->loadModel('Funcionarios');
                $var_codigo_funcionario = $this->Funcionarios->find()->where(['codigo' => $payload['codigo_funcionario']])->first();
                if(is_null($var_codigo_funcionario)) {
                    throw new Exception("Código passado do funcionario não existe na tabela, favor verificar");
                }

                //pega a função do funcionario na funcionario_setores_cargo
                $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($payload['codigo_funcionario'], $payload['codigo_cliente']);
                if(is_null($dados_matricula_funcao)) {
                    throw new Exception("Não foi possível encontrar a função do funcionário.");
                }

                //pega o codigo da empresa
                $codigo_empresa = $dados_matricula_funcao[0]['codigo_empresa'];

                // obter codigo vinculo com cargo x setor
                $codigo_func_setor_cargo = $dados_matricula_funcao[0]['FuncionarioSetorCargo']['codigo'];
                $codigo_cliente_alocacao = $dados_matricula_funcao[0]['FuncionarioSetorCargo']['codigo_cliente_alocacao'];

                //comentado para validar melhor
                //
                // //valida se não esta criando pedido em duplicidade por duplo clique
                // $arr_validacao_duplicidade = array(
                //     'codigo_cliente_funcionario' => $payload['codigo_cliente_funcionario'],
                //     'codigo_cliente' => $codigo_cliente_alocacao,
                //     'codigo_funcionario' => $payload['codigo_funcionario'],
                //     'codigo_func_setor_cargo' => $codigo_func_setor_cargo,

                //     'exame_admissional' => $exame_admissional,
                //     'exame_periodico' => $exame_periodico,
                //     'exame_demissional' => $exame_demissional,
                //     'exame_retorno' => $exame_retorno,
                //     'exame_mudanca' => $exame_mudanca,
                //     'exame_monitoracao' => $exame_monitoracao,

                //     'pontual' =>  $codigo_consulta_pontual,
                //     'codigo_status_pedidos_exames' => 1
                // );

                $observacao = isset($payload['observacao']) ? trim($payload['observacao']) : NULL;

                if (empty($codigo_pedido_exame)) {

                    // //valida os exames duplicados
                    // $pedido_duplicado = $this->PedidosExames->find()->where($arr_validacao_duplicidade)->first();
                    // // debug($pedido_duplicado);exit;
                    // if(!empty($pedido_duplicado)) {
                    //     throw new Exception("Existe um pedido criado para este funcionario nesta alocação, favor verificar!");
                    // }

                    // dados para salvar pedido exame
                    $dados_salvar = [
                        'codigo_cliente_funcionario' => $payload['codigo_cliente_funcionario'],
                        'codigo_cliente' => $codigo_cliente_alocacao,
                        'codigo_funcionario' => $payload['codigo_funcionario'],
                        'codigo_func_setor_cargo' => $codigo_func_setor_cargo,

                        'exame_admissional' => $exame_admissional,
                        'exame_periodico' => $exame_periodico,
                        'exame_demissional' => $exame_demissional,
                        'exame_retorno' => $exame_retorno,
                        'exame_mudanca' => $exame_mudanca,
                        'exame_monitoracao' => $exame_monitoracao,

                        'pontual' =>  $codigo_consulta_pontual,

                        'observacao' => $observacao,

                        'codigo_usuario_inclusao' => $codigo_usuario,

                        // dados nao mapeados
                        'codigo_empresa' => $codigo_empresa,
                        'portador_deficiencia' => '0',
                        'data_solicitacao' => date('Y-m-d'),
                        'codigo_status_pedidos_exames' => '1',
                        'endereco_parametro_busca' => '',

                    ];

                    $registro = $this->PedidosExames->newEntity($dados_salvar);

                    if (!$this->PedidosExames->save($registro)) {

                        throw new Exception($registro->getValidationErrors());
                    }

                    $codigo_pedido_exame = isset($registro->codigo) ? $registro->codigo : null;

                    //tira uma foto do pcmso e ppra para gerar o aso corretamente no futuro
                    //verifica se é um agendamento ocupacional
                    if($codigo_consulta_pontual == 0) {
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
                                            WHERE pe.codigo = " . $codigo_pedido_exame;
                        $dados_ppra = $this->connection->execute($query_dados_ppra);
                    }
                }else{

                    //Verifica se existe  outro exame agendado no mesmo horário
                    $this->loadModel('ItensPedidosExames');

                    $conditions = [
                        'codigo_pedidos_exames' => $codigo_pedido_exame,
                        'hora_agendamento' => $exames['0']['horario'],
                        'data_agendamento' => $exames['0']['data']
                    ];

                    $itens_pedidos_exames = $this->ItensPedidosExames->find()->where($conditions)->first();

                    if(!empty($itens_pedidos_exames)){
                        throw new Exception("Já existe outro exame agendado para esse pedido nesta data e hora.");
                    }
                }


                //seta os itens
                $exames_salvos = $this->setItensPedidosExames($codigo_usuario, $codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_tipo_exames_pedidos, $exames, $payload['codigo_medico'], $observacao);

                //tira uma foto do pcmso e ppra para gerar o aso corretamente no futuro
                //verifica se é um agendamento ocupacional
                if($codigo_consulta_pontual == 0) {
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
                                            LEFT JOIN RHHealth.dbo.pedidos_exames_pcmso_aso pepa on pepa.codigo_pedidos_exames = pe.codigo
                                                and pepa.codigo_exame = ae.codigo_exame
                                        WHERE ae.codigo_exame = ipe.codigo_exame
                                            AND ae.codigo IN (select * from dbo.ufn_aplicacao_exames(fsc.codigo_cliente_alocacao,fsc.codigo_setor,fsc.codigo_cargo,pe.codigo_funcionario))
                                            AND pepa.codigo IS NULL
                                            AND pe.codigo = " . $codigo_pedido_exame;
                    $dados_pcmso = $this->connection->execute($query_dados_pcmso);
                }//fim verificacao se é exame pontual

            }else{


                if (empty($codigo_pedido_exame)) {

                    // dados para salvar pedido exame
                    $dados_salvar = [
                        'codigo_cliente_funcionario' => null,
                        'codigo_cliente' => null,
                        'codigo_funcionario' => null,
                        'codigo_func_setor_cargo' => null,
                        'codigo_paciente' => $payload['codigo_funcionario'],

                        'exame_admissional' => $exame_admissional,
                        'exame_periodico' => $exame_periodico,
                        'exame_demissional' => $exame_demissional,
                        'exame_retorno' => $exame_retorno,
                        'exame_mudanca' => $exame_mudanca,
                        'exame_monitoracao' => $exame_monitoracao,

                        'pontual' =>  $codigo_consulta_pontual,

                        'observacao' => trim($payload['observacao']),

                        'codigo_usuario_inclusao' => $codigo_usuario,

                        // dados nao mapeados
                        'codigo_empresa' => $codigo_empresa,
                        'portador_deficiencia' => '0',
                        'data_solicitacao' => date('Y-m-d'),
                        'codigo_status_pedidos_exames' => '1',
                        'endereco_parametro_busca' => '',

                    ];

                    $registro = $this->PedidosExames->newEntity($dados_salvar);

                    if (!$this->PedidosExames->save($registro)) {

                        throw new Exception($registro->getValidationErrors());
                    }

                    $codigo_pedido_exame = isset($registro->codigo) ? $registro->codigo : null;

                }else{

                    //Verifica se existe  outro exame agendado no mesmo horário
                    $this->loadModel('ItensPedidosExames');

                    $conditions = [
                        'codigo_pedidos_exames' => $codigo_pedido_exame,
                        'hora_agendamento' => $exames['0']['horario'],
                        'data_agendamento' => $exames['0']['data']
                    ];

                    $itens_pedidos_exames = $this->ItensPedidosExames->find()->where($conditions)->first();

                    if(!empty($itens_pedidos_exames)){
                        throw new Exception("Já existe outro exame agendado para esse pedido nesta data e hora.");
                    }

                }

                //seta os itens
                $exames_salvos = $this->setItensPedidosExames($codigo_usuario, $codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_tipo_exames_pedidos, $exames, $payload['codigo_medico'], $payload['observacao']);

            }

            //Inseri tipos de relatórios que o exame pode imprimir
            $dados_tipo_notificacao = $this->TipoNotificacao->find('list', ['keyField' => 'codigo','valueField' => 'tipo'])->where(['notificacao_especifica IS NULL'])->toArray();

            foreach($dados_tipo_notificacao as $k => $tipo) {

                $vias_aso = 1;

                if ($k == 2) {

                    $gruposEconomicos = $this->GruposEconomicos->find()->where(['codigo_cliente' => $codigo_cliente])->first();
                    $vias_aso = $gruposEconomicos['vias_aso'];
                }

                $array_organiza_inclusao[] = array(
                    'campo_funcionario' => 1,
                    'campo_cliente' => 1,
                    'campo_fornecedor' => 1,
                    'codigo_tipo_notificacao' => $k,
                    'codigo_pedidos_exames' => $codigo_pedido_exame,
                    'vias_aso' => $vias_aso
                );
            }

            //apaga os registros anteriores das notificações pois estava duplicando
            $this->TipoNotificacaoValores->deleteAll(['codigo_pedidos_exames' => $codigo_pedido_exame]);

            $entities = $this->TipoNotificacaoValores->newEntities($array_organiza_inclusao);

            foreach ($entities as $entity) {
                $this->TipoNotificacaoValores->save($entity);
            }

            $data = [
                'retorno' => 'Agendamento Realizado!',
                'codigo_pedido_exame' => $codigo_pedido_exame,
                'exames_necessarios' => $exames_salvos
            ];

            //finaliza a transacao
            $conn->commit();

        } catch (\Exception $e) {

            //rollback da transacao
            $conn->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }


        $this->set(compact('data'));
        return;
    }


    /**
     * metodo para retornar o tipo de atendimento para o serviço
     * @param $codigo_servico
     * @param $codigo_fornecedor
     * @return mixed
     */
    private function verificaTipoAtendimento($codigo_servico, $codigo_fornecedor)
    {
        //Seta as variaveis do request para bindar os dados na query
        $params = ['codigo_servico' => $codigo_servico, 'codigo_fornecedor' => $codigo_fornecedor];

        $strSql = 'select lpps.tipo_atendimento from produto_servico ps
                    inner join listas_de_preco lp on lp.codigo_fornecedor = :codigo_fornecedor and ps.codigo_servico = :codigo_servico
                    inner join listas_de_preco_produto lpp on lpp.codigo_produto = ps.codigo_produto and lpp.codigo_lista_de_preco = lp.codigo
                    inner join listas_de_preco_produto_servico lpps on lpps.codigo_lista_de_preco_produto = lpp.codigo and lpps.codigo_servico = ps.codigo_servico';

        //Retorna os dados da consulta ao banco
        $result = $this->connection->execute($strSql, $params)->fetchAll('assoc');

        // debug($result);
        $return = 0;
        if(!empty($result)) {
            $return = $result[0]['tipo_atendimento'];
        }


        return $return;
    }
    /**
     * metodo para validar o usuario se pode criar um exame
     *
     * @param  int $codigo_usuario      codigo do usuario
     * @return array
     */
    private function validaUsuarioPedidosExames($codigo_usuario, $codigo_documento = null)
    {

        $return = array();
        $clientes = array();

        if (!empty($codigo_documento)) {

            $this->loadModel('Funcionarios');

            //Pega código do funcionário
            $codigo_funcionario = $this->Funcionarios->getCodigoFuncionario($codigo_documento);

            if (!empty($codigo_funcionario)) {

                $codigo_funcionario = $codigo_funcionario[0]->codigo;
                $clientes = $this->getCodigosClientesFuncionario($codigo_funcionario);
            }
        } else {
            //carrega os dados do usuario
            $this->getDadosUsuario($codigo_usuario);

            //carrega os dados do usuario
            if (!empty($this->usuario->codigo_funcionario)) {
                $clientes = $this->getCodigosClientesVinculados($codigo_usuario);
            }
        }

        if (empty($clientes)) {
            $return = "Não encontramos nenhuma empresa vinculada!";
        }

        return $return;
    } //fim validaUsuarioPedidosExames

    /**
     * pega os codigos clientes vinculados
     *
     * @param  int $codigo_funcionario      codigo do funcionario
     * @return array
     */
    private function getCodigosClientesFuncionario($codigo_funcionario)
    {
        $this->loadModel('ClienteFuncionario');

        //pega os codigos clientes que estao validados para o funcionario
        $dados = $this->ClienteFuncionario->find()->where(['codigo_funcionario' => $codigo_funcionario])->hydrate(false)->toArray();

        $clientes = [];
        if (!empty($dados)) {
            //varre os clientes
            foreach ($dados as $cli) {
                $clientes[] = $cli['codigo_cliente'];
            } //fim foreach clientes
        }

        return $clientes;
    } // fim getCodigosClientesFuncionario

    private function getDadosUsuario($codigo_usuario)
    {
        //carrega a model de usuario
        $this->loadModel('Usuario');

        //pega o usuario para recuperar o codigo do funcionario
        $this->usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);
    } //fim getDadasousaurio

    /**
     * pega os codigos clientes vinculados
     *
     * @param  int $codigo_usuario      codigo do usuario
     * @return array
     */
    private function getCodigosClientesVinculados($codigo_usuario)
    {
        //carrega a model de usuario
        $this->loadModel('Usuario');

        //pega os codigos clientes que estao validados para o usuario
        //$dados_usuario = $this->Usuario->obterDadosDoUsuario($codigo_usuario)->toArray();
        $dados_usuario = $this->Usuario->obterDadosDoUsuario($codigo_usuario);

        $clientes = [];
        if (!empty($dados_usuario['cliente'])) {
            //varre os clientes
            foreach ($dados_usuario['cliente'] as $cli) {
                $clientes[] = $cli['codigo'];
            } //fim foreach clientes
        }

        return $clientes;
    } // fim getCodigosClientesVinculados

    /**
     * [valida_pedido_exame_ppra description]
     *
     * metodo para saber se existe ppra para a funcao
     *
     * @param  [type] $dadosClienteFuncionario [description]
     * @return [type]                          [true/false]
     */
    public function valida_pedido_exame_ppra($codigo_funcionario_setor_cargo)
    {
        $this->loadModel('GrupoExposicao');

        //verifica se tem ppra para o funcionario
        $dados_ppra = $this->GrupoExposicao->verificaFuncionarioTemPpra($codigo_funcionario_setor_cargo);

        //retorna caso encontre um ppra
        if (!empty($dados_ppra)) {
            return true;
        }

        return false;
    } //FINAL FUNCTION valida_pedido_exame_ppra

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

        $this->loadModel('FuncionarioSetoresCargos');

        $arr_exames = array();

        //pega onde o funcionario esta alocado
        $codigo_cliente_alocacao = $this->FuncionarioSetoresCargos->find()->select(['codigo_cliente_alocacao'])->where(['codigo' => $codigo_funcionario_setor_cargo])->first();
        $codigo_cliente = $codigo_cliente_alocacao['codigo_cliente_alocacao'];

        //Recupera os exames do PCMSO aplicados para unidade + setor + cargo de alocação do funcionário
        $itens_exames = $this->FuncionarioSetoresCargos->retornaExamesNecessarios($codigo_funcionario_setor_cargo);

        // debug($itens_exames);exit;

        // adiciona exames na lista
        if (count($itens_exames)) {

            //varre os itens de exames
            foreach ($itens_exames as $key => $item) {

                /**
                 * Verifica se existe assinatura e recupera o valor do exame
                 * Inicialmente consulta a unidade de alocação se não encontrar consulta a matriz (Grupo Econômico)
                 */
                $item['assinatura'] = $this->PedidosExames->verificaExameTemAssinatura($item['codigo_servico'], $codigo_cliente, $codigo_cliente_matriz);

                //Verifica se existe fornecedor no cliente de alocação (exame na lista de preços do fornecedor)
                $fornecedores = $this->PedidosExames->verificaExameTemFornecedor($item['codigo_servico'], $codigo_cliente);

                //verifica se tem fornecedor
                if (count($fornecedores) > 0) {
                    $item['fornecedores'] = 1;
                } else {
                    $item['fornecedores'] = 0;
                }

                //grava sessao com todos os exames do PCMSO (até os sem valor de assinatura)
                $arr_exames[] = $item;
            } //fim foreach dos itens de exames
        } //fim count itens_exames

        return $arr_exames;
    } //FINAL FUNCTION atualiza_lista_exames_grupo

    public function getPontual($codigo_funcionario, $codigo_cliente)
    {

        $this->loadModel('ClienteFuncionario');
        //pega a função do funcionario na funcionario_setores_cargo
        $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($codigo_funcionario, $codigo_cliente);

        //verifica se existe alguma matricula para este usuario
        if (!empty($dados_matricula_funcao)) {

            $this->loadModel('ClienteProduto');
            $this->loadModel('Exames');
            $this->loadModel('Configuracao');

            //lista pcmso
            $lista_pontuais = array();
            $retorno_exames = array();

            //pega os dados dos produtos configurados para este cliente

            //pontual
            //pega os produtos liberados pela matriz
            //pega os produtos liberados por quem ira pagar
            $lista_exames = array();
            //varre as matriculas do funcionario
            foreach ($dados_matricula_funcao as $dados) {

                //verifica se tem ppra
                // if (!$this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo'])) {
                //     $error[] = "Não existe PPRA para este funcionário da empresa: " . $dados['Cliente']['nome_fantasia'];
                // } else {
                    $codigo_cliente = $dados['FuncionarioSetorCargo']['codigo_cliente_alocacao'];
                    $codigo_cliente_matriz = $dados['codigo_cliente_matricula'];

                    //pega os dados do produto configurado para este cliente
                    $produtos = $this->ClienteProduto->listarPorCodigoCliente($codigo_cliente);
                    // debug($produtos);exit;
                    // debug($codigo_cliente. "!=" .$codigo_cliente_matriz);exit;

                    //seta a variavel grupo economico
                    $produto_matriz = array();
                    $produto_matriz_produto = array();

                    ############## TRECHO PARA PEGAR AS ASSINATURAS DA MATRIZ  #####################
                    //verifica se o codigo da matriz é o mesmo codigo do cliente que esta querendo ver a assinatura pois precisa ser diferente
                    if ($codigo_cliente != $codigo_cliente_matriz) {
                        //array servicos que nao devem ser buscados
                        $array_codigos_servicos = false;

                        //verifica se existe produto cadastrado
                        if (!empty($produtos)) {
                            //para nao exibir os dados que ja estao cadastrados no cliente
                            foreach ($produtos as $prod) {
                                //varre os servicos
                                foreach ($prod['ClienteProdutoServico2'] as $servico) {
                                    $array_codigos_servicos[] = $servico['Servico']['codigo'];
                                } //fim foreach servicos
                            } //fim foreach dos produtos
                        } //fim verificacao se existe produtos

                        //produtos da matriz
                        $produto_matriz_liberado = $this->ClienteProduto->listarPorCodigoCliente($codigo_cliente_matriz, $array_codigos_servicos, true);

                        //verifica se existe produto matriz
                        if (!empty($produto_matriz_liberado)) {
                            //seta o produto matriz
                            foreach ($produto_matriz_liberado as $pml) {
                                $produto_matriz_produto = $pml['Produto'];
                                $produto_matriz = $pml['ClienteProdutoServico2'];
                            }
                        } //fim if empty produto matriz
                    } //fim verifica o codigo da matriz
                    // debug($produtos);

                    ############## TRECHO PARA PEGAR OS SERVICOS QUE IRÁ PAGAR #####################
                    //verifica se existe os exames pela matriz
                    if (isset($produtos[0])) {
                        $produtos_lista = $produtos[0]['ClienteProdutoServico2'];
                    } else {
                        $produtos_lista = array();
                        $produtos[0]['Produto'] = $produto_matriz_produto;
                    }

                    $cliente_produto_servico2 = array_merge($produtos_lista, $produto_matriz);
                    // debug($cliente_produto_servico2);

                    $produtos[0]['ClienteProdutoServico2'] = $cliente_produto_servico2;

                    //pega todos os exames setados na assinatura
                    $produtos_servicos = $produtos;
                    // debug($produtos_servicos);

                    if (!empty($produtos_servicos)) {

                        //PC-1330
                        //pega o codigo do exame aso nas configurações para verificar se ele está habilitado para exames pontuais e retirar ele
                        $configCodigoASO = $this->Configuracao->getChave('INSERE_EXAME_CLINICO');
                        //recupera o codigo do servico pelo exame acima 
                        $exame_servico = $this->Exames->find()->select(['codigo_servico'])->where(['codigo' => $configCodigoASO])->first();
                        $codigo_servico_exame_aso = $exame_servico->codigo_servico;

                        //verifica se tem o exame ASO para retirar
                        foreach($produtos_servicos AS $keyPS => $dadosPS) {
                            
                            if(!isset($dadosPS['ClienteProdutoServico2'])) {
                                continue;
                            }

                            //varre a cliente produto servico
                            foreach($dadosPS['ClienteProdutoServico2'] AS $keyCPS => $dadosCPS) {
                                if($dadosCPS['Servico']['codigo'] == $codigo_servico_exame_aso) { //PC-1330
                                    unset($produtos_servicos[$keyPS]['ClienteProdutoServico2'][$keyCPS]);
                                }
                            }//fim foreach cliente produto servico2
                        }//fim foreach produto servicos

                        if (isset($produtos_servicos[0]['ClienteProdutoServico2']) && count($produtos_servicos[0]['ClienteProdutoServico2'])) {

                            //varre os produtos
                            foreach ($produtos_servicos as $keyProd => $prod) {

                                foreach ($produtos_servicos[$keyProd]['ClienteProdutoServico2'] as $key => $servico) {

                                    //pega os exames deste servico
                                    $exames = $this->Exames->find()->select(['codigo', 'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])->where(['codigo_servico' => $servico['Servico']['codigo']])->hydrate(false)->toArray();
                                    // debug($exames);
                                    //pega os exames dos servicos cadastrados
                                    if ($exames) {
                                        $lista_exames['consulta_pontual'][] = array(
                                            'id' => $exames[0]['codigo'],
                                            'descricao' => $exames[0]['descricao']
                                        );
                                    }
                                } //fim foreach

                            }//fim produtos_servicos


                        } //fim if produto servico
                    } //FINAL SE empty($produtos_servicos)
                // } //fim if existe ppra
            } //fim foreach dados matricula funcao

            // debug($lista_exames);
            // exit;

            //retorna a lista de exames do pcmso
            return $lista_exames;
        } //fim dados_matricula_funcao
        else {
            $error[] = "Não encontramos nenhuma matricula/função as empresa(s) vinculada(s)!";
            return $error;
        }
    }

    public function conferirExame()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //verifica se tem codigo do usuario
            if (empty($dados['codigo_usuario'])) {
                $error[] = "Codigo do usuario não informado!";
                $this->set(compact('error'));
                return null;
            }

            $codigo_usuario = $dados['codigo_usuario'];

            $this->loadModel('ItensPedidosExames');
            $getItenPedidoExameByCodigo = $this->ItensPedidosExames->get($dados['codigo_itens_pedidos_exames']);

            // debug($getItenPedidoExameByCodigo);exit;

            if (!empty($getItenPedidoExameByCodigo)) {

                //pega o pedido de exame para pegar o funcionario
                $this->loadModel('FuncionariosContatos');
                $this->loadModel('PedidosExames');
                $pedido = $this->PedidosExames->find()->select(['codigo_funcionario'])->where(['codigo' => $getItenPedidoExameByCodigo->codigo_pedidos_exames])->first()->toArray();
                $codigo_funcionario = $pedido['codigo_funcionario'];

                //verifica se tem o indice do email
                if (isset($dados['email'])) {
                    if (!empty($dados['email']) && !is_null($dados['email'])) {

                        $contato_email = $this->FuncionariosContatos->find()->where(['codigo_tipo_retorno' => 2, 'codigo_funcionario' => $codigo_funcionario])->first();
                        if (!empty($contato_email)) {
                            $email['codigo'] = $contato_email->codigo;
                            $email['descricao'] = $dados['email'];
                            $email['codigo_usuario_alteracao'] = $codigo_usuario;
                            $email['data_alteracao'] = date('Y-m-d H:i:s');
                            //seta os dados para atualizacao
                            $dados_email = $this->FuncionariosContatos->patchEntity($contato_email, $email);
                        } else {
                            $email['codigo_tipo_retorno'] = '2';
                            $email['codigo_tipo_contato'] = '2';
                            $email['codigo_funcionario'] = $codigo_funcionario;
                            $email['descricao'] = $dados['email'];
                            $email['data_inclusao'] = date('Y-m-d H:i:s');
                            $email['codigo_usuario_inclusao'] = $codigo_usuario;
                            $email['codigo_empresa'] = 1;

                            $dados_email = $this->FuncionariosContatos->newEntity($email);
                        }

                        if (!$this->FuncionariosContatos->save($dados_email)) {
                            $error[] = "Erro ao atualizar contato de email do funcionario!";
                            $this->set(compact('error'));
                            return null;
                        }

                        $data['email'] = $dados['email'];
                    }
                }

                //verifica se tem o indice do email
                if (isset($dados['telefone'])) {
                    if (!empty($dados['telefone']) && !is_null($dados['telefone'])) {

                        //formata o telefone
                        $ddd = null;
                        $telefone = $dados['telefone'];
                        if (strstr($dados['telefone'], ")")) {
                            $dados['telefone'] = str_replace("(", '', str_replace(')', '', $dados['telefone']));
                            $separa = explode(' ', $dados['telefone']);
                            $ddd = $separa[0];
                            $telefone = $separa[1];
                        }

                        $contato_tel = $this->FuncionariosContatos->find()->where(['codigo_tipo_retorno' => 1, 'codigo_funcionario' => $codigo_funcionario])->first();

                        if (!empty($contato_tel)) {
                            $tel['codigo'] = $contato_tel->codigo;
                            $tel['ddd'] = $ddd;
                            $tel['descricao'] = $telefone;
                            $tel['codigo_usuario_alteracao'] = $codigo_usuario;
                            $tel['data_alteracao'] = date('Y-m-d H:i:s');
                            //seta os dados para atualizacao
                            $dados_tel = $this->FuncionariosContatos->patchEntity($contato_tel, $tel);
                        } else {
                            $tel['codigo_tipo_retorno'] = 1;
                            $tel['codigo_tipo_contato'] = 2;
                            $tel['codigo_funcionario'] = $codigo_funcionario;
                            $tel['ddd'] = $ddd;
                            $tel['descricao'] = $telefone;
                            $tel['data_inclusao'] = date('Y-m-d H:i:s');
                            $tel['codigo_usuario_inclusao'] = $codigo_usuario;
                            $tel['codigo_empresa'] = 1;

                            $dados_tel = $this->FuncionariosContatos->newEntity($tel);
                        }

                        if (!$this->FuncionariosContatos->save($dados_tel)) {
                            $error[] = "Erro ao atualizar contato de telefone do funcionario!";
                            $this->set(compact('error'));
                            return null;
                        }

                        $data['telefone'] = $dados['telefone'];
                    }
                }

                $this->loadModel('AptidaoExame');
                $getAptidaoExame = $this->AptidaoExame->find()->where(['codigo_itens_pedidos_exames' => $dados['codigo_itens_pedidos_exames']]);

                $aptidaoTable = TableRegistry::getTableLocator()->get('AptidaoExame');
                if (empty($getAptidaoExame->toArray())) {
                    $aptidao = $aptidaoTable->newEntity();
                } else {
                    $aptidao = $aptidaoTable->findByCodigoItensPedidosExames($dados['codigo_itens_pedidos_exames'])->first();
                }

                $aptidao->codigo_itens_pedidos_exames = $dados['codigo_itens_pedidos_exames'];

                if (isset($dados['check_telefone'])) {
                    if (!empty($dados['check_telefone']) && !is_null($dados['check_telefone'])) {
                        $aptidao->check_telefone              = $dados['check_telefone'];
                    }
                }

                if (isset($dados['check_email'])) {
                    if (!empty($dados['check_email']) && !is_null($dados['check_email'])) {
                        $aptidao->check_email                 = $dados['check_email'];
                    }
                }

                if (isset($dados['check_requisitos'])) {
                    if (!empty($dados['check_requisitos']) && !is_null($dados['check_requisitos'])) {
                        $aptidao->check_requisitos            = $dados['check_requisitos'];
                    }
                }
                $this->AptidaoExame->save($aptidao);

                if (isset($dados['codigo_status_itens_pedidos_exames'])) {
                    if ($dados['codigo_status_itens_pedidos_exames']) {
                        $getItenPedidoExameByCodigo->codigo_status_itens_pedidos_exames = $dados['codigo_status_itens_pedidos_exames'];
                        $this->ItensPedidosExames->save($getItenPedidoExameByCodigo);
                    }
                }

                $data['aptidao'] = $aptidao;

                $this->set(compact('data'));
            } else {
                $error = "Codigo Item pedido não existe!";
                $this->set(compact('error'));
            }
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function listaAgendamentoExames()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        try {

            //pega os dados que veio do post
            $dados = $this->request->getData();
            // debug($dados)
            // $dia_semana_por_data = $this->convertToWeekDayCode($this->verificaDiaSemana($dados['data_agendamento']));
            $dia_semana_por_data = $this->convertToWeekDay($this->verificaDiaSemana($dados['data_agendamento']));

            $indisponivel = false;

            //Seta as variaveis do request para bindar os dados na query
            $params = ['codigo_fornecedor' => $dados['codigo_fornecedor'], 'dia_semana' => $dia_semana_por_data];
            //AGRUPA OS EXAMES
            $queryAgenda = "select
                         e.codigo as codigo_exame,
                         RHHealth.dbo.ufn_decode_utf8_string(e.descricao) AS descricao,
                         fga.hora,
                         fga.dia_semana
                        from fornecedores forn
                         inner join listas_de_preco lp on forn.codigo = lp.codigo_fornecedor
                         inner join listas_de_preco_produto lpp on lp.codigo = lpp.codigo_lista_de_preco
                         inner join listas_de_preco_produto_servico lpps on lpp.codigo = lpps.codigo_lista_de_preco_produto
                         inner join exames e on lpps.codigo_servico = e.codigo_servico
                         left join fornecedores_grade_agenda fga on forn.codigo = fga.codigo_fornecedor and fga.codigo_lista_de_preco_produto_servico = lpps.codigo
                        where forn.codigo = :codigo_fornecedor
                         and e.codigo IN (" . $dados['codigos_exames'] . ")
                         and lpps.tipo_atendimento = 1
                          and fga.dia_semana = :dia_semana
                        group by e.codigo, e.descricao, fga.hora, fga.dia_semana
                        order by fga.hora, e.descricao";

            // debug($params);
            //debug($queryAgenda);exit;

            //Retorna os dados da consulta ao banco
            $resultAgenda = $this->connection->execute($queryAgenda, $params)->fetchAll('assoc');

            // trara indisponivel
            if(empty($resultAgenda)) {

                $indisponivel = true;

                //Seta as variaveis do request para bindar os dados na query
                $params = ['codigo_fornecedor' => $dados['codigo_fornecedor']];

                $queryAgenda = "select
                             e.codigo as codigo_exame,
                             RHHealth.dbo.ufn_decode_utf8_string(e.descricao) as descricao,
                             fga.hora
                            from fornecedores forn
                             inner join listas_de_preco lp on forn.codigo = lp.codigo_fornecedor
                             inner join listas_de_preco_produto lpp on lp.codigo = lpp.codigo_lista_de_preco
                             inner join listas_de_preco_produto_servico lpps on lpp.codigo = lpps.codigo_lista_de_preco_produto
                             inner join exames e on lpps.codigo_servico = e.codigo_servico
                             left join fornecedores_grade_agenda fga on forn.codigo = fga.codigo_fornecedor and fga.codigo_lista_de_preco_produto_servico = lpps.codigo
                            where forn.codigo = :codigo_fornecedor
                             and e.codigo IN (" . $dados['codigos_exames'] . ")
                             and lpps.tipo_atendimento = 1
                            group by e.codigo, e.descricao, fga.hora
                            order by fga.hora, e.descricao";

                // debug($params);
                // debug($queryAgenda);exit;

                //Retorna os dados da consulta ao banco
                $resultAgenda = $this->connection->execute($queryAgenda, $params)->fetchAll('assoc');
            }

            //pega os médicos do corpo clinico
            $medicos = array();
            if(!$indisponivel) {
                $this->loadModel("FornecedoresMedicos");
                $medicosList = $this->FornecedoresMedicos->getMedicos($dados['codigo_fornecedor'])->all()->toArray();

                $params = ['codigo_fornecedor' => $dados['codigo_fornecedor']];
                $queryAgendaDisponibilidade = "SELECT
                                m.codigo
                                , mch.dia_semana
                                , mch.hora_inicio_manha
                                , mch.hora_fim_manha
                                , mch.hora_inicio_tarde
                                , mch.hora_fim_tarde
                            FROM
                                dbo.medicos m
                                INNER JOIN dbo.fornecedores_medicos fm ON fm.codigo_medico = m.codigo
                                INNER JOIN dbo.medico_calendario mc ON mc.codigo_medico = m.codigo
                                INNER JOIN dbo.medico_calendario_horarios mch ON mch.codigo_medico_calendario = mc.codigo
                            WHERE
                                fm.codigo_fornecedor = :codigo_fornecedor";
                $medicosAgendaDisponibilidade = $this->connection->execute($queryAgendaDisponibilidade, $params)->fetchAll('assoc');



                foreach ($medicosList as $item) {
                $horarioDisponivel = [];
                    foreach ($medicosAgendaDisponibilidade as $key => $value) {
                        if ($item['codigo'] == $value['codigo']) {
                            $horarioDisponivel = $value;
                        }

                    }
                    $medicos[$item['codigo']] = [
                        "codigo"            => $item['codigo'],
                        "nome"              => $item['nome'],
                        "numero_conselho"   => $item['numero_conselho'],
                        "conselho_uf"       => $item['conselho_uf'],
                        "especialidade"     => $item['especialidade'],
                        "foto"              => $item['foto'],
                        "agendaHorario"     => $horarioDisponivel
                    ];


                }
            }


            //Seta as variaveis do request para bindar os dados na query
            $params = ['codigo_fornecedor' => $dados['codigo_fornecedor'], 'data_agendamento' => $dados['data_agendamento']];

            //RETORNA OS PEDIDOS DE AGENDAMENTO DE EXAMES COM VINCULO DE MÉDICO. POR horario
            $queryAgendamento = "select func.nome as nome_funcionario, func.codigo as codigo_funcionario,
                      ipe.codigo_medico, m.nome as nome_medico, ipe.data_agendamento, ipe.hora_agendamento,
                      ipe.codigo_exame, codigo_status_itens_pedidos_exames
                     from itens_pedidos_exames ipe
                      inner join pedidos_exames pe on ipe.codigo_pedidos_exames = pe.codigo
                      inner join funcionarios func on pe.codigo_funcionario = func.codigo
                      left join medicos m on ipe.codigo_medico = m.codigo
                     where ipe.codigo_fornecedor = :codigo_fornecedor
                      and data_agendamento = :data_agendamento ";

            //Retorna os dados da consulta ao banco
            $resultAgendamento = $this->connection->execute($queryAgendamento, $params)->fetchAll('assoc');
            $m = [];

            // debug($medicos);exit;

            foreach ($resultAgenda as $key => $agenda) {

                $hora = $this->formatHour($agenda['hora']);

                //verifica os medicos que podem atender olhando para o calendario do medico
                $resultAgenda[$key]['hora'] = $this->formatHour($agenda['hora']); //Formata a string para o formato de horas;
                $resultAgenda[$key]['dia_semana'] = $this->verificaDiaSemana($dados['data_agendamento']);
                $resultAgenda[$key]['indisponivel'] = $indisponivel;

                // $resultAgenda[$key]['medicos'] = $medicos;
                $resultAgenda[$key]['agendamento'] = [];

                $medicos_new =  $medicos;

                //MONTA OS EXAMES COM AGENDAMENTO
                $acumuladorAgendamento = [];
                // debug($resultAgendamento);exit;
                foreach ($resultAgendamento as $keyAgendamento => $agendamento) {


                    //PREENCHE COM AGENDAMENTO
                    if (    $agenda['hora'] == $agendamento['hora_agendamento'] &&
                            $agenda['codigo_exame'] == $agendamento['codigo_exame']) {

                        if(isset($medicos_new[$agendamento['codigo_medico']])) {
                            unset($medicos_new[$agendamento['codigo_medico']]);
                        }

                        $acumuladorAgendamento[] = $agendamento;
                    }
                }
                //PREENCHE COM INFORMACAO DO MEDICO
                foreach ($medicos_new as $itemMedicos) {

                    if (!empty($itemMedicos['agendaHorario'])) {

                        $hora_inicio_manha = $itemMedicos['agendaHorario']['hora_inicio_manha'];
                        $hora_fim_manha = $itemMedicos['agendaHorario']['hora_fim_manha'];
                        $hora_inicio_tarde = $itemMedicos['agendaHorario']['hora_inicio_tarde'];
                        $hora_fim_tarde = $itemMedicos['agendaHorario']['hora_fim_tarde'];

                        $disponivel = 0;
                        //verifica se esta denro da hora de disponibulidade
                        if($hora >= $hora_inicio_manha && $hora <= $hora_fim_manha ) {
                            $disponivel = 1;
                        }

                        //verifica se esta denro da hora de disponibulidade
                        if($hora >= $hora_inicio_tarde && $hora <= $hora_fim_tarde ) {
                            $disponivel= 1;
                        }

                        //verifica se esta INDISPONIVEL
                        if(!$disponivel) {
                            //deixa o medico na lista do select
                            unset($medicos_new[$itemMedicos['codigo']]);
                        }//fim fisponivel
                    }
                }//.ENDMEDICOS

                // debug($resultAgenda[$key]['medicos']);exit;

                $resultAgenda[$key]['medicos'] = array_values($medicos_new);
                $resultAgenda[$key]['agendamento'] = $acumuladorAgendamento;
            }// fim foreach


            $data = $resultAgenda;

            $this->set(compact('data'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    } // fim listaAgendamentoExames

    /**
     * [getReagendamento metodo para buscar a agenda do medico para saber se pode agendar nesta data que esta passando]
     * @param  int    $codigo_usuario [description]
     * @param  int    $codigo_medico  [description]
     * @param  [type] $data           [description]
     * @return [type]                 [description]
     */
    public function getReagendamento(int $codigo_usuario, int $codigo_medico, int $codigo_fornecedor, $data_agendamento)
    {

        $data = array();
        //verifica se os parametros estao certos
        if (empty($codigo_usuario)) {
            $error = 'codigo_usuario requerido';
            $this->set(compact('error'));
            return;
        }

        if (empty($codigo_medico)) {
            $error = 'codigo_medico requerido';
            $this->set(compact('error'));
            return;
        }

        if (empty($codigo_fornecedor)) {
            $error = 'codigo_fornecedor requerido';
            $this->set(compact('error'));
            return;
        }

        if (empty($data_agendamento)) {
            $error = 'data_agendamento requerido';
            $this->set(compact('error'));
            return;
        }


        try {

            //monta os dados
            $dados = array(
                'codigo_medico' => $codigo_medico,
                'codigo_fornecedor' => $codigo_fornecedor,
                'data_por_dia' => $data_agendamento,
                'tipo_data' => 1
            );
            //agenda
            $agenda = $this->getAgendamentoList($dados);

            //verifica se o hr esta disponivel
            if (!empty($agenda)) {
                //varre a agenda
                foreach ($agenda as $hr) {

                    // debug($hr); die;
                    if (empty($hr['agendamento'])) {
                        $data[]['hora'] = $hr['hora'];
                    }
                } //fim varrendo as hras da agenda
            } //fim agenda

            // debug($data);exit;

            $this->set(compact('data'));
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    } //fim getReagendamento


    public function getFornecesoresHorarios(int $codigo_fornecedor = null, $diaSemanaCod = null, $codigo_medico = null)
    {

        try {
            $whereAdd = !is_null($diaSemanaCod) && is_numeric($diaSemanaCod) ? " AND mch.dia_semana = {$diaSemanaCod}" : '';

            if(!is_null($codigo_medico)) {
                $whereAdd .= " AND m.codigo IN ({$codigo_medico}) ";
            }

            $params = ['codigo_fornecedor' => $codigo_fornecedor];
            $query = "SELECT
                        m.codigo
                        , RHHealth.dbo.ufn_decode_utf8_string(m.nome) as nome
                        , mch.dia_semana
                        , mch.hora_inicio_manha
                        , mch.hora_fim_manha
                        , mch.hora_inicio_tarde
                        , mch.hora_fim_tarde
                    FROM
                        dbo.medicos m
                        INNER JOIN dbo.fornecedores_medicos fm ON fm.codigo_medico = m.codigo
                        INNER JOIN dbo.medico_calendario mc ON mc.codigo_medico = m.codigo
                        INNER JOIN dbo.medico_calendario_horarios mch ON mch.codigo_medico_calendario = mc.codigo
                    WHERE
                        fm.codigo_fornecedor = :codigo_fornecedor
                        {$whereAdd}";
            $result = $this->connection->execute($query, $params)->fetchAll('assoc');

            $medicoGrade = [];
            foreach ($result as $value) {
                $dia_semana_string = $this->convertToWeekDayCode($value['dia_semana']);
                $medicoGrade[$value['dia_semana']][$value['codigo']] = [
                    'nome'                      => $value['nome'],
                    'dia_semana'                => $value['dia_semana'],
                    'dia_semana_string'         => $dia_semana_string,
                    'hora_inicio_manha'         => $value['hora_inicio_manha'],
                    'hora_fim_manha'            => $value['hora_fim_manha'],
                    'hora_inicio_tarde'         => $value['hora_inicio_tarde'],
                    'hora_fim_tarde'            => $value['hora_fim_tarde'],
                    //EPOC
                    // 'epoc_hora_inicio_manha'    => strtotime($value['hora_inicio_manha']),
                    // 'epoc_hora_fim_manha'       => strtotime($value['hora_inicio_manha']),
                    // 'epoc_hora_inicio_tarde'    => strtotime($value['hora_inicio_tarde']),
                    // 'epoc_hora_fim_tarde'       => strtotime($value['hora_fim_tarde']),

                ];
            }
            return $medicoGrade;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function getAgendamentosPorHoraMarcada($codigo_fornecedor, $data_inicio, $data_fim, $tipo_exame = null, $status = null, $especialidade = null, $especialista = null)
    {
        //Verifica se fornecedor existe
        $fornecedores = $this->Fornecedores->find()
            ->select(['codigo'])
            ->first();

        if(!empty($fornecedores)) { // Retorna todos os exames do fornecedor

            $pedidos_exames = $this->PedidosExames->retornaPedidos($codigo_fornecedor, $data_inicio, $data_fim, $tipo_exame, $status, $especialidade, $especialista);

            $this->paginate = array(
                'limit' => 5,
            );

            $this->set('data', $this->paginate($pedidos_exames));

        } else {
            $error = 'Fornecedor não encontrado.';
            $this->set(compact('error'));
        }
    }

    public function getAgendamentosPorOrdemChegada($codigo_fornecedor, $data_inicio, $data_fim, $tipo_exame = null, $status = null, $especialidade = null, $especialista = null)
    {
        //Verifica se fornecedor existe
        $fornecedores = $this->Fornecedores->find()
            ->select(['codigo'])
            ->first();

        if(!empty($fornecedores)) { // Retorna todos os exames do fornecedor

            $pedidos_exames = $this->PedidosExames->retornaPedidosOrdemChegada($codigo_fornecedor, $data_inicio, $data_fim, $tipo_exame, $status, $especialidade, $especialista);

            $this->paginate = array(
                'limit' => 5,
            );

            $this->set('data', $this->paginate($pedidos_exames));

        } else {
            $error = 'Fornecedor não encontrado.';
            $this->set(compact('error'));
        }
    }
}
