<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * FornecedoresAvalicoes Controller
 *
 *
 * @method \App\Model\Entity\FornecedoresAvalico[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedoresAvaliacoesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $fornecedoresAvalicoes = $this->paginate($this->FornecedoresAvalicoes);

        $this->set(compact('fornecedoresAvalicoes'));
    }

    public function getAvalicaoTipo(){
        $data = [];
        $this->loadModel("FornecedoresTipoAvaliacao");
        $data = $this->FornecedoresTipoAvaliacao->find()->toArray();
        $this->set(compact('data'));
    }

    /**
     * Avalia um fornecedor através do código do exame
     */
    public function postAvaliar(){

        // carregando models
        $this->loadModel("ItensPedidosExames");
        $this->loadModel("FornecedoresAvaliacoes");

        // variaveis
        $error = [];
        $data = [];

        // post
        $codigo_item_pedido_exame = (int) $this->request->getData('codigo_item_pedido_exame');


        $avaliacoes = $this->request->getData('avaliacoes');
        $comentario = $this->request->getData('comentario');

        // verificando se os campos foram preenchidos
        if(empty($codigo_item_pedido_exame)){
            $error[]="codigo_pedido_exame não informado";
        }
        if(empty($avaliacoes)){
            $error[]="Avaliações não informadas";
        }

        // enviando erro
        if($error){
            $this->set(compact('error'));
            return;
        }

        // Buscando dados do pedido de exame
        $pedidoExame = $this->ItensPedidosExames->getItemPedidoExame($codigo_item_pedido_exame)->first();


        // verificando se o pedido de exame existe
        if(empty($pedidoExame)){
            $error[]="Pedido de exame não encontrado.";
            $this->set(compact('error'));
            return;
        }

        //Verificando se a avaliação já existe
        /*$avaliacao_existente = $this->FornecedoresAvaliacoes->find()->where(['codigo_pedido_exame'=>$codigo_item_pedido_exame])->toArray();
        if(!empty($avaliacao_existente)){
            $error[]="Você já avaliou este fornecedor.";
            $this->set(compact('error'));
            return;
        }*/
        // Pegando o código do fornecedor
        $codigo_fornecedor = $pedidoExame->codigo;
        // Cadastrando as avaliações
        foreach ($avaliacoes as $avaliacao){

            $entity = [
                'codigo_fornecedor' => $pedidoExame['codigo_fornecedor'],
                'codigo_fornecedor_tipo_avaliacao' => $avaliacao['codigo_fornecedor_tipo_avaliacao'],
                'codigo_usuario' => $pedidoExame->UsuariosDados['codigo_usuario'],
                'codigo_funcionario' => $pedidoExame->PedidosExames['codigo_funcionario'],
                'codigo_item_pedido_exame' => $codigo_item_pedido_exame,
                'avaliacao' => $avaliacao['avaliacao'],
                'data_inclusao' => date("Y-m-d H:i:s"),
                'comentario' => $comentario,
                'codigo_usuario_inclusao'=> $pedidoExame->UsuariosDados['codigo_usuario']
            ];

            // Criando entity para inserção
            $fornecedor_avaliacao = $this->FornecedoresAvaliacoes->newEntity($entity);

            // Verifica se os dados foram salvos
            if (!$this->FornecedoresAvaliacoes->save($fornecedor_avaliacao)) {
                $error[]="Não foi possível cadastrar a avaliação.";
                $this->set(compact('error'));
                return;
            }else {
                $data['avaliacao'][] = $fornecedor_avaliacao;
            }
        }

        $data['mensagem'] = "Operação realizada com sucesso.";
        $this->set(compact('data'));
    }

}
