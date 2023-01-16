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
 * FichaPsicossocial Controller
 *
 *
 * @method \App\Model\Entity\FichaPsicossocial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FichaPsicossocialController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fichaPsicossocial = $this->paginate($this->FichaPsicossocial);

        $this->set(compact('fichaPsicossocial'));
    }

    /**
     * View method
     *
     * @param string|null $id Ficha Psicossocial id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo_usuario, $codigo_pedido_exame)
    {
         //pega os campos para montar o formulario
        $psicossocial = $this->FichaPsicossocial->getCamposPsicossocial($codigo_pedido_exame);

        //seta a variavel para retornar
        $data = array();
        //verifica se existe dados na audiometria
        if(!empty($psicossocial)) {
            $data = $psicossocial;
        }

        $this->set(compact('data'));
    }

    /**
     * [getDatadosFichaPsicossocial metodo para pegar os dados cadastrados da ficha psicossocial]
     *
     * @param  int    $codigo_pedido_exame [description]
     * @return [type]                       [description]
     */
    public function getDatadosFichaPsicossocial(int $codigo_pedido_exame)
    {
        //variavel auxiliar para o retorno do metodo
        $data = array();

        //pega os dados da ficha clinica
        $ficha_psicossocial = $this->FichaPsicossocial->find()->where(['codigo_pedido_exame' => $codigo_pedido_exame])->first();

        if(empty($ficha_psicossocial)) {
            $error[] = "Codigo da ficha psicossocial não encontrado!";
            $this->set(compact('error'));
            return;
        }
        $ficha_psicossocial = $ficha_psicossocial->toArray();
        $codigo_ficha_psicossocial = $ficha_psicossocial['codigo'];
        //debug($codigo_ficha_psicossocial);exit;

        //pega o codigo do pedido de exame
        $codigo_pedido_exames = $ficha_psicossocial['codigo_pedido_exame'];
        $codigo_usuario = $ficha_psicossocial['codigo_usuario_inclusao'];

        //monta a respostas das questoes
        $this->loadModel('FichaPsicossocialRespostas');

        // organiza as respostas em um array no padrão que a view necessita para se relacionar com $this->data
        $respostas = $this->FichaPsicossocialRespostas->find()->where(['codigo_ficha_psicossocial' => $codigo_ficha_psicossocial])->hydrate(false)->toArray();

        $dados = array();
        //varre as respostas
        foreach ($respostas as $key => $value) {
            //verifica a resposta
            if(Comum::isJson($value['resposta'])) {
                $value['resposta'] = (array)json_decode($value['resposta']);
                if(count($value['resposta']) == 1) {
                    $value['resposta'] = $value['resposta'][key($value['resposta'])];
                }
            }//fim resposta
            $dados['FichaPsicossocialPergunta.'.$value['codigo_ficha_psicossocial_perguntas']] = $value['resposta'];

        }//fim foreach
        // debug($dados);exit;

        //pega os dados da ficha clinica
        $formulario = $this->FichaPsicossocial->getCamposPsicossocial($codigo_pedido_exames);

        //varre as questoes para colocar as respostas
        foreach ($formulario['formulario'] as $keyForm => $form) {
            //varre os grupos
            foreach($form AS $keyGrupo => $tipoGrupo) {

                if(!isset($tipoGrupo['questao'])) {
                    continue;
                }

                //varre as questoes
                foreach($tipoGrupo['questao'] AS $keyQuestao => $questao) {

                    //configura o formulario
                    $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = null;

                    //verifica se tem codigo da questao
                    if(!empty($questao['codigo'])) {

                        //verifica se tem a resposta
                        if(isset($dados[$questao['name']])) {

                            //seta as respostas
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $dados[$questao['name']];

                        }//verifica se tem o nome

                        // debug($questao);
                        // exit;

                    }//vim verificacao codigo da questao
                    else {

                        //grupo header vem os campos da ficha clinica
                        //separa para pegar o indice 1 que tem o nome do campo
                        $separa_nome = explode(".",$questao['name']);

                        if(!isset($separa_nome[1])) {
                            continue;
                        }

                        if(isset($ficha_psicossocial[$separa_nome[1]])) {
                            $formulario['formulario'][$keyForm][$keyGrupo]['questao'][$keyQuestao]['resposta'] = $ficha_psicossocial[$separa_nome[1]];
                        }

                    }

                }//fim questoes

            }//fim tipogrupo
        }//fim formulario

        // debug($formulario);
        // exit;
        $data = $formulario;

        $this->set(compact('data'));
        return;


    }//fim getDatadosFichaPsicossocial

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

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
                $codigo_pedido_exame = $dados['FichaPsicossocial']['codigo_pedido_exame'];

                //seta o codigo do usuario que esta incluindo
                $dados['FichaPsicossocial']['codigo_usuario_inclusao'] = $codigo_usuario;
                $dados['FichaPsicossocial']['data_inclusao'] = date('Y-m-d H:i:s');

                //pega o codigo do usuario como medico e o codigo da empresa
                $dados['FichaPsicossocial']['codigo_empresa'] = 1;

                //pega no item o codigo do medico agendado
                $this->loadModel('ItensPedidosExames');
                $item = $this->ItensPedidosExames->find()->select(['codigo_medico'])->where(['codigo_exame' => '27', 'codigo_pedidos_exames' => $codigo_pedido_exame])->first();
                $codigo_medico = null;
                if(!empty($item)) {
                    $codigo_medico = $item->codigo_medico;
                }
                $dados['FichaPsicossocial']['codigo_medico'] = $codigo_medico;

                // debug($dados['FichaPsicossocial']);exit;

                //Declara variaveis pra receber o tatal de resposta sim ou não
                $total_sim = 0;
                $total_nao = 0;
                //Faz count para saber o total de sim ou não paras as perguntas
                foreach ($dados['FichaPsicossocialPergunta'] as $total_resposta) {
                    switch ($total_resposta['resposta']) {
                        case 0:
                            $total_nao++;
                            break;
                        case 1:
                            $total_sim++;
                            break;
                    }
                }
                //Inseri campos total_sim e total_nao no array para serem preenchidos na tabela
                $dados['FichaPsicossocial']['total_sim'] = $total_sim;
                $dados['FichaPsicossocial']['total_nao'] = $total_nao;

                $fichaPsicossocial = $this->FichaPsicossocial->newEntity($dados['FichaPsicossocial']);

                //verifica se vai gravar corretamente os dados na ficha clinica
                if (!$this->FichaPsicossocial->save($fichaPsicossocial)) {
                    throw new Exception(json_encode($fichaPsicossocial->getValidationErrors($fichaPsicossocial)));
                }

                //recupera o codigo da ficha clinica
                $codigo_ficha_psicossocial = $fichaPsicossocial->codigo;
                //verifica se tem dados de respostas
                if(!empty($dados['FichaPsicossocialPergunta']) && !empty($codigo_ficha_psicossocial)) {

                    //variavel complementar de erro para as respostas
                    $erro_reposta = array();

                    $this->loadModel('FichaPsicossocialRespostas');
                    //varre as respostas da ficha clinica
                    foreach($dados['FichaPsicossocialPergunta'] AS $resposta){


                        //set o codigo da ficha clinica
                        $resposta['codigo_ficha_psicossocial'] = $codigo_ficha_psicossocial;
                        $resposta['data_inclusao'] = date('Y-m-d H:i:s');
                        $resposta['ativo'] = '1';

                        //seta o que vai gravar no banco de dados de respostas
                        $registro = $this->FichaPsicossocialRespostas->newEntity($resposta);

                        //verifica se vai gravar corretamente os dados na tabela de respostas da ficha clinica
                        if (!$this->FichaPsicossocialRespostas->save($registro)) {
                            // debug('aqui');debug($registro);

                            //verifica o erro
                            if(!empty($registro->getValidationErrors($registro))) {
                                $erro_reposta[] = $registro->getValidationErrors($registro);
                            }
                            else {
                                $erro_reposta[] = $registro->errors;
                            }

                        }//fim respostas

                    }//fim foreach

                    //verifica se tem algum erro caso ocorra volta todo os dados para serem inputados novamente
                    if(!empty($erro_reposta)) {
                        throw new Exception(json_encode($erro_reposta));
                    }//fim erro

                }//fim verificacao de dados de respostas

                //dados de retorno
                $data = [
                    'retorno'=>'Ficha psicossocial inserida com sucesso!',
                    'codigo_pedido_exame' => $codigo_pedido_exame,
                    'codigo_usuario'=>$codigo_usuario,
                    'codigo_ficha_psicossocial'=>$codigo_ficha_psicossocial
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

    }//fim add

    /**
     * Edit method
     *
     * @param string|null $id Ficha Psicossocial id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $fichaPsicossocial = $this->FichaPsicossocial->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fichaPsicossocial = $this->FichaPsicossocial->patchEntity($fichaPsicossocial, $this->request->getData());
            if ($this->FichaPsicossocial->save($fichaPsicossocial)) {
                $this->Flash->success(__('The ficha psicossocial has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The ficha psicossocial could not be saved. Please, try again.'));
        }
        $this->set(compact('fichaPsicossocial'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Ficha Psicossocial id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $fichaPsicossocial = $this->FichaPsicossocial->get($id);
        if ($this->FichaPsicossocial->delete($fichaPsicossocial)) {
            $this->Flash->success(__('The ficha psicossocial has been deleted.'));
        } else {
            $this->Flash->error(__('The ficha psicossocial could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
