<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use DateInterval;
use DateTime;

/**
 * LevantamentoChamado Controller
 *
 * @property \App\Model\Table\LevantamentosChamadosTable $LevantamentosChamados
 *
 * @method \App\Model\Entity\LevantamentosChamado[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LevantamentoChamadoController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        $this->loadModel('LevantamentosChamados');
        $this->loadModel("Chamados");
        $this->loadModel("Processos");
        $this->loadModel("ClienteEndereco");
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($codigo_cliente)
    {
        $levantamentoChamado = $this->LevantamentosChamados->find()->where(['codigo_cliente' => $codigo_cliente]);

        $this->set(compact('levantamentoChamado'));
    }

    /**
     * View method LevantamentoChamado
     *
     * url: /api/levantamento/10011
     * url: /api/levantamento/10011/4
     *
     * @param string|null $id Levantamento Chamado id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_cliente, $codigo_levantamento = null)
    {
        if (!isset($codigo_levantamento)) { // GET ALL ($codigo_cliente)
            $levantamentoChamado = $this->LevantamentosChamados->find()->where(['codigo_cliente' => $codigo_cliente]);

            foreach ($levantamentoChamado as $key => $levantamento) {

                //Get endereço do cliente
                $cliente_dados = $this->LevantamentosChamados->getDadosEmpresa($levantamento['codigo_cliente']);
                $levantamento['dados_cliente'] = $cliente_dados;

                //Get processos
                $processo = $this->Processos->find()->where(['codigo_levantamento_chamado' => $levantamento['codigo']])->first();
                $levantamento['processo'] = $processo;

                //Get responsável pelo chamado
                $chamado = $this->Chamados->find()->select(['responsavel', 'data_adiar_para', 'razao_adiar', 'data_cancelamento', 'razao_cancelamento'])->where(['codigo' => $levantamento['codigo_chamado']])->first();
                $levantamento['responsavel'] = $chamado['responsavel'];
                $levantamento['data_adiar_para'] = $chamado['data_adiar_para'];
                $levantamento['razao_adiar'] = $chamado['razao_adiar'];
                $levantamento['data_cancelamento'] = $chamado['data_cancelamento'];
                $levantamento['razao_cancelamento'] = $chamado['razao_cancelamento'];
            }

            $this->set(compact('levantamentoChamado'));
        } else { // GET BY CODE ($codigo_levantamento)

            $levantamentoChamado = $this->LevantamentosChamados->find()->where(['codigo' => $codigo_levantamento])->first();

            if (!empty($levantamentoChamado)) {

                //Get endereço do cliente
                $cliente_dados = $this->LevantamentosChamados->getDadosEmpresa($levantamentoChamado['codigo_cliente']);
                $levantamentoChamado['dados_cliente'] = $cliente_dados;

                //Get processos
                $processo = $this->Processos->find()->where(['codigo_levantamento_chamado' => $levantamentoChamado['codigo']])->first();
                $levantamentoChamado['processo'] = $processo;

                //Get responsável pelo chamado
                $chamado = $this->Chamados->find()->select(['responsavel', 'data_adiar_para', 'razao_adiar', 'data_cancelamento', 'razao_cancelamento'])->where(['codigo' => $levantamentoChamado['codigo_chamado']])->first();
                $levantamentoChamado['responsavel'] = $chamado['responsavel'];
                $levantamentoChamado['data_adiar_para'] = $chamado['data_adiar_para'];
                $levantamentoChamado['razao_adiar'] = $chamado['razao_adiar'];
                $levantamentoChamado['data_cancelamento'] = $chamado['data_cancelamento'];
                $levantamentoChamado['razao_cancelamento'] = $chamado['razao_cancelamento'];
            }

            $this->set('levantamentoChamado', $levantamentoChamado);
        }
    }

    /**
     * View method LevantamentoChamado
     *
     * url: /api/levantamento/10011
     * url: /api/levantamento/10011/4
     *
     * @param string|null $id Levantamento Chamado id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewAll($codigo_cliente)
    {

        $levantamentoChamado = $this->LevantamentosChamados->find()->where(['codigo_cliente' => $codigo_cliente]);
        // debug($levantamentoChamado->hydrate(false)->toArray());exit;

        foreach ($levantamentoChamado as $key => $levantamento) {

            //Get endereço do cliente
            $cliente_dados = $this->LevantamentosChamados->getDadosEmpresa($levantamento['codigo_cliente']);
            $levantamento['dados_cliente'] = $cliente_dados;

            //Get processos all processos
            $processo = $this->Processos->getProcessos(null,$levantamento['codigo']);
            $levantamento['processo'] = $processo;
            
            //Get responsável pelo chamado
            $chamado = $this->Chamados->find()->select(['responsavel', 'data_adiar_para', 'razao_adiar', 'data_cancelamento', 'razao_cancelamento'])->where(['codigo' => $levantamento['codigo_chamado']])->first();
            $levantamento['responsavel'] = $chamado['responsavel'];
            $levantamento['data_adiar_para'] = $chamado['data_adiar_para'];
            $levantamento['razao_adiar'] = $chamado['razao_adiar'];
            $levantamento['data_cancelamento'] = $chamado['data_cancelamento'];
            $levantamento['razao_cancelamento'] = $chamado['razao_cancelamento'];
        }

        $this->set(compact('levantamentoChamado'));

    }//fim viewAll

    public function getLevantamentosPorResponsavel($codigo_cliente, $codigo_responsavel)
    {
        // pega o parametro pendente da url e verifica se esta true
        $pendente = null;
        if ($this->request->getQuery('pendente') !== null) {
            $pendente = $this->request->getQuery('pendente');
            if ($pendente === "true") {
                $pendente = true;
            }
        }

        $levantamentoChamado = $this->LevantamentosChamados
            ->getLevantamentosPorResponsavel($codigo_cliente, $codigo_responsavel, $pendente);

        foreach ($levantamentoChamado as $key => $levantamento) {

            //Get endereço do cliente
            $cliente_dados = $this->LevantamentosChamados->getDadosEmpresa($levantamento['codigo_cliente']);
            $levantamento['dados_cliente'] = $cliente_dados;

            //Get processos
            $processo = $this->Processos->find()->where(['codigo_levantamento_chamado' => $levantamento['codigo']])->first();
            $levantamento['processo'] = $processo;
        }

        $this->set(compact('levantamentoChamado'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $levantamentoChamado = $this->LevantamentosChamados->newEntity();
        if ($this->request->is('post')) {
            $levantamentoChamado = $this->LevantamentosChamados->patchEntity($levantamentoChamado, $this->request->getData());
            if ($this->LevantamentosChamados->save($levantamentoChamado)) {
                $this->Flash->success(__('The levantamento chamado has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The levantamento chamado could not be saved. Please, try again.'));
        }
        $this->set(compact('levantamentoChamado'));
    }

    /**
     * @function addChamado()
     * url: /api/chamado (POST)
     * payload
     * {
     *    "codigo_cliente": 10011,
     *    "descricao": "Novo chamado para gestão risco",
     *    "data_original": "2020-10-23 10:00:00",
     *    "codigo_chamado_tipo": 1,
     *    "codigo_chamado_status": 1,
     *    "responsavel": 73251
     *    "descricao_levantamento": "Vamos precisar fazer um novo levantamento"
     * }
     * */
    public function postChamado()
    {
        //Abre a transação
        $this->connect->begin();

        try {
            //seta para o retorno do objeto
            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();
            $codigo_usuario = $this->getAuthUser();

            //Define campos gerados pelo sistema
            $dados['codigo_usuario_inclusao'] = $codigo_usuario;
            $dados['data_inclusao'] = date('Y-m-d H:i:s');

            //Cria no entity para salvar no banco
            $entityChamado = $this->Chamados->newEntity($dados);

            $entityChamado['data_adiar_para'] = $entityChamado['data_original'];

            if (!$this->Chamados->save($entityChamado)) {
                $data['message'] = 'Erro ao inserir em Chamados';
                $data['error'] = $entityChamado->errors();
                $this->set(compact('data'));
                return;
            }

            //Define dados para inserir em LevantamentosChamado
            $dadosLevantamentoChamado = array();
            $dadosLevantamentoChamado['codigo_chamado'] = $entityChamado['codigo'];
            $dadosLevantamentoChamado['codigo_cliente'] = $entityChamado['codigo_cliente'];
            $dadosLevantamentoChamado['codigo_usuario_inclusao'] = $codigo_usuario;
            $dadosLevantamentoChamado['data_inclusao'] = date('Y-m-d H:i:s');
            $dadosLevantamentoChamado['observacao'] = $entityChamado['descricao'];
            $dadosLevantamentoChamado['codigo_levantamento_chamado_status'] = 1;
            $dadosLevantamentoChamado['descricao'] = $entityChamado['descricao_levantamento'];

            $levantamentoChamadoEntity = $this->LevantamentosChamados->newEntity($dadosLevantamentoChamado);

            if (!$this->LevantamentosChamados->save($levantamentoChamadoEntity)) {
                $data['message'] = 'Erro ao inserir em LevantamentosChamados';
                $data['error'] = $levantamentoChamadoEntity->errors();
                $this->set(compact('data'));
                return;
            }

            //Define dados para inserir em Processos
            $dadosProcessos = array();
            $dadosProcessos['codigo_levantamento_chamado'] = $levantamentoChamadoEntity['codigo'];
            $dadosProcessos['codigo_cliente'] = $levantamentoChamadoEntity['codigo_cliente'];
            $dadosProcessos['codigo_usuario_inclusao'] = $codigo_usuario;
            $dadosProcessos['data_inclusao'] = date('Y-m-d H:i:s');

            $processosEntity = $this->Processos->newEntity($dadosProcessos);

            if (!$this->Processos->save($processosEntity)) {
                $data['message'] = 'Erro ao inserir em Processos';
                $data['error'] = $processosEntity->errors();
                $this->set(compact('data'));
                return;
            }

            $entityChamado['levantamento'] = $levantamentoChamadoEntity;
            $entityChamado['processo'] = $processosEntity;

            $data[] = $entityChamado;

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function putChamado()
    {
        //Abre a transação
        $this->connect->begin();

        try {
            //seta para o retorno do objeto
            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();
            $codigo_usuario = $this->getAuthUser();

            //Define campos gerados pelo sistema
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');

            // Essa linha é para não permitir que altere a data_original e data_adiar_de pelo PUT
            unset($dados['data_original']);
            unset($dados['data_adiar_de']);

            $chamados = $this->Chamados->find()
                ->where(['codigo' => $dados['codigo']])->first();

            if (empty($chamados)) {
                $error = 'Não foi possivel encontrar o chamado!';
                $this->set(compact('error'));
                return;
            }

            $dados['data_adiar_de'] = $chamados['data_adiar_para'];

            //Cria no entity para salvar no banco
            $entityChamado = $this->Chamados->patchEntity($chamados, $dados);

            if (!$this->Chamados->save($entityChamado)) {
                $data['message'] = 'Erro ao editar em Chamados';
                $data['error'] = $entityChamado->errors();
                $this->set(compact('data'));
                return;
            }

            $data = $entityChamado;

            // Salva dados
            $this->connect->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }
    /**
     * Edit method
     *
     * url: /api/levantamento/10011/4
     * PAYLOAD
     {
        "data_adiamento": "2020-07-30",
        "observacao": "Observação TEXT",
        "codigo_levantamento_chamado_status": 2
     }
     *
     * @param string|null $id Levantamento Chamado id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($codigo_cliente, $codigo_levantamento_chamado)
    {
        //pega os dados que veio do post
        $dados = $this->request->getData();

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

        // Retorna dados do levantamento
        $levantamentoChamado = $this->LevantamentosChamados->find()->where(['codigo_cliente' => $codigo_cliente, 'codigo' => $codigo_levantamento_chamado])->first();

        if (empty($levantamentoChamado)) {
            $data['error'] = 'LevantamentosChamados inexistente';
            $this->set(compact('data'));
            return;
        }

        if ($this->request->is(['patch', 'put'])) {

            //Define campos gerados pelo sistema
            $dados['codigo_usuario_alterecao'] = $codigo_usuario;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');

            //Formata data adiamento para inserir
            $date = new DateTime($dados['data_adiamento']);
            $dados['data_adiamento'] = $date->format('Y-m-d H:i:s') ;

            $entityLevantamentoChamado = $this->LevantamentosChamados->patchEntity($levantamentoChamado, $dados);

            if (!$this->LevantamentosChamados->save($entityLevantamentoChamado)) {
                $data['message'] = 'Erro ao editar em LevantamentoChamado';
                $data['error'] = $entityLevantamentoChamado->errors();
                $this->set(compact('data'));
                return;
            }
        }

        $this->set(compact('dados'));
    }

    public function editAvaliacao()
    {
        $this->request->allowMethod(['put']);

        $data = array();

        $request = $this->request->getData();
        $codigo_usuario = $this->getAuthUser();

        $levantamentoChamado = $this->LevantamentosChamados
            ->find()
            ->where(['codigo' => $request['codigo_levantamento_chamado']])
            ->first();

        if (empty($levantamentoChamado)) {
            $error = "Código {$request['codigo_ghe']} do Levantamento de chamado inexistente";
            $this->set(compact('error'));
            return;
        }

        $dados = array();

        $dados["codigo_usuario_gestor_operacao"]    = $request["codigo_usuario_gestor_operacao"];
        $dados["codigo_usuario_tecnico_ehs"]        = $request["codigo_usuario_tecnico_ehs"];
        $dados["codigo_usuario_operador"]           = $request["codigo_usuario_operador"];
        $dados["data_inicio_avaliacao"]             = $request["data_inicio_avaliacao"];
        $dados["data_fim_avaliacao"]                = $request["data_fim_avaliacao"];
        $dados["companheiro_avaliacao"]             = $request["companheiro_avaliacao"];
        $dados["nota_avaliacao"]                    = $request["nota_avaliacao"];
        $dados["descricao_avaliacao"]               = $request["descricao_avaliacao"];
        $dados['codigo_usuario_alteracao']          = $codigo_usuario;
        $dados['data_alteracao']                    = date('Y-m-d H:i:s');

        $entityLevantamentoChamado = $this->LevantamentosChamados->patchEntity($levantamentoChamado, $dados);

        if (!$this->LevantamentosChamados->save($entityLevantamentoChamado)) {
            $data['message'] = 'Erro ao alterar Levantamento de chamado';
            $data['error'] = $entityLevantamentoChamado->errors();
            $this->set(compact('data'));
            return;
        }

        $this->connect->commit();
        $this->set(['levantamentoChamado' => $entityLevantamentoChamado]);
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
