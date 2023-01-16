<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use App\Utils\Comum;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Exames Controller
 *
 * @property \App\Model\Table\ExamesTable $Exames
 *
 * @method \App\Model\Entity\Exame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExamesController extends AppController
{
    public function getAllExames(){

        $query_params['descricao'] = urldecode($this->request->query('nome'));

        $this->loadModel('Exames');

        $data = $this->Exames->obterLista($query_params);


        if(!empty($data)) {
            $this->set(compact('data'));
        }
        else {
            $error[] =  'Nenhum resultado encontrado.';
            $this->set(compact('error'));
        }

    }

    public function getEstabelecimentosEndereco(){
        if(empty($this->request->query('nome'))){
            $error = 'Parâmetro nome não encontrado';
            $this->set('error', $error);
            return;
        }

        $query_params['descricao'] = urldecode($this->request->query('nome'));

        $this->loadModel('Fornecedores');
        $data = $this->Fornecedores->obterEstabelecimentoEndereco($query_params);

        $this->set('data', $data);
    }

    //Inicio da função para salvar exame
    public function salvarExame(){


        //Carregamento das models para inserção dos dados no BD
        $this->loadModel('UsuarioExames');
        $this->loadModel('UsuarioExamesImagens');
        $dados = $this->request->getData();

        //Criando validator para dados recebidos
        $validator = new Validator();


        //É necessário vir quatro parâmetros: Código do usuário, código do exame,
        //Local (Logradouro), data de realização e foto(s) (não obrigatório)

        $validator->requirePresence(['codigo_usuario', 'local', 'codigo_exame', 'data']);

        //Verificação dos dados recebidos
        $errors = $validator->errors($this->request->getData());
        if (!empty($errors)) {
            $this->set('error', $errors);
            return;
        }

        $data = array();

        $data_realizacao = explode('/', $dados['data']);

        $entity_exame = [
            'codigo_usuario' => $dados['codigo_usuario'],
            'codigo_exames' => $dados['codigo_exame'],
            'data_realizacao' => join('-',array_reverse($data_realizacao)),
            'endereco_clinica' => $dados['local'],
            'data_inclusao' => date("Y-m-d H:i:s"),
            'codigo_usuario_inclusao' => $dados['codigo_usuario'],
        ];

        // Criando entity para inserção
        $enviar_exame = $this->UsuarioExames->newEntity($entity_exame);
        $result = $this->UsuarioExames->save($enviar_exame);

        //Verificação se houve uma nova entrada no BD
        if (!$result) {
            $error="Não foi possível cadastrar o exame.";
            $this->set(compact('error', [$error,$result]));
            return;

        //Se houve sucesso, o resultado é inserido no objeto de retorno
        }else {

            $data['result'] = $result;
        }


        //Verificação para, caso imagens sejam enviadas, construir objeto para salvar no servidor e no BD
        if(!empty($dados['imagem_exame'])){
            $data['imagens'] = array();

            //A(s) imagem(s) devem vir em um array e, para cada uma, construir objeto para BD e servidor
            foreach($dados['imagem_exame'] as $key => $value){
                $foto_url = $this->salvarImagemExameServer($value);
                $entity_imagem = [
                    'codigo_usuario' => $dados['codigo_usuario'],
                    'codigo_usuario_exames' => $result['codigo'],
                    'imagem' => $foto_url,
                    'codigo_usuario_inclusao' => $dados['codigo_usuario'],
                ];

                $enviar_imagem = $this->UsuarioExamesImagens->newEntity($entity_imagem);
                $result_imagem = $this->UsuarioExamesImagens->save($enviar_imagem);

                if (!$result_imagem) {
                    $error="Não foi possível cadastrar o exame.";
                    $this->set(compact('error', [$error,$result]));
                    return;

                //Se houve sucesso, o resultado é inserido no objeto de retorno
                }else {

                    array_push($data['imagens'],$result_imagem);
                }


            }
        }
        //Caso todo o script ocorre com sucesso, este objeto será retornado
        $this->set('data', $data);
    }
    //Fim da função para salvar exame


    public function salvarImagemExameServer($arquivo_base64){

        if(empty($arquivo_base64)){
            return ['error'=>'Imagem não definida corretamente em base64'];
        }
        //monta o array para enviar
        $dados = array(
            'file'   => $arquivo_base64,
            'prefix' => 'nina',
            'type'   => 'base64'
        );

        //url de imagem
        $url_imagem = Comum::sendFileToServer($dados);

        //pega o caminho da imagem
        $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

        //verifica se subiu corretamente a imagem
        if(!empty($caminho_image)) {

            //url criada
            return FILE_SERVER.$caminho_image['path'];
        }

        return ['error'=>'Não foi possível gravar imagem'];
    }

    /**
     * Endpoint para visualizar histórico de histórico de exames com consulta
     *
     * @param integer $codigo_usuario
     * @return array|null
     */
    public function historico(int $codigo_usuario)
    {

            if (empty($codigo_usuario)) {
                $error = 'Parametro codigo_usuario inválido.';
                $this->set(compact('error'));
                return;
            }

            $this->loadModel('Usuario');
            $this->loadModel('ClienteFuncionario');
            $usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);
            $clientes=[];
            if(isset($usuario->codigo_funcionario)){

                $clientes = array_column($this->ClienteFuncionario->find()->where(['codigo_funcionario'=>$usuario->codigo_funcionario])->toArray(), 'codigo_cliente');
            }
            if(empty($usuario) && !isset($usuario->codigo_fexamesuncionario) ){
                $error = 'Usuário não encontrado';
                $this->set(compact('error'));
                return;
            }

            $query_params = [];

            if(!empty($this->request->query('nome'))){
                $query_params['nome'] = $this->request->query('nome');
            }

            $this->loadModel('PedidosExames');
            if(!empty($clientes)){
                $data = $this->PedidosExames->ultimas_consultas($clientes, $usuario->codigo_funcionario, false, $query_params);
                $data = $this->paginate($data);
            } else {
                $data = [];
            }


            $this->set(compact('data'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $exames = $this->paginate($this->Exames);

        $this->set(compact('exames'));
    }

    /**
     * View method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => ['ExamesLog', 'ItensPedidos', 'Pedidos', 'PropostasCredenciamento', 'Riscos']
        ]);

        $this->set('exame', $exame);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exame = $this->Exames->newEntity();
        if ($this->request->is('post')) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $examesLog = $this->Exames->ExamesLog->find('list', ['limit' => 200]);
        $itensPedidos = $this->Exames->ItensPedidos->find('list', ['limit' => 200]);
        $pedidos = $this->Exames->Pedidos->find('list', ['limit' => 200]);
        $propostasCredenciamento = $this->Exames->PropostasCredenciamento->find('list', ['limit' => 200]);
        $riscos = $this->Exames->Riscos->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'examesLog', 'itensPedidos', 'pedidos', 'propostasCredenciamento', 'riscos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exame = $this->Exames->get($id, [
            'contain' => ['ExamesLog', 'ItensPedidos', 'Pedidos', 'PropostasCredenciamento', 'Riscos']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exame = $this->Exames->patchEntity($exame, $this->request->getData());
            if ($this->Exames->save($exame)) {
                $this->Flash->success(__('The exame has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exame could not be saved. Please, try again.'));
        }
        $examesLog = $this->Exames->ExamesLog->find('list', ['limit' => 200]);
        $itensPedidos = $this->Exames->ItensPedidos->find('list', ['limit' => 200]);
        $pedidos = $this->Exames->Pedidos->find('list', ['limit' => 200]);
        $propostasCredenciamento = $this->Exames->PropostasCredenciamento->find('list', ['limit' => 200]);
        $riscos = $this->Exames->Riscos->find('list', ['limit' => 200]);
        $this->set(compact('exame', 'examesLog', 'itensPedidos', 'pedidos', 'propostasCredenciamento', 'riscos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Exame id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exame = $this->Exames->get($id);
        if ($this->Exames->delete($exame)) {
            $this->Flash->success(__('The exame has been deleted.'));
        } else {
            $this->Flash->error(__('The exame could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getExameDetalhe($codigo_id_agendamento = null){
        
            //verifica se esta vazio o codigo id agendamento
            if (empty($codigo_id_agendamento)) {
                $error = 'Parametro codigo_id_agendamento inválido.';
            }
            else {

                $data = array();

                $this->loadModel('PedidosExames');

                //pega os dados
                $dados = $this->PedidosExames->consultas($codigo_id_agendamento);
                
                $codigo_fornecedor = null;

                //verifica se existe os dados
                if(!empty($dados)) {

                    $this->loadModel('FornecedoresHorario');

                    //pega os dados de horario da clinica
                    $fornecedor_horario = $this->FornecedoresHorario->find()->where(['codigo_fornecedor' => $dados->codigo_fornecedor])->all()->toArray();
                    
                    $codigo_fornecedor = $dados->codigo_fornecedor;

                    $horarios = array();
                    $abertura = array();

                    $dia_semana_hoje = date('w', strtotime(date('Y-m-d')));

                    //verifica se existe os dados de hr do fornecedor
                    if(!empty($fornecedor_horario)) {
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
                                    $str_dia_semana_hoje = Comum::diaDaSemana($dia_semana_hoje);

                                    if($diaSemana == $str_dia_semana_hoje) {

                                        $abe = 'false';
                                        if((date('Hi') >= $fh['de_hora']) && (date('Hi') <= $fh['ate_hora'])) {
                                            $abe = 'true';
                                        }


                                        $abertura = array(
                                            'horario_atendimento_atual' => Comum::formataHora($fh['de_hora']) .' - '. Comum::formataHora($fh['ate_hora']),
                                            'abertura' => $abe
                                        );
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
                                $str_dia_semana_hoje = Comum::diaDaSemana($dia_semana_hoje);

                                if($dias_semana == $str_dia_semana_hoje) {

                                    $abe = 'false';
                                    if((date('Hi') >= $fh['de_hora']) && (date('Hi') <= $fh['ate_hora'])) {
                                        $abe = 'true';
                                    }


                                    $abertura = array(
                                        'horario_atendimento_atual' => Comum::formataHora($fh['de_hora']) .' - '. Comum::formataHora($fh['ate_hora']),
                                        'abertura' => $abe
                                    );
                                }

                                $horarios[] = array(
                                    "dia_semana" => $dias_semana,// "Segunda-Feira",
                                    "horario_inicio" => Comum::formataHora($fh['de_hora']),// "08:00",
                                    "horario_fim" => Comum::formataHora($fh['ate_hora']),// "17:00"
                                );
                            }
                        }//fim foreach fornecedor horario

                    }//fim if fornecedor horario

                    $data_auxiliar = explode(" ",$dados->data);
                    if(!empty($data_auxiliar[1]) && !strpos($data_auxiliar[1], ":" )){
                        $data_auxiliar[1] = substr($data_auxiliar[1],0,2).":".substr($data_auxiliar[1],-2,2);
                    }

                    $dados->data = join(" ", $data_auxiliar);
                    $resultado_exame = ['1'=>"Normal", '2'=>'Alterado', '3'=>'Estável', '4'=>'Agravamento', '5'=>'Referencial', '6'=>'Sequencial'];




                    //formata a saida
                    $data = array(
                        'agendamento_detalhe' => array(
                            'data' => $dados->data_realizacao_exames,
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
                                "imagem_exame" => $dados->imagem_exame,
                                "imagem_ficha_clinica" => $dados->imagem_ficha_clinica,
                                "resultado_pedido_exame" => isset($resultado_exame[$dados->resultado_pedido_exame]) ? $resultado_exame[$dados->resultado_pedido_exame] : '',
                            ),
                            "local_agendamento" => array(
                                'codigo_fornecedor' => $dados->codigo_fornecedor,
                                "nome" => $dados->nome_credenciado,
                                "lat" => $dados->lat ,// "-29.661655",
                                "long" => $dados->long ,// "-51.143918",
                                "endereco" => $dados->endereco ,// "Av Assis Brasil, 1548, Passo d'Areia, Porto Alegre - RS",
                                "avalicao" => $dados->avaliacao ,// 4.3,
                                "total_avaliacoes" => $dados->total_avaliacoes ,// 6,
                                "telefone_clinica" => $dados->telefone ,// 555135950055,
                                "email_clinica" => $dados->email,// "email@clinica.com.br",
                                "abertura" => $abertura,
                                "horario_funcionamento" => $horarios,
                            )
                        ),
                    );
                    $this->loadModel("FornecedoresAvaliacoes");

                    $avaliacao = $this->FornecedoresAvaliacoes->getFornecedorNota($codigo_fornecedor);
                    $nota = isset($avaliacao['pontuacao_arredondada']) ? $avaliacao['pontuacao_arredondada'] : 0;
                    $total_avaliacoes = isset($avaliacao['quantidade_avaliacoes']) ? $avaliacao['quantidade_avaliacoes'] : 0;
                    $data['agendamento_detalhe']["local_agendamento"]['nota'] = $nota;
                    $data['agendamento_detalhe']["local_agendamento"]['total_avaliacoes'] = $total_avaliacoes;
                    $data['agendamento_detalhe']["local_agendamento"]['avaliacao'] = $avaliacao;


                }//fim empty dados

            }//fim empty codigo_id_agendamento

            $this->set(compact('data'));


    }

    /**
     * Endpoint para visualizar histórico de histórico de exames realizados
     * Retorna exames assistenciais e ocupacionais
     *
     * @param integer $codigo_usuario
     * @return array|null
     */
    public function historicoExamesOcupacionaisAssistenciais($codigo_usuario)
    {

        if (empty($codigo_usuario)) {
            $error = 'Parametro codigo_usuario inválido.';
            $this->set(compact('error'));
            return;
        }

        $this->loadModel('Usuario');
        $this->loadModel('UsuarioExames');
        $this->loadModel('PedidosExames');

        $pagina_atual = ($this->request->query('page') && $this->request->query('page') > 0) ? intval($this->request->query('page')) : 1;
        $por_pagina = 5;
        $offset = !empty($pagina_atual) ? ($pagina_atual - 1) * $por_pagina : 0;

        // $this->loadModel('Usuario');
        // $this->loadModel('UsuarioExames');
        // $this->loadModel('PedidosExames');

        // $pagina_atual = ($this->request->query('page') && $this->request->query('page') > 0) ? intval($this->request->query('page')) : 0;
        // $por_pagina = 5;
        // $offset = !empty($pagina_atual) ? ($pagina_atual - 1) * $por_pagina : 0;

        $conexao = ConnectionManager::get('default');

        $usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);

        if(empty($usuario) && !isset($usuario->codigo_funcionario) ){
            $error = 'Usuário não encontrado';
            $this->set(compact('error'));
            return;
        }
        $condicoesOc = [];
        $condicoesAs = [];

        if(!empty($this->request->query('query'))){
            $condicoesOc["RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao) LIKE"] = "%".$this->request->query('query')."%";
            $condicoesAs["RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao) LIKE"] = "%".$this->request->query('query')."%";
        }

        $condicoesAs["UsuarioExames.codigo_usuario"] = $codigo_usuario;
        $condicoesOc["codigo_funcionario"] = $usuario->codigo_funcionario;
        
        $assistencial = $this->UsuarioExames->historico_exames($condicoesAs);
        
        $ocupacional = $this->PedidosExames->historico_exames_ocupacional($condicoesOc, false);

        $unionQuery = $ocupacional->unionAll($assistencial);

        $total_registros = $unionQuery->epilog(
            $conexao->newQuery()
        )->count();

        $data = $unionQuery->epilog(
            $conexao->newQuery()->order(['data_realizacao' => 'DESC'])->limit($por_pagina)->offset($offset)
        );

        $total_paginasCt = intval(ceil($total_registros / $por_pagina));
        $total_paginas = $total_paginasCt > 0 ? $total_paginasCt : 1;

        $pagination = array(
            "page_count" => $total_paginas,
            "current_page" => $pagina_atual,
            "has_next_page" => $pagina_atual < $total_paginas ? true : false,
            "has_prev_page" => $pagina_atual > 1 ? true : false,
            "count" => $total_registros,
            "limit" => $por_pagina,
            "offset" => $offset
        );

        $this->set(compact('pagination'));
        $this->set(compact('data'));
        }

    /**
     * Endpoint para visualizar dados de um exame ocupacional realizado
     *
     * @param integer $codigo_exame
     * @return array|null
     */
    public function exameOcupacional(int $codigo_exame)
    {

        if (empty($codigo_exame)) {
            $error = 'Parametro codigo_exame inválido.';
            $this->set(compact('error'));
            return;
        }

        $this->loadModel('PedidosExames');
        $this->loadModel('FornecedoresAvaliacoes');

        $condicoes["ItemPedidoExame.codigo"] = $codigo_exame;
        $result = $this->PedidosExames->historico_exames_ocupacional($condicoes, true);

        if($result->count() == 0) {
            $error = 'Exame não encontrado';
            $this->set(compact('error'));
            return;
        }

        $result = $result->toArray()[0];
        $avaliacao = $this->FornecedoresAvaliacoes->getFornecedorNota($result['codigo_fornecedor']);

        $data = array(
            "tipo" => $result['tipo'],
            "titulo_tipo" => $result['titulo_tipo'],
            "codigo" => $result['codigo'],
            "codigo_exame" => $result['codigo_exame'],
            "exame" => $result['exame'],
            "data_realizacao" => $result['data_realizacao'],
            "clinica" => $result['clinica'],
            "clinica_telefone" => $result['clinica_telefone'],
            "clinica_email" => $result['clinica_email'],
            "clinica_endereco" => $result['clinica_endereco'],
            "empresa" => $result['empresa'],
            "resultado" => $result['resultado'],
            "clinica_avaliacao" => $avaliacao,
            "clinica_nota" => isset($avaliacao['pontuacao_arredondada']) ? $avaliacao['pontuacao_arredondada'] : 0,
            "clinica_total_avaliacoes" => isset($avaliacao['quantidade_avaliacoes']) ? $avaliacao['quantidade_avaliacoes'] : 0,
            "exame_imagem" => $result['imagem_exame'],
            "id_agendamento" => $result['id_agendamento'],
            "local_agendamento" => array(
                'codigo_fornecedor' => $result->codigo_fornecedor,
                "nome" => $result->nome_credenciado,
                "lat" => $result->lat ,// "-29.661655",
                "long" => $result->long ,// "-51.143918",
                "endereco" => $result->endereco ,// "Av Assis Brasil, 1548, Passo d'Areia, Porto Alegre - RS",
            )
        );

        $this->set(compact('data'));

    }

    /**
     * Endpoint para visualizar dados de um exame assistencial realizado
     *
     * @param integer $codigo_exame
     * @return array|null
     */

    public function exameAssistencial(int $codigo_exame)
    {

        if (empty($codigo_exame)) {
            $error = 'Parametro codigo_exame inválido.';
            $this->set(compact('error'));
            return;
        }

        $this->loadModel('UsuarioExames');

        $condicoes["UsuarioExames.codigo"] = $codigo_exame;
        $data = $this->UsuarioExames->historico_exames($condicoes)->limit(1);

        if(empty($data)) {
            $error = 'Exame não encontrado';
            $this->set(compact('error'));
            return;
        }

        $registro = $data->toArray()[0];

        $this->loadModel('UsuarioExamesImagens');
        $imagens = $this->UsuarioExamesImagens->imagensExames($registro->codigo);

        $data = array(
            "tipo" => $registro->tipo,
            "titulo_tipo" => $registro->titulo_tipo,
            "codigo" => $registro->codigo,
            "codigo_exame" => $registro->codigo_exame,
            "exame" => $registro->exame,
            "clinica" => $registro->clinica,
            "data_realizacao" => !empty($registro->data_realizacao) ? date_format($registro->data_realizacao, "Y-m-d") : null,
            "exame_imagem" => $imagens
        );

        $this->set(compact('data'));

    }


}
