<?php
namespace App\Controller\Api;
use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Core\App;
use Cake\Http\Client;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

use DirectoryIterator;
use InvalidArgumentException;
use App\Utils\EncodingUtil;

use ZipArchive;

define('BASE_CAKE', WWW_ROOT . '../');

use Cake\I18n\Time;

/**
 * PedidosExames Controller
 *
 * @property \App\Model\Table\PedidosExamesTable $PedidosExames
 *
 * @method \App\Model\Entity\PedidosExame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PedidosExamesController extends ApiController
{
    public $codigo_funcionario;
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['imprimirKit']);
        $this->loadModel("FuncionarioSetoresCargos");
        $this->loadModel("GruposEconomicosClientes");
        $this->loadModel("MotivosCancelamento");
        $this->loadModel("MotivosConclusaoParcial");
        $this->loadModel("Configuracao");
        $this->loadModel("TipoNotificacao");
        $this->loadModel("FichasClinicas");
        $this->loadModel("Fornecedores");
        $this->loadModel("PedidosExames");
        $this->loadModel("PedidosExames");
        $this->loadModel("TipoNotificacaoValores");
        $this->loadModel("ItensPedidosExames");
    }
    public function deleteAgendamentoExameFoto()
    {
        $codigo_item_pedido_exame = $this->request->getData('codigo_item_pedido_exame');
        $this->loadModel("AnexosExames");
        //variavel auxiliar
        $data = array();
        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_item_pedido_exame)) {
            $error[] = 'Parametro codigo_item_pedido_exame inválido.';
        }
        // saída
        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            $deletado = $this->AnexosExames->deleteFoto($codigo_item_pedido_exame);
            if($deletado){
                $data[] = "Operação realizada com sucesso.";
                $this->set(compact('data'));
            } else {
                $error[] = "Foto não encontrada.";
                $this->set(compact('error'));
            }
        }

    }
    public function getClinicasProximas($codigo_usuario = null)
    {
        //variavel auxiliar
        $data = array();
        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_usuario)) {
            $error[] = 'Parametro codigo_usuario inválido.';
        }
        else {
            // Request
            $codigo_cliente = (int)  $this->request->getData('codigo_cliente');
            $ordenacao = (String)  $this->request->getData('ordenacao');
            $codigo_solicitacao_exame = (int)  $this->request->getData('codigo_solicitacao_exame');
            $exame = $this->request->getData('codigo_exame');
            // $atendimento_online = (boolean) $this->request->getData('atendimento_online');
            $atendimento_online = null;
            $raio_distancia = (!empty($this->request->getData('raio_distancia')) ? $this->request->getData('raio_distancia') : 30);
            $endereco = (string) $this->request->getData('endereco');
            $tipo_agendamento = (int)  $this->request->getData('tipo_agendamento');
            //valida se o usuario pode emitir um pedido de exames
            $validar = $this->validaUsuarioPedidosExames($codigo_usuario);
            if (!empty($validar)) {
                $error = $validar;
            }
            else {
                //instancio todos as class que irei precisar para o metodo
                $http = new Client();
                $this->loadModel('ClienteFuncionario');
                $this->loadModel('ClienteEndereco');
                $this->loadModel('ClientesFornecedores');
                $this->loadModel('FornecedoresGradeAgenda');
                $this->loadModel('Exames');
                $this->loadModel('FornecedoresAvaliacoes');
                $cliente_data =[];
                //caso o filtro seja de endereco
                $dados_endereco = array();
                if(!empty($endereco)) {
                    //pega o endereco pesquisado
                    $cliente_data = new \stdClass;
                    $cliente_endereco = urlencode($endereco);
                    $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/consulta_lat_long?endereco='.$cliente_endereco);
                    $result = json_decode($response->getStringBody());
                    $cliente_data->longitude = $result->longitude;
                    $cliente_data->latitude = $result->latitude;
                }
                else{
                    $cliente_data = $this->ClienteEndereco->find()->select(['longitude','latitude','logradouro'=>'RHHealth.dbo.ufn_decode_utf8_string(logradouro)'
                        ,'bairro'=>'RHHealth.dbo.ufn_decode_utf8_string(bairro)'
                        ,'cidade'=>'RHHealth.dbo.ufn_decode_utf8_string(cidade)'])
                        ->where(['codigo_cliente'=>$codigo_cliente])
                        ->first();
                    // Verifica se a latitude e longitude foram informadas na querystring
                    if(empty($cliente_data->latitude) OR empty($cliente_data->longitude )){
                        $cliente_endereco = urlencode($cliente_data->logradouro." ".$cliente_data->bairro." - ".$cliente_data->cidade." ");
                        $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/consulta_lat_long',['endereco'=>$cliente_endereco]);
                        $result = json_decode($response->getStringBody());
                        $cliente_data->longitude = $result->longitude;
                        $cliente_data->latitude = $result->latitude;
                    }
                }
                if(!empty($cliente_data->latitude) && !empty($cliente_data->longitude) && !empty($raio_distancia)) {
                    $dados_endereco['latitude_min']    = $cliente_data->latitude - ($raio_distancia / 111.18);
                    $dados_endereco['latitude_max']    = $cliente_data->latitude + ($raio_distancia / 111.18);
                    $dados_endereco['longitude_min']   = $cliente_data->longitude - ($raio_distancia / 111.18);
                    $dados_endereco['longitude_max']   = $cliente_data->longitude + ($raio_distancia / 111.18);
                    $dados_endereco['latitude']    = $cliente_data->latitude;
                    $dados_endereco['longitude']   = $cliente_data->longitude;
                }
                //pega a função do funcionario na funcionario_setores_cargo
                $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($this->usuario->codigo_funcionario, $codigo_cliente);
                // debug($dados_matricula_funcao);exit;
                //verifica se existe alguma matricula para este usuario
                if (!empty($dados_matricula_funcao)) {
                    //lista pcmso
                    $lista_pcmso = array();
                    $retorno_exames = array();
                    //varre as matriculas do funcionario
                    foreach ($dados_matricula_funcao as $dados) {
                        //verifica se tem ppra
                        if (!$this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo'])) {
                            $error[] = "Não existe PPRA para este funcionário da empresa: " . $dados['Cliente']['nome_fantasia'];
                        } else {
                            #################preciso montar os exames que tenho que fazer#################
                            $array_exames = array();
                            $array_servicos = array();
                            //verifica o tipo do exame periodico / pontual
                            if($codigo_solicitacao_exame == '1') {
                                //ocupacionais pega os exames do pcmso
                                //pega a lista de pcmso da configuracao do funcionario setores cargos
                                $dados_pcmso = $this->PedidosExames->lista_exames_pcmso($dados['FuncionarioSetorCargo']['codigo'], $codigo_cliente);
                                //varre os dados de pcmso
                                foreach($dados_pcmso AS $dpcmso) {
                                    $array_exames[$codigo_cliente][$dpcmso['codigo_exame']] = $dpcmso['codigo_exame'];
                                    $array_servicos[$codigo_cliente][$dpcmso['codigo_servico']] = $dpcmso['codigo_servico'];
                                }//fim foreach
                            }//fim periodico
                            else {
                                //verifica se existe exames pontuais
                                if(empty($exame)) {
                                    $this->set(compact('data'));
                                    break;
                                }
                                //pega os dados do exame
                                $dados_exame = $this->Exames->find()->select(['codigo_servico'])->where(['codigo' => $exame])->hydrate(false)->first();
                                //monta os exames
                                $array_exames[$codigo_cliente][$exame] = $exame;
                                $array_servicos[$codigo_cliente][$dados_exame['codigo_servico']] = $dados_exame['codigo_servico']; //$dexames['codigo_servico'];
                            }//fim codigo_solicitacao_exame
                            #################pegar todos os fornecedores para os exames que preciso fazer e dentro do raio estipulado#################
                            // retorna todos os fornecedores dentro do raio do cliente e na lista de fornecedores do cliente de alocação
                            $dados_fornecedores_disponiveis[$codigo_cliente] = $this->PedidosExames->retornaFornecedoresExames(implode(",", array_flip($array_servicos[$codigo_cliente])), $dados_endereco, $codigo_cliente, $atendimento_online);
                            // debug($dados_fornecedores_disponiveis);exit;
                            //varre os fornecedores e exames disponiveis
                            foreach($dados_fornecedores_disponiveis[$codigo_cliente] as $k => $fornecedor) {
                                // faz array com exames (todos)
                                $array_exames[$codigo_cliente][$fornecedor['Exame']['codigo']] = $fornecedor['utf8_exame_descricao'];
                                // faz array com fornecedores (todos)
                                $array_fornecedores[$codigo_cliente][$fornecedor['Fornecedor']['codigo']] = $fornecedor;
                                // faz array de fornecedores disponiveis por exame
                                $array_exames_fornecedores[$codigo_cliente][$fornecedor['Fornecedor']['codigo']][$fornecedor['Exame']['codigo']] = $fornecedor;
                                // valida custo do fornecedor mais barato para o exame!
                                // if(!isset($array_exame_mais_barato[$codigo_cliente][$fornecedor['Exame']['codigo']])) {
                                //     //array com o exame mais barato
                                //     $array_exame_mais_barato[$codigo_cliente][$fornecedor['Exame']['codigo']] = array('preco' => $fornecedor['ListaPrecoProdutoServico']['valor'], 'fornecedor' => $fornecedor['Fornecedor']['codigo']);
                                // }
                                // else if(isset($array_exame_mais_barato[$codigo_cliente][$fornecedor['Exame']['codigo']]) && $array_exame_mais_barato[$codigo_cliente][$fornecedor['Exame']['codigo']]['preco'] > $fornecedor['ListaPrecoProdutoServico']['valor']) {
                                //     $array_exame_mais_barato[$codigo_cliente][$fornecedor['Exame']['codigo']] = array('preco' => $fornecedor['ListaPrecoProdutoServico']['valor'], 'fornecedor' => $fornecedor['Fornecedor']['codigo']);
                                // }
                            }//FINAL FOREACH dados_fornecedores_disponiveis
                            #################montar o array com todos os exames para o endpoint colocando o disponivel ou não#################
                            $lat_long_fornecedores = "";
                            $array_indice = array();
                            $array_final_fornecedores = array();
                            $monta_exames = array();
                            $monta_exames_aso = array();
                            $fornecedores_dados = array();
                            // debug($array_exames_fornecedores);exit;
                            //varre os exames do pmcso/pontual do funcionario da matricula dele pela unidade/setor/cargo
                            foreach($array_exames[$codigo_cliente] as $k_exame => $exame) {
                                //verifica se existe o array de fornecedores com exames
                                if(isset($array_fornecedores[$codigo_cliente])) {
                                    //varre o array de fornecedores para pegar todos os fornecedores
                                    foreach($array_fornecedores[$codigo_cliente] as $k_fornecedor => $fornecedor) {
                                        $valor = "0.00";
                                        $disponibilidade = 'false';
                                        $var_grade = '0';
                                        //verificar se tem no forneceodor o exame que esta na passagem do array de exames
                                        if(isset($array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame])) {
                                            $disponibilidade = 'true';
                                            $valor = substr($fornecedor['ListaPrecoProdutoServico']['valor'], 0,-2);
                                            // add exame por fornecedor no array
                                            $array_final_fornecedores['cliente'][$codigo_cliente]['fornecedores_por_exame'][$k_fornecedor][$k_exame] = $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame];
                                            // cria indice com enderecos dos fornecedores para fazer buscar distancia no api do google maps
                                            if(!array_key_exists($k_fornecedor, array_flip($array_indice))) {
                                                //concatena para saber qual a distancia pelo endereco pesquisado
                                                $lat_long_fornecedores .= $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]['FornecedorEndereco']['longitude'] . ";" . $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]['FornecedorEndereco']['latitude'] . "|";
                                                //variavel auxiliar
                                                $array_indice[] = $k_fornecedor;
                                            }
                                            //pega se tem agendamento,e atendimento online
                                            if($array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]['Fornecedor']['tipo_atendimento'] == 1 && $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]['ListaPrecoProdutoServico']['tipo_atendimento'] == 1) {
                                                $codigo_fornecedor = $k_fornecedor;
                                                $codigo_servico = $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]['ListaPrecoProdutoServico']['codigo_servico'];
                                                //if($codigo_fornecedor == 8350 && $k_exame == 10) {
                                                //    debug($array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame]);
                                                //}
                                                // verifica se existe uma grade cadastrada para este serviço
                                                $verifica_grade_especifica = $this->FornecedoresGradeAgenda->retorna_grade_especifica($codigo_fornecedor, $codigo_servico);
                                                // se existir registro monta grande
                                                if(isset($verifica_grade_especifica['ListaDePrecoProdutoServico']['codigo_servico']) && ($verifica_grade_especifica['ListaDePrecoProdutoServico']['codigo_servico'] == $codigo_servico)) {
                                                    //verifica se existe grade de agendamento
                                                    $grade_agendamento = $this->FornecedoresGradeAgenda->retorna_agenda_especifica($codigo_fornecedor, $codigo_servico);
                                                    // if($codigo_fornecedor == 8350 && $k_exame == 10){
                                                    //     debug($grade_agendamento);exit;
                                                    // }
                                                    if(!empty($grade_agendamento)) {
                                                        $var_grade = '1';
                                                    }
                                                }//fim verificacao grade especifica
                                            }//fim fornecedor
                                        }//FINAL SE $array_exames_fornecedores[$codigo_cliente][$k_fornecedor][$k_exame] EXISTE
                                        $avaliacao = $this->FornecedoresAvaliacoes->getFornecedorNota($k_fornecedor);
                                        $nota = isset($avaliacao['pontuacao_arredondada']) ? $avaliacao['pontuacao_arredondada'] : 0;
                                        $total_avaliacoes = isset($avaliacao['quantidade_avaliacoes']) ? $avaliacao['quantidade_avaliacoes'] : 0;
                                        if(empty($tipo_agendamento) || $var_grade == 1){
                                            // debug($k_exame);
                                            $fornecedores_dados[$k_fornecedor] = array(
                                                "id_clinica" => $k_fornecedor,
                                                "descricao" => $fornecedor['utf8_fornecedor_nome'],
                                                "telefone" => $fornecedor['telefone'],
                                                "tipo_agendamento" => $fornecedor['Fornecedor']['tipo_atendimento'],
                                                "descricao_tipo_agendamento" => (($fornecedor['Fornecedor']['tipo_atendimento'] == 1 ) ? "Hora Marcada" : "Ordem de Chegada"),
                                                "endereco" => $fornecedor['utf8_fornecedor_endereco_logradouro'] . ", " . $fornecedor['FornecedorEndereco']['numero'].' - '.$fornecedor['utf8_fornecedor_endereco_complemento'].' - '.$fornecedor['utf8_fornecedor_endereco_cidade'].' - '.$fornecedor['utf8_fornecedor_endereco_estado_descricao'],
                                                "nota" => $nota,
                                                "total_avaliacoes" => $total_avaliacoes,
                                                "avaliacao" => $avaliacao,
                                                "atendimento_online" => $fornecedor['Fornecedor']['tipo_atendimento'], // 0 ordem de chegada quando for 1 hora marcada
                                            );
                                            //verifica se é o aso para colocar ele por ultimo na linda de exames
                                            if($k_exame == 52) {
                                                $monta_exames_aso[$k_fornecedor] = array(
                                                    'id_exame_tipo' => $k_exame,
                                                    'descricao' => $exame,
                                                    'valor' => $valor,
                                                    'tipo_atendimento' => $var_grade, //quando 0 nao tem grade 1 tem grade
                                                    'disponibilidade' => $disponibilidade,
                                                    'aso' => true
                                                );
                                            }
                                            else {
                                                //outros exames
                                                $monta_exames[$k_fornecedor]['exames_solicitados'][] = array(
                                                    'id_exame_tipo' => $k_exame,
                                                    'descricao' => $exame,
                                                    'valor' => $valor,
                                                    'tipo_atendimento' => $var_grade, //quando 0 ordem de chegada quando for 1 hora marcada
                                                    'disponibilidade' => $disponibilidade,
                                                    'aso' => false
                                                );
                                            }
                                        }
                                    }//FINAL FOREACH array_fornecedores[$codigo_cliente]
                                }//fim if exames fornecedores
                            }//FINAL FOREACH $array_exames[$codigo_cliente]
                            // debug($monta_exames_aso);
                            // debug($monta_exames);
                            // exit;
                            //print_r($cliente_data);
                            //exit;
                            //verifica se tenho o endereco para a distancia
                            if(!empty($lat_long_fornecedores) && count($array_indice)) {
                                //origem
                                $endereco_origem = $cliente_data->longitude . ";" . $cliente_data->latitude;
                                // debug('https://portal.rhhealth.com.br/portal/api/mapa/distancia_lat_long?origem='.$endereco_origem.'&destino='.$lat_long_fornecedores);exit;
                                //pega a distancia
                                $distancia_retorno = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/distancia_lat_long?origem='.$endereco_origem.'&destino='.$lat_long_fornecedores);
                                $result = json_decode(json_decode(json_encode($distancia_retorno->getStringBody()), true));
                                // debug($result);exit;
                                // organiza distancia
                                foreach($array_indice as $key => $item) {
                                    //verifica se tem lat/long do fornecedor
                                    if(isset($result->distancia->rows[0]->elements[$key]->distance->text)) {
                                        //seta a distancoa
                                        // $array_fornecedores[$codigo_cliente][$item]['Km'] = $result['rows'][0]['elements'][$key]['distance']['text'];
                                        $array_fornecedores[$codigo_cliente][$item]['Km'] = $result->distancia->rows[0]->elements[$key]->distance->text;
                                    }
                                }//FINAL FOREACH $array_indice
                            }//entra no if do fornecedor que tem latitude e longitude
                            //foreachfinal
                            $data_dados = array();
                            foreach($fornecedores_dados as $codigo_fornecedor => $dados_forn) {
                                $data_dados = array();
                                // $data_dados[$codigo_fornecedor] = $dados_forn;
                                // $data_dados[$codigo_fornecedor]['distancia'] = $array_fornecedores[$codigo_cliente][$codigo_fornecedor]['Km'];
                                // $data_dados[$codigo_fornecedor]['exames_solicitados'] = $monta_exames[$codigo_fornecedor]['exames_solicitados'];
                                //coloca o aso por ultimo
                                if(!empty($monta_exames_aso[$codigo_fornecedor])) {
                                    $monta_exames[$codigo_fornecedor]['exames_solicitados'][] = $monta_exames_aso[$codigo_fornecedor];
                                }
                                $data_dados = $dados_forn;
                                $data_dados['distancia'] = $array_fornecedores[$codigo_cliente][$codigo_fornecedor]['Km'];
                                $data_dados['distancia_numero'] = floatval(str_replace("km","",$array_fornecedores[$codigo_cliente][$codigo_fornecedor]['Km']));
                                $data_dados['exames_solicitados'] = $monta_exames[$codigo_fornecedor]['exames_solicitados'];
                                $data_dados['qtd_exames'] = count($monta_exames[$codigo_fornecedor]['exames_solicitados']);
                                // adiciona informação para filtrar recomendados
                                $data[] = $data_dados;
                            }//fim foreach data
                        } // FIM valida_pedido_exame_ppra
                    }// FIM matriculas do funcionario
                } //fim matricula funcao
            } //fim else validar
        }//fim else codigo usuario
        // saída
        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            if($ordenacao == "distancia"){
                $distancia = array_column($data, 'distancia_numero');
                array_multisort($distancia, SORT_ASC, $data);
            }
            if($ordenacao == "recomendados"){
                $recomendados = array_column($data, 'qtd_exames');
                array_multisort($recomendados, SORT_DESC, $data);
            }
            if($ordenacao == "avaliacao"){
                $notas= array_column($data, 'nota');
                array_multisort($notas, SORT_DESC, $data);
            }
            $this->set(compact('data'));
        }

    }// fim getClinicasProximas
    /**
     * clinicaEndereco method
     *
     * Autocomplete para endereços
     *
     * @return [type]  lista de enderecos
     */
    public function getClinicaEndereco($codigo_usuario = null)
    {
        //variavel auxiliar
        $http = new Client();
        $parameters = $this->request->getAttribute('params');
        $data = array();
        $error = array();
        if(!isset($parameters["?"]["endereco"]) OR empty($parameters["?"]["endereco"])) {
            $error[] = "Endereço não informado";
        } else {
            try {
                $endereco = urlencode($parameters["?"]["endereco"]);
                $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/getEndereco',['logradouro'=>$endereco]);
                $data[] = json_decode($response->getStringBody());
            } catch (Exception $e){
                $error[]='Não foi possível obter a latitude e longitude. Não foi possível conectar na API.';
            }
        }
        // saída
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }

    }
    /**
     * endereco method
     *
     * Consulta o endereço pela lat e long
     *
     * @return [type]  endereco
     */
    public function getEndereco()
    {
        //variavel auxiliar
        $http = new Client();
        $parameters = $this->request->getAttribute('params');
        $data = array();
        $error = array();
        // verifica se os parametros foram informados na url
        if(!isset($parameters["?"]["lat"]) OR empty($parameters["?"]["lat"]) OR
            !isset($parameters["?"]["long"]) OR empty($parameters["?"]["long"])) {
            $error[] = "Latitude e longitude não informados";
        } else {
            try {
                // busca os dados
                $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/getEnderecoLatLong?',['lat'=>$parameters["?"]["lat"], 'long'=>$parameters["?"]["long"]]);
                $result = json_decode($response->getStringBody());
                // organiza o array para a saída esperada
                $data['enderecos'] = $result->endereco;
            } catch (Exception $e){
                $error[]='Não foi possível obter a latitude e longitude. Não foi possível conectar na API.';
            }
        }
        // saída
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }

    }
    /**
     * Altera  ou adiciona a imagem do pedido item exame
     * @return |null
     */
    public function setAgendamentoExameFoto()
    {
        //verifica se é post
        if ($this->request->is(['post', 'put'])) {
            $this->loadModel('AnexosExames');
            $this->loadModel('ItensPedidosExames');
            $foto = $this->request->getData('foto');
            $codigo_item_pedido_exame = (int) $this->request->getData('codigo_item_pedido_exame');
            $codigo_usuario = (int) $this->request->getData('codigo_usuario');
            // verifica se uma foto foi inforamada
            if(empty($foto)){
                $error[] = "Foto não informada";
                $this->set(compact('error'));
                return null;
            }
            // verifica se uma foto foi inforamada
            if(empty($codigo_item_pedido_exame)){
                $error[] = "Codigo do pedido de exame não informado";
                $this->set(compact('error'));
                return null;
            }
            // verifica se uma foto foi inforamada
            if(empty($codigo_usuario)){
                $error[] = "Codigo do usuário não informado";
                $this->set(compact('error'));
                return null;
            }
            // verifica se a ficha clinica existe na tabela antes de inserir ou editar o dados do anexo
            $hasCodigoItemPedidoExame = $this->ItensPedidosExames->find()->where(['codigo'=>$codigo_item_pedido_exame])->first();
            if(empty($hasCodigoItemPedidoExame)){
                $error[] = "Codigo item pedido exame não encontrado";
                $this->set(compact('error'));
                return null;
            }
            // configura a pasta de upload dos arquivos
            $dados = array(
                'file'   => $foto,
                'prefix' => 'nina',
                'type'   => 'base64'
            );
            // envia a foto para o systemstorage
            $url_imagem = Comum::sendFileToServer($dados);
            $imagem_caminho_completo = "";
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});
            // verifica se foi possível obter o caminho da imagem
            if(!empty($caminho_image)) {
                //seta o valor para a imagem que esta sendo criada
                $imagem_caminho_completo = FILE_SERVER.$caminho_image['path'];
                try {
                    // $this->AnexosExames->existsCodigoItemPedidoExame($codigo_item_pedido_exame);
                    $anexos = array(
                        'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                        'caminho_arquivo' => $imagem_caminho_completo,
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'data_inclusao' => date('Y-m-d H:i:s'),
                        'status' => 1,
                        'codigo_empresa' => 1,
                    );
                    $anexos_exames = $this->AnexosExames->find()->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])->first();
                    if(!empty($anexos_exames)) {
                        $anexos['codigo'] = $anexos_exames->codigo;
                        //seta os dados para atualizacao
                        $anexos = $this->AnexosExames->patchEntity($anexos_exames, $anexos);
                    }
                    else {
                        $anexos = $this->AnexosExames->newEntity($anexos);
                    }
                    $exame = $this->AnexosExames->save($anexos);
                    if ($exame) {
                        $data['mensagem'] = "Registro atualizado com sucesso!";
                        $data['foto'] = $imagem_caminho_completo;
                        $data['exame'] = $exame;
                    }
                    else {
                        $error[] = "Erro ao inserir exame!";
                    }
                } catch(Exception $e) {
                    $error[] = "Não foi possível atualizar registro.";
                }
            }
            else {
                $error = "Problemas em enviar a imagem para o file-server";
            }
        }//fim post/put
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }
        // saída
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }

    }
    /**
     * Altera ou adiciona arquivo e laudo no exame / atualiza data e hora de realização e status do exame / Da baixa no exame
     * @return |null
     */
    public function setAgendamentoExame()
    {
        //verifica se é post
        if ($this->request->is(['post', 'put'])) {
            $this->loadModel('AnexosExames');
            $this->loadModel('AnexosLaudos');
            $this->loadModel('ItensPedidosExames');
            $this->loadModel('ItensPedidosExamesBaixa');

            $codigo_item_pedido_exame = (int) $this->request->getData('codigo_item_pedido_exame');
            $codigo_usuario = (int) $this->request->getData('codigo_usuario');
            $data_resultado_exame = $this->request->getData('data_resultado_exame');
            $data_realizacao_exame = $this->request->getData('data_realizacao_exame');
            $hora_realizacao_exame = $this->request->getData('hora_realizacao_exame');
            $laudo = $this->request->getData('laudo');
            $arquivo = $this->request->getData('arquivo_imagem');
            $arquivo_laudo = $this->request->getData('arquivo_laudo');
            $status_resultado = (int) $this->request->getData('status');

            if (!empty($data_realizacao_exame)) {
                $compareceu = 1;
            } else {
                $compareceu = 0;
            }

            // retiradoa pedido do pablo chamado pc24
            // Valida campos
            // if(empty($arquivo)){
            //     $error[] = "Arquivo não informado";
            //     $this->set(compact('error'));
            //     return null;
            // }
            if(empty($codigo_item_pedido_exame)){
                $error[] = "Codigo do pedido de exame não informado";
                $this->set(compact('error'));
                return null;
            }
            if(empty($codigo_usuario)){
                $error[] = "Codigo do usuário não informado";
                $this->set(compact('error'));
                return null;
            }
            // verifica se a ficha clinica existe na tabela antes de inserir ou editar o dados do anexo
            $hasCodigoItemPedidoExame = $this->ItensPedidosExames->find()->where(['codigo'=>$codigo_item_pedido_exame])->first();
            if(empty($hasCodigoItemPedidoExame)){
                $error[] = "Codigo item pedido exame não encontrado";
                $this->set(compact('error'));
                return null;
            }

            // debug($hasCodigoItemPedidoExame);exit;

            // //abrir transacao
            // $conn = ConnectionManager::get('default');
            // $conn->begin();
            //ANEXA ARQUIVO
            $anexou_arquivo = '';
            if(!empty($arquivo)){
                // configura a pasta de upload dos arquivos
                $dados = array(
                    'file'   => $arquivo,
                    'prefix' => 'agendamento',
                    'type'   => 'base64'
                );
                // envia o arquivo para o systemstorage
                $url_imagem = Comum::sendFileToServer($dados);
                $arquivo_caminho_completo = "";
                $caminho_arquivo = array("path" => $url_imagem->{'response'}->{'path'});
                // verifica se foi possível obter o caminho da imagem
                if(!empty($caminho_arquivo)) {
                    //seta o valor para a imagem que esta sendo criada
                    $arquivo_caminho_completo = FILE_SERVER.$caminho_arquivo['path'];

                    try {

                        $anexos = array(
                            'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                            'caminho_arquivo' => $arquivo_caminho_completo,
                            // 'status' => $status,
                            'codigo_empresa' => 1,
                        );
                        $anexos_arquivo = $this->AnexosExames->find()->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])->first();
                        if(!empty($anexos_arquivo)) {
                            $anexos['codigo'] = $anexos_arquivo->codigo;
                            $anexos['codigo_usuario_alteracao'] = $codigo_usuario;
                            $anexos['data_alteracao'] = date('Y-m-d H:i:s');
                            //seta os dados para atualizacao
                            $anexos = $this->AnexosExames->patchEntity($anexos_arquivo, $anexos);
                        }
                        else {
                            $anexos['codigo_usuario_inclusao'] = $codigo_usuario;
                            $anexos['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta os dados para inclusão
                            $anexos = $this->AnexosExames->newEntity($anexos);
                        }
                        $anexou_arquivo = $this->AnexosExames->save($anexos);
                        // $conn->commit();
                    } catch(Exception $e) {
                        $error[] = "Não foi possível atualizar registro.";
                    }
                }
                else {
                    $error = "Problemas em enviar a imagem para o file-server";
                }
            }
            //ANEXA LAUDO
            $anexou_laudo = '';
            if(!empty($arquivo_laudo)){
                // configura a pasta de upload dos arquivos
                $dados = array(
                    'file'   => $arquivo_laudo,
                    'prefix' => 'agendamento',
                    'type'   => 'base64'
                );
                // envia o arquivo para o systemstorage
                $url_imagem = Comum::sendFileToServer($dados);
                $laudo_caminho_completo = "";
                $caminho_laudo = array("path" => $url_imagem->{'response'}->{'path'});
                // verifica se foi possível obter o caminho da imagem
                if(!empty($caminho_laudo)) {
                    //seta o valor para a imagem que esta sendo criada
                    $laudo_caminho_completo = FILE_SERVER.$caminho_laudo['path'];

                    try {

                        $anexos = array(
                            'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                            'caminho_arquivo' => $laudo_caminho_completo,
                            // 'status' => $status,
                            'codigo_empresa' => 1,
                        );
                        $anexos_laudos = $this->AnexosLaudos->find()->where(['codigo_item_pedido_exame' => $codigo_item_pedido_exame])->first();
                        if(!empty($anexos_laudos)) {
                            $anexos['codigo'] = $anexos_laudos->codigo;
                            $anexos['codigo_usuario_alteracao'] = $codigo_usuario;
                            $anexos['data_alteracao'] = date('Y-m-d H:i:s');
                            //seta os dados para atualizacao
                            $anexos = $this->AnexosLaudos->patchEntity($anexos_laudos, $anexos);
                        }
                        else {
                            $anexos['codigo_usuario_inclusao'] = $codigo_usuario;
                            $anexos['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta os dados para inclusão
                            $anexos = $this->AnexosLaudos->newEntity($anexos);
                        }
                        $anexou_laudo = $this->AnexosLaudos->save($anexos);
                        // $conn->commit();
                    } catch(Exception $e) {
                        $error[] = "Não foi possível atualizar registro.";
                    }
                }
                else {
                    $error[] = "Problemas em enviar a imagem para o file-server";
                }
            }
            //retirado a pedido do pablo pc24
            // if($anexou_arquivo){

                //Atualiza itens_pedidos_exames
                $item = array(
                    'codigo'=>$codigo_item_pedido_exame,
                    'codigo_usuario_alteracao' => $codigo_usuario,
                    'data_alteracao' => date('Y-m-d H:i:s'),
                    'data_realizacao_exame' => $data_realizacao_exame,
                    'hora_realizacao_exame' => $hora_realizacao_exame,
                    'compareceu' => $compareceu,
                    'laudo' => $laudo,
                );

                $item = $this->ItensPedidosExames->patchEntity($hasCodigoItemPedidoExame, $item);

                if ($this->ItensPedidosExames->save($item)) {

                    //Baixa do exame
                    // $conn->commit();
                    $params = array(
                        'codigo_itens_pedidos_exames'=>$codigo_item_pedido_exame,
                        'resultado'=>$status_resultado,
                        'data_realizacao_exame' => $data_resultado_exame,
                        'fornecedor_particular' => 0,
                        'pedido_importado'=> 0
                    );
                    $baixa = $this->ItensPedidosExamesBaixa->find()->where(['codigo_itens_pedidos_exames' => $codigo_item_pedido_exame])->first();
                    if(!empty($baixa)) {
                        /*$params['codigo'] = $baixa->codigo;
                        $params['codigo_usuario_alteracao'] = $codigo_usuario;
                        $params['data_alteracao'] = date('Y-m-d H:i:s');
                        //seta os dados para atualizacao
                        $params = $this->ItensPedidosExamesBaixa->patchEntity($baixa, $params);*/
                        $error[] = "Exame já baixado anteriormente!";
                    }
                    else {
                        $params['codigo_usuario_inclusao'] = $codigo_usuario;
                        $params['data_inclusao'] = date('Y-m-d H:i:s');
                        //seta os dados para inclusão
                        $params = $this->ItensPedidosExamesBaixa->newEntity($params);

                        if ($this->ItensPedidosExamesBaixa->save($params)) {

                            //verifica se já foi baixado todos os exames do pedido
                            $codigo_pedido_exame = $hasCodigoItemPedidoExame->codigo_pedidos_exames;
                            $this->PedidosExames->baixaTotalPedidoExame($codigo_pedido_exame);

                            $data['mensagem'] = "Registro atualizado com sucesso!";
                            $data['itemPedidoExame'] = $item;
                            $data['itemPedidoExameBaixa'] = $params;
                            $data['arquivo_imagem'] = $anexou_arquivo;
                            $data['arquivo_laudo'] = $anexou_laudo;
                        } else {
                            //rollback da transacao
                            // $conn->rollback();
                            $error[] = "Erro ao dar baixa no exame!";
                        }
                    }
                    //fim Baixa do exame
                } else {

                    // debug($hasCodigoItemPedidoExame->errors());exit;

                    $error[] = "Erro ao atualizar exame!";
                }
            // }else{
            //     $error[] = "Erro ao anexar arquivo imagem!";
            // }

        }//fim post/put
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }
        // saída
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }

    }
    /**
     * Altera  ou adiciona a imagem da ficha clinica
     * @return |null
     */
    public function setAgendamentoFichaClinicaFoto()
    {
        //verifica se é post
        if ($this->request->is(['post', 'put'])) {
            $this->loadModel('AnexosFichasClinicas');
            $this->loadModel('FichasClinicas');
            $foto = $this->request->getData('foto');
            $codigo_ficha_clinica = (int) $this->request->getData('codigo_ficha_clinica');
            $codigo_usuario = (int) $this->request->getData('codigo_usuario');
            // verifica se uma foto foi inforamada
            if(empty($foto)){
                $error[] = "Foto não informada";
                $this->set(compact('error'));
                return null;
            }
            // verifica se uma foto foi inforamada
            if(empty($codigo_usuario)){
                $error[] = "Codigo do usuário não informado";
                $this->set(compact('error'));
                return null;
            }
            // verifica se a ficha clinica existe na tabela antes de inserir ou editar o dados do anexo
            $hasFichaClinica = $this->FichasClinicas->find()->where(['codigo'=>$codigo_ficha_clinica])->first();
            if(empty($hasFichaClinica)){
                $error[] = "Codigo da ficha clínica não encontrado";
                $this->set(compact('error'));
                return null;
            }
            // configura a pasta de upload dos arquivos
            $dados = array(
                'file'   => $foto,
                'prefix' => 'nina',
                'type'   => 'base64'
            );
            // envia a foto para o systemstorage
            $url_imagem = Comum::sendFileToServer($dados);
            $imagem_caminho_completo = "";
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});
            // verifica se foi possível obter o caminho da imagem
            if(!empty($caminho_image)) {
                //seta o valor para a imagem que esta sendo criada
                $imagem_caminho_completo = FILE_SERVER.$caminho_image['path'];
                try {
                    $anexos = array(
                        'codigo_ficha_clinica' => (int) $codigo_ficha_clinica,
                        'caminho_arquivo' => $imagem_caminho_completo,
                        'codigo_usuario_inclusao' => $codigo_usuario,
                        'data_inclusao' => date('Y-m-d H:i:s'),
                        'status' => 1,
                        'codigo_empresa' => 1,
                    );
                    $anexos_ficha_clinica = $this->AnexosFichasClinicas->find()->where(['codigo_ficha_clinica' => $codigo_ficha_clinica])->first();
                    if(!empty($anexos_ficha_clinica)) {
                        $anexos['codigo'] = $anexos_ficha_clinica->codigo;
                        //seta os dados para atualizacao
                        $anexos = $this->AnexosFichasClinicas->patchEntity($anexos_ficha_clinica, $anexos);
                    }
                    else {
                        $anexos = $this->AnexosFichasClinicas->newEntity($anexos);
                    }
                    if ($this->AnexosFichasClinicas->save($anexos)) {
                        $data['mensagem'] = "Registro atualizado com sucesso!";
                        $data['foto'] = $imagem_caminho_completo;
                    }
                    else {
                        $error[] = "Erro ao inserir ficha clínica!";
                    }
                } catch(Exception $e) {
                    $error[] = "Não foi possível atualizar registro.";
                }
            }
            else {
                $error = "Problemas em enviar a imagem para o file-server";
            }
        }//fim post/put
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }
        // saída
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }

    }
    /**
     * consultas method
     *
     * metodo para pegar o detalhe da consulta
     *
     * @return \Cake\Http\Response|null
     */
    public function consultasDetalhes( $codigo_item_pedido_exame )
    {
        // Busca os dados da consulta
        $data = $this->PedidosExames->consultas( $codigo_item_pedido_exame );
        $this->loadModel('FornecedoresAvaliacoes');
        $avaliacao = $this->FornecedoresAvaliacoes->getFornecedorNota($data['codigo_fornecedor']);
        $nota = isset($avaliacao['pontuacao_arredondada']) ? $avaliacao['pontuacao_arredondada'] : 0;
        $total_avaliacoes = isset($avaliacao['quantidade_avaliacoes']) ? $avaliacao['quantidade_avaliacoes'] : 0;
        $data['nota'] = $nota;
        $data['total_avaliacoes'] = $total_avaliacoes;
        $data['avaliacao'] = $avaliacao;
        $this->set(compact('data'));

    }//fim consultasDetalhes
    private function getDadosUsuario($codigo_usuario)
    {
        //carrega a model de usuario
        $this->loadModel('Usuario');
        //pega o usuario para recuperar o codigo do funcionario
        $this->usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);

    }//fim getDadasousaurio
    /**
     * pelo codigo do usuario iremos identificar o funcionario apresentando as ultimas 10 consultas
     *
     * @param  int $codigo_usuario    codigo do usuário
     * @param  int $quantidade        quantidade de registros
     * @return array
     */
    public function getConsultaHistorico($codigo_usuario = null, $quantidade = 10)
    {
        if (empty($codigo_usuario)) {
            $data = ['error'=>'Parametro codigo_usuario inválido.'];
            return $this->responseJson($data);
        }
        // buscar dados do usuario
        $this->getDadosUsuario($codigo_usuario);
        if(empty($this->usuario)){
            $data = ['error'=>'Parametro codigo_usuario inválido ou usuário não encontrado.'];
            return $this->responseJson($data);
        }
        // verifica se possui algum vinculo com empresas
        $this->Funcionarios = $this->loadModel('Funcionarios');
        $codigo_cliente_vinculado = $this->Funcionarios->obterCodigoClienteVinculado($this->usuario->cpf);
        //verifica se o codigo_funcionario existe
        if(!empty($codigo_cliente_vinculado) && !empty($this->usuario) && $this->usuario->codigo_funcionario) {
            // obter ultimas consultas
            $data = $this->PedidosExames->ultimas_consultas($codigo_cliente_vinculado, $this->usuario->codigo_funcionario, $quantidade)->all();
        } else {
            $data = ['error' => "Este usuario nao existe relacionamento com um funcionario!"];
        }
        return $this->responseJson($data); // padronizando resultado na controller

    }//fim getConsultaHistorico
    /**
     * metodo para pegar os dados das proximas consultas ou consultas agendadas
     *
     * @param  int $codigo_usuario    codigo do usuario
     * @return \Cake\Http\Response
     */
    public function getConsultasAgendadas( $codigo_usuario = null )
    {
        if (empty($codigo_usuario)) {
            $data = ['error'=>'Parametro codigo_usuario inválido.'];
            return $this->responseJson($data);
        }
        // buscar dados do usuario
        $this->getDadosUsuario($codigo_usuario);
        if(empty($this->usuario)){
            $data = ['error'=>'Parametro codigo_usuario inválido ou usuário não encontrado.'];
            return $this->responseJson($data);
        }
        // verifica se possui algum vinculo
        $this->Funcionarios = $this->loadModel('Funcionarios');
        $codigo_cliente_vinculado = $this->Funcionarios->obterCodigoClienteVinculado($this->usuario->cpf);
        //verifica se o codigo_funcionario existe
        if(!empty($codigo_cliente_vinculado) && !empty($this->usuario) && $this->usuario->codigo_funcionario) {
            $data = $this->PedidosExames->proximas_consultas($codigo_cliente_vinculado, $this->usuario->codigo_funcionario);
        } else {
            $data = ['error' => "Este usuario nao existe relacionamento com um funcionario!"];
        }
        return $this->responseJson($data); // padronizando resultado na controller

    }//fim getConsultasAgendadas
    /**
     * metodo para pegar as proximas consultas e historicas
     *
     * @param  int $codigo_usuario    codigo de usuario
     * @return \Cake\Http\Response
     */
    public function getAgendamentos($codigo_usuario)
    {
        if (empty($codigo_usuario)) {
            $data = ['error'=>'Parametro codigo_usuario inválido.'];
            return $this->responseJson($data);
        }
        // buscar dados do usuario
        $this->getDadosUsuario($codigo_usuario);
        if(empty($this->usuario)){
            $data = ['error'=>'Parametro codigo_usuario inválido ou usuário não encontrado.'];
            return $this->responseJson($data);
        }
        // verifica se possui algum vinculo
        $this->Funcionarios = $this->loadModel('Funcionarios');
        $codigo_cliente_vinculado = $this->Funcionarios->obterCodigoClienteVinculado($this->usuario->cpf);
        //verifica se o codigo_funcionario existe
        if(!empty($codigo_cliente_vinculado) && !empty($this->usuario) && $this->usuario->codigo_funcionario) {

            //proximas consultas que é maior ou igual que o dia de hoje
            // $param_conditions_prx_consultas[] = array();
            $param_conditions_prx_consultas[] = "ItemPedidoExame.data_agendamento >= '" . date('Y-m-d')."' ";
            $proximos_agendamentos = $this->PedidosExames->proximas_consultas($codigo_cliente_vinculado, $this->usuario->codigo_funcionario,$param_conditions_prx_consultas)->toArray();

            foreach ($proximos_agendamentos as $agendamento) {

                if(isset($agendamento['data_agendamento'])
                    && !empty($agendamento['data_agendamento'])){
                        // esta chegando do banco como YYYY-mm-dd e foi mantido
                        // formate aqui se necessario
                } else {
                    // inicializa por um valor que o app espera como nulo
                    $agendamento['data_agendamento'] = null;
                }
                // pode retornat nulo e o concat de algum sql ta colocando 0 em algumas horas que são nulas no banco
                // pode retornar hora com 3 digitos
                // pode retornar texto undef no lugar da hora

                if(isset($agendamento['hora_agendamento'])
                    && !empty($agendamento['hora_agendamento'])
                        && $agendamento['hora_agendamento'] != '0'
                        && $agendamento['hora_agendamento'] != 'undef'
                        && is_numeric($agendamento['hora_agendamento'])){

                        // deve chegar aqui no hormato Hm ex. 1806 , 2032, se for preciso
                        // formate a hora aqui se for preciso retornar com dois pontos por exemplo

                        if ( strlen($agendamento['hora_agendamento']) == 3){
                            $agendamento['hora_agendamento'] = '0'.$agendamento['hora_agendamento'];
                        }

                } else {
                    // inicializa por um valor que o app espera como nulo
                    $agendamento['hora_agendamento'] = null;
                }


                $agendamento['reponder_atraves_lyn'] = false;
                $agendamento['formulario_perguntas'] = null;
                $agendamento['formulario_respostas'] = null;

                if($agendamento['codigo_exame'] == 27){
                    $avaliacao = $this->PedidosExames->getUsuariosResponderExame($codigo_usuario);

                    if($avaliacao && empty($agendamento['resultado'])){
                        $agendamento['reponder_atraves_lyn'] = true;
                        $agendamento['formulario_perguntas'] = "/psicossocial/perguntas/$codigo_usuario";
                        $agendamento['formulario_respostas'] = "/psicossocial/responder/$codigo_usuario";
                    }
                }
            }

            //historico
            $quantidade = 20;
            // $historico_agendamentos = $this->PedidosExames->ultimas_consultas($codigo_cliente_vinculado, $this->usuario->codigo_funcionario, $quantidade)->all();
            //consultas atrasadas são as consultas que é agendada como menor que hoje e deve estar no historico
            $param_conditions_consultas_atrasadas[] = "ItemPedidoExame.data_agendamento < '" . date('Y-m-d')."' ";

            $historico_agendamentos = $this->PedidosExames->proximas_consultas($codigo_cliente_vinculado, $this->usuario->codigo_funcionario,$param_conditions_consultas_atrasadas,$quantidade)->toArray();

            foreach ($historico_agendamentos as $key => $value) {

                if(isset($value['data_agendamento'])
                    && !empty($value['data_agendamento'])){
                        // esta chegando do banco como YYYY-mm-dd e foi mantido
                        // formate aqui se necessario
                } else {
                    // inicializa por um valor que o app espera como nulo
                    $historico_agendamentos[$key]['data_agendamento'] = null;
                }
                // pode retornat nulo e o concat de algum sql ta colocando 0 em algumas horas que são nulas no banco
                // pode retornar hora com 3 digitos
                // pode retornar texto undef no lugar da hora
                if(isset($value['hora_agendamento'])
                    && !empty($value['hora_agendamento'])
                        && $value['hora_agendamento'] != '0'
                        && $value['hora_agendamento'] != 'undef'
                        && is_numeric($value['hora_agendamento'])){

                        // deve chegar aqui no hormato Hm ex. 1806 , 2032, se for preciso
                        // formate a hora aqui se for preciso retornar com dois pontos por exemplo

                        if ( strlen($value['hora_agendamento']) == 3){
                            $historico_agendamentos[$key]['hora_agendamento'] = '0'.$value['hora_agendamento'];
                        }

                } else {
                    // inicializa por um valor que o app espera como nulo
                    $historico_agendamentos[$key]['hora_agendamento'] = null;
                }
            }

            //arruma os dados
            $data['ProximosAgendamentos'] = $proximos_agendamentos;
            $data['HistoricoAgendamentos'] = $historico_agendamentos;
            //pega os pedidos para saber se tem algum aberto ainda
            // $podeAgendar = $this->PedidosExames->getPodeAgendar($this->usuario->codigo_funcionario);
            //verifica se existe ppra e pcmso configurado para este funcionario deixar ele agendar ou não
            //para verificar se tem ppra precisa do codigo de configuração da funcionario setores cargos
            $this->loadModel('ClienteFuncionario');
            //pega a função do funcionario na funcionario_setores_cargo
            $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($this->usuario->codigo_funcionario,$codigo_cliente_vinculado);
            $podeAgendar = false;
            //varre as empresas para saber se tem ppra configurado ou não
            foreach($dados_matricula_funcao as $dados) {
                //verifica se tem ppra ou não
                $podeAgendar = $this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo']);
            }
            //para apresentar o botão de agendamento na tela do app
            $data['PodeAgendar'] = $podeAgendar;
        } else {
            $data = ['error' => "Este usuario nao existe relacionamento com um funcionario!"];
        }
        return $this->responseJson($data);
    }//fim getAgendamentos
    /**
     * metodo para pegar as proximas consultas e historicas
     *
     * @param  int $codigo_usuario      codigo do usuario
     * @return \Cake\Http\Response
     */
    public function getAgendamentosHistorico($codigo_usuario, $cpf=null)
    {
        if (empty($codigo_usuario)) {
            $data = ['error'=>'Parametro codigo_usuario inválido.'];
            return $this->responseJson($data);
        }else{
            $this->Funcionarios = $this->loadModel('Funcionarios');
            if (empty($cpf)) {
                // buscar dados do usuario
                $this->getDadosUsuario($codigo_usuario);
                if(empty($this->usuario)){
                    $data = ['error'=>'Parametro codigo_usuario inválido ou usuário não encontrado.'];
                    return $this->responseJson($data);
                }else{
                    $cpf = $this->usuario->cpf;
                    $codigo_funcionario = $this->usuario->codigo_funcionario;
                }
            }else{

                $codigo_funcionario = $this->Funcionarios->getCodigoFuncionario($cpf)[0]->codigo;
            }

            // verifica se possui algum vinculo
            //$codigo_cliente_vinculado = $this->Funcionarios->obterCodigoClienteVinculado($cpf);
            $codigo_cliente_vinculado = $this->getCodigosClientesFuncionario($codigo_funcionario);
            //verifica se o codigo_funcionario existe
            if(!empty($codigo_cliente_vinculado) && $codigo_funcionario) {
                //historico
                $quantidade = 2;
                $data = $this->PedidosExames->ultimas_consultas($codigo_cliente_vinculado, $codigo_funcionario, $quantidade);
                $data = $this->paginate($data);
            } else {
                $data = ['error' => "Este código de usuário ou cpf não tem relacionamento com um funcionario!"];
            }
        }
        return $this->responseJson($data);
    }//fim getAgendamentos
    /**
     * metodo para pegar os detalhes da consulta
     *
     * @param  int $codigo_id_agendamento
     * @return \Cake\Http\Response
     */
    public function getAgendamentosDetalhe($codigo_id_agendamento)
    {
        $this->loadModel("FornecedoresAvaliacoes");

        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_id_agendamento)) {
            $data = ['error'=>'Parametro codigo_id_agendamento inválido.'];
            return $this->responseJson($data);
        }

        $data = array();

        //pega os dados
        $dados = $this->PedidosExames->consultas($codigo_id_agendamento);

        // verifica se existe os dados
        if (empty($dados)) {
            $data = ['error'=>'Pedido de Exame não encontrado.'];
            return $this->responseJson($data);
        }


        $this->loadModel('FornecedoresHorario');

        //pega os dados de horario da clinica
        $fields = ['codigo_fornecedor', 'de_hora','ate_hora','dias_semana','horario_atendimento_diferenciado'];
        $fornecedor_horario = $this->FornecedoresHorario->find()->select($fields)->where(['codigo_fornecedor' => $dados->codigo_fornecedor])->group(['codigo_fornecedor', 'de_hora,ate_hora,dias_semana','horario_atendimento_diferenciado'])->all()->toArray();

        $horarios = array();

        // inicializa
        $abertura = array(
            'horario_atendimento_atual' => null,
            'abertura' => false
        );

        $str_dia_semana_hoje = (new DatetimeUtil())->dayOfWeek(true);

        //verifica se existe os dados de hr do fornecedor
        if(!empty($fornecedor_horario)) {

            // debug($fornecedor_horario);exit;

            //varre os horarios do fornecedor
            foreach ($fornecedor_horario as $key => $fh) {

                $dias_semana = $fh['dias_semana'];

                //verifica se deve explodir os dias da semana
                if(strpos($dias_semana,',')) {

                    $arr_dias_semana = explode(',', $dias_semana);

                    //monta o array com todos os dias
                    foreach ($arr_dias_semana as $key => $diaSemana) {

                        //formata
                        $diaSemana = Comum::diaDaSemanaExtenso($diaSemana);

                        if($diaSemana == $str_dia_semana_hoje) {

                            $abertura['horario_atendimento_atual'] = Comum::formataHora($fh['de_hora']) .' - '. Comum::formataHora($fh['ate_hora']);

                            $abe = (new DatetimeUtil())->nowInTimeInterval($fh['de_hora'], $fh['ate_hora'], 2) ? true : false;

                            if($abe) {
                               $abertura['abertura'] = $abe;
                            }
                        }
                        $horarios[] = array(
                            "dia_semana" => $diaSemana,// "Segunda-Feira",
                            "horario_inicio" => Comum::formataHora($fh['de_hora']),// "08:00",
                            "horario_fim" => Comum::formataHora($fh['ate_hora']),// "17:00"
                        );
                    }//fim foreach arr dias semana
                }//fim spos
                else {
                    //formata
                    $dias_semana = Comum::diaDaSemanaExtenso($dias_semana);

                    if($dias_semana == $str_dia_semana_hoje) {

                        $abertura['horario_atendimento_atual'] = Comum::formataHora($fh['de_hora']) .' - '. Comum::formataHora($fh['ate_hora']);

                        $abe = (new DatetimeUtil())->nowInTimeInterval($fh['de_hora'], $fh['ate_hora'], 2) ? true : false;

                        if($abe) {
                            $abertura['abertura'] = $abe;
                        }
                    }
                    $horarios[] = array(
                        "dia_semana" => $dias_semana,// "Segunda-Feira",
                        "horario_inicio" => Comum::formataHora($fh['de_hora']),// "08:00",
                        "horario_fim" => Comum::formataHora($fh['ate_hora']),// "17:00"
                    );
                }
            }//fim foreach fornecedor horario
        }//fim if fornecedor horario
        //formata a saida

        $data = array(
            'agendamento_detalhe' => array(
                'data' => $dados->data,
                'numero_pedido' => $dados->codigo_pedido_exame,
                'solicitante' => $dados->nome_fantasia_solicitante,
                'empresa' => array(
                    'razao_social' => $dados->razao_social_solicitante,
                    "nome_fantasia" => $dados->nome_fantasia_solicitante,
                    "cnpj" => $dados->cnpj_solicitante
                ),
                "seus_dados" => array(
                    "cpf" => $dados->cpf ,// "01234567890",
                    "data_nascimento" => $dados->data_nascimento ,// "09121992",
                    "idade" => $dados->idade ,// 27,
                    "setor" => $dados->setor ,// "Marketing",
                    "cargo" => $dados->cargo ,// "Social Media"
                ),
                "sobre_exame" => array(
                    "medico_reponsavel" => $dados->medico_responsavel,// "Dr Olva Bittencourt",
                    "grade_exames" => $dados->exame,
                    "preparo_exame" => $dados->preparo_exame,
                    "imagem" => $dados->imagem_exame
                ),
                "local_agendamento" => array(
                    "nome" => $dados->nome_credenciado,// ,
                    "lat" => $dados->lat ,// "-29.661655",
                    "long" => $dados->long ,// "-51.143918",
                    "endereco" => $dados->endereco ,// "Av Assis Brasil, 1548, Passo d'Areia, Porto Alegre - RS",
                    "avaliacao" => $dados->avaliacao ,// 4.3,
                    "total_avaliacoes" => $dados->total_avaliacoes ,// 6,
                    "telefone_clinica" => $dados->telefone ,// 555135950055,
                    "email_clinica" => $dados->email,// "email@clinica.com.br",
                    "abertura" => $abertura,
                    "horario_funcionamento" => $horarios,
                    "codigo_fornecedor" => $dados->codigo_fornecedor,
                    "imagens" => array(
                        array("ordem" => '',"imagem" => "")
                    )
                )
            ),
        );

        $avaliacao = $this->FornecedoresAvaliacoes->getFornecedorNota($dados->codigo_fornecedor);
        $nota = isset($avaliacao['pontuacao_arredondada']) ? $avaliacao['pontuacao_arredondada'] : 0;
        $total_avaliacoes = isset($avaliacao['quantidade_avaliacoes']) ? $avaliacao['quantidade_avaliacoes'] : 0;
        $data['agendamento_detalhe']["local_agendamento"]['nota'] = $nota;
        $data['agendamento_detalhe']["local_agendamento"]['total_avaliacoes'] = $total_avaliacoes;
        $data['agendamento_detalhe']["local_agendamento"]['avaliacao'] = $avaliacao;

        // acrescentando imagens da clinica se existirem
        $this->loadModel('FornecedorFotos');
        $fornecedor_fotos = $this->FornecedorFotos->obterImagens($dados->codigo_fornecedor);
        if($fornecedor_fotos && gettype($fornecedor_fotos) == 'object'){
            $data['agendamento_detalhe']["local_agendamento"]['imagens'] = [];
            foreach ($fornecedor_fotos->toArray() as $key => $value) {
                $data['agendamento_detalhe']["local_agendamento"]['imagens'][] = array('ordem'=>$value['codigo'], 'imagem'=> $value['caminho_arquivo']);
            }

        }

        return $this->responseJson($data);
    }//fim getAgendamentosDetalhe
    /**
     * metodo para validar o usuario se pode criar um exame
     *
     * @param  int $codigo_usuario      codigo do usuario
     * @return array
     */
    private function validaUsuarioPedidosExames($codigo_usuario, $codigo_documento=null)
    {

        $return = array();
        $clientes = array();
        if(!empty($codigo_documento)){

            $this->loadModel('Funcionarios');

            //Pega código do funcionário
            $codigo_funcionario = $this->Funcionarios->getCodigoFuncionario($codigo_documento);

            if(!empty($codigo_funcionario)) {

                $codigo_funcionario = $codigo_funcionario[0]->codigo;
                $clientes = $this->getCodigosClientesFuncionario($codigo_funcionario);
            }
        }else{
            //carrega os dados do usuario
            $this->getDadosUsuario($codigo_usuario);
            //carrega os dados do usuario
            if(!empty($this->usuario->codigo_funcionario)) {
                $clientes = $this->getCodigosClientesVinculados($codigo_usuario);
            }
        }
        if(empty($clientes)) {
            $return = "Não encontramos nenhuma empresa vinculada!";
        }
        return $return;
    }//fim validaUsuarioPedidosExames
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
        if(!empty($dados_usuario['cliente'])) {
            //varre os clientes
            foreach($dados_usuario['cliente'] AS $cli) {
                $clientes[] = $cli['codigo'];
            }//fim foreach clientes
        }
        return $clientes;
    }// fim getCodigosClientesVinculados
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
        if(!empty($dados)) {
            //varre os clientes
            foreach($dados AS $cli) {
                $clientes[] = $cli['codigo_cliente'];
            }//fim foreach clientes
        }
        return $clientes;
    }// fim getCodigosClientesFuncionario
    /**
     * [getCriarAgendamento description]
     *
     * metodo para criar agendamento trazer os exames da grade
     *
     * definicao de codigos para trabalhar com os tipos de exames
     *
     * 1-> periodico
     * 2-> retorno ao trabalho
     * 3-> mudança de função
     * 4-> pontual
     *
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getCriarAgendamento($codigo_usuario, $codigo_documento=null)
    {
        //variavel auxiliar
        $data = array();
        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_usuario)) {
            $error[] = 'Parametro codigo_usuario inválido.';
        }
        else {
            //valida se o usuario pode emitir um pedido de exames
            $validar = $this->validaUsuarioPedidosExames($codigo_usuario, $codigo_documento);
            if(!empty($validar)) {
                $error = $validar;
            }
            else{
                if(!empty($codigo_documento)){

                    $this->loadModel('Funcionarios');

                    //Pega código do funcionário
                    $codigo_funcionario = $this->Funcionarios->getCodigoFuncionario($codigo_documento);

                    if(!empty($codigo_funcionario)) {
                        $codigo_funcionario = $codigo_funcionario[0]->codigo;
                        $clientes = $this->getCodigosClientesFuncionario($codigo_funcionario);
                    }
                }else{

                    //carrega os dados do usuario
                    $this->getDadosUsuario($codigo_usuario);
                    //carrega os dados do usuario
                    if(!empty($this->usuario->codigo_funcionario)) {
                        $codigo_funcionario = $this->usuario->codigo_funcionario;
                        $clientes = $this->getCodigosClientesVinculados($codigo_usuario);
                    }
                }
                $this->loadModel('ClienteFuncionario');
                //pega a função do funcionario na funcionario_setores_cargo
                //$dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($this->usuario->codigo_funcionario,$clientes);
                $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($codigo_funcionario, $clientes);
                //verifica se existe alguma matricula para este usuario
                if(!empty($dados_matricula_funcao)) {
                    //lista pcmso
                    $lista_pcmso = array();
                    $retorno_exames = array();

                    //codifica o encoding
                    $iconv = new EncodingUtil();

                    //varre as matriculas do funcionario
                    foreach ($dados_matricula_funcao as $dados) {
                        //verifica se tem ppra
                        if(!$this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo'])) {
                            $error[] = "Não existe PPRA para este funcionário da empresa: " . $dados['Cliente']['nome_fantasia'];
                        }
                        else {
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
                            $exames_periodicos = array();
                            $exames_retorno = array();
                            $exames_mudanca = array();
                            //monta retorno de exames pmcso
                            $cliente_pcmso[$dados['Cliente']['codigo']] = array(
                                'codigo_cliente' => $dados['Cliente']['codigo'],
                                'nome_cliente' => $iconv->convert($dados['Cliente']['nome_fantasia']),
                            );
                            //verifica se tem pedido ocupacional aberto
                            //$pedido_periodico_aberto = $this->PedidosExames->getPodeAgendarPeriodico($this->usuario->codigo_funcionario);
                            $pedido_periodico_aberto = $this->PedidosExames->getPodeAgendarPeriodico($codigo_funcionario);
                            //se retornar true significa que não tem periodico aberto
                            if($pedido_periodico_aberto) {
                                //ocupacionais pega os exames do pcmso
                                //pega a lista de pcmso da configuracao do funcionario setores cargos
                                $dados_pcmso = $this->PedidosExames->lista_exames_pcmso($dados['FuncionarioSetorCargo']['codigo'],$dados['Cliente']['codigo']);
                                //varre os dados de pcmso com os exames por cliente
                                foreach($dados_pcmso AS $dpcmso) {
                                    //pega os dados dos tipos de exames
                                    if($dpcmso['exame_periodico'] == '1') {
                                        //monta o array de periodico
                                        $exames_pcmso_periodico[$dados['Cliente']['codigo']] = array(
                                            "codigo_solicitacao_exame" => 1,
                                            "descricao" => "Periódico",
                                        );
                                        if($dpcmso['codigo_exame'] == 52) {
                                            $exames_necessarios_periodico_aso = array(
                                                "codigo_exame" => $dpcmso['codigo_exame'],
                                                "exame" => $dpcmso['exame'],
                                                "aso" => true
                                            );
                                        }
                                        else {
                                            $exames_necessarios_periodico[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                                "codigo_exame" => $dpcmso['codigo_exame'],
                                                "exame" => $dpcmso['exame'],
                                                "aso" => false
                                            );
                                        }
                                    }//fim periodico
                                    // //exame retorno
                                    // if($dpcmso['exame_retorno'] == '1') {
                                    //     //monta o array de retorno
                                    //     $exames_pcmso_retorno[$dados['Cliente']['codigo']] = array(
                                    //         "codigo_solicitacao_exame" => 2,
                                    //         "descricao" => "Retorno ao Trabalho",
                                    //     );
                                    //     $exames_necessarios_retorno[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                    //         "codigo_exame" => $dpcmso['codigo_exame'],
                                    //         "exame" => $dpcmso['exame']
                                    //     );
                                    // }//fim exame retorno
                                    // //verifica se tem mudanca de funcao
                                    // if($dpcmso['exame_mudanca'] == '1') {
                                    //     //monta o array de mudança de função
                                    //     $exames_pcmso_mudanca[$dados['Cliente']['codigo']] = array(
                                    //         "codigo_solicitacao_exame" => 3,
                                    //         "descricao" => "Mudança de cargo",
                                    //     );
                                    //     $exames_necessarios_mudanca[$dados['Cliente']['codigo']]["exames_necessarios"][] = array(
                                    //         "codigo_exame" => $dpcmso['codigo_exame'],
                                    //         "exame" => $dpcmso['exame']
                                    //     );
                                    // }//fim mudanca
                                }//fim foreach
                                $exames_necessarios_periodico[$dados['Cliente']['codigo']]["exames_necessarios"][] = $exames_necessarios_periodico_aso;
                                //junta os arrays para deixar corretamente a saida da lista de exames por empresa
                                if(isset($exames_pcmso_periodico[$dados['Cliente']['codigo']]) && isset($exames_necessarios_periodico[$dados['Cliente']['codigo']])) {
                                    $exames_periodicos = array_merge($exames_pcmso_periodico[$dados['Cliente']['codigo']],$exames_necessarios_periodico[$dados['Cliente']['codigo']]);
                                }
                                // if(isset($exames_pcmso_retorno[$dados['Cliente']['codigo']]) && isset($exames_necessarios_retorno[$dados['Cliente']['codigo']])) {
                                //     $exames_retorno = array_merge($exames_pcmso_retorno[$dados['Cliente']['codigo']],$exames_necessarios_retorno[$dados['Cliente']['codigo']]);
                                // }
                                // if(isset($exames_pcmso_mudanca[$dados['Cliente']['codigo']]) && isset($exames_necessarios_mudanca[$dados['Cliente']['codigo']])) {
                                //     $exames_mudanca = array_merge($exames_pcmso_mudanca[$dados['Cliente']['codigo']],$exames_necessarios_mudanca[$dados['Cliente']['codigo']]);
                                // }
                            }
                            //verifica se tem dados na configuração
                            if(isset($cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente'])) {
                                $consulta_pontual = $this->getPontual($codigo_funcionario, $codigo_cliente);

                                //verifica se existe exames disponiveis pcmso
                                $exames_disponiveis = array();
                                if(!empty($exames_periodicos)) {
                                    $exames_disponiveis = array(
                                        $exames_periodicos,
                                        // $exames_retorno,
                                        // $exames_mudanca
                                    );
                                }
                                //monta a lista do exames pcmso
                                $lista_pcmso['cliente'][] = array(
                                    'codigo_cliente' => $cliente_pcmso[$dados['Cliente']['codigo']]['codigo_cliente'],
                                    'nome_cliente' => $cliente_pcmso[$dados['Cliente']['codigo']]['nome_cliente'],
                                    'exames_disponiveis' => $exames_disponiveis,
                                    "consulta_pontual" => (isset($consulta_pontual['consulta_pontual'])) ? $consulta_pontual['consulta_pontual'] : []
                                );
                            }
                        }//fim if existe ppra
                    }//fim foreach dados matricula funcao
                    //retorna a lista de exames do pcmso
                    $data = $lista_pcmso;
                    // debug($lista_pcmso);
                    // exit;
                }//fim dados_matricula_funcao
                else {
                    $error[] = "Não encontramos nenhuma matricula/função as empresa(s) vinculada(s)!";
                }
            }//fim validausuariopedidosexames
        }//fim else codigo_usuario
        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            $this->set(compact('data'));
        }
    }//fim getCriarAgendamento
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
        if(!empty($dados_ppra)) {
            return true;
        }
        return false;
    }//FINAL FUNCTION valida_pedido_exame_ppra
    // /**
    //  * [atualiza_lista_exames_grupo description]
    //  *
    //  * metodo para validar os exames de pcmso do funcionario setor e cargo
    //  *
    //  * @param  [type] $codigo_funcionario_setor_cargo [description]
    //  * @return [type]                                 [description]
    //  */
    // public function lista_exames_pcmso($codigo_funcionario_setor_cargo, $codigo_cliente_matriz)
    // {
    //     $this->loadModel('FuncionarioSetoresCargos');
    //     $arr_exames = array();
    //     //pega onde o funcionario esta alocado
    //     $codigo_cliente_alocacao = $this->FuncionarioSetoresCargos->find()->select(['codigo_cliente_alocacao'])->where(['codigo' => $codigo_funcionario_setor_cargo])->first();
    //     $codigo_cliente = $codigo_cliente_alocacao['codigo_cliente_alocacao'];
    //     //Recupera os exames do PCMSO aplicados para unidade + setor + cargo de alocação do funcionário
    //     $itens_exames = $this->FuncionarioSetoresCargos->retornaExamesNecessarios($codigo_funcionario_setor_cargo);
    //     // adiciona exames na lista
    //     if(count($itens_exames)) {
    //         //varre os itens de exames
    //         foreach($itens_exames as $key => $item) {
    //             /**
    //              * Verifica se existe assinatura e recupera o valor do exame
    //              * Inicialmente consulta a unidade de alocação se não encontrar consulta a matriz (Grupo Econômico)
    //              */
    //             $item['assinatura'] = $this->PedidosExames->verificaExameTemAssinatura($item['codigo_servico'],$codigo_cliente, $codigo_cliente_matriz);
    //             //Verifica se existe fornecedor no cliente de alocação (exame na lista de preços do fornecedor)
    //             $fornecedores = $this->PedidosExames->verificaExameTemFornecedor($item['codigo_servico'],$codigo_cliente);
    //             //verifica se tem fornecedor
    //             if(count($fornecedores) > 0) {
    //                 $item['fornecedores'] = 1;
    //             }
    //             else {
    //                 $item['fornecedores'] = 0;
    //             }
    //             //grava sessao com todos os exames do PCMSO (até os sem valor de assinatura)
    //             $arr_exames[] = $item;
    //         }//fim foreach dos itens de exames
    //     }//fim count itens_exames
    //     return $arr_exames;
    // }//FINAL FUNCTION atualiza_lista_exames_grupo

    /**
     * [getClinicaDisponibilidade description]
     *
     * metodo para pegar as datas da agenda e disponibilidade
     *
     * @param  [type] $codigo_usuario    [description]
     * @param  [type] $codigo_fornecedor [description]
     * @param  [type] $codigo_exame      [description]
     * @return [type]                    [description]
     */
    public function getClinicaDisponibilidade($codigo_usuario, $codigo_cliente, $codigo_fornecedor, $codigo_exame)
    {
        //variavel auxiliar
        $data = array();
        $error = array();
        //valida os paramentros
        if (empty($codigo_usuario)) {
            $error[] = 'Parametro codigo_usuario inválido.';
        }
        if (empty($codigo_cliente)) {
            $error[] = 'Parametro codigo_cliente inválido.';
        }
        if (empty($codigo_fornecedor)) {
            $error[] = 'Parametro codigo_fornecedor inválido.';
        }
        if (empty($codigo_exame)) {
            $error[] = 'Parametro codigo_exame inválido.';
        }
        //verifica se houve algum error
        if(empty($error)) {
            //carrega os dados do usuario
            $this->getDadosUsuario($codigo_usuario);
            //verifica se existe codigo funcionario
            if(empty($this->usuario->codigo_funcionario)) {
                $error[] = "Usuario não encontrado!";
            }
            else{
                $this->loadModel('ClienteFuncionario');
                //pega a função do funcionario na funcionario_setores_cargo
                $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($this->usuario->codigo_funcionario,$codigo_cliente);
                //verifica se existe alguma matricula para este usuario
                if(!empty($dados_matricula_funcao)) {
                    //instancia as models
                    $this->loadModel('Exames');
                    $this->loadModel('FornecedoresGradeAgenda');
                    $this->loadModel('AgendamentoExames');
                    //pega o codigo cliente funcionario da matricula
                    $codigo_cliente_funcionario = $dados_matricula_funcao[0]['codigo'];
                    //variaveis auxiliares
                    $datas_disponiveis = array();
                    $data_inicio = date("Ymd");
                    $data_fim = date('Ymd', strtotime("+60 days",strtotime(str_replace("/", "-", $data_inicio))));
                    //pega o codigo do servico pelo exame
                    $exames = $this->Exames->find()->where(['codigo' => $codigo_exame])->first();
                    $codigo_servico = $exames->codigo_servico;
                    // verifica se existe uma grade cadastrada para este serviço
                    $verifica_grade_especifica = $this->FornecedoresGradeAgenda->retorna_grade_especifica($codigo_fornecedor, $codigo_servico);
                    // existe dias habilitados na grade cadastrada para este servico ?
                    $lista_grade_agenda = array();
                    $lista_grade_agenda_codigo = array();
                    $lista_datas_disponiveis = array();
                    // se existir registro monta grande
                    if(isset($verifica_grade_especifica['ListaDePrecoProdutoServico']['codigo_servico']) && ($verifica_grade_especifica['ListaDePrecoProdutoServico']['codigo_servico'] == $codigo_servico)) {
                        // retorna grade de agendamento para este serviço
                        $verifica_agenda = $this->FornecedoresGradeAgenda->retorna_agenda_especifica($codigo_fornecedor, $codigo_servico);
                        // retorna quais exames já foram agendados
                        $agendados = array();
                        //pega os agendamentos dos pedidos (datas ocupadas)
                        $verifica_agendados = $this->AgendamentoExames->retorna_agenda($codigo_fornecedor, $codigo_servico, date('Y-m-d'), date('Y-m-d', strtotime("+30 days", strtotime(str_replace("/", "-", $data_inicio)))));
                        //varre as datas de agendamento que estão ocupadas
                        foreach($verifica_agendados as $key => $campo) {
                            if(isset($campo['AgendamentoExame'])) {
                                $dataLegivel = $campo['AgendamentoExame']['data']->i18nFormat('yyyy-MM-dd');
                                $agendados[$dataLegivel][$campo['AgendamentoExame']['hora']] = isset($agendados[$dataLegivel][$campo['AgendamentoExame']['hora']]) ? ($agendados[$dataLegivel][$campo['AgendamentoExame']['hora']] + 1) : 1;
                            }
                            else {
                                $dataLegivel = $campo['data']->i18nFormat('yyyy-MM-dd');
                                $agendados[$dataLegivel][$campo['hora']] = isset($agendados[$dataLegivel][$campo['hora']]) ? ($agendados[$dataLegivel][$campo['hora']] + 1) : 1;
                            }
                        }

                        if(count($verifica_agenda)) {
                            // debug($verifica_agenda);exit;
                            // organiza dias, horarios e vagas (por dia da semana)
                            foreach($verifica_agenda as $key => $dia_hora) {
                                $lista_grade_agenda[$dia_hora['dia_semana']][$dia_hora['hora']]['qtd'] = $dia_hora['capacidade_simultanea'];
                                $lista_grade_agenda[$dia_hora['dia_semana']][$dia_hora['hora']]['codigo'] = $dia_hora['codigo'];
                            }//fim foreach
                            // percore periodo e organiza grade no periodo
                            if(count($lista_grade_agenda)) {
                                for($data = $data_inicio; $data <= $data_fim; $data = date('Ymd', strtotime("+1 days", strtotime(Comum::__formata_data($data))))) {
                                    // retorna dia da semana da data
                                    $dia_da_semana = date('w', strtotime(Comum::__formata_data($data)));
                                    if(array_key_exists($dia_da_semana, $lista_grade_agenda)) {
                                        $data_extenso = str_replace("-", "/", Comum::__formata_data($data));
                                        $lista_datas_disponiveis[$data_extenso] = array(
                                            'start' => $data_extenso,
                                            'end' => $data_extenso,
                                            'title' => 'Data Disponível',
                                            'horas_disponiveis' => $lista_grade_agenda[$dia_da_semana]
                                        );
                                    }
                                }
                            }
                        }//fim verifica agenda
                        // retira da agenda os horarios ja agendados!!!
                        foreach($agendados as $key => $linha) {
                            foreach($linha as $hora => $quantidade) {
                                if(isset($lista_datas_disponiveis[$key]['horas_disponiveis'][$hora])) {
                                    if($lista_datas_disponiveis[$key]['horas_disponiveis'][$hora] == $quantidade) {
                                        unset($lista_datas_disponiveis[$key]['horas_disponiveis'][$hora]);
                                        // retirada data (se ja não existe nenhum horario disponível)
                                        if(!count($lista_datas_disponiveis[$key]['horas_disponiveis'])) {
                                            unset($lista_datas_disponiveis[$key]);
                                        }
                                    } else {
                                        $lista_datas_disponiveis[$key]['horas_disponiveis'][$hora] = $lista_datas_disponiveis[$key]['horas_disponiveis'][$hora] - $quantidade;
                                        $lista_datas_disponiveis[$key]['horas_disponiveis'][$hora]['codigo'] = $lista_datas_disponiveis[$key]['horas_disponiveis'][$hora];
                                    }
                                }
                            }
                        }
                        $this->loadModel('FornecedoresAgendasDatasBloqueadas');
                        $bloqueados = $this->FornecedoresAgendasDatasBloqueadas->find()->select(['data','bloqueado_dia_inteiro','horarios'])->where(['codigo_fornecedor' => $codigo_fornecedor,'codigo_lista_de_preco_produto_servico' => $verifica_grade_especifica['ListaDePrecoProdutoServico']['codigo'],'ativo' => 1])->all();
                        foreach ($lista_datas_disponiveis as $data => $value) {
                            foreach ($bloqueados as $key => $bloqueado) {
                                if($data == $bloqueado['data']) {
                                    if($bloqueado['bloqueado_dia_inteiro'] > 0) {
                                        unset($lista_datas_disponiveis[$data]);
                                    } else {
                                        foreach ($value['horas_disponiveis'] as $hora => $val) {
                                            if(in_array($hora, json_decode(str_replace('"', '', $bloqueado['horarios'])))) {
                                                unset($lista_datas_disponiveis[$data]['horas_disponiveis'][$hora]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // debug($lista_datas_disponiveis);exit;
                        //formata a saida para o endpoint
                        $formatacao = array();
                        foreach($lista_datas_disponiveis as $keys => $dados) {
                            $data_separada = explode("/",$keys);
                            $ano = $data_separada['2'];
                            $mes = $data_separada['1'];
                            $dia = $data_separada['0'];
                            $disponibilidade = array();
                            //varre os horarios
                            foreach($dados['horas_disponiveis'] as $horas => $val) {
                                //horarios
                                $disponibilidade[] = array(
                                    'codigo_horario' => $val['codigo'],
                                    'horario' => Comum::formataHora($horas),
                                    'qtd_simultaneo' => $val['qtd']
                                );
                            }//fim foreach horas
                            //formatacao
                            $formatacao[] = array(
                                'ano' => $ano,
                                'mes' => $mes,
                                'dia' => $dia,
                                'data_completa' => $ano.'-'.$mes.'-'.$dia,
                                'disponibilidade_horario' => $disponibilidade
                            );
                            // debug($formatacao);
                            // debug($disponibilidade);
                            // exit;
                        }//fim foreach data
                        //valores de retorno
                        $data = $formatacao;
                    }//fim if para montar a grade
                    else {
                        $data = "Não existe grade para este fornecedor!";
                    }
                }//fim dados_matricula_funcao
                else {
                    $error[] = "Não encontramos nenhuma matricula/função na empresa vinculada!";
                }
            }//fim validausuariopedidosexames
        }//fim else codigo_usuario
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }
    }//fim getClinicaDisponibilidade
    /**
     * [getEnviarExames description]
     *
     * metodo para pegar os dados do exame uma forma para anexar os exames/ficha_clinica
     *
     * @param  [type] $codigo_id_agendamento [description] este codigo_id_agendamento é o codigo_item_pedido_exame
     * @return [type]                           [description]
     */
    public function getEnviarExames($codigo_id_agendamento)
    {
        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_id_agendamento)) {
            $error = 'Parametro codigo_id_agendamento inválido.';
        }
        else {
            $data = array();
            //pega os dados
            $dados = $this->PedidosExames->consultas($codigo_id_agendamento);
            //verifica se existe os dados
            if(!empty($dados)) {
                //formata a saida
                $data = array(
                    'pedido_exames' => array(
                        'empresa' => array(
                            'razao_social' => $dados->razao_social_solicitante,
                            "nome_fantasia" => $dados->nome_fantasia_solicitante,
                            "cnpj" => $dados->cnpj_solicitante
                        ),
                        "sobre_exame" => array(
                            'data' => $dados->data,
                            'exame' => $dados->exame,
                            // "exame_ficha_clinica" => (($dados->codigo_exame == 52) ? 'ASO - Ficha Clinica' : ''),
                            "exame_ficha_clinica" => '',
                            'tipo_exame' => $dados->tipo_exame,
                            'numero_pedido' => $dados->codigo_pedido_exame,
                            "status" => null,//($dados->data_realizacao_exames) ? "Exame realizado" : "Pendente",
                            "imagem_exame" => $dados->imagem_exame,
                            "imagem_ficha_clinica" => $dados->imagem_ficha_clinica
                        ),
                        "clinica" => array(
                            'nome'=> $dados->nome_credenciado,
                        )
                    )
                );
            }//fim empty dados
        }//fim empty codigo_id_agendamento
        $this->set(compact('data'));
    }//fim getEnviarExames
    /**
     * [consultasPontuais description]
     *
     * metodo para trazer os exames pontuais, ou seja os exames complementares da assinatura
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getConsultasPontuais($codigo_usuario, $codigo_cliente)
    {
        //variavel auxiliar
        $data = array();
        //verifica se esta vazio o codigo id agendamento
        if (empty($codigo_usuario)) {
            $error[] = 'Parametro codigo_usuario inválido.';
        }
        else {
            //valida se o usuario pode emitir um pedido de exames
            $validar = $this->validaUsuarioPedidosExames($codigo_usuario);
            if(!empty($validar)) {
                $error = $validar;
            }
            else{
                $data = $this->getPontual($codigo_usuario, $codigo_cliente);
            }//fim validausuariopedidosexames
        }//fim else codigo_usuario
        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $this->set(compact('error'));
        }
    }//fim consultasPontuais
    public function getPontual($codigo_funcionario,$codigo_cliente)
    {
        $this->loadModel('ClienteFuncionario');
        //pega a função do funcionario na funcionario_setores_cargo
        $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($codigo_funcionario,$codigo_cliente);

        //verifica se existe alguma matricula para este usuario
        if(!empty($dados_matricula_funcao)) {
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
                if(!$this->valida_pedido_exame_ppra($dados['FuncionarioSetorCargo']['codigo'])) {
                    $error[] = "Não existe PPRA para este funcionário da empresa: " . $dados['Cliente']['nome_fantasia'];
                }
                else {

                    $codigo_cliente = $dados['FuncionarioSetorCargo']['codigo_cliente_alocacao'];
                    $codigo_cliente_matriz = $dados['codigo_cliente_matricula'];
                    //pega os dados do produto configurado para este cliente
                    $produtos = $this->ClienteProduto->listarPorCodigoCliente($codigo_cliente);

                    // debug($produtos);exit;

                    //seta a variavel grupo economico
                    $produto_matriz = array();
                    $produto_matriz_produto = array();
                    ############## TRECHO PARA PEGAR AS ASSINATURAS DA MATRIZ  #####################
                    //verifica se o codigo da matriz é o mesmo codigo do cliente que esta querendo ver a assinatura pois precisa ser diferente
                    if($codigo_cliente != $codigo_cliente_matriz) {
                        //array servicos que nao devem ser buscados
                        $array_codigos_servicos = false;
                        //verifica se existe produto cadastrado
                        if(!empty($produtos)) {
                            //para nao exibir os dados que ja estao cadastrados no cliente
                            foreach ($produtos as $prod) {
                                //varre os servicos
                                foreach($prod['ClienteProdutoServico2'] as $servico){
                                    $array_codigos_servicos[] = $servico['Servico']['codigo'];
                                }//fim foreach servicos
                            }//fim foreach dos produtos
                        }//fim verificacao se existe produtos
                        //produtos da matriz
                        $produto_matriz_liberado = $this->ClienteProduto->listarPorCodigoCliente($codigo_cliente_matriz,$array_codigos_servicos,true);
                        ########################################parei aqui precisa arrumar para pegar o produtos
                        // debug($produto_matriz_liberado);exit;
                        //verifica se existe produto matriz
                        if(!empty($produto_matriz_liberado)) {
                            //seta o produto matriz
                            foreach($produto_matriz_liberado as $pml) {
                                $produto_matriz_produto = $pml['Produto'];
                                $produto_matriz = $pml['ClienteProdutoServico2'];
                            }
                            // debug($produto_matriz);exit;
                        }//fim if empty produto matriz
                    } //fim verifica o codigo da matriz
                    // debug($produtos);
                    // debug($produto_matriz);
                    // exit;
                    ############## TRECHO PARA PEGAR OS SERVICOS QUE IRÁ PAGAR #####################
                    //verifica se existe os exames pela matriz
                    if(isset($produtos[0])) {
                        $produtos_lista = $produtos[0]['ClienteProdutoServico2'];
                    }
                    else {
                        $produtos_lista = array();
                        $produtos[0]['Produto'] = $produto_matriz_produto;
                    }
                    // debug($produtos_lista); debug($produto_matriz);exit;
                    $cliente_produto_servico2 = array_merge($produtos_lista,$produto_matriz);
                    // sort($cliente_produto_servico2);
                    // debug($cliente_produto_servico2);
                    // exit;
                    $produtos[0]['ClienteProdutoServico2'] = $cliente_produto_servico2;
                    //pega todos os exames setados na assinatura
                    $produtos_servicos = $produtos;

                    // debug($produtos_servicos);exit;

                    if(!empty($produtos_servicos)) {

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

                        if(isset($produtos_servicos[0]['ClienteProdutoServico2']) && count($produtos_servicos[0]['ClienteProdutoServico2'])) {
                            foreach($produtos_servicos[0]['ClienteProdutoServico2'] as $key => $servico) {
                                //pega os exames deste servico
                                $exames = $this->Exames->find()->select(['codigo','descricao'=>'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])->where(['codigo_servico' => $servico['Servico']['codigo']])->hydrate(false)->toArray();
                                //pega os exames dos servicos cadastrados
                                if($exames) {
                                    $lista_exames['consulta_pontual'][] = array(
                                        'id' => $exames[0]['codigo'],
                                        'descricao' => $exames[0]['descricao']
                                    );
                                }
                            }//fim foreach
                        }//fim if produto servico
                    }//FINAL SE empty($produtos_servicos)
                }//fim if existe ppra
            }//fim foreach dados matricula funcao

            // debug($lista_exames); exit;

            //retorna a lista de exames do pcmso
            return $lista_exames;
        }//fim dados_matricula_funcao
        else{
            $error[] = "Não encontramos nenhuma matricula/função as empresa(s) vinculada(s)!";
            return $error;
        }
    }
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
    public function setCriarAgendamento()
    {
        $data = [];
        $payload = $this->request->getData();
        // validar se dados corretos necessários foram enviados no payload
        if(empty($payload)){
            $error = 'Payload não encontrado';
            $this->set(compact('error'));
            return;
        }
        if(!isset($payload['codigo_usuario']) && empty($payload['codigo_usuario'])){
            $error = 'codigo_usuario requerido';
            $this->set(compact('error'));
            return;
        }
        // obter codigo_usuario
        $codigo_usuario = $payload['codigo_usuario'];
        if(!isset($payload['codigo_empresa']) && empty($payload['codigo_empresa'])){
            $error = 'codigo_empresa requerido';
            $this->set(compact('error'));
            return;
        }
        // obter codigo_cliente
        $codigo_cliente = $payload['codigo_empresa'];
        // obter codigo_exame_tipo
        // if(!isset($payload['codigo_exame_tipo'])) {
        //     $error = 'codigo_exame_tipo requerido';
        //     $this->set(compact('error'));
        //     return;
        // }
        $codigo_exame_tipo = $payload['codigo_exame_tipo'];
        // obter codigo_consulta_pontual
        /*if(!isset($payload['codigo_consulta_pontual']) && empty($payload['codigo_consulta_pontual'])){
            $error = 'codigo_consulta_pontual requerido';
            $this->set(compact('error'));
            return;
        }*/
        $codigo_consulta_pontual = $payload['codigo_consulta_pontual'];
        // não podem ser iguais
        if($codigo_exame_tipo == $codigo_consulta_pontual){
            //|| $codigo_exame_tipo > 5
            //|| $codigo_consulta_pontual > 2){
            $error = 'Corrija a configuração codigo_exame_tipo e codigo_consulta_pontual';
            $this->set(compact('error'));
            return;
        }
        // obter codigo_funcionario
        $codigo_usuario = $payload['codigo_usuario'];
        //verifica se tem o codigo_pedido_exame
        $codigo_pedido_exame = '';
        if(isset($payload['codigo_pedido_exame'])) {
            if(!empty($payload['codigo_pedido_exame'])) {
                $codigo_pedido_exame = $payload['codigo_pedido_exame'];
            }
        }
        $this->loadModel('Usuario');
        $usuario = $this->Usuario->obterDadosDoUsuarioAlocacao($codigo_usuario);
        if(empty($usuario)){
            $error = 'Usuário não encontrado';
            $this->set(compact('error'));
            return;
        }
        // verifica se tem relação com codigo empresa informado
        $valida_tem_relacao_empresa = false;
        foreach ($usuario->cliente as $key => $value) {
            if($valida_tem_relacao_empresa == true || $value['codigo'] == $codigo_cliente){
                $valida_tem_relacao_empresa = true;
                break;
            }
        }

        if(!$valida_tem_relacao_empresa){
            $error = 'Usuário não tem relacionamento com a empresa fornecida';
            $this->set(compact('error'));
            return;
        }
        //pega o codigo_cliente_funcionario para os pedidos
        $vinculo_cliente_funcionario = $this->Usuario->obterVinculoClienteFuncionario($codigo_usuario, $codigo_cliente);
        // debug($vinculo_cliente_funcionario);exit;

        if(!$vinculo_cliente_funcionario){
            $error = 'Usuário não tem relacionamento (alocação) com a empresa fornecida';
            $this->set(compact('error'));
            return;
        }
        // obter codigo do vinculo
        $codigo_cliente_funcionario = isset($vinculo_cliente_funcionario->codigo) ? $vinculo_cliente_funcionario->codigo : $vinculo_cliente_funcionario->codigo;
        // obter codigo do funcionario
        $codigo_funcionario = isset($vinculo_cliente_funcionario->codigo_funcionario) ? $vinculo_cliente_funcionario->codigo_funcionario : null;
        // se são obrigatórios
        if(empty($codigo_cliente_funcionario) || empty($codigo_funcionario)){
            $error = 'Usuário não tem relacionamento(matricula) com a empresa fornecida ou não configurado corretamente';
            $this->set(compact('error'));
            return;
        }
        /**
         *  {
         *  "codigo_fornecedor": 444,
         *  "codigo_exame":77,
         *  "tipo_atendimento":null, //0 ordem chegada 1 hora marcada
         *  "tipo_agendamento":null,
         *  "data": "2019-10-31",
         *  "horario": "1735"
         * }
         */
        if(!isset($payload['exames']) && empty($payload['exames']) || !is_array($payload['exames']) || count($payload['exames']) == 0 ){
            $error = 'exames requerido';
            $this->set(compact('error'));
            return;
        }
        $exames = $payload['exames'];
        $campos_exames = array("codigo_fornecedor"=>"codigo_fornecedor","codigo_exame"=>"codigo_exame","tipo_atendimento"=>"tipo_atendimento","tipo_agendamento"=>"tipo_agendamento","data"=>"data","horario"=>"horario");
        $valida_campos = array();
        //valida os indices do objeto se tem todos os campos
        foreach($exames AS $chaves => $campos_indices) {
            //varre os objetos para saber se tem todos os obrigatorios
            foreach($campos_exames AS $obj => $val) {
                //verifica os campos de indices
                if(!isset($campos_indices[$obj])) {
                    $valida_campos[] = $obj;
                }
            }//fim foreach campos exames
        }//fim foreach
        //verifica os campos
        if(!empty($valida_campos)) {
            $error = 'campos requeridos: ' . implode(",", $valida_campos);
            $this->set(compact('error'));
            return;
        }//fim validacao
        // obter codigo_cliente_funcionario
        $this->loadModel('ClienteFuncionario');
        //pega a função do funcionario na funcionario_setores_cargo
        $dados_matricula_funcao = $this->ClienteFuncionario->getFuncionarioFuncao($codigo_funcionario, $codigo_cliente);
        //pega o codigo da empresa
        $codigo_empresa = $dados_matricula_funcao[0]['codigo_empresa'];
        // obter codigo vinculo com cargo x setor
        $codigo_func_setor_cargo = $dados_matricula_funcao[0]['FuncionarioSetorCargo']['codigo'];
        $codigo_cliente_alocacao = $dados_matricula_funcao[0]['FuncionarioSetorCargo']['codigo_cliente_alocacao'];
        $exame_admissional = 0;
        $exame_periodico = ($codigo_exame_tipo == 1) ? 1 : 0;
        $exame_demissional = 0;
        $exame_retorno = ($codigo_exame_tipo == 2) ? 1 : 0;
        $exame_mudanca = ($codigo_exame_tipo == 3) ? 1 : 0;
        $exame_monitoracao = ($codigo_exame_tipo == 4) ? 1 : 0;
        if($exame_periodico > 0 && $codigo_consulta_pontual > 0) {
            $error = 'Não pode passar um exame Periódico e Pontual para cadastrar ao mesmo tempo, ou é um Pedido Ocupacional ou Pontual para ser cadastrado!';
            $this->set(compact('error'));
            return;
        }
        else if($codigo_consulta_pontual > 0) {
            $codigo_consulta_pontual = 1;
        }

        //para gravar o pedido pcmso e ppra
        $set_risco_regra = false;

        ########PONTO DE ATENÇÃO PARA QUANDO FOR PONTUAL DEVE CRIAR O PEDIDO DIRETO
        // $cod_pedido_exame = $this->PedidosExames->set_pre_pedido_exame_para_pedido_exame($codigo_pedido_exame,$codigo_func_setor_cargo,$codigo_cliente_alocacao);

        // $this->PedidosExames->enviarKit(191869);
        // debug('pedidos exames controller :)');
        // exit;

        //abrir transacao
        $conn = ConnectionManager::get('default');
        try{

            //variavel auxiliar para envaiar o kit
            $enviar_kit = false;

            //abre a transacao
            $conn->begin();
            if(empty($codigo_pedido_exame)) {
                // dados para salvar pedido exame
                $dados_salvar = [
                    'codigo_cliente_funcionario' => $codigo_cliente_funcionario,
                    'codigo_cliente' => $codigo_cliente_alocacao,
                    'codigo_funcionario' => $codigo_funcionario,
                    'codigo_func_setor_cargo' => $codigo_func_setor_cargo,
                    'exame_admissional'=> $exame_admissional,
                    'exame_periodico'=> $exame_periodico,
                    'exame_demissional'=>$exame_demissional,
                    'exame_retorno'=> $exame_retorno,
                    'exame_mudanca'=> $exame_mudanca,
                    'exame_monitoracao'=> $exame_monitoracao,
                    'pontual'=>  $codigo_consulta_pontual,
                    'codigo_usuario_inclusao'=> $codigo_usuario,
                    // dados nao mapeados
                    'codigo_empresa' => $codigo_empresa,
                    'portador_deficiencia' => '0',
                    'data_solicitacao' => date('Y-m-d'),
                    'codigo_status_pedidos_exames' => '1',
                    'endereco_parametro_busca' => '',
                ];

                //verfica se não é consulta pontual
                if( $codigo_consulta_pontual == 0) {
                    $set_risco_regra = true;

                    //gera o pre pedido pois precisa validar se ira gerar o pedido de exame
                    $this->loadModel('PrePedidosExames');
                    //grava o pre pedido
                    $pre_pedido = $this->PrePedidosExames->set_pre_pedidos_exames($dados_salvar);

                    //verfica se retornou erro para dar rollback
                    if (is_array($pre_pedido)) {
                        throw new Exception($pre_pedido);
                    }

                    //seta o codigo do pedido
                    $codigo_pedido_exame = $pre_pedido;

                }//fim verificacao se é consulta pontual
                else {
                    //gera o pre pedido pois precisa validar se ira gerar o pedido de exame
                    $this->loadModel('PedidosExames');
                    //grava o pedido
                    $pedido = $this->PedidosExames->set_pedidos_exames($dados_salvar);

                    //verfica se retornou erro para dar rollback
                    if (is_array($pedido)) {
                        throw new Exception($pedido);
                    }

                    //seta o codigo do pedido
                    $codigo_pedido_exame = $pedido;
                }

            }


            $cod_pedidos_exames = '';
            //seta os itens
            if( $codigo_consulta_pontual == 0) {
                $this->loadModel('PreItensPedidosExames');
                $exames_salvos = $this->PreItensPedidosExames->setPreItensPedidosExames($codigo_usuario,$codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_exame_tipo, $exames);

                ################verifica se deve disparar gerar o pedido efetivamente################
                #o codigo_pedido_exame na verdade é referente a tabela pre_pedidos_exames
                $cod_pedidos_exames = $this->PedidosExames->set_pre_pedido_exame_para_pedido_exame($codigo_pedido_exame,$codigo_func_setor_cargo,$codigo_cliente_alocacao);
                ################FIM verifica se deve disparar kit################
            }
            else {
                $this->loadModel('ItensPedidosExames');
                $exames_salvos = $this->ItensPedidosExames->setItensPedidosExames($codigo_usuario,$codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_exame_tipo, $exames);

                $cod_pedidos_exames = $codigo_pedido_exame;
            }

            $data = [
                'retorno'=>'Agendamento Realizado!',
                'codigo_pedido_exame' => $codigo_pedido_exame,
                'exames_necessarios'=>$exames_salvos
            ];
            //finaliza a transacao
            $conn->commit();

            //verifica se ira gravar risco e regra do pcmso e ppra pois tem que estar com commit realizado
            if(!empty($cod_pedidos_exames)) {
                //seta o ppra e pcmso do momento
                $this->PedidosExames->setDadosRiscoRegraAso($cod_pedidos_exames);

                ################################################################
                ###########################ENVIAR KIT###########################
                ################################################################
                $this->PedidosExames->enviarKit($cod_pedidos_exames);

            }

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
    // /**
    //  * [setItensPedidosExames description]
    //  *
    //  * metodo para gravar os exames que foram selecionados
    //  *
    //  * @param [type] $codigo_pedido_exame [description]
    //  * @param [array] $exames              [description]
    //  */
    // public function setItensPedidosExames($codigo_usuario,$codigo_pedido_exame, $codigo_cliente, $codigo_cliente_alocacao, $codigo_exame_tipo, $exames)
    // {
    //     // obter codigo servico
    //     $this->loadModel('Exames');
    //     /**
    //      * array:6 [▼
    //     "codigo_fornecedor" => 444
    //     "codigo_exame" => 77
    //     "tipo_atendimento" => null
    //     "tipo_agendamento" => null
    //     "data" => "2019-10-31"
    //     "horario" => "1735"
    //     ]
    //      */
    //     $exames_salvos = [];
    //     $codigo_fornecedor = null;
    //     foreach($exames as $key => $item) {
    //         if(!empty($item['codigo_fornecedor'])) {
    //             $codigo_fornecedor = $item['codigo_fornecedor'];
    //         }
    //         $codigo_exame = $item['codigo_exame'];
    //         $tipo_atendimento = $item['tipo_atendimento'];
    //         $tipo_agendamento = $item['tipo_agendamento'];
    //         $data_agendamento = $item['data'];
    //         $hora_agendamento = $item['horario'];
    //         // obter codigo servico
    //         $exame = $this->Exames->find()->select(['codigo_servico', 'descricao'=>'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])->where(['codigo'=>$codigo_exame])->first();
    //         $codigo_servico = $exame->codigo_servico;
    //         $item_exame_descricao = $exame->descricao;

    //         //pega o valor do custo do serviço/exame para aquele fornecedor
    //         //$valor_custo = $this->ItemPedidoExame->ObterFornecedorCusto($codigo_fornecedor, $codigo_exame);

    //         // codigos de serviços dos exames para retornar preço
    //         $d = $this->PedidosExames->retornaFornecedoresExames($codigo_servico, null, $codigo_cliente, null);
    //         $valor_custo = $d[0]['ListaPrecoProdutoServico']['valor'];

    //         // $codigo_cliente_alocacao = $codigo_cliente; //todo
    //         $codigo_matriz = $codigo_cliente;
    //         $assinatura = $this->PedidosExames->verificaExameTemAssinatura($codigo_servico, $codigo_cliente_alocacao, $codigo_matriz);
    //         $dados_salvar_item = array(
    //             'codigo_pedidos_exames' => $codigo_pedido_exame,
    //             'codigo_exame' => $codigo_exame,
    //             'codigo_fornecedor' => $codigo_fornecedor,
    //             'tipo_atendimento' => $tipo_atendimento,
    //             'tipo_agendamento' => $tipo_agendamento,
    //             'data_agendamento' => $data_agendamento,
    //             'hora_agendamento' => $hora_agendamento,
    //             'codigo_tipos_exames_pedidos' => $codigo_exame_tipo,
    //             'valor_custo' => $valor_custo,
    //             'valor' => $assinatura['valor'],
    //             'codigo_cliente_assinatura' => $assinatura['codigo'],
    //             'codigo_usuario_inclusao' => $codigo_usuario
    //         );
    //         // debug($dados_salvar_item);
    //         // salvar item



    //         $this->loadModel('PreItensPedidosExames');
    //         //verifica se existe um pedido de exame, caso exista e tem o exame do laço irá atualziar
    //         $ipe = $this->PreItensPedidosExames->find()->where(['codigo_pedidos_exames' => $codigo_pedido_exame,'codigo_exame' => $codigo_exame])->first();
    //         //verifica se existe o item
    //         if(!empty($ipe)) {
    //             $registro_item = $this->PreItensPedidosExames->patchEntity($ipe,$dados_salvar_item);
    //         }
    //         else {
    //             $registro_item = $this->PreItensPedidosExames->newEntity($dados_salvar_item);
    //         }

    //         //cria um item ou atualiza o item
    //         if (!$this->PreItensPedidosExames->save($registro_item)) {
    //             throw new Exception('Ocorreu algum erro! Precisamos reagendar ou entre contato com a clínica! '.print_r($registro_item->getValidationErrors(),1));
    //         }

    //         //verifica se é alteracao e tem que ser inclusao
    //         if(empty($ipe)) {
    //             //verifica se tem data de agendamento
    //             if(!empty($data_agendamento) && !empty($hora_agendamento)) {
    //                 $this->loadModel('PreAgendamentoExames');
    //                 $array_incluir = array(
    //                     'data' => $data_agendamento,
    //                     'hora' => (int) str_replace(":", "", $hora_agendamento),
    //                     'codigo_fornecedor' => $codigo_fornecedor,
    //                     'codigo_itens_pedidos_exames' => $registro_item->codigo,
    //                     'ativo' => '1',
    //                     'data_inclusao' => date('Y-m-d H:i:s'),
    //                     'codigo_usuario_inclusao' => $codigo_usuario,
    //                     'codigo_empresa' => 1,
    //                     'codigo_lista_de_preco_produto_servico' => null
    //                 );
    //                 $agenda_item = $this->PreAgendamentoExames->newEntity($array_incluir);
    //                 if(!$this->PreAgendamentoExames->save($agenda_item)) {
    //                     throw new Exception("Houve um erro ao salvar o Agendamento!");
    //                 }
    //             }
    //         }//fim empty ipe
    //         $item_exame_resposta['codigo'] = $registro_item->codigo;
    //         $item_exame_resposta['codigo_tipo_exame'] = $codigo_exame_tipo;
    //         $item_exame_resposta['descricao'] = $item_exame_descricao;
    //         $item_exame_resposta['agendado'] = true;
    //         $exames_salvos[] = $item_exame_resposta;
    //     } // foreach
    //     // exit;
    //     return $exames_salvos;
    // }//fim setItensPedidosExames

    /**
     * [setCancelarAgendamento description]
     *
     * cancelar o pedido de exames
     *
     * @param [type] $pedido_exame [description]
     */
    public function setCancelarAgendamento($pedido_exame)
    {
        $data = array();
        //pega o pedido
        $pedido = $this->PedidosExames->get($pedido_exame);
        // debug($pedido);exit;
        $dados_pedido = array(
            'codigo' => $pedido_exame,
            'codigo_status_pedidos_exames' => 5
        );
        $registro_item = $this->PedidosExames->patchEntity($pedido,$dados_pedido);
        if($this->PedidosExames->save($registro_item)) {
            $data[] = "Pedido Cancelado!";
        }
        $this->set(compact($data));
    }//fim setCancelarAgendamento
    /**
     * Busca os funcionarios por cpf, nome ou numero do pedido
     */
    public function getFuncionarios( int $codigo_fornecedor=null,  string $busca = null)
    {

        try {
            $dados = $this->PedidosExames->getFuncionarios($codigo_fornecedor, urldecode($busca));
            //print_r($dados);
            //die();
            if(empty($dados)){
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }
            foreach($dados as $v) {
                if(!empty($v['matricula'])) {
                    $v['vinculo'] = 1; //tipo do vinculo do paciente é funcionario
                    $v['vinculo_descricao'] = "Colaborador";
                    $v['codigo_cor'] =1;
                } else {
                    $v['vinculo'] = 2; //tipo do vinculo do paciente é terceirizado
                    $v['vinculo_descricao'] = "Terceirizado";
                    $v['codigo_cor'] =2;
                }
            }
            $this->set('data', $dados);
        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }


    public function getFuncForn( int $codigo_fornecedor=null,  string $busca = null)
    {

        try {
            $dados = $this->PedidosExames->getDadosFuncionarios($codigo_fornecedor, urldecode($busca));

            $this->set('dados', $dados);
            return;
            if(empty($dados)){
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }
            foreach($dados as $v) {
                if(!empty($v['matricula'])) {
                    $v['vinculo'] = 1; //tipo do vinculo do paciente é funcionario
                    $v['vinculo_descricao'] = "Colaborador";
                    $v['codigo_cor'] =1;
                } else {
                    $v['vinculo'] = 2; //tipo do vinculo do paciente é terceirizado
                    $v['vinculo_descricao'] = "Terceirizado";
                    $v['codigo_cor'] =2;
                }
            }
            $this->set('data', $dados);
        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    /**
     * Busca o histórico de exames do funcionário
     */
    public function getHistoricoFuncionario(int $codigo)
    {
        try {
            $query = $this->PedidosExames->getHistoricoFuncionario($codigo);
            //die($query->sql());

            if (!empty($query)) {
                $this->set('data', $query);
            } else {
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }
        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Busca os médicos pelo codigo_fornecedor
     */
    public function getMedicos(int $codigo_fornecedor, string $especialidade=null)
    {

        $this->loadModel('FornecedoresMedicos');
        try {

            $query = $this->FornecedoresMedicos->getMedicos($codigo_fornecedor, $especialidade);
            //die($query);

            if (count($query->all()) > 0) {

                foreach($query as $v){
                    if(empty($v['foto'])){
                        $v['foto'] = "https://api.rhhealth.com.br/ithealth/2020/05/19/F6A883A3-20C6-C7FE-3A3E-6D20BF3B1177.png";
                    }
                }

                $this->set('data', $query);
            } else {
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }
        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Busca as especialidades médicas pelo codigo_fornecedor
     */
    public function getEspecialidades(int $codigo_fornecedor)
    {
        $this->loadModel('FornecedoresMedicos');
        try {
            $query = $this->FornecedoresMedicos->getEspecialidades($codigo_fornecedor);
            // die($query);
            if (count($query->all()) > 0) {
                $this->set('data', $query);
            } else {
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }
        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }
    /**
     * Retorna status dos resultados de exames
     */
    public function getStatusResultados()
    {

        $status_resultados = array(
            array("codigo"=>"1", "descricao" => "Normal"),
            array("codigo"=>"2", "descricao" => "Alterado"),
            array("codigo"=>"3", "descricao" => "Estável"),
            array("codigo"=>"4", "descricao" => "Agravamento"),
            array("codigo"=>"5", "descricao" => "Referencial"),
            array("codigo"=>"6", "descricao" => "Sequencial")
        );

        $this->set('data', $status_resultados);
    }
    /**
     * Busca os exames de um agendamento/pedido pelo codigo_pedido e codigo_fornecedor
     */
    public function getPedidoExames(int $codigo_pedido, int $codigo_fornecedor)
    {

        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if(empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        //seta o codigo usuario
        $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;

        if(empty($codigo_usuario)) {
            $error = 'Pedido Exame - Código de usuario não encontrado!';
            $this->set(compact('error'));
            return;
        }

        //para pegar o perfilpermissao do usuario
        $this->loadModel('Usuario');
        $permissoes = $this->Usuario->getUsuarioPerfilPermissao($codigo_usuario);
        // debug($permissoes);exit;

        $dados = $this->PedidosExames->getPedidoExames($codigo_pedido);
        // debug($dados->toArray());exit;

        if(empty($dados)){
            $error = 'Código de pedido não encontrado para este fornecedor';
            $this->set(compact('error'));
            return;
        }
        $dados_compl = $this->PedidosExames->getDadosComplementaresFuncionario($codigo_pedido);
        //pega as configuracoes do sistema para os exames
        $this->loadModel('Configuracao');
        $arrConfig = $this->Configuracao->getConfiguracaoTiposExames();
        //seta os status de resultados do exame
        $status_resultados = array(
            "1" => "Normal",
            "2" => "Alterado",
            "3" => "Estável",
            "4" => "Agravamento",
            "5" => "Referencial",
            "6" => "Sequencial",
        );

        //chama a tabela de fornecedores
        $this->loadModel('Fornecedores');
        //para apresentar o parecer ou nao
        $variavelParecer = false;
        $todosExamesBaixados = true;
        $codigo_pedido_exame_aso = null;
        $imprime_aso = false;

        $exames=array();

        foreach($dados as $v){
            //variavel auxiliar para saber se o exame que esta vindo é externo ou nao
            $msg_exame = null;
            $msg = null;
            $agendamento_externo = false;
            $pode_baixar = true;

            //verifica se é um agendamento externo para poder indicar e colocar o endereco na msg do exame
            if($codigo_fornecedor != $v->codigo_fornecedor) {
                $agendamento_externo = true;
                $msg_exame .= '';
                //verifica se o fornecedor que esta atendendo é ambulatorio
                $fornecedor = $this->Fornecedores->find()->where(['codigo' => $codigo_fornecedor])->first();
                //verifica se é ambulatorio
                if($fornecedor->ambulatorio != 1) {
                    //nao pode baixar o exame
                    $pode_baixar = false;
                    $msg = "Não é possivel baixar este exame pois pertence a outro prestador!";
                }//fim ambulatorio

            }//fim codigo_fornecedor diferente

            //verifica se o usuario tem permissao para baixar o exame
            if(!$permissoes['baixa_pedido']) {
                //nao pode baixar o exame
                $pode_baixar = false;
                $msg = "O usuário não tem permissão para baixar o exame!";
            }


            //verifica se tem que passar algum tipo de configuracao
            $tipo_config = '';
            $respondido = '';
            if(isset($arrConfig[$v->codigo_exame])) {
                $tipo_config = $arrConfig[$v->codigo_exame];
                //verifica se deve apresetar o parecer ou nao
                if($tipo_config == 'fichaclinica') {

                    //verifica se pode ter acesso ao exame ou não
                    if(!$permissoes['ficha_clinica']) {
                        continue;
                    }

                    //verifica se o aso foi baixado
                    $variavelParecer = true;
                    /**
                     * TRECHO DE CODIGO COMETANDO E INCLUIDO NA LINHA CO CASE 'FICHACLINICA' 2523 PARA ATENDER UMA LOGICA DO FRONT END
                     */
                    // if(!empty($v->codigo_item_pedido_exame_baixado)) {
                    //     $variavelParecer = false;
                    // }



                    //para incluir o parecer como um exame na listagem
                    $codigo_pedido_exame_aso = $v->codigo_item_pedido;

                    //variavel para apresentar se deve imprimir o aso ou nao
                    $imprime_aso = true;

                    //verifica se tem anexo
                    if(is_null($v->imagem_exame)) {
                        $msg_exame .= 'Arquivo ASO pendente.';
                    }

                }

                //verifica se pode ter acesso ao exame ou não
                if(isset($permissoes[$tipo_config])) {
                    if(!$permissoes[$tipo_config]) {
                        continue;
                    }
                }//fim verificacao tipo do exame


                switch ($tipo_config) {
                    case 'psicossocial':
                        $this->loadModel('FichaPsicossocial');
                        //busca na tabela de psicossocial se ja foi resondido
                        $p = $this->FichaPsicossocial->find()->where(['codigo_pedido_exame' => $v->codigo_pedido])->first();
                        if(!empty($p)) {
                            $respondido = true;
                        }
                        break;
                    case 'fichaclinica':
                        $this->loadModel('FichasClinicas');
                        //busca na tabela de psicossocial se ja foi resondido
                        $fc = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $v->codigo_pedido])->first();
                        if(!empty($fc)) {
                            $respondido = true;
                            /**
                             * TRECHO DE CODIGO INCLUIDO PARA ATENDER UMA LOGICA DO FRONT END
                             */
                            if(!empty($fc->parecer)) {
                                $variavelParecer = false;
                            }
                        }
                        break;
                    case 'assistencial':
                        $this->loadModel('FichasAssistenciais');
                        //busca na tabela de psicossocial se ja foi resondido
                        $fa = $this->FichasAssistenciais->find()->where(['codigo_pedido_exame' => $v->codigo_pedido])->first();
                        if(!empty($fa)) {
                            $respondido = true;
                        }
                        break;
                    case 'audiometria':
                        $this->loadModel('Audiometrias');
                        //busca na tabela de psicossocial se ja foi resondido
                        $a = $this->Audiometrias->find()->where(['codigo_itens_pedidos_exames' => $v->codigo_item_pedido])->first();
                        if(!empty($a)) {
                            $respondido = true;
                        }
                        break;
                }

            }//fim verificacao

            $codigo_pedido = $v->codigo_pedido;
            $tipo_exame = $v->tipo_exame;
            $status_pedidos_exames = $v->status_pedidos_exames;
            $data_solicitacao = $v->data_solicitacao;
            $data_agendado = $v->data_agendado;
            $codigo_cliente_funcionario = $v->codigo_cliente_funcionario;
            $codigo_func_setor_cargo = $v->codigo_func_setor_cargo;

            $exame_baixado = true;
            // debug($v->codigo_item_pedido_exame_baixado);
            if(empty($v->codigo_item_pedido_exame_baixado)) {
                $todosExamesBaixados = false;
                $exame_baixado = false;
            }

            //quando tiver aso e a ficha clinica estiver preenchida mesmo que exame_baixado false deve trazer true para o front funcionar
            if(!$exame_baixado && $tipo_config == 'fichaclinica') {
                $this->loadModel('FichasClinicas');
                //valida se ja tem a ficha criada
                $fichaClinica = $this->FichasClinicas->find()->where(['codigo_pedido_exame' => $codigo_pedido])->first();
                if(!empty($fichaClinica) && $respondido) {
                    $todosExamesBaixados = true;
                    // $exame_baixado = false;
                }
            }//fim verificacao se pode dar o parecer

            $exames[] = array(
                'codigo_item_pedido'=>$v->codigo_item_pedido,
                'codigo_exame'=>$v->codigo_exame,
                'exame'=>$v->exame,
                "status_exame" => $v->status_exame,
                "tipo" => $tipo_config,
                "respondido" => $respondido,
                'agendamento' => array(
                    'codigo_fornecedor' => $v->codigo_fornecedor,
                    'nome' => $v->nome_credenciado,
                    'local' => $v->endereco,
                    'data' => $v->data,
                    'hora' => $v->hora,
                    'tipo_atendimento'=> (is_null($v->tipo_atendimento)) ? "Ordem de Chegada" : ($v->tipo_atendimento == 1 ) ? "Hora Marcada" : "Ordem de Chegada"
                ),
                'resultado' => array(
                    "data_inicio_triagem" => $v->data_inicio_triagem,
                    "data_fim_triagem" => $v->data_fim_triagem,
                    "data_inicio_realizacao_exame" => $v->data_inicio_realizacao_exame,
                    "data_realizacao_exame" => $v->data_realizacao_exame,
                    "hora_realizacao_exame" => $v->hora_realizacao_exame,
                    "data_resultado_exame" => $v->data_resultado_exame,
                    "medico" => $v->medico,
                    "imagem_exame" => $v->imagem_exame,
                    "imagem_ficha_clinica" => $v->imagem_ficha_clinica,
                    "imagem_laudo" => $v->imagem_laudo,
                    "laudo" => $v->laudo,
                    "status_resultado" => ((!is_null($v->status_resultado)) ? $status_resultados[$v->status_resultado] : $v->status_resultado),
                ),
                'agendamento_externo' => $agendamento_externo,
                'pode_baixar' => $pode_baixar,
                'msg_exame' => $msg_exame,
                'msg' => $msg,
                'exame_baixado' => $exame_baixado,
            );
        }
        if($variavelParecer) {
            $msg = null;
            if(!$todosExamesBaixados) {
                $msg = 'Não é possivel realizar o parecer pois existe exames pendentes.';
            }
            $exames[] = array(
                'codigo_item_pedido'=>$codigo_pedido_exame_aso,
                'codigo_exame'=>null,
                'exame'=>"Parecer",
                "status_exame" => null,
                "tipo" => 'parecer',
                'agendamento' => array(
                    'codigo_fornecedor' => null,
                    'nome' => null,
                    'local' => null,
                    'data' => null,
                    'hora' => null,
                    'tipo_atendimento'=> null
                ),
                'resultado' => array(
                    "data_realizacao_exame" => null,
                    "data_resultado_exame" => null,
                    "medico" => null,
                    "imagem_exame" => null,
                    "imagem_ficha_clinica" => null,
                    "imagem_laudo" => null,
                    "laudo" => null,
                    "status_resultado" => null,
                ),
                'agendamento_externo' => null,
                'pode_baixar' => null,
                'msg_exame' => $msg_exame,
                'msg' => $msg,
                'exame_baixado' => null,
            );
        }
        //formata resultado
        $data = array(

            'codigo_pedido' => $codigo_pedido,
            'tipo_exame' => $tipo_exame,
            'data_solicitacao'=> $data_solicitacao,
            'data_agendado'=> $data_agendado,
            'status_pedidos_exames' => $status_pedidos_exames,
            'imprime_aso' => $imprime_aso,
            'dados' => array(
                "codigo_funcionario" => $dados_compl->Funcionario['codigo'],
                "codigo_cliente_funcionario"=> $codigo_cliente_funcionario,
                "codigo_func_setor_cargo" => $codigo_func_setor_cargo,
                "nome" => $dados_compl->Funcionario['nome'],
                "cpf" => $dados_compl->Funcionario['cpf'],
                "idade" => $dados_compl->idade,
                "sexo" => $dados_compl->sexo,
                "empresa" => $dados_compl->Empresa['razao_social'],
                "unidade" => $dados_compl->Unidade['razao_social'],
                "data_admissao" => $dados_compl->ClienteFuncionario['admissao'],
                "cargo" => $dados_compl->cargo
            ),
            'exames'=> $exames

        );

        $this->set('data', $data);
    }

    /**

     * Lista os pedidos de exames de um fornecedor com filtros
     * @param $codigo_fornecedor
     * @param $data_agendamento
     * @param null $tipo_exame
     * @param null $status
     * @param null $especialidade
     * @param null $especialista
     * @return array
     */
    public function getListaPedidos($codigo_fornecedor, $data_agendamento, $tipo_exame = null, $status = null, $especialidade = null, $especialista = null) {

        //Verifica se fornecedor existe
        $dados_consulta = $this->Fornecedores->find()
            ->select(['codigo'])
            ->where(['codigo' => $codigo_fornecedor])
            ->hydrate(false)
            ->first();

        if($dados_consulta) { // Retorna todos os exames do fornecedor
            $lista_pedidos = $this->PedidosExames->retornaPedidosFornecedor($codigo_fornecedor, $data_agendamento, $tipo_exame, $status, $especialidade, $especialista);

        } else {
            $data = ['error'=>'Fornecedor não encontrado.'];
            return $this->responseJson($data);
        }

        $tipo_exames = array(
            'exame_admissional' => 'Exame Admissional',
            'exame_periodico'   => 'Exame Periódico',
            'exame_demissional' => 'Exame Demissional',
            'exame_retorno'     => 'Retorno ao Trabalho',
            'exame_mudanca'     => 'Mudança de Função',
            'exame_monitoracao' => 'Monitoração Pontual',
            'pontual'           => 'Pontual'
        );

        foreach($lista_pedidos as $key => $item) {

            if($item['exame_admissional'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_admissional'];

            if($item['exame_periodico'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_periodico'];

            if($item['exame_demissional'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_demissional'];

            if($item['exame_retorno'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_retorno'];

            if($item['exame_mudanca'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_mudanca'];

            if($item['exame_monitoracao'] == '1')
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['exame_monitoracao'];

            if($item['pontual'] == '1') {
                $lista_pedidos[$key]['tipo_exame'] = $tipo_exames['pontual'];
            }
        }

        foreach ($lista_pedidos as $key => $pedido) {

            $lista_pedidos[$key]['relatorio'] = array();

            $tipo_notificacao_valores = $this->TipoNotificacaoValores->tiposRelatoriosPorPedido($pedido['codigo']);

            foreach ($tipo_notificacao_valores as $key2 => $qtd_vias) {

                if (is_null($qtd_vias['qtd_vias'])) {
                    $tipo_notificacao_valores[$key2]['qtd_vias'] = 1;
                }

                switch ($qtd_vias['codigo_tipo_notificacao']) {
                    case $qtd_vias['codigo_tipo_notificacao'] = 1:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'pedidos_exame';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 2:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'ASO';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 3:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'ficha_clinica';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 4:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'laudo_pcd';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 5:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'Recomendacoes';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 6:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'audiometria';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 7:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'ficha_assistencial_exame';
                        break;
                    case $qtd_vias['codigo_tipo_notificacao'] = 8:
                        $tipo_notificacao_valores[$key2]['nome_relatorio'] = 'psicossocial';
                        break;
                }

            }

            $lista_pedidos[$key]['relatorio'] = $tipo_notificacao_valores;

            unset($lista_pedidos[$key]['GruposEconomicos']);

        }

        $this->set(compact('lista_pedidos', 'tipo_exames'));
    }//FINAL FUNCTION lista_pedidos

    /**
     * Enviar codigo_pedido_exame . tipo do relatorio . quantidade de impressos
     * @param $codigo_pedido_exame_relatorio_quantidade

     * Enviar codigo_pedido_exame . tipo do relatorio . quantidade de impressos
     * @param $codigo_pedido_exame_relatorio_quantidade -> (numero do pedido.tipo relatorio'nome_relatorio'.quantidade_vias)
     * exemplo: /1010.1.1,1010.2.3,1010.3.1/8606
     * exemplo: /1010.6.1/8607

     */
    public function imprimirKit($codigo_pedido_exame_relatorio_quantidade, $codigo_fornecedor)
    {

        //Cria pasta temporaria para armezena os pdf's com permissão 777
        $dir = BASE_CAKE . 'tmp/exames_pdf/';

        exec("rm -Rf {$dir}");

        exec("mkdir {$dir}");
        chmod($dir, 0777);

        $array_dados = explode(",", $codigo_pedido_exame_relatorio_quantidade);

        $nome_relatorio['1'] = 'pedidos_exame';
        $nome_relatorio['2'] = 'ASO';
        $nome_relatorio['3'] = 'ficha_clinica';
        $nome_relatorio['4'] = 'laudo_pcd';
        $nome_relatorio['5'] = 'Recomendacoes';
        $nome_relatorio['6'] = 'audiometria';
        $nome_relatorio['7'] = 'ficha_assistencial_exame';
        $nome_relatorio['8'] = 'psicossocial';

        foreach ($array_dados as $key => $dados) {

            $dados = explode(".", $dados);

            $codigo_pedido_exame = $dados[0];
            $relatorio = $dados[1];
            $quantidade = $dados[2];

            // gerarPDF(), gera os pdf's de acordo com o tipo do relatório
            if ($this->gerarPDF($codigo_pedido_exame, $relatorio, $codigo_fornecedor)) {

                for ($i = 1; $i < $quantidade; $i++) {

                    $copia_relatorio = $nome_relatorio[$relatorio] . "_" . $i;

                    $caminho_realatorio = BASE_CAKE . "tmp/exames_pdf/{$nome_relatorio[$relatorio]}.pdf";
                    $copia_realatorio = BASE_CAKE . "tmp/exames_pdf/{$copia_relatorio}.pdf";

                    exec("cp -rp {$caminho_realatorio} {$copia_realatorio} ");
                }

            } else {
                echo "Error no exame {$key}";
            }
        }

        //Verifica todos os arquivos que foram gerados para concatenar
        $this->concatenarPDF();

        // Apresenta usando cabeçalho apropriado
        $opcoes = array(
            'FILE_NAME'=> "final.pdf" // nome do relatório para saida
        );

        //Pega o arquivo concatenado para baixar
        $arquivo_final = file_get_contents("../tmp/exames_pdf/final.pdf");

        // Adiciona os headers ao pdf concatenado
        header(sprintf('Content-Disposition: attachment; filename="%s"', $opcoes['FILE_NAME']));
        header('Cache-Control: public, must-revalidate, max-age=0');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Origin, X-Request-Width, Content-Type, Accept");
        header('Pragma: public');
        header('Content-type: application/pdf');

        echo $arquivo_final;

        $dir = BASE_CAKE . 'tmp/exames_pdf/';

        //Remove todos os pds's gerados da pasta temporária
        $this->removerArquivos("{$dir}", false);
    }


    public function gerarPDF($codigo_pedido_exame, $relatorio, $codigo_fornecedor=null,$codigo_cliente=null,$codigo_funcioanrio=null)
    {
        $http = new Client();

        $base_portal = BASE_URL_PORTAL;

        $codigo_usuario = $this->getAuthUser();

        $dados_funcionario = $this->PedidosExames->find()
            ->select(['codigo_cliente_funcionario', 'codigo_func_setor_cargo'])
            ->where(['codigo' => $codigo_pedido_exame])->hydrate(false)->first();

        switch ($relatorio) {
            case 1://Pedido de exames
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/{$codigo_fornecedor}/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/ASO");
                break;
            case 2://ASO
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/{$codigo_usuario}/ASO");
                break;
            case 3://Ficha clinica
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Ficha_Clinica");
                break;
            case 4://Laudo PCD
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Laudo_PCD");
                break;
            case 5://Recomendações
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Recomendacoes");
                break;
            case 6://Audiometria
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Audiometria");
                break;
            case 7://Ficha Assistencial
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Ficha_Assistencial");
                break;
            case 8://Psicossocial
                $response = $http->get("{$base_portal}/portal/impressoes/imp_geral/{$codigo_pedido_exame}/null/{$dados_funcionario['codigo_cliente_funcionario']}/{$relatorio}/{$dados_funcionario['codigo_func_setor_cargo']}/Psicossocial");
                break;
        }

        $result = $response->getStringBody();

        $nome_relatorio['1'] = 'pedidos_exame';
        $nome_relatorio['2'] = 'ASO';
        $nome_relatorio['3'] = 'ficha_clinica';
        $nome_relatorio['4'] = 'laudo_pcd';
        $nome_relatorio['5'] = 'Recomendacoes';
        $nome_relatorio['6'] = 'audiometria';
        $nome_relatorio['7'] = 'ficha_assistencial_exame';
        $nome_relatorio['8'] = 'psicossocial';

        $file_name = basename( $nome_relatorio[$relatorio].'.pdf' );

        // opcoes de relatorio
        $newFileName = BASE_CAKE . 'tmp/exames_pdf/'.$file_name;

        $newFileContent = $result;

        if(!empty($result)){

            if (file_put_contents($newFileName, $newFileContent) !== false) {
                chmod($newFileName, 0777);
                return true;
            } else {
                return false;
            }
        }
    }

    public function concatenarPDF()
    {
        $dir = BASE_CAKE . 'tmp/exames_pdf/';
        $files = scandir($dir);

        $all_files = "";

        foreach ($files as $file) {
            $all_files .= "[" . $dir .$file."]";
        }

        $str = str_replace("[{$dir}..]", "", $all_files);
        $str = str_replace("[{$dir}.]", "", $str);
        $str = str_replace("[", "", $str);
        $str = str_replace("]", " ", $str);

        $arquivo_concatenado = BASE_CAKE . "tmp/exames_pdf/final.pdf";
        exec("gs -Rp -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile={$arquivo_concatenado} {$str}");
        chmod("{$arquivo_concatenado}", 0777);

        return true;
    }

    public function removerArquivos($dir, $deleteRootToo)
    {
        if(!$dh = @opendir($dir))
        {
            return;
        }
        while (false !== ($obj = readdir($dh)))
        {
            if($obj == '.' || $obj == '..')
            {
                continue;
            }

            if (!@unlink($dir . '/' . $obj))
            {
                $this->removerArquivos($dir.'/'.$obj, true);
            }
        }
        closedir($dh);
        if ($deleteRootToo)
        {
            @rmdir($dir);
        }
        return;
    }

    public function putAgendamentoExameTriagem()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        $dados = $this->request->getData();

        if (empty($dados['codigo'])) {
            $error[] = 'Insira o campo codigo.';
        }

        $itensPedidosExames = $this->ItensPedidosExames->find()->where(['codigo' => $dados['codigo']])->first();

        if (empty($itensPedidosExames['codigo'])) {
            $error[] = 'Código não encontrado.';
        }

        if(!empty($error)) {
            $this->set(compact('error'));
        }

        if (!empty($dados['data_fim_triagem'])) {

            $dados['data_inicio_realizacao_exame'] = date('Y-m-d H:i:s');
        }

        $entityItensPedidosExames = $this->ItensPedidosExames->patchEntity($itensPedidosExames, $dados);

        if (!$this->ItensPedidosExames->save($entityItensPedidosExames)) {
            $error[] = 'Erro ao editar.';
            $this->set(compact('error'));
        }

        $data = array();
        $data = $entityItensPedidosExames;

        $this->set(compact('data'));
    }

    public function getAuthUser()
    {
        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if (empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        //seta o codigo usuario
        $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

        if (empty($codigo_usuario)) {
            $error = 'Logar novamente o usuario';
            $this->set(compact('error'));
            return;
        }

        return $codigo_usuario;
    }
}

