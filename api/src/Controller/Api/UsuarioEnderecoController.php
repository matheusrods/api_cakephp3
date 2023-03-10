<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Validation\Validator;
use Cake\Http\Client;

/**
 * UsuarioEndereco Controller
 *
 * @property \App\Model\Table\UsuarioEnderecoTable $UsuarioEndereco
 *
 * @method \App\Model\Entity\UsuarioEndereco[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioEnderecoController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $usuarioEndereco = $this->paginate($this->UsuarioEndereco);

        $this->set(compact('usuarioEndereco'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario Endereco id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usuarioEndereco = $this->UsuarioEndereco->get($id, [
            'contain' => []
        ]);

        $this->set('usuarioEndereco', $usuarioEndereco);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usuarioEndereco = $this->UsuarioEndereco->newEntity();
        if ($this->request->is('post')) {
            $usuarioEndereco = $this->UsuarioEndereco->patchEntity($usuarioEndereco, $this->request->getData());
            if ($this->UsuarioEndereco->save($usuarioEndereco)) {
                $this->Flash->success(__('The usuario endereco has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario endereco could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioEndereco'));
    }

    /**
     * Edit method
     *(int)  $this->request->getData('codigo_cliente')
     * @param string|null $id Usuario Endereco id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usuarioEndereco = $this->UsuarioEndereco->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usuarioEndereco = $this->UsuarioEndereco->patchEntity($usuarioEndereco, $this->request->getData());
            if ($this->UsuarioEndereco->save($usuarioEndereco)) {
                $this->Flash->success(__('The usuario endereco has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The usuario endereco could not be saved. Please, try again.'));
        }
        $this->set(compact('usuarioEndereco'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Usuario Endereco id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usuarioEndereco = $this->UsuarioEndereco->get($id);
        if ($this->UsuarioEndereco->delete($usuarioEndereco)) {
            $this->Flash->success(__('The usuario endereco has been deleted.'));
        } else {
            $this->Flash->error(__('The usuario endereco could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    //Come??o endpoint de endere??o
    public function endereco()
    {
        $this->loadModel('UsuarioEndereco');

        //Valida????o dos campos necess??rios
        $validator = new Validator();

        //Campos necess??rios e modifica????o da mensagem de erro
        $field_need = array(
            'codigo_usuario' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'codigo_empresa' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'codigo_usuario_endereco_tipo' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'numero' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'logradouro' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'cep' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'bairro' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'cidade' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),
            'estado_descricao' => array(
                'message' => 'Este campo ?? necess??rio.'
            ),

        );

        //Campo necess??rios
        //$validator->requirePresence($field_need);

        //Verifica????o dos dados recebidos
        /*$errors = $validator->errors($this->request->getData());
        if (!empty($errors)) {
            $this->set('error', $errors);
            return;
        };*/

        //Pegando dados recebidos
        $data = $this->request->getData();

        //Transformando Estado descricao em Estado abreviacao
        $expr = '/(?<=\s|^)[A-Z]/';
        preg_match_all($expr, $data['estado_descricao'], $matches);
        $estado = implode('', $matches[0]);

        //Abertura de objeto para pesquisa da latitude e longitude
        $http = new Client();

        //Prepara????o para construir URL
        $endereco = $data['logradouro'].' '.$data['numero'];

        //Pesquisa pela latitude e longitude
        $cliente_endereco = urlencode($endereco);
        $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/consulta_lat_long', ['endereco'=>$cliente_endereco]);
        $result = json_decode($response->getStringBody());

        //Constru????o do objeto para salvar
        $entity = [
            'codigo_usuario' => $this->request->getData('codigo_usuario'),
            'codigo_empresa' => $this->request->getData('codigo_empresa'),
            'codigo_usuario_inclusao' => $this->request->getData('codigo_usuario'),
            'codigo_usuario_endereco_tipo' => $this->request->getData('codigo_usuario_endereco_tipo'),
            'numero' => $this->request->getData('numero'),
            'latitude' => $result->latitude,
            'longitude' => $result->longitude,
            'logradouro' => $this->request->getData('logradouro'),
            'cep' => $this->request->getData('cep'),
            'bairro' => $this->request->getData('bairro'),
            'cidade' => $this->request->getData('cidade'),
            'estado_descricao' => $this->request->getData('estado_descricao'),
            'estado_abreviacao'=> $estado,
            'descricao'=> $this->request->getData('descricao'),
            'endereco_completo'=> $this->request->getData('endereco_completo'),
            'data_inclusao' => date("Y-m-d H:i:s")
        ];

        //Vendo se possui complemento, pois n??o ?? obrigat??rio
        if (isset($data['complemento'])) {
            $entity['complemento'] = $data['complemento'];
        }

        // Salvando dados
        $save_address = $this->UsuarioEndereco->newEntity($entity);
        $result = $this->UsuarioEndereco->save($save_address);

        //Verificando se n??o houve erro
        if (!$result) {
            $error="N??o foi poss??vel cadastrar o endere??o.";
            $this->set(compact('error', [$error,$result]));
            return;
        }

        $this->set('data', $save_address);
    }
    //Fim endpoint de endere??o

    //Come??o endpoint de altera????o de endere??o
    public function alterarEndereco($codigo)
    {
        //Carregando model
        $this->loadModel('UsuarioEndereco');

        $this->request->allowMethod(['put']);

        //Busca pelo endere??o cadastrado
        $address = $this->UsuarioEndereco->find()->where(['codigo'=>$codigo])->first();

        //Se n??o h?? resultado v??lido da busca, retorna erro
        if (empty($address)) {
            $error[] = "Endere??o n??o encontrado";
            $this->set(compact('error'));
            return null;
        }

        //Pegando dados recebidos
        $data = $this->request->getData();

        $http = new Client();

        //Prepara????o para construir URL
        $endereco = $data['logradouro'].' '.$data['numero'];

        //Pesquisa pela latitude e longitude
        $cliente_endereco = urlencode($endereco);
        $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/consulta_lat_long', ['endereco'=>$cliente_endereco]);
        $result = json_decode($response->getStringBody());

        $data['codigo_usuario_alteracao'] = $data['codigo_usuario'];
        $data['data_alteracao'] = date("Y-m-d H:i:s");
        $data['latitude'] = $result->latitude;
        $data['longitude'] = $result->longitude;


        $att = $this->UsuarioEndereco->patchEntity($address, $data);
        $result = $this->UsuarioEndereco->save($att);

        if (!$result) {
            $error="N??o foi poss??vel alterar o endere??o.";
            $this->set(compact('error', [$error,$result]));
            return;
        }
        $result['mensagem'] = "Opera????o realizada com sucesso.";
        $this->set('data', $result);
    }
    //Fim endpoint de altera????o de endere??o

    //Come??o endpoint para deletar endere??o
    public function deletarEndereco($codigo)
    {
        //Carregando model
        $this->loadModel('UsuarioEndereco');

        //Busca pelo endere??o cadastrado
        $address = $this->UsuarioEndereco->find()->where(['codigo'=>$codigo])->first();

        //Caso o resultado seja vazio, retornar erro
        if (empty($address)) {
            $error="N??o foi poss??vel encontrar o endere??o.";
            $this->set('error', $error);
            return;
        }

        //Efetuando o delete
        $result['data'] = $this->UsuarioEndereco->delete($address);

        //Se o resultado da opera????o for salse, retornar error e finalizar
        if (!$result) {
            $error="N??o foi poss??vel deletar o endere??o.";
            $this->set(compact('error', [$error,$result]));
            return;
        }

        //Obtendo sucesso na fun????o, ?? adicionado mensagem de sucesso no objeto de retorno.
        $result['mensagem'] = "Opera????o realizada com sucesso.";
        $this->set('data', $result);
    }
    //Fim endpoint para deletar endere??o

    //Come??o endpoint para pegar endere??o
    public function getEndereco($codigo)
    {
        //Carregando model
        $this->loadModel('UsuarioEndereco');

        //Busca pelo endere??o cadastrado
        $address = $this->UsuarioEndereco->find()->where(['codigo_usuario'=>$codigo])->toArray();

        //Caso o resultado seja vazio, retornar erro
        if (empty($address)) {

            //verifica se tem empresa vinculada sendo funcionario
            $this->loadModel('Usuario');
            if($this->Usuario->validaSeUsuarioPossuiVinculoCliente($codigo)) {

                $error= '';
                //pega o endereco do funcionario
                $this->loadModel('Funcionarios');
                $func_end = $this->Funcionarios->getFuncionarioEndereco($codigo);

                if(!empty($func_end)) {
                    //casa
                    $func_end['codigo_usuario_endereco_tipo'] = 1;
                    $func_end['codigo_usuario'] = $codigo;
                    $func_end['endereco_completo'] = $func_end['logradouro']." - ".$func_end['bairro'].', '. $func_end['cidade'].' - '.$func_end['estado_abreviacao'];

                    //verifica se gravou o endereco
                    if(!$this->setEndereco($func_end)) {
                        $error="N??o foi poss??vel encontrar o endere??o do funcionario referente a esse usu??rio.";
                    }

                    // debug($func_end);
                    // debug($error);
                }

                //pega o endereco do cliente que ?? a emrpesa que ele esta vinculado
                //pega os dados do usuario
                $this->loadModel('UsuariosDados');

                //dados do usuario
                $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo])->first();
                if (!empty($usuarios_dados)) {

                    // obtem (codigo_cliente) vinculos em empresas
                    $this->FuncionariosModel = $this->loadModel('Funcionarios');
                    $codigo_cliente_vinculado = $this->FuncionariosModel->obterCodigoClienteVinculado($usuarios_dados->cpf);//array
                    
                    //verifica se tem mais de um cliente vinculado
                    if($codigo_cliente_vinculado && count($codigo_cliente_vinculado) == 1) {
                        //busca o endereco do cliente vinculado para popular como trabalho
                        $codigo_cliente =  $codigo_cliente_vinculado[0];

                        $this->loadModel('ClienteEndereco');
                        $cli_end = $this->ClienteEndereco
                                        ->find()
                                        ->select([
                                            'numero' => 'numero',
                                            'logradouro' => 'RHHealth.dbo.ufn_decode_utf8_string(logradouro)',
                                            'cep' => 'cep',
                                            'complemento' => 'RHHealth.dbo.ufn_decode_utf8_string(complemento)',
                                            'bairro' => 'RHHealth.dbo.ufn_decode_utf8_string(bairro)',
                                            'cidade' => 'RHHealth.dbo.ufn_decode_utf8_string(cidade)',
                                            'estado_descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(estado_descricao)',
                                            'estado_abreviacao' => 'estado_abreviacao',
                                            'codigo_empresa' => 'codigo_empresa',
                                        ])
                                        ->where(['codigo_cliente' => $codigo_cliente])
                                        ->first();

                        //verifica se tem os dados de endereco
                        if(!empty($cli_end)) {
                            $cli_end = $cli_end->toArray();
                            
                            //pega os dados do cliente
                            $dados_cli_end = [
                                'numero' => $cli_end['numero'],
                                'logradouro' => $cli_end['logradouro'],
                                'cep' => $cli_end['cep'],
                                'complemento' => $cli_end['complemento'],
                                'bairro' => $cli_end['bairro'],
                                'cidade' => $cli_end['cidade'],
                                'estado_descricao' => $cli_end['estado_descricao'],
                                'estado_abreviacao' => $cli_end['estado_abreviacao'],
                                'codigo_empresa' => $cli_end['codigo_empresa'],
                                'codigo_usuario_endereco_tipo' => 2,
                                'codigo_usuario' => $codigo,
                                'endereco_completo' => $cli_end['logradouro']." - ".$cli_end['bairro'].', '. $cli_end['cidade'].' - '.$cli_end['estado_abreviacao']
                            ];

                            // verifica se gravou o endereco
                            if(!$this->setEndereco($dados_cli_end)) {
                                $error="N??o foi poss??vel encontrar o endere??o do cliente referente a esse usu??rio.";
                            }

                            // debug($dados_cli_end);
                        }

                    }//fim contagem de cliente vinculado

                }//fium usuarios dados

                // exit;

                if(!empty($error)) {
                    $this->set('error', $error);
                    return;
                }
                else {
                    //Busca pelo endere??o cadastrado
                    $address = $this->UsuarioEndereco->find()->where(['codigo_usuario'=>$codigo])->toArray();
                }

            }
            else {
                $error="N??o foi poss??vel encontrar o endere??o referente a esse usu??rio.";
                $this->set('error', $error);
                return;
            }

        }

        $this->set('data', $address);
    }//Fim endpoint para pegar endere??o


    private function setEndereco($dados)
    {
        
        //Transformando Estado descricao em Estado abreviacao
        $expr = '/(?<=\s|^)[A-Z]/';
        preg_match_all($expr, $dados['estado_descricao'], $matches);
        $estado = implode('', $matches[0]);

        //Abertura de objeto para pesquisa da latitude e longitude
        $http = new Client();

        //Prepara????o para construir URL
        $endereco = $dados['logradouro'].' '.$dados['numero'];

        //Pesquisa pela latitude e longitude
        $cliente_endereco = urlencode($endereco);
        $response = $http->get('https://portal.rhhealth.com.br/portal/api/mapa/consulta_lat_long', ['endereco'=>$cliente_endereco]);
        $result = json_decode($response->getStringBody());

        //Constru????o do objeto para salvar
        $entity = [
            'codigo_usuario' => $dados['codigo_usuario'],
            'codigo_empresa' => $dados['codigo_empresa'],
            'codigo_usuario_inclusao' => $dados['codigo_usuario'],
            'codigo_usuario_endereco_tipo' => $dados['codigo_usuario_endereco_tipo'],
            'numero' => $dados['numero'],
            'latitude' => $result->latitude,
            'longitude' => $result->longitude,
            'logradouro' => $dados['logradouro'],
            'cep' => $dados['cep'],
            'bairro' => $dados['bairro'],
            'cidade' => $dados['cidade'],
            'estado_descricao' => $dados['estado_descricao'],
            'estado_abreviacao'=> $dados['estado_abreviacao'],
            'descricao'=> (isset($dados['descricao'])) ? $dados['descricao'] : null,
            'endereco_completo'=> (isset($dados['endereco_completo'])) ? $dados['endereco_completo'] : null,
            'data_inclusao' => date("Y-m-d H:i:s")
        ];

        //Vendo se possui complemento, pois n??o ?? obrigat??rio
        if (isset($dados['complemento'])) {
            $entity['complemento'] = $dados['complemento'];
        }

        // debug($entity);exit;

        // Salvando dados
        $save_address = $this->UsuarioEndereco->newEntity($entity);
        $result = $this->UsuarioEndereco->save($save_address);

        //Verificando se n??o houve erro
        if (!$result) {
            return false;
        }

        return true;

    }//fim setEndreco

}
