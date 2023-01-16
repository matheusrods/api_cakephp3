<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;

/**
 * Psicossocial Controller
 *
 * @property \App\Model\Table\FichaPsicossocialTable $FichaPsicossocial
 *
 * @method \App\Model\Entity\FichaPsicossocial[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PsicossocialController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');
        
        $this->loadModel('PedidosExames');
        $this->loadModel('FichaPsicossocial');
        $this->loadModel('FichaPsicossocialPerguntas');
        $this->loadModel('FornecedoresContato');
        $this->loadModel('Medicos');
        $this->loadModel('Cliente');
    }

    public function perguntasRespostas($codigo_usuario)
    {
        $pedidoExame = $this->PedidosExames->getUsuariosResponderExame($codigo_usuario);
        if (!$pedidoExame) {
            $this->set(array('error' => 'Usuário não atende aos requisitos para responder a avaliação psicossocial'));
            return;
        }

        $data = $this->FichaPsicossocialPerguntas->listarPerguntasRespostasFormatadas();
        $this->set(compact('data'));
    }

    public function salvarResposta($codigo_usuario)
    {
        $this->request->allowMethod(['post']);

        
        //teste do email que estaca cortando os caracteres
        /*
        $dadosEmail = [
            'codigo_pedido_exame' => '191696',
            // 'funcionario_nome' => mb_convert_encoding('BRUNNA POTAMEÇÃ É aaaaaaaaaaaaaaa', "UTF-8", "ISO-8859-1"),
            // 'fornecedor_nome' => mb_convert_encoding('SOLICITAÇÂO Envio de Kité com ção', "UTF-8", "ISO-8859-1"),
            
            'funcionario_nome' => htmlentities('BRUNNA POTAMEÇÃ É aaaaaaaaaaaaaaa'),
            'fornecedor_nome' => htmlentities('SOLICITAÇÂO Envio de Kité com ção'),

            'fornecedor_email' => 'willians.pedroso@ithealth.com.br',
        ];

        $this->FichaPsicossocial->enviarEmail($dadosEmail);
        print "ok";
        exit;
        */
        
        
        /* OBS: Foi comentado a linha do begin, porque está impedindo 
        de gerar o PDF da ficha psicossocial, porque bloqueia a tabela e acaba dando o 
        erro: 504 Gateway Time-out Microsoft-Azure-Application-Gateway/v2 */

        // Abre a transação
        // $this->connect->begin();

        try {
            //pega os dados do token
            $dados_token = $this->getDadosToken();

            //veifica se encontrou os dados do token
            if (empty($dados_token)) {
                $this->set(array('error' => 'Não foi possivel encontrar os dados no Token!'));
                return;
            }

            //seta o codigo usuario
            $codigo_usuario_token = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

            if (empty($codigo_usuario_token)) {
                $this->set(array('error' => 'Logar novamente o usuario'));
                return;
            }

            $payload = $this->request->getData();

            if (!isset($payload['respostas']) || !count($payload['respostas'])) {
                $this->set(array('error' => 'Payload com respostas irregular'));
                return;
            }

            $pedidoExame = $this->PedidosExames->getUsuariosResponderExame($codigo_usuario);

            if (!$pedidoExame) {
                $this->set(array('error' => 'Usuário não atende aos requisitos para responder a avaliação psicossocial'));
                return;
            }            
            
            //busca o email do fornecedor com o tipo envio de kit
            $fornecedorContato = $this->FornecedoresContato->find()
                ->where(['codigo_fornecedor' => $pedidoExame['codigo_fornecedor'], 'codigo_tipo_retorno' => 2, 'codigo_tipo_contato' => 8])
                ->order('codigo DESC')
                ->first();

            if (empty($fornecedorContato)) {
                $this->set(array('error' => 'Não foi possível encontrar o fornecedor para enviar o e-mail.'));
                return;
            }

            $fornecedorContatoAso = array();
            if($pedidoExame['pontual'] != 1) {
                //verifica se tem aso, pois pode ser um pedido de exame pontual
                if(!empty($pedidoExame['codigo_fornecedor_aso'])) {
                    //para não disparar 2 emails
                    if($pedidoExame['codigo_fornecedor_aso'] != $pedidoExame['codigo_fornecedor']) {
                        //busca o email do fornecedor com o tipo envio de kit
                        $fornecedorContatoAso = $this->FornecedoresContato->find()
                            ->where(['codigo_fornecedor' => $pedidoExame['codigo_fornecedor_aso'], 'codigo_tipo_retorno' => 2, 'codigo_tipo_contato' => 8])
                            ->order('codigo DESC')
                            ->first();

                        if (empty($fornecedorContatoAso)) {
                            $this->set(array('error' => 'Não foi possível encontrar o fornecedor para o aso enviar o e-mail.'));
                            return;
                        }
                    }//fim codigo_fornecedor_aso diferente do fornecedor que vai atender
                }//fim codigo_fornecedor aso pois pode ser pontual
            }


            $medico = $this->Medicos->find()
                ->where(['codigo' => $pedidoExame['codigo_medico'], 'ativo' => 1])
                ->first();

            if (empty($medico)) {
                $this->set(array('error' => 'Não foi possível encontrar o medico para enviar o e-mail.'));
                return;
            }

            $cliente = $this->Cliente->find()
                ->where(['codigo' => $pedidoExame['codigo_cliente'], 'ativo' => 1])
                ->first();

            if (empty($cliente)) {
                $this->set(array('error' => 'Não foi possível encontrar o cliente para enviar o e-mail.'));
                return;
            }

            $payload['codigo_pedido_exame']     = $pedidoExame['codigo_pedidos_exames'];
            $payload['codigo_medico']           = $medico['codigo'];
            $payload['medico_nome']             = $medico['nome'];
            $payload['codigo_cliente']          = $cliente['codigo'];
            $payload['cliente_razao_social']    = $cliente['razao_social'];
            $payload['codigo_usuario']          = $pedidoExame['codigo_usuario'];
            $payload['codigo_empresa']          = 1;
            $payload['funcionario_nome']        = $pedidoExame['funcionario_nome'];
            $payload['setor']                   = $pedidoExame['setor'];
            $payload['cargo']                   = $pedidoExame['cargo'];
            $payload['fornecedor_nome']         = $fornecedorContato['nome'];
            $payload['fornecedor_email']        = $fornecedorContato['descricao'];
            $payload['ativo'] = 1;
            //implementado para enviar o email para o fornecedor do aso
            $payload['fornecedor_aso_nome']         = (isset($fornecedorContatoAso['nome'])) ? $fornecedorContatoAso['nome'] : null;
            $payload['fornecedor_aso_email']        = (isset($fornecedorContatoAso['descricao'])) ? $fornecedorContatoAso['descricao'] : null;

            $data = $this->FichaPsicossocial->salvarRespostas($payload);
            if (isset($data['error'])) {
                // $this->connect->rollback();
                
                $this->set(array('error' => $data['error']));
                return;
            }

            // Salva dados
            // $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            // $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
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
