<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Http\Client;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

use InvalidArgumentException;


/**
 * Audiometrias Controller
 *
 *
 * @method \App\Model\Entity\Audiometria[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AudiometriasController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $audiometrias = $this->paginate($this->Audiometrias);

        $this->set(compact('audiometrias'));
    }

    /**
     * View method
     *
     * metodo para pegar os dados de como montar o formulario de audiometria
     *
     * @param string|null $id Audiometria id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $codigo_usuario, int $codigo_pedido_exame)
    {
        //pega os campos para montar o formulario
        $audiometria = $this->Audiometrias->getCamposAudiometria($codigo_usuario, $codigo_pedido_exame);

        //seta a variavel para retornar
        $data = array();
        //verifica se existe dados na audiometria
        if(!empty($audiometria)) {
            $data = $audiometria;
        }

        // debug($data);exit;
        // echo json_encode( $data,JSON_FORCE_OBJECT);
        // exit;
        
        $this->set(compact('data'));
        // return $this->responseJson($data);

    }//fim view audiometria

    /**
     * [getDatadosAudiometrias metodo para pegar os dados cadastrados da audiometria]
     *
     * @param  int    $codigo_pedido_exame [description]
     * @return [type]                       [description]
     */
    public function getDatadosAudiometrias(int $codigo_pedido_exame)
    {

        //variavel auxiliar para o retorno do metodo
        $data = array();

        //pega os dados da ficha clinica
        //$audiometria = $this->Audiometrias->find()->where(['codigo' => $codigo_audiometria])->first();
        $audiometria = $this->Audiometrias->getAudiometriaPorPedidoExame($codigo_pedido_exame);

        if(empty($audiometria)) {
            $error[] = "Audiometria não encontrada!";
            $this->set(compact('error'));
            return;
        }
        $audiometria = $audiometria->toArray();

        //formata a hora pois o cake colocar a funcao dele onde bagunça
        $audiometria['data_exame'] = $audiometria['data_exame']->i18nFormat('dd/MM/yyyy');
        $audiometria['calibracao'] = $audiometria['calibracao']->i18nFormat('dd/MM/yyyy');

        // debug($audiometria);exit;

        //pega o codigo do pedido de exame
        $codigo_item_pedido_exame = $audiometria['codigo_itens_pedidos_exames'];
        $codigo_usuario = $audiometria['codigo_usuario_inclusao'];

        //pega os dados da ficha clinica
        $formulario = $this->Audiometrias->getCamposAudiometria($codigo_usuario, $codigo_pedido_exame);
        // debug($formulario);exit;
        if(!isset($formulario['formulario'])) {

            $error = $formulario;
            $this->set(compact('error'));
            return;
        }
        // debug($formulario);
        //varre as questoes para colocar as respostas
        foreach ($formulario['formulario'] as $keyForm => $form) {
            //varre os grupos
            foreach($form AS $keyGrupo => $tipoGrupo) {

                if(isset($tipoGrupo['questao'])) {

                    //varre as questoes
                    foreach($tipoGrupo['questao'] AS $keyQuestao => $questao) {

                        //configura o formulario
                        $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = null;

                        //grupo header vem os campos da ficha clinica
                        //separa para pegar o indice 1 que tem o nome do campo
                        $separa_nome = explode(".",$questao['name']);

                        if(!isset($separa_nome[1])) {
                            continue;
                        }

                        if(isset($audiometria[$separa_nome[1]])) {

                            //verifica o valor dos campos resultado, meatoscopia_od,meatoscopia_oe
                            if($separa_nome[1] == 'resultado') {
                                $audiometria[$separa_nome[1]] = ($audiometria[$separa_nome[1]] == '0') ? '10' : $audiometria[$separa_nome[1]];
                            }
                            if($separa_nome[1] == 'meatoscopia_od') {
                                $audiometria[$separa_nome[1]] = ($audiometria[$separa_nome[1]] == '0') ? '10' : $audiometria[$separa_nome[1]];
                            }
                            if($separa_nome[1] == 'meatoscopia_oe') {
                                $audiometria[$separa_nome[1]] = ($audiometria[$separa_nome[1]] == '0') ? '10' : $audiometria[$separa_nome[1]];
                            }
                            
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $audiometria[$separa_nome[1]];
                        }

                    }//fim questoes
                }//fim questoes
                else { //as tabelas

                    
                    // debug($tipoGrupo);//exit;

                    if(isset($tipoGrupo['linha'])) {
                        //varre as tabelas
                        foreach($tipoGrupo['linha'] AS $keyTabelaPrincipal => $dadosTabela){


                            foreach($dadosTabela as $keyDadosTabela => $conteudo) {
                                
                                if($conteudo['tipo'] == 'LABEL') {
                                    continue;
                                }

                                //separa para pegar o indice 1 que tem o nome do campo
                                $separa_nome = explode(".",$conteudo['name']);

                                if(!isset($separa_nome[1])) {
                                    continue;
                                }

                                $formulario['formulario'][$keyForm][$keyGrupo]['linha'][$keyTabelaPrincipal][$keyDadosTabela]['resposta'] = '';
                                if(isset($audiometria[$separa_nome[1]])) {
                                    $formulario['formulario'][$keyForm][$keyGrupo]['linha'][$keyTabelaPrincipal][$keyDadosTabela]['resposta'] = $audiometria[$separa_nome[1]];
                                }
                            }


                        }//fim foreach das tabelas                        
                    }
                    //seta as observacoes
                    if(isset($tipoGrupo['label'])) {
                        // debug($tipoGrupo['label']);exit;
                        if($tipoGrupo['label'] == "Observações:") {
                            $formulario['formulario'][$keyForm][$keyGrupo]['resposta'] = $audiometria['observacoes2'];
                        }
                    }


                }//fim else tabelas

            }//fim tipogrupo
        }//fim formulario

        // exit;

        // debug($formulario);
        // exit;
        $data = $formulario;

        // echo json_encode( $data,JSON_FORCE_OBJECT);
        // exit;

        $this->set(compact('data'));
        return;


    }//fim getDatadosAudiometrias

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        // debug($this->request->getData());exit;

        $data = '';

        //verfica se é post
        if ($this->request->is('post')) {

            //abrir transacao
            $conn = ConnectionManager::get('default');

            try{

                //abre a transacao
                $conn->begin();

                //seta a variavel para os dados
                $dados = $this->request->getData();

                //seta o codigo do usuario que fez o post
                $codigo_usuario = $dados['codigo_usuario'];
                $codigo_itens_pedidos_exames = $dados['Audiometria']['codigo_itens_pedidos_exames'];

                //seta o codigo do usuario que esta incluindo
                $dados['Audiometria']['codigo_usuario_inclusao'] = $codigo_usuario;
                // $dados['Audiometria']['codigo_empresa'] = "1";
                $dados['Audiometria']['data_inclusao'] = date('Y-m-d H:i:s');

                $dados['Audiometria']['data_exame'] = (!empty($dados['Audiometria']['data_exame'])) ? $dados['Audiometria']['data_exame'] : null ;
                $dados['Audiometria']['calibracao'] = (!empty($dados['Audiometria']['calibracao'])) ? $dados['Audiometria']['calibracao'] : null ;
                // debug($dados['Audiometrias']);exit;
                
                //verifica o valor dos campos resultado, meatoscopia_od,meatoscopia_oe
                $dados['Audiometria']['resultado'] = (isset($dados['Audiometria']['resultado'])) ? ($dados['Audiometria']['resultado'] == 10) ? 0 : $dados['Audiometria']['resultado'] : null;
                $dados['Audiometria']['meatoscopia_od'] = (isset($dados['Audiometria']['meatoscopia_od'])) ? ($dados['Audiometria']['meatoscopia_od'] == 10) ? 0 : $dados['Audiometria']['meatoscopia_od'] : null;
                $dados['Audiometria']['meatoscopia_oe'] = (isset($dados['Audiometria']['meatoscopia_oe'])) ? ($dados['Audiometria']['meatoscopia_oe'] == 10) ? 0 : $dados['Audiometria']['meatoscopia_oe'] : null;


                $audiometria = $this->Audiometrias->newEntity($dados['Audiometria']);

                //verifica se vai gravar corretamente os dados na ficha clinica
                if (!$this->Audiometrias->save($audiometria)) {
                    throw new Exception(json_encode($audiometria->getValidationErrors($audiometria)));
                }

                //recupera o codigo da ficha clinica
                $codigo_audiometria = $audiometria->codigo;

                //dados de retorno
                $data = [
                    'retorno'=>'Audiometria inserida com sucesso!',
                    'codigo_itens_pedidos_exames' => $codigo_itens_pedidos_exames,
                    'codigo_usuario'=>$codigo_usuario,
                    'codigo_audiometria'=>$codigo_audiometria
                ];

                $conn->commit();

            } catch (\Exception $e) {

                //rollback da transacao
                $conn->rollback();

                $error[] = $e->getMessage();
                $this->set(compact('error'));
                return;
            }//fim try/catch

        }//fim do post

        $this->set(compact('data'));
        return;
    }

    /**
     * Edit method
     *
     * @param string|null $id Audiometria id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $audiometria = $this->Audiometrias->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $audiometria = $this->Audiometrias->patchEntity($audiometria, $this->request->getData());
            if ($this->Audiometrias->save($audiometria)) {
                $this->Flash->success(__('The audiometria has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The audiometria could not be saved. Please, try again.'));
        }
        $this->set(compact('audiometria'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Audiometria id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $audiometria = $this->Audiometrias->get($id);
        if ($this->Audiometrias->delete($audiometria)) {
            $this->Flash->success(__('The audiometria has been deleted.'));
        } else {
            $this->Flash->error(__('The audiometria could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
