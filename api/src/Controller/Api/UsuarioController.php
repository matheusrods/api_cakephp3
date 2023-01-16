<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
// use App\Utils\MdEncriptacao;
use App\Utils\Comum;
use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use App\Validator\AuthValidator;
use App\Services\Mailer\MailerService;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Collection\Collection;
use App\Utils\ArrayUtil;

use App\Auth\BuonnyPasswordHasher;
use Cake\Http\Client;

use Cake\Utility\Security as Security;
use DateInterval;
use DateTime;
use Firebase\JWT\JWT;

use Cake\Log\Log;

use Cake\I18n\Time;

use Google_Client;
use Google_Service_Oauth2;
use Google_Service_PeopleService;

use Cake\Http\Exception\BadRequestException;

/**
 * Usuario Controller
 *
 * @property \App\Model\Table\UsuarioTable $Usuario
 *
 * @method \App\Model\Entity\Usuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');

        $this->Auth->allow(['view', 'add', 'getCodigoCliente', 'getValidarVinculo', 'recuperarSenha', 'getLinks', 'getCallbackLinkedin', 'getCallbackFacebook', 'getCallbackGoogle', 'testeOpa', 'descriptTeste', 'testeCript', 'testeDecript', 'postRecuperarSenhaToken', 'putRecuperarSenhaToken', 'updatePassword']);

        $this->loadModel("ValidarTokenTipo");
        $this->loadModel("SistemaValidarTokenTipo");
        $this->loadModel("UsuarioValidarToken");

        $this->loadModel('Cliente');
        $this->loadModel('GruposEconomicos');
        $this->loadModel('GruposEconomicosClientes');
        $this->loadModel('Subperfil');
    }

    private function getAuthenticatedUser()
    {
        $authenticatedUser = $this->getDadosToken();

        if (empty($authenticatedUser)) {
            $error = 'Não foi possível encontrar os dados no Token!';

            $this->set(compact('error'));

            return;
        } else {
            $userId = isset($authenticatedUser->codigo_usuario) ? $authenticatedUser->codigo_usuario : '';

            if (empty($userId)) {
                $error = 'Logar novamente o usuário';

                $this->set(compact('error'));

                return;
            } else {
                return $userId;
            }
        }
    }

    /**
     * @OA\Put(
     *   path="/usuario/credencial",
     *   summary="Atualiza a senha do usuario",
     *   @OA\Parameters(
     *     response=200,
     *     description="A list with products"
     *   ),
     *   @OA\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function atualizaSenha()
    {
        $this->request->allowMethod(['put']); // aceita apenas PUT

        $params = $this->request->getData();

        $validado = (new AuthValidator())->validaAtualizaSenha($params);

        if (isset($validado['error'])) {
            $error = $validado['error'];
            $this->set(compact('error'));
            return;
        }

        $codigo_usuario = $params['codigo_usuario'];

        $this->Auth = TableRegistry::get('Auth');

        $acao = $this->Auth->atualizaSenha($codigo_usuario, $params);

        if (isset($acao['error'])) {
            $error = $acao['error'];
            $this->set(compact('error'));
            return;
        }

        $data = 'Senha atualizada com sucesso';

        $this->set(compact('data'));
    }

    public function updatePassword()
    {

        $this->request->allowMethod(['put']); // aceita apenas PUT

        $params = $this->request->getData();

        $validado = (new AuthValidator())->validateUpdatePassword($params);

        if (isset($validado['error'])) {
            $error = $validado['error'];
            $this->set(compact('error'));
            return;
        }

        $codigo_usuario = $params['codigo_usuario'];

        $this->Auth = TableRegistry::get('Auth');

        $acao = $this->Auth->atualizaSenha($codigo_usuario, $params);

        if (isset($acao['error'])) {
            $error = $acao['error'];
            $this->set(compact('error'));
            return;
        }

        $data = 'Senha atualizada com sucesso';

        $this->set(compact('data'));
    }

    public function getUuariosByCliente(int $codigo_cliente)
    {
        $usuarios = $this->Usuario->getUuariosByCliente($codigo_cliente);

        $data = array();
        if (!empty($usuarios)) {
            foreach ($usuarios as $key => $usuario) {
                //desencript para mostar a senha do usuario
                // $encriptacao = new Encriptacao();
                // $usuario->senha = $encriptacao->desencriptar($usuario->senha);

                $usuario['permissoes'] = [
                    'Lyn' => [
                        'menu' => [],
                        'skin' => [],
                    ]
                ];

                //permissoes do lyn por empresa
                $codigo_cliente_usuario = null;
                $dados_ge = null;
                if ($usuario['cliente']) {
                    $codigo_cliente_usuario = $usuario['cliente']['codigo'];
                    //pega o grupo economico do cliente
                    $dados_ge = $this->Usuario->getClienteGrupoEconomico($codigo_cliente_usuario);
                }

                //verifica se tem dados do grupoeconomico
                if (!empty($dados_ge)) {
                    //pega o codigo cliente matriz
                    $codigo_cliente_matriz = $dados_ge['codigo_cliente'];

                    //pega o skin
                    $this->loadModel('Cliente');
                    $skin = $this->Cliente->getSkin($codigo_cliente_matriz);

                    //verifica se tem skin a ser aplicado
                    if (!empty($skin)) {
                        // debug($skin);exit;
                        $skin['width'] = '190';
                        $skin['height'] = '50';
                        //seta o skin
                        $usuario['permissoes']['Lyn']['skin'] = $skin;
                    } //fim skin

                    //pega o menu
                    $this->loadModel('LynMenuCliente');
                    $menu = $this->LynMenuCliente->getMenuCliente($codigo_cliente_matriz);
                    //verifica se tem menu para inativar
                    if (!empty($menu)) {
                        //seta a inativacao do menu
                        $usuario['permissoes']['Lyn']['menu'] = $menu;
                    }
                }

                $data[] = $usuario;
            }
        } else {
            //componente para log da api
            $error[] = "Não existe dados!";
            $this->set(compact('error'));
        }

        $this->set(compact('data'));
    }

    /**
     * View method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(int $codigo_usuario = null)
    {
        //pega os dados de usuario
        $usuario = $this->Usuario->obterDadosDoUsuarioAlocacao($codigo_usuario);
        // debug($usuario);exit;
        if (isset($usuario['error'])) {
            //componente para log da api
            $error[] = $usuario['error'];
            $this->set(compact('error'));
        } else if (!empty($usuario)) {

            $data = array();

            $codigo_perfil = $usuario->codigo_perfil;

            //unset($usuario->codigo_perfil);


            //desencript para mostar a senha do usuario
            // $encriptacao = new Encriptacao();
            // $usuario->senha = $encriptacao->desencriptar($usuario->senha);

            $usuario->permissoes = [
                'Lyn' => [
                    'menu' => [],
                    'skin' => [],
                ]
            ];
            $data = $usuario;

            $this->loadModel('Subperfil');
            $subperfis = $this->Subperfil->getByCodigoUsuario($codigo_usuario);
            $data->subperfis = !empty($subperfis) ? $subperfis : [];

            //permissoes do lyn por empresa
            $codigo_cliente_usuario = null;
            $dados_ge = null;
            if ($usuario->cliente) {
                $indice_codigo_cliente = array_search(1, array_column($usuario->cliente, 'flag_pda'));
                $codigo_cliente_usuario = $indice_codigo_cliente ? $usuario->cliente[$indice_codigo_cliente]['codigo'] : $usuario->cliente[0]['codigo'];
                //pega o grupo economico do cliente
                $dados_ge = $this->Usuario->getClienteGrupoEconomico($codigo_cliente_usuario);
            }

            //verifica se tem dados do grupoeconomico para o lyn | GRO | POS
            if (!empty($dados_ge) && $codigo_perfil == 9 || !empty($dados_ge) && $codigo_perfil == 43 || !empty($dados_ge) && $codigo_perfil == 50) {

                //pega o codigo cliente matriz
                $codigo_cliente_matriz = $dados_ge->codigo_cliente;

                //pega o skin
                $this->loadModel('Cliente');
                $skin = $this->Cliente->getSkin($codigo_cliente_matriz);
                $acoes_subperfil = $this->Subperfil->getPermissoesUsuario($codigo_usuario);

                //                pr($skin);exit;
                //verifica se tem skin a ser aplicado
                if (!empty($skin)) {
                    // debug($skin);exit;
                    $skin['width'] = '190';
                    $skin['height'] = '50';
                    //seta o skin
                    switch ($codigo_perfil) {
                        case 9: // Perfil Lyn

                            if (isset($skin['flag_logo_lyn']) and $skin['flag_logo_lyn'] == 1) {
                                unset($skin['codigo_cliente']);
                                unset($skin['nome_fantasia']);
                                unset($skin['razao_social']);
                                unset($skin['flag_logo_lyn']);
                                unset($skin['flag_logo_gestao_risco']);
                                $data->permissoes['Lyn']['skin'] = $skin;
                                $data->permissoes['Lyn']['acoes'] = $acoes_subperfil;
                            }
                            break;
                        case 43: // Perfil Gestão de Risco

                            if (isset($skin['flag_logo_gestao_risco']) and $skin['flag_logo_gestao_risco'] == 1) {
                                unset($skin['flag_logo_lyn']);
                                unset($skin['flag_logo_gestao_risco']);
                                $data->permissoes['GestaoRisco']['skin'] = $skin;
                                $data->permissoes['GestaoRisco']['acoes'] = $acoes_subperfil;
                            }
                            break;
                        case 50: // Perfil Plano de ação

                            if ((isset($skin['flag_pda']) and $skin['flag_pda'] == 1)
                                || (isset($skin['flag_swt']) and $skin['flag_swt'] == 1)
                                || (isset($skin['flag_obs']) and $skin['flag_obs'] == 1)
                            ) {

                                unset($skin['flag_logo_lyn']);
                                unset($skin['flag_logo_gestao_risco']);
                                unset($skin['flag_pda']);
                                unset($skin['flag_swt']);
                                unset($skin['flag_obs']);
                                $data->permissoes['POS']['skin'] = $skin;
                                $data->permissoes['POS']['acoes'] = $acoes_subperfil;
                            }
                            break;
                    }
                } //fim skin

                //pega o menu
                if ($codigo_perfil == 9) {

                    $this->loadModel('LynMenuCliente');
                    $menu = $this->LynMenuCliente->getMenuCliente($codigo_cliente_matriz); // lyn
                    //verifica se tem menu para inativar
                    if (!empty($menu)) {
                        //seta a inativacao do menu
                        $data->permissoes['Lyn']['menu'] = $menu;
                    }
                }
            } //fim dados_ge
            // debug($data);exit;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $error[] = "Não existe dados!";
            $this->set(compact('error'));
        }
    } //fim view

    /**
     * ViewTherma method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewTherma(int $codigo_usuario = null)
    {
        //pega os dados de usuario
        $usuario = $this->Usuario->obterDadosDoUsuarioAlocacaoTherma($codigo_usuario);

        // debug($usuario);exit;

        if (isset($usuario['error'])) {
            //componente para log da api
            $error[] = $usuario['error'];
            $this->set(compact('error'));
        } else if (!empty($usuario)) {

            $data = array();

            $codigo_perfil = $usuario->codigo_perfil;
            unset($usuario->codigo_perfil);

            //desencript para mostar a senha do usuario
            // $encriptacao = new Encriptacao();
            // $usuario->senha = $encriptacao->desencriptar($usuario->senha);

            // debug($usuario);exit;

            $usuario->permissoes = [
                'ThermaCare' => [
                    'menu' => [],
                    'skin' => [],
                ]
            ];

            $codigo_cliente_matriz = (isset($usuario->cliente[0]['codigo']) && !empty($usuario->cliente[0]['codigo'])) ? $usuario->cliente[0]['codigo'] : 0;
            $onboarding = $this->obterOnboarding($codigo_cliente_matriz);
            if (is_array($onboarding)) {
                $usuario->permissoes['ThermaCare']['onboarding'] = $onboarding;
            }

            $data = $usuario;

            //permissoes do lyn por empresa
            $codigo_cliente_usuario = null;
            $dados_ge = null;
            if ($usuario->cliente) {
                $codigo_cliente_usuario = $usuario->cliente[0]['codigo'];
                //pega o grupo economico do cliente
                $dados_ge = $this->Usuario->getClienteGrupoEconomico($codigo_cliente_usuario);
            }

            //verifica se tem dados do grupoeconomico para o therma care
            if (!empty($dados_ge) && $codigo_perfil == 42) {
                //pega o codigo cliente matriz
                $codigo_cliente_matriz = $dados_ge->codigo_cliente;

                //pega o skin
                $this->loadModel('Cliente');
                $skin = $this->Cliente->getSkin($codigo_cliente_matriz);

                //verifica se tem skin a ser aplicado
                if (!empty($skin)) {
                    // debug($skin);exit;
                    $skin['width'] = '190';
                    $skin['height'] = '50';
                    //seta o skin
                    $data->permissoes['ThermaCare']['skin'] = $skin;
                } //fim skin

                //pega o menu
                $this->loadModel('LynMenuCliente');
                $menu = $this->LynMenuCliente->getMenuCliente($codigo_cliente_matriz, 3); //therma care
                //verifica se tem menu para inativar
                if (!empty($menu)) {
                    //seta a inativacao do menu
                    $data->permissoes['ThermaCare']['menu'] = $menu;
                }
            } //fim dados_ge

            // debug($data);exit;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $error[] = "Não existe dados!";
            $this->set(compact('error'));
        }
    } //fim view

    /**
     * Add method
     *
     * aguardando um json para criar um novo usuario na base de dados
     *
     * agurando a seguinte estrutura json:
     *
     * {
     *       "nome": "SERGIO LEANDRO LIMA SALLES",
     *       "login": "teste_sergio_siemens",
     *       "senha": "123456",
     *       "email": "teste@teste.com.br",
     *       "cpf": "073.290.997-08",
     *       "data_nascimento": "05/07/1976",
     *       "sexo": "M",
     *       "telefone": "(81) 3861-2194",
     *       "celular": "(81) 99955-6553",
     *       "apelido_sistema": "Serginho",
     *       "cliente": [
     *           {
     *               "codigo_cliente": "71758",
     *               "codigo_confirmacao":"123456"
     *           }
     *       ]
     * }
     *
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        // seta para o retorno
        $data = array();
        $error = array();

        //verifica se é post
        if ($this->request->is('post')) {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //variavel com os erros caso existam
            $validacoes = $this->validacao_usuario($dados);

            //verifica se existe erros
            if (!isset($validacoes['error'])) {

                //formata o cpf
                $cpf = Comum::soNumero($dados['cpf']);

                //codigo de verificacao da empresa
                $codigo_cliente = null;
                $codigo_funcionario = null;

                //seta os codigos
                list($codigo_cliente, $codigo_funcionario) = $this->getCodigoClienteFuncionario($cpf, $dados);

                //organiza os dados para criar um novo usuario
                $dados_usuario = $this->set_dados_usuario($dados, $codigo_cliente, $codigo_funcionario);

                //verifica se existe dados
                if (!empty($dados_usuario)) {

                    //para transacao
                    $conn = ConnectionManager::get('default');

                    try {

                        //transacao
                        $conn->begin();

                        //para criar um novo usuario
                        $usuario = $this->Usuario->newEntity($dados_usuario['Usuario']);

                        if ($this->Usuario->save($usuario)) {

                            //pega o codigo do usuario
                            $codigo_usuario = isset($usuario->codigo) ? $usuario->codigo : $usuario->id;

                            //seta o codigo do usuario que acabou de inserir
                            $dados_usuario['UsuariosDados']['codigo_usuario'] = $codigo_usuario;

                            //seta os dados para gravar
                            $usuarios_dados = $this->Usuario->UsuariosDados->newEntity($dados_usuario['UsuariosDados']);

                            // dd($usuarios_dados);

                            $this->loadModel('UsuariosDados');
                            //salva os dados
                            if ($this->UsuariosDados->save($usuarios_dados)) {
                                // $data["codigo_cliente"] = "1";

                                //verifica se nao é empty
                                if (!empty($codigo_cliente)) {
                                    //verifica se tem mais de uma empresa para relacionar ao usuario
                                    if (count($codigo_cliente) > 1) {
                                        //monta os multi-clientes
                                        $this->usuarioMultiCliente($codigo_usuario, $codigo_cliente);
                                    } //fim count codigo_cliente
                                }

                                //finaliza a transacao
                                $conn->commit();

                                //verifica se vai notificar ou nao
                                if ($dados['notificacao'] == 1) {
                                    //verifica se tem o token_push
                                    if (!empty($dados['token_push'])) {
                                        $dados_token_push = $this->grava_token_push($codigo_usuario, $dados['codigo_sistema'], $dados['token_push']);
                                    } //fim verificacao token_push
                                } //fim notificacao ativa


                                //para autenticar o novo usuario
                                $autenticacao = $this->autenticacaoUsuario($codigo_usuario);

                                //verifica se conseguiu se logar
                                if (!$autenticacao) {

                                    $log = new Log();
                                    $log::debug(print_r($autenticacao, 1));

                                    $error[]  = 'Erro ao autenticar o usuario';
                                } else {
                                    $data = $autenticacao['result']['data'];
                                }
                            } //fim save usuarios dados
                            else {
                                $error[]  = $usuarios_dados->errors();

                                $message[] = $usuarios_dados->errors();

                                throw new Exception("Erro ao criar usuarios_dados: " . print_r($message, 1));
                            } //fim else usuario dados
                        } else {
                            $error[]  = $usuario->errors();

                            $message[] = $usuario->errors();

                            throw new Exception("Erro ao criar usuario: " . print_r($message, 1));
                        }
                    } catch (Exception $e) {
                        $error[] = $e->getMessage();

                        //rollback da transacao
                        $conn->rollback();
                    }
                } //fim empty
                else {
                    $error[]  = 'Não existem dados para o cadastro do usuario';
                }
            } //verifica se valida os dados
            else {
                $error[]  = $validacoes['validations'];
            } //fim else das validacoes
        } //fim post
        else {

            //erro de metodo
            $error[]   = 'Erro de metodo aguardando POST';
        } //fim else post


        //log
        $entrada = json_encode((!empty($this->request->getData()) ? $this->request->getData() : 'ERRO getData'));
        $log_type = "MOBILE_API_POST_USUARIO";

        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim add

    /**
     * Edit method
     *
     * metodo para editar os dados de usuario aguardando o seguinte json
     *
     * {
     *       "nome": "SERGIO LEANDRO LIMA SALLES",
     *       "login": "teste_sergio_siemens",
     *       "senha": "123456",
     *       "email": "teste@teste.com.br",
     *       "cpf": "073.290.997-08",
     *       "data_nascimento": "05/07/1976",
     *       "sexo": "M",
     *       "telefone": "(81) 3861-2194",
     *       "celular": "(81) 99955-6553",
     *       "apelido_sistema": "Serginho",
     *       "cliente": [
     *           {
     *               "codigo_cliente": "71758",
     *               "codigo_confirmacao":"123456"
     *          }
     *       ]
     *   }
     *
     * @param string|null $codigo Usuario codigo.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($codigo = null)
    {
        $this->loadModel('UsuariosDados');

        //pega os dados do usuario
        $usuario = $this->Usuario->get($codigo);

        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['patch', 'post', 'put'])) {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //variavel com os erros caso existam
            $validacoes = $this->validacao_usuario($dados, $codigo);

            //verifica se existe erros
            if (!isset($validacoes['error'])) {


                //para transacao
                $conn = ConnectionManager::get('default');

                try {

                    //formata o cpf
                    $cpf = Comum::soNumero($dados['cpf']);

                    //codigo de verificacao da empresa
                    $codigo_cliente = null;
                    $codigo_funcionario = null;

                    //seta os codigos
                    list($codigo_cliente, $codigo_funcionario) = $this->getCodigoClienteFuncionario($cpf, $dados);

                    $dados_usuario = array(
                        'nome' => $dados['nome'],
                        'email' => $dados['email'],
                        'codigo_cliente' => ((isset($codigo_cliente[0])) ? $codigo_cliente[0] : null),
                        'codigo_funcionario' => $codigo_funcionario,
                        'data_alteracao' => date('Y-m-d H:i:s'),
                    );

                    //seta os dados para atualizacao
                    $usuario = $this->Usuario->patchEntity($usuario, $dados_usuario);

                    //transacao
                    $conn->begin();

                    if ($this->Usuario->save($usuario)) {

                        //seta os dados para gravar
                        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo])->first();

                        //seta os valores
                        $dados_users = array(
                            'cpf' => $cpf,
                            // "data_nascimento" => Comum::formataData($dados['data_nascimento'],'dmy','ymd'),
                            "data_nascimento" => $dados['data_nascimento'],
                            "sexo" => $dados['sexo'],
                            "telefone" => Comum::soNumero($dados['telefone']),
                            "celular" => Comum::soNumero($dados['celular']),
                            "apelido_sistema" => $dados['apelido_sistema'],
                            "data_alteracao" => date('Y-m-d H:i:s'),
                        );

                        //seta a troca
                        $usuarios_dados = $this->Usuario->UsuariosDados->patchEntity($usuarios_dados, $dados_users);

                        //salva os dados
                        if ($this->UsuariosDados->save($usuarios_dados)) {

                            //verifica se tem mais de uma empresa para relacionar ao usuario
                            if (is_array($codigo_cliente) && count($codigo_cliente) > 1) {
                                //monta os multi-clientes
                                $this->usuarioMultiCliente($usuario->codigo, $codigo_cliente);
                            } //fim count codigo_cliente



                            //finaliza a transacao
                            $conn->commit();

                            $data = "Registro atualizado com sucesso!";
                        } //fim save usuarios dados
                        else {
                            $error[]  = $usuarios_dados->errors();

                            $message[] = $usuarios_dados->errors();

                            throw new Exception("Erro ao criar usuarios_dados: " . print_r($message, 1));
                        } //fim else usuario dados
                    } else {
                        $error[]  = $usuario->errors();

                        $message[] = $usuario->errors();

                        throw new Exception("Erro ao criar usuario: " . print_r($message, 1));
                    }
                } catch (Exception $e) {

                    //rollback da transacao
                    $conn->rollback();
                }
            } //verifica se valida os dados
            else {
                $error[]  = $validacoes['validations'];
            } //fim else das validacoes

            // $usuario = $this->Usuario->patchEntity($usuario, $this->request->getData());
            // if ($this->Usuario->save($usuario)) {
            //     $this->Flash->success(__('The usuario has been saved.'));

            //     return $this->redirect(['action' => 'index']);
            // }
            // $this->Flash->error(__('The usuario could not be saved. Please, try again.'));
        } //fim metodo put

        //log
        $entrada = json_encode((!empty($this->request->getData()) ? $this->request->getData() : 'ERRO getData'));
        $log_type = "MOBILE_API_PUT_USUARIO";

        //verifica o retorno para quem chamou a api
        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;
            $this->cod_usuario = $codigo;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim edit

    /**
     * Edit method
     *
     * metodo para editar os dados de usuario aguardando o seguinte json
     *
     * {
     *       "nome": "SERGIO LEANDRO LIMA SALLES",
     *       "login": "teste_sergio_siemens",
     *       "senha": "123456",
     *       "email": "teste@teste.com.br",
     *       "cpf": "073.290.997-08",
     *       "data_nascimento": "05/07/1976",
     *       "sexo": "M",
     *       "telefone": "(81) 3861-2194",
     *       "celular": "(81) 99955-6553",
     *       "apelido_sistema": "Serginho",
     *       "cliente": [
     *           {
     *               "codigo_cliente": "71758",
     *               "codigo_confirmacao":"123456"
     *          }
     *       ]
     *   }
     *
     * @param string|null $codigo Usuario codigo.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editTherma($codigo = null)
    {
        //pega os dados do usuario
        $usuario = $this->Usuario->get($codigo);

        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['patch', 'post', 'put'])) {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            // debug($dados);exit;

            //para transacao
            $conn = ConnectionManager::get('default');

            try {

                $dados_usuario = array(
                    'nome' => $dados['nome'],
                    'email' => $dados['email'],
                    'celular' => $dados['celular'],
                    'data_alteracao' => date('Y-m-d H:i:s'),
                );

                //seta os dados para atualizacao
                $usuario = $this->Usuario->patchEntity($usuario, $dados_usuario);

                //transacao
                $conn->begin();

                if ($this->Usuario->save($usuario)) {

                    //finaliza a transacao
                    $conn->commit();

                    $data = "Registro atualizado com sucesso!";
                } else {
                    $error[]  = $usuario->errors();

                    $message[] = $usuario->errors();

                    throw new Exception("Erro ao criar usuario: " . print_r($message, 1));
                }
            } catch (Exception $e) {

                //rollback da transacao
                $conn->rollback();
            }


            // $usuario = $this->Usuario->patchEntity($usuario, $this->request->getData());
            // if ($this->Usuario->save($usuario)) {
            //     $this->Flash->success(__('The usuario has been saved.'));

            //     return $this->redirect(['action' => 'index']);
            // }
            // $this->Flash->error(__('The usuario could not be saved. Please, try again.'));
        } //fim metodo put

        //log
        $entrada = json_encode((!empty($this->request->getData()) ? $this->request->getData() : 'ERRO getData'));
        $log_type = "MOBILE_API_PUT_THERMA_USUARIO";

        //verifica o retorno para quem chamou a api
        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;
            $this->cod_usuario = $codigo;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim editTherma


    /**
     * [usuarioMultiCliente description]
     *
     * metodo para pegar os dados
     *
     * @param  [type] $codigo_usuario [description]
     * @param  [type] $clientes       [description]
     * @return [type]                 [description]
     */
    private function usuarioMultiCliente($codigo_usuario, $clientes)
    {
        //instancia a usuario multi cliente
        $this->loadModel('UsuarioMultiCliente');

        //pega os dados da multi cliente
        $del_usuario_clientes = $this->UsuarioMultiCliente->deleteAll(['codigo_usuario' => $codigo_usuario]);

        //varre os dados de cliente
        foreach ($clientes as $codigo_cliente) {
            $dadosMultiCliente = array(
                'codigo_usuario' => $codigo_usuario,
                'codigo_cliente' => $codigo_cliente,
                'codigo_usuario_inclusao' => $codigo_usuario,
                'data_inclusao' => date('Y-m-d H:i:s')
            );

            //instancia para um novo registro
            $usuario_multi_cliente = $this->UsuarioMultiCliente->newEntity($dadosMultiCliente);

            if (!$this->UsuarioMultiCliente->save($usuario_multi_cliente)) {
                throw new Exception("Erro ao relacionar clientes ao usuario");
            }
        } //fim foreach

        return true;
    } //fim usuarioMultiCliente

    /**
     * Delete method
     *
     * @param string|null $id Usuario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function delete($id = null)
    // {
    //     $this->request->allowMethod(['post', 'delete']);
    //     $usuario = $this->Usuario->get($id);
    //     if ($this->Usuario->delete($usuario)) {
    //         $this->Flash->success(__('The usuario has been deleted.'));
    //     } else {
    //         $this->Flash->error(__('The usuario could not be deleted. Please, try again.'));
    //     }

    //     return $this->redirect(['action' => 'index']);
    // }

    /**
     * [validacao_usuario description]
     *
     * metodo para validar os dados do usuario
     *
     * validações:
     *     quantidade de caracteres de senha validado
     *     cpf invalido
     *     cpf ja cadastrado
     *     apelido login existente
     *     apelido 3 caracteres
     *     data nascimento invalida
     *
     * @param  [type] $dados [description]
     * @return [type]        [description]
     */
    public function validacao_usuario($dados, $codigo_usuario = null, $tipo = 1)
    {
        //variavel de erro
        $error = array();

        if (strlen($dados['senha']) < 6) {
            $error['error'] = true;
            $error['validations']['senha'] = 'Senha precisa ter ao menos 6 caracteres.';
        }

        if ($tipo == 1) { //lyn

            $cpf = null;
            if (isset($dados['cpf'])) {
                //formata o cpf
                $cpf = Comum::soNumero($dados['cpf']);

                //Valida CPF
                if (!Comum::validarCPF($cpf)) {
                    $error['error'] = true;
                    $error['validations']['cpf'] = 'CPF inválido';
                }

                if ($this->request->is(['put'])) {
                    if (!empty($codigo_usuario)) {

                        //Certifica se já existe um usuário com esse CPF e nivel de permissão CLIENTE onde seja diferente
                        $usuarios_dados = $this->Usuario->UsuariosDados->find()->where(['cpf' => $cpf])->first();

                        if ($codigo_usuario != $usuarios_dados->codigo_usuario) {
                            $error['error'] = true;
                            $error['validations']['cpf'] = 'CPF já cadastrado';
                        }
                    } else {
                        //Certifica se já existe um usuário com esse CPF e nivel de permissão CLIENTE onde seja diferente
                        if (!is_null($this->Usuario->UsuariosDados->find()->where(['cpf' => $cpf])->first())) {
                            $error['error'] = true;
                            $error['validations']['cpf'] = 'CPF já cadastrado';
                        }
                    }
                } else {

                    //Verifica a quantidade de caracteres na senha
                    if (strlen($dados['senha']) >= 6) {
                        //verifica se tem o codigo_perfil para validar o cpf com o codigo_perfil para não duplicar para o mesmo sistema e mesmo perfil
                        if (isset($dados['codigo_perfil'])) {
                            if (!empty($dados['codigo_perfil'])) {

                                $validar_dados_usuario = $this->Usuario->find()->where(['apelido' => $dados['login'], 'codigo_uperfil' => $dados['codigo_perfil']])->first();

                                //verifica se já existe um usuario com o apelido (login) já cadastrado
                                $val_codigo_usuario = (!is_null($validar_dados_usuario)) ? $validar_dados_usuario->codigo : '';
                                if (!is_null($validar_dados_usuario)) {
                                    $error['error'] = true;
                                    $error['validations']['login'] = 'Login já existente';
                                }

                                $validar_usuarios_dados = $this->Usuario->UsuariosDados->find()->where(['cpf' => $cpf])->first();
                                //Certifica se já existe um usuário com esse CPF e nivel de permissão CLIENTE com esse perfil
                                if (!is_null($validar_usuarios_dados) && $validar_usuarios_dados->codigo_usuario == $val_codigo_usuario) {
                                    $error['error'] = true;
                                    $error['validations']['cpf'] = 'CPF já cadastrado';
                                }
                            } else {
                                $error['error'] = true;
                                $error['validations']['codigo_perfil'] = 'Codigo Perfil vazio.';
                            }
                        } else {
                            //Certifica se já existe um usuário com esse CPF e nivel de permissão CLIENTE
                            if (!is_null($this->Usuario->UsuariosDados->find()->where(['cpf' => $cpf])->first())) {
                                $error['error'] = true;
                                $error['validations']['cpf'] = 'CPF já cadastrado';
                            }

                            //verifica se já existe um usuario com o apelido (login) já cadastrado
                            if (!is_null($this->Usuario->find()->where(['apelido' => $dados['login']])->first())) {
                                $error['error'] = true;
                                $error['validations']['login'] = 'Login já existente';
                            }
                        }
                    } else {
                        $error['error'] = true;
                        $error['validations']['senha'] = 'A senha deve ser maior ou igual a seis caracteres.';
                    }
                }


                //Valida apelido_sistema
                if (!empty($dados['apelido_sistema']) && isset($dados['apelido_sistema'])) {
                    if (strlen($dados['apelido_sistema']) < 3) {
                        $error['erro'] = true;
                        $error['validations']['apelido_sistema'] = 'O apelido no sistema deve ter no mínimo 3 caracteres';
                    }
                }
                if (!empty($dados['data_nascimento'])) {
                    // $ano_informado = explode("/",$dados['data_nascimento']);
                    // $ano_informado = end($ano_informado);
                    $ano_informado = explode("-", $dados['data_nascimento']);
                    $ano_informado = $ano_informado[0];
                    $ano_hoje = date('Y');

                    if ($ano_informado > $ano_hoje || ($ano_hoje - $ano_informado) > 101) {
                        $error['erro'] = true;
                        $error['validations']['data_nascimento'] = 'Data inválida';
                    }
                }
            } else {
                $error['error'] = true;
                $error['validations']['cpf'] = 'CPF inválido';
            }
        } else if ($tipo == 2) { //modulo agenda

            if (isset($dados['login']) && isset($dados['senha'])) {

                //Valida apelido_sistema
                if (!empty($dados['login']) && isset($dados['login'])) {
                    if (strlen($dados['login']) < 3) {
                        $error['erro'] = true;
                        $error['validations']['login'] = 'O login no sistema deve ter no mínimo 3 caracteres';
                    }
                }

                //verifica se já existe um usuario com o apelido (login) já cadastrado
                if (!is_null($this->Usuario->find()->where(['apelido' => $dados['login']])->first())) {
                    $error['error'] = true;
                    $error['validations']['login'] = 'Login já existente';
                }
            }
        }

        return $error;
    } //fim validacao_usuario



    /**
     * [set_dados_usuario description]
     *
     * metodo para organizar os dados para insercao/alteracao dos usuarios
     *
     * @param [type] $dados [description]
     */
    public function set_dados_usuario($dados, $codigo_cliente, $codigo_funcionario)
    {

        //formata o cpf
        $cpf = Comum::soNumero($dados['cpf']);

        //instancia para criptografar a senha
        $Encriptador = new Encriptacao();

        //seta os dados para gravar na base o novo usuario
        $dados_usuario = array(
            'Usuario' => array(
                "nome" => $dados['nome'],
                "apelido" => $dados['login'],
                "senha" => $Encriptador->encriptar($dados['senha']),
                "email" => $dados['email'],
                "codigo_cliente" => ((isset($codigo_cliente[0])) ? $codigo_cliente[0] : null),
                'ativo' => true,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                // 'codigo_uperfil' => 9, //todos bem
                'codigo_uperfil' => (isset($dados['codigo_perfil'])) ? $dados['codigo_perfil'] : 9,
                'admin' => 0,
                'restringe_base_cnpj' => 0,
                'codigo_departamento' => 1,
                'codigo_empresa' => 1,
                'codigo_funcionario' => $codigo_funcionario,
                'data_inclusao' => date('Y-m-d H:i:s'),

            ),
            'UsuariosDados' => array(
                "cpf" => $cpf, //retira a formatacao
                // "data_nascimento" => Comum::formataData($dados['data_nascimento'],'dmy','ymd'),
                "data_nascimento" => $dados['data_nascimento'],
                "sexo" => $dados['sexo'], //M|F
                "telefone" => Comum::soNumero($dados['telefone']),
                "celular" => isset($dados['celular']) ? Comum::soNumero($dados['celular']) : null,
                "apelido_sistema" => isset($dados['apelido_sistema']) ? $dados['apelido_sistema'] : $dados['nome'],
                "codigo_usuario_inclusao"   => 1,
                "codigo_usuario_alteracao"  => 1,
                "ultimo_acesso" => null,
                "data_inclusao" => date('Y-m-d H:i:s'),
                "notificacao" => $dados['notificacao'],
            ),
        );

        return $dados_usuario;
    } //fim set_dados_usuario

    /**
     * [getCodigoClienteFuncionario description]
     *
     *
     * @OA\Get(
     *   path="/usuario/cliente/{cpf}",
     *   summary="Traz o codigo do usuário pelo cpf do mesmo pesquisado",
     *   @OA\Response(
     *     response="default",
     *     description="Sucesso"
     *     @OA\Schema(
     *         schema="status",
     *         type="integer",
     *         format="int64",
     *         description="Status do retorno"
     *     ),
     *   )
     * )
     *
     *
     * para pegar os clientes e funcionarios caso exista relacionamento
     * @param  [type] $dados [description]
     * @return [type]        [description]
     */
    private function getCodigoClienteFuncionario($cpf, $dados)
    {
        //seta os codigos
        $codigo_cliente = null;
        $codigo_funcionario = null;

        if (!empty($dados['cliente'])) {
            //varre os dados dos clientes
            foreach ($dados['cliente'] as $cliente_dado) {

                //pega o codigo do cliente para validar
                $this->Funcionarios = $this->loadModel('Funcionarios');
                $dadosNina = $this->Funcionarios->getValidaCodigoNinaCliente($cpf, $cliente_dado['codigo_cliente'], $cliente_dado['codigo_confirmacao']);
                // dd($dadosNina);

                //verifica se encontrou o codigo do cliente
                if (!empty($dadosNina)) {

                    //pega o codigo do cliente
                    $codigo_cliente[] = $cliente_dado['codigo_cliente'];

                    //pega os dados do funcionario
                    $this->Funcionario = $this->loadModel('Funcionarios');

                    $funcionario = $this->Funcionario->find()->where(['cpf' => $cpf])->first();
                    //verifica se encontrou o cpf com o funcionario
                    if (!empty($funcionario)) {
                        $codigo_funcionario = $funcionario->codigo;
                    }
                } //fim verificacao codigo_cliente
            } //fim foreach
        } //fim verificacao se mandou um codigo cliente para validar

        return array($codigo_cliente, $codigo_funcionario);
    } //fim getCodigoClienteFuncionario

    /**
     * [getCodigoCliente description]
     *
     * pega o codigo_cliente do usuario que esta se cadastrando pelo cpf
     *
     * @return [type] [description]
     */
    public function getCodigoCliente($cpf)
    {
        //seta os dados de retorno
        $data = [
            'usuario' => false,
            'clientes' => []
        ];

        if (!empty($cpf)) {

            $cpf = Comum::soNumero($cpf);

            // TODO: outros lugares não usam então não sei deve, pois se ja cadastrou errado não vai deixar logar ???? if (!Comum::validarCPF($cpf)) {

            //Certifica se já existe um usuário com esse CPF e nivel de permissão CLIENTE onde seja diferente
            $usuarios_dados = $this->Usuario->UsuariosDados->find()->select([
                'codigo_usuario',
                'avatar',
                'telefone',
                'sexo',
                'apelido_sistema',
                'ultimo_acesso',
                'notificacao',
            ])->where(['cpf' => $cpf])->first();

            // cpf ja cadastrado
            if (!empty($usuarios_dados->codigo_usuario)) {

                $usuario = $this->Usuario->find()->select(['ativo', 'email', 'apelido'])->where(['codigo' => $usuarios_dados->codigo_usuario])->first();
                if (!empty($usuario)) {
                    $data['usuario'] = $usuario->toArray();
                }
                $data['usuario'] = array_merge($data['usuario'], $usuarios_dados->toArray());
            }
        }

        //pega as empresas
        $this->Funcionarios = $this->loadModel('Funcionarios');
        $dados = $this->Funcionarios->getValidaCodigoNinaCliente($cpf);

        if (!empty($dados)) {
            $data['clientes'] = $dados;
        }

        //log
        $entrada = $cpf;
        $log_type = "MOBILE_API_USUARIO_CLIENTE";

        //componente para log da api
        $log_status = '1';
        $ret_mensagem = 'SUCESSO';
        $retorno = $data;

        $this->set(compact('data'));

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim getCodigoCliente

    /**
     * [getValidarVinculo description]
     *
     * metodo para validar o vinculo de usuario com empresa
     *
     *
     * @param  [type] $cpf                   [description]
     * @param  [type] $codigo_cliente        [description]
     * @param  [type] $codigo_nina_validacao [description]
     * @return [type]                        [description]
     */
    public function getValidarVinculo()
    {

        // seta para o retorno
        $data = array();
        $error = array();

        //verifica se é post
        if ($this->request->is('post')) {

            //pega os dados que veio do post
            $request = $this->request->getData();

            //pega as empresas
            $this->Funcionarios = $this->loadModel('Funcionarios');
            $dados = $this->Funcionarios->getValidaCodigoNinaCliente($request['cpf'], $request['codigo_cliente'], $request['codigo_confirmacao']);

            // debug($dados);exit;

            if (!empty($dados)) {

                //atualizar dados
                $this->loadModel('UsuariosDados');

                //pega os dados de usuarios_dados para pegar o usuario_codigo
                $usuarios_dados = $this->UsuariosDados->find()->where(['cpf' => $request['cpf']])->first();

                // debug($usuarios_dados);

                //pega os dados do usuario
                $usuario = $this->Usuario->get($usuarios_dados->codigo_usuario);

                // debug(array($usuario, $request['codigo_cliente']));exit;

                if ($usuario->codigo_cliente == $request['codigo_cliente']) {
                    $data = 'Cliente vinculado com sucesso!';
                } else {
                    if (is_null($usuario->codigo_cliente)) {
                        $dados_usuario = array(
                            'nome' => $usuario['nome'],
                            'email' => $usuario['email'],
                            'codigo_cliente' => $request['codigo_cliente'],
                            'codigo_funcionario' => $usuario['codigo_funcionario'],
                            'data_alteracao' => date('Y-m-d H:i:s'),
                        );

                        //seta os dados para atualizacao
                        $usuario = $this->Usuario->patchEntity($usuario, $dados_usuario);

                        if ($this->Usuario->save($usuario)) {
                            $data = 'Cliente vinculado com sucesso!';
                        } else {
                            $error[] = "Erro ao relacionar cpf com a empresa!";
                        }
                    } else {

                        //instancia a usuario multi cliente
                        $this->loadModel('UsuarioMultiCliente');

                        $usuarioMulti = $this->UsuarioMultiCliente->find()->where(['codigo_cliente' => $request['codigo_cliente'], 'codigo_usuario' => $usuario->codigo])->first();

                        if (empty($usuarioMulti)) {
                            $dadosMultiCliente = array(
                                'codigo_usuario' => $usuario->codigo,
                                'codigo_cliente' => $request['codigo_cliente'],
                                'codigo_usuario_inclusao' => $usuario->codigo,
                                'data_inclusao' => date('Y-m-d H:i:s')
                            );
                            // debug($dadosMultiCliente);exit;

                            //instancia para um novo registro
                            $usuario_multi_cliente = $this->UsuarioMultiCliente->newEntity($dadosMultiCliente);

                            if ($this->UsuarioMultiCliente->save($usuario_multi_cliente)) {
                                $data = 'Cliente vinculado com sucesso!';
                            } else {
                                $error[] = "Erro ao relacionar cpf com a empresa!";
                            }
                        } else {
                            $error[] = "Não existe cliente relacionado para este cpf!";
                        }
                    } //fim isnull usuario codigo_cliente
                } //fim codigo_cliente igual
            } //fim empty dados
            else {
                $error[] = "Erro ao validar vinculo da empresa com esse CPF!";
            }
        } //fim post
        else {

            //erro de metodo
            $error[]   = 'Erro de metodo aguardando POST';
        } //fim else post

        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim getValidarVinculo


    /**
     * [autenticacaoUsuario description]
     *
     * metodo para autenticar o usuario quando ele acabar de se logar
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function autenticacaoUsuario($codigo_usuario)
    {

        // $log= new Log();

        //pega os dados do usuario
        $usuario = $this->Usuario->get($codigo_usuario);

        // $log::debug(print_r($usuario,1));

        $encriptar = new Encriptacao();
        //trabalhando os dados
        $login = $usuario->apelido;
        $senha = $encriptar->desencriptar($usuario->senha);

        $codigo_perfil = $usuario->codigo_uperfil;

        // $log::debug(print_r(array($login,$senha),1));

        //para autenticar na aplicacao
        $http = new Client();
        $response = $http->post(BASE_URL . '/api/auth', [
            'apelido' => $login,
            'senha' => $senha,
            'codigo_perfil' => $codigo_perfil,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);


        // $log::debug(print_r($response,1));
        // $log::debug(var_dump($response));

        //pegando a resposta do status
        if ($response->code == 200) {
            //retorna o token
            return $response->getJson();
        }

        return false;
    } //fim autenticacaoUsuarioPOST



    /**
     * [envia_foto description]
     *
     * metodo para enviar a foto para o file-server (lyn)
     *
     * @return [type] [description]
     */
    public function enviaFoto($codigo_usuario)
    {
        $data = '';
        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['put'])) {


            //verifica se é um usuario do sistema
            $usuario = $this->Usuario->get($codigo_usuario);
            //se não tiver usuario valido
            if (empty($usuario)) {
                $error = "Usuário inválido!";
            } else {
                $this->loadModel('UsuariosDados');

                //pega os dados do usuario
                $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

                if (empty($usuarios_dados)) {

                    $dados = array(
                        "codigo_usuario" => $codigo_usuario,
                        "data_inclusao" => date("Y-m-d H:i:s"),
                        "codigo_usuario_inclusao" => $codigo_usuario
                    );

                    $dados = $this->UsuariosDados->newEntity($dados);

                    if ($this->UsuariosDados->save($dados)) {
                        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();
                    } else {
                        $error[]  = $dados->errors();
                        $this->set(compact('error'));
                        return;
                    }
                }

                $dados_json = $this->request->getData();

                //monta o array para enviar
                $dados = array(
                    'file'   => $dados_json['foto'],
                    'prefix' => 'nina',
                    'type'   => 'base64'
                );

                //url de imagem
                $url_imagem = Comum::sendFileToServer($dados);
                //pega o caminho da imagem
                $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                // debug($caminho_image);

                //verifica se subiu corretamente a imagem
                if (!empty($caminho_image)) {

                    //seta o valor para a imagem que esta sendo criada
                    $usuarios_dados->avatar = FILE_SERVER . $caminho_image['path'];

                    //salva os dados
                    if ($this->UsuariosDados->save($usuarios_dados)) {
                        $data = $usuarios_dados->avatar;
                    } //fim save usuarios dados
                    else {
                        $error[]  = $usuarios_dados->errors();
                    } //fim else usuario dados
                } else {
                    $error = "Problemas em enviar a imagem para o file-server";
                }
            } //fim usuario

        } //fim validacao put
        else {
            $error = "Favor enviar o metodo PUT";
        }

        //log
        $entrada = $codigo_usuario;
        $log_type = "MOBILE_API_PUT_FOTO";
        $this->cod_usuario = $codigo_usuario;

        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim envia_foto

    /**
     * [getFoto description]
     *
     * pega a imagem para exibir pelo codigo do usuario LYN
     *
     * @return [type] [description]
     */
    public function getFoto($codigo_usuario)
    {

        //pega a imagem no banco de dados de usuarios_dados
        $this->loadModel('UsuariosDados');
        //pesquisa os dados do usuario
        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        $foto = '';

        //log
        $entrada = $codigo_usuario;
        $log_type = "MOBILE_API_GET_FOTO";
        $this->cod_usuario = $codigo_usuario;

        if (!empty($usuarios_dados->avatar)) {
            $data = $usuarios_dados->avatar;
            $this->set(compact('data'));

            //para autenticar na aplicacao
            // $http = new Client();
            // $response = $http->get($usuarios_dados->avatar);

            //pegando a resposta do statyus
            // if($response->code == 200) {


            // //componente para log da api
            // $log_status = '1';
            // $ret_mensagem = 'SUCESSO';
            // $retorno = json_encode($usuarios_dados->avatar);

            // //componente para log da api
            // $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);

            // //retorna o token
            // header("Content-type: image/jpeg");
            // echo $response->getBody();
            // }
        } else {
            $error = "Nao existe avatar";
            $this->set(compact('error'));

            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            //componente para log da api
            $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
        }
    } //fim getFoto

    /**
     * [grava_nina_token_push description]
     *
     * metodo para gravar o token push por sistema
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */

    private function grava_token_push($codigo_usuario, $codigo_sistema, $token, $platform = null)
    {

        //usuario_sistema
        $this->loadModel('UsuarioSistema');

        $dados_usuario_sistema['codigo_usuario'] = $codigo_usuario;
        $dados_usuario_sistema['codigo_sistema'] = $codigo_sistema;
        $dados_usuario_sistema['token_push'] = $token;
        $dados_usuario_sistema['platform'] = $platform;

        //busca dados do usuario relacionado no sistema
        $usuarioSistema = $this->UsuarioSistema->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        //verifica se existe dados no usuario_sistema para alteracao
        if (!empty($usuarioSistema)) {

            //para alteracao
            $usuario_sistema = $this->UsuarioSistema->patchEntity($usuarioSistema, $dados_usuario_sistema);
            // debug($usuario_sistema);
        } else { //inclusao
            $dados_usuario_sistema['data_inclusao'] = date('Y-m-d H:i:s');
            $dados_usuario_sistema['codigo_usuario_inclusao'] = $codigo_usuario;
            $dados_usuario_sistema['ativo'] = 1;

            //para criar um novo usuario
            $usuario_sistema = $this->UsuarioSistema->newEntity($dados_usuario_sistema);
        }

        if (!$this->UsuarioSistema->save($usuario_sistema)) {

            // debug($usuario_sistema->errors());exit;

            return false;
        }

        return true;
    } //fim grava_nina_token_push

    /**
     * [tokenPush description]
     *
     * metodo para endpoint de alteração do token push
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function tokenPush($codigo_usuario)
    {
        $this->loadModel('UsuariosDados');

        //pega os dados do usuario para saber se ele aceitou a notificação
        $usuarios_dados = $this->UsuariosDados->find()->where(["codigo_usuario" => $codigo_usuario])->first();

        // if($usuarios_dados->notificacao == 1) {
        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['put'])) {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //verifica se existe erros
            if (!empty($dados['token_push'])) {
                try {

                    //model para cadastro do token_push
                    $this->loadModel('UsuarioSistema');

                    //dados do sistema
                    $codigo_sistema = $dados['codigo_sistema'];
                    $token = $dados['token_push'];
                    $platform = (isset($dados['platform']) ? $dados['platform'] : null);

                    $results = $this->grava_token_push($codigo_usuario, $codigo_sistema, $token, $platform);

                    if ($results) {
                        $data = "Registro atualizado com sucesso!";
                    } //fim save usuario sistema
                    else {
                        throw new Exception("Error ao atualizar o token.");
                    }
                } catch (Exception $e) {
                    $error[] = $e->getMessage();
                }
            } //verifica se exite token
            else {
                $error[]  = "Precisa de um token para alteração!";
            } //fim else das validacoes
        } //fim metodo put
        // }
        // else {
        //     $data = "Usuario nao permitiu notificacao";
        // }


        //verifica o retorno para quem chamou a api
        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim tokenPush

    public function removeTokenPush($codigo_sistema)
    {
        $this->request->allowMethod(['DELETE']);
        $this->connect->begin();

        try {
            if (empty($codigo_sistema) || !is_numeric($codigo_sistema)) {
                $data = [
                    'error' => [
                        'message' => 'Não foi informado o código da aplicação ou foi informado no padrão incorreto.',
                    ],
                ];

                $this->set(compact('data'));

                return;
            }

            $userId = $this->getAuthenticatedUser();

            $this->loadModel('UsuarioSistema');

            $systemUser = $this->UsuarioSistema->find()
                ->where([
                    'codigo_usuario' => $userId,
                    'codigo_sistema' => (int) $codigo_sistema,
                    'ativo' => 1,
                ])
                ->first();

            if (empty($systemUser)) {
                $data = [
                    'message' => 'Não foi encontrado dados referente ao código informado.',
                ];

                $this->set(compact('data'));

                return;
            }

            $putData = [
                'token_push' => null,
                'platform' => null,
                'data_alteracao' => date('Y-m-d H:i:s'),
                'codigo_usuario_alteracao' => $userId,
            ];

            $entity = $this->UsuarioSistema->patchEntity($systemUser, $putData);

            if (!$this->UsuarioSistema->save($entity)) {
                $data = [
                    'error' => [
                        'message' => 'Erro ao remover token.',
                    ],
                ];

                $this->connect->rollback();

                $this->set(compact('data'));

                return;
            }

            $this->connect->commit();

            $data = [
                'message' => 'Token do usuário removido com sucesso.',
            ];

            $this->set(compact('data'));
        } catch (\Exception $exception) {
            $this->connect->rollback();

            $error = [
                'message' => 'Erro interno no servidor.',
            ];

            $this->set(compact('error'));
        }
    }

    /**
     * [home description]
     *
     * metodo para buscar os dados da Home do app
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function home($codigo_usuario)
    {

        //variavel auxiliar para o retorno do metodo home
        $data = array();

        $data['contato_emergencia'] = array();
        //sua saude
        $data['porcent_quest'] = '';
        //consultas
        $data['proximas_consultas'] = array();
        //atestados
        $data['atestados_ativos'] = array();
        $data['atestados_historico'] = array();
        //medicamento
        $data['medicamentos'] = array();
        //covid-19 brasil
        $data['mapa_covid'] = false;

        $data['banner_covid'] = array();

        //pega os dados do contato de emergencia
        $this->loadModel('UsuarioContatoEmergencia');

        // verificação de vinculo com cliente,
        // conforme o usuário não escolhe um cliente ao se cadastrar, não pode apresentar informações de exames associadas a clientes
        $usuario_possui_vinculo = $this->Usuario->validaSeUsuarioPossuiVinculoCliente((int)$codigo_usuario);

        $contato_emergencia = $this->UsuarioContatoEmergencia->find()->select(['codigo_usuario', 'nome', 'telefone', 'celular', 'grau_parentesco', 'email'])->where(['codigo_usuario' => $codigo_usuario])->first();
        //verifica se existe contato de emergencia
        if (!empty($contato_emergencia)) {
            $data['contato_emergencia'] = $contato_emergencia;
        }

        //pega os dados do usuario
        $this->loadModel('UsuariosDados');

        //dados do usuario
        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        if (!empty($usuarios_dados)) {

            //instancia a model
            $this->loadModel('Questionarios');
            $this->loadModel('UsuariosQuestionarios');
            $this->loadModel('PedidosExames');

            //FILTRO MENU
            $filtro_menu = array(
                'sua_saude' => true,
                'consultas' => true,
                'atestados' => true,
                'medicamentos' => true,
                'mapa_covid_brasil' => true,
                'seus_exames' => true
            );

            // obtem (codigo_cliente) vinculos em empresas
            $this->FuncionariosModel = $this->loadModel('Funcionarios');
            $codigo_cliente_vinculado = $this->FuncionariosModel->obterCodigoClienteVinculado($usuarios_dados->cpf); //array

            //verifica se existe exames a vencer daqui a 30 dias
            $solicitacao_consulta = false;
            $de = date('Ymd');
            $base_periodo = strtotime('+1 month', strtotime(Date('Y-m-d')));
            $ate = date('Ymd', $base_periodo);

            $dados_nova_consulta = ($usuario_possui_vinculo) ? $this->PedidosExames->getPosicaoExamesAVencer($usuarios_dados->cpf, $de, $ate) : null;
            //verifica se existe exames a vencer dados para a nova consulta
            if (!empty($dados_nova_consulta)) {
                $solicitacao_consulta = true;
            } //fim verificacao dados_nova_consulta

            //verifica solicitacao para preencher novamente o questionario
            $novo_quiz = false;

            //verifica se tem vinculo a algum cliente
            //caso nao exista irá explodir excessão pois precisa de algum cliente vinculado para pesquisar os proximos exames
            if (!empty($codigo_cliente_vinculado)) {

                #####################FILTRO MENU##################
                //validação menu
                //pega o grupo economico do cliente
                $dados_ge = $this->Usuario->getClienteGrupoEconomico($codigo_cliente_vinculado[0]);

                //verifica se tem dados do grupoeconomico
                if (!empty($dados_ge)) {
                    //pega o codigo cliente matriz
                    $codigo_cliente_matriz = $dados_ge->codigo_cliente;

                    //pega o menu
                    $this->loadModel('LynMenuCliente');
                    $menu = $this->LynMenuCliente->getMenuCliente($codigo_cliente_matriz);
                    //verifica se tem menu para filtrar caso tenha deve aplicar o filtro
                    if (!empty($menu)) {

                        $filtro_menu = array(
                            'sua_saude' => false,
                            'consultas' => false,
                            'atestados' => false,
                            'medicamentos' => false,
                            'mapa_covid_brasil' => false,
                            'seus_exames' => false
                        );

                        //varre os menus que deve filtrar os dados da home
                        foreach ($menu as $key => $m) {

                            switch ($m['codigo']) {
                                case '1': //sua saude
                                    $filtro_menu['sua_saude'] = true;
                                    break;
                                case '2': //consultas
                                    $filtro_menu['consultas'] = true;
                                    break;
                                case '3': //atestados
                                    $filtro_menu['atestados'] = true;
                                    break;
                                case '4': //medicamentos
                                    $filtro_menu['medicamentos'] = true;
                                    break;
                                case '5': //covid 19 mapa
                                    $filtro_menu['mapa_covid_brasil'] = true;
                                    break;
                                case '6': //seus exames
                                    $filtro_menu['seus_exames'] = true;
                                    break;
                            }
                        } //fim foreach do menu

                    }
                } //fim dados_ge

                #####################FIM FILTRO MENU##################


                $dados_ultimos_quiz = $this->UsuariosQuestionarios->getUltimosQuestionariosRespondidos($codigo_usuario);
                //verifica se existe dados
                if (!empty($dados_ultimos_quiz)) {

                    $data_ultima_resposta = '';
                    //varre os dados retornados
                    foreach ($dados_ultimos_quiz as $d_ultimo_quiz) {

                        if (empty($data_ultima_resposta)) {
                            $data_ultima_resposta = $d_ultimo_quiz['data_concluido'];
                        } else {
                            if ($data_ultima_resposta < $d_ultimo_quiz['data_concluido']) {
                                $data_ultima_resposta = $d_ultimo_quiz['data_concluido'];
                            }
                        }
                    } //fim foreach

                    if (!empty($data_ultima_resposta)) {
                        //para verificar se existe agendamento com a data maior que a da resposta do quiz
                        $param_conditions['CONVERT(CHAR(8),PedidosExames.data_inclusao ,112) > '] = $data_ultima_resposta;
                        $param_conditions[] = 'PedidosExames.exame_periodico = 1';

                        //pega os dados de agendamento
                        $dados_agendamento = $this->PedidosExames->proximas_consultas($codigo_cliente_vinculado, $d_ultimo_quiz['codigo_funcionario'], $param_conditions)->toArray();
                        //verifica se encontrou algo na base de dados
                        if (!empty($dados_agendamento)) {
                            $novo_quiz = true;
                        }
                    }
                } //fim verifica se existe dados_ultimo_quiz

                // se possuir vinculo
                if ($usuario_possui_vinculo) {

                    //pega os dados do usuario
                    $usuario = $this->Usuario->get($codigo_usuario);

                    // Pega o código do funcionario
                    //$this->loadModel('Funcionarios');
                    $funcionarios = $this->FuncionariosModel->getCodigoFuncionario($usuarios_dados->cpf);

                    foreach ($funcionarios as $funcionario) {

                        $usuario->codigo_funcionario = $funcionario->codigo;

                        //verifica se tem o codigo de funcionarios
                        if (!empty($usuario->codigo_funcionario)) {

                            //carrega o pedido de exames
                            $this->loadModel('PedidosExames');

                            //proximas consultas
                            $dias = 30;
                            // $param_conditions_prx_consultas[] = 'PedidosExames.data_inclusao BETWEEN DATEADD(day,-'.$dias.', GETDATE()) AND GETDATE()';
                            $param_conditions_prx_consultas[] = "ItemPedidoExame.data_agendamento >= '" . date('Y-m-d') . "' ";

                            //verifica se pode pegar os dados de consutas
                            if ($filtro_menu['consultas']) {
                                $proximas = $this->PedidosExames->proximas_consultas($codigo_cliente_vinculado, $usuario->codigo_funcionario, $param_conditions_prx_consultas)->toArray();

                                foreach ($proximas as $proximas_consultas) {

                                    if (
                                        isset($proximas_consultas['data_agendamento'])
                                        && !empty($proximas_consultas['data_agendamento'])
                                    ) {
                                        // esta chegando do banco como YYYY-mm-dd e foi mantido
                                        // formate aqui se necessario
                                    } else {
                                        // inicializa por um valor que o app espera como nulo
                                        $proximas_consultas['data_agendamento'] = null;
                                    }
                                    // pode retornat nulo e o concat de algum sql ta colocando 0 em algumas horas que são nulas no banco
                                    // pode retornar hora com 3 digitos
                                    // pode retornar texto undef no lugar da hora

                                    if (
                                        isset($proximas_consultas['hora_agendamento'])
                                        && !empty($proximas_consultas['hora_agendamento'])
                                        && $proximas_consultas['hora_agendamento'] != '0'
                                        && $proximas_consultas['hora_agendamento'] != 'undef'
                                        && is_numeric($proximas_consultas['hora_agendamento'])
                                    ) {

                                        // deve chegar aqui no hormato Hm ex. 1806 , 2032, se for preciso
                                        // formate a hora aqui se for preciso retornar com dois pontos por exemplo
                                        if (strlen($proximas_consultas['hora_agendamento']) == 3) {
                                            $proximas_consultas['hora_agendamento'] = '0' . $proximas_consultas['hora_agendamento'];
                                        }
                                    } else {
                                        // inicializa por um valor que o app espera como nulo
                                        $proximas_consultas['hora_agendamento'] = null;
                                    }

                                    $proximas_consultas['reponder_atraves_lyn'] = false;
                                    $proximas_consultas['formulario_perguntas'] = null;
                                    $proximas_consultas['formulario_respostas'] = null;

                                    if ($proximas_consultas['codigo_exame'] == 27) {
                                        $avaliacao = $this->PedidosExames->getUsuariosResponderExame($codigo_usuario);

                                        if ($avaliacao && empty($proximas_consultas['resultado'])) {
                                            $proximas_consultas['reponder_atraves_lyn'] = true;
                                            $proximas_consultas['formulario_perguntas'] = "/psicossocial/perguntas/$codigo_usuario";
                                            $proximas_consultas['formulario_respostas'] = "/psicossocial/responder/$codigo_usuario";
                                        }
                                    }

                                    $data['proximas_consultas'][] = $proximas_consultas;
                                }

                                if (isset($proximas_consultas[0])) {
                                    $data['proximas_consultas'] = [$proximas_consultas[0]];
                                }
                            } //fim consulta

                            //verifica se pode pegar os dados de atestados
                            if ($filtro_menu['atestados']) {

                                //atestados
                                $this->loadModel('Atestados');

                                //atestados ativos
                                foreach ($this->Atestados->getAtestados($usuario->codigo_funcionario, 'A') as $key => $atestado) {
                                    if ($key < 2) {
                                        $data['atestados_ativos'][] = $atestado;
                                    }
                                }

                                //atestados historico
                                foreach ($this->Atestados->getAtestados($usuario->codigo_funcionario, 'H', 2) as $atestado_historico) {
                                    //atestados historico
                                    $data['atestados_historico'][] = $atestado_historico;
                                }
                            } //fim filtro atestados


                            #####################Banner Covid#####################

                            //Questionário está ativo?
                            $retornoaotrabalho = 13;
                            $sintomasdiarios = 16;

                            $retornoaotrabalho_permissao = false;
                            $sintomasdiarios_permissao = false;

                            $this->loadModel('ClienteQuestionarios');

                            if ($this->Questionarios->find()->where(['codigo' => $retornoaotrabalho, 'status' => 1])) {
                                //Verifica permissao cliente
                                if ($this->ClienteQuestionarios->QuestionarioPermissao($codigo_cliente_vinculado, $retornoaotrabalho)) {
                                    $retornoaotrabalho_permissao = true;
                                }
                            }
                            if ($this->Questionarios->find()->where(['codigo' => $sintomasdiarios, 'status' => 1])) {
                                //Verifica permissao cliente
                                if ($this->ClienteQuestionarios->QuestionarioPermissao($codigo_cliente_vinculado, $sintomasdiarios)) {
                                    $sintomasdiarios_permissao = true;
                                }
                            }

                            // var_dump($retornoaotrabalho_permissao);
                            // var_dump($sintomasdiarios_permissao);
                            // exit;

                            $permissoes = array(
                                "retornoaotrabalho" => $retornoaotrabalho_permissao,
                                "sintomasdiarios" => $sintomasdiarios_permissao
                            );

                            //se nenhum dos dois tem permissão, nem monta o banner
                            if ($retornoaotrabalho_permissao || $sintomasdiarios_permissao) {

                                $dados_funcionario = $this->Funcionarios->getColaboradorDetalhe($usuario->codigo_funcionario);


                                $this->loadModel('Respostas');
                                $data['banner_covid'] = $this->Respostas->montaBanner($codigo_usuario, $permissoes);

                                ##########################DISCLAMER##########################
                                //verifica se vai aparecer o disclamer
                                if (isset($data['banner_covid']['codigo_questionario'])) {
                                    if ($data['banner_covid']['codigo_questionario'] == 13) {

                                        //pega o disclamer
                                        $this->loadModel('LynMsg');
                                        //disclamer para retorno ao trabalho
                                        $disclamer = $this->LynMsg->find()->where(['codigo' => 1])->first();

                                        //verifica se tem o disclamer
                                        if (!empty($disclamer)) {
                                            $disclamer = $disclamer->toArray();
                                            $data['banner_covid']['disclamer'] = [
                                                'titulo' => $disclamer['titulo'],
                                                'conteudo' => [
                                                    ['subtitulo' => '', 'subconteudo' => $disclamer['descricao']]
                                                ]
                                            ];
                                        }
                                    } //fim verificacao disclamer
                                }
                                ##########################FIM DISCLAMER##########################

                                // debug($data['banner_covid']);exit;

                                if (isset($data['banner_covid']['grupo'])) {

                                    $grupo = $data['banner_covid']['grupo'];

                                    if ($grupo == "Vermelho") {

                                        //Caso tenha mais de uma empresa para este usuário, devemos trazer os contatos cadastrados no ITHealth, que deve entrar em contato
                                        $this->loadModel('ClienteContato');
                                        $contato_empresa = array();
                                        foreach ($codigo_cliente_vinculado as $v) {

                                            $dados_empresa = $this->ClienteContato->find()->where(['codigo_cliente' => $v, 'codigo_tipo_contato' => 12])->hydrate(false)->first();
                                            // debug($dados_empresa);exit;

                                            if ($dados_empresa) {
                                                //$dados_empresa = $this->ClienteContato->find()->where(['codigo_cliente' => $v, 'codigo_tipo_contato'=>7])->hydrate(false)->first();
                                                //}
                                                //var_dump($dados_empresa);
                                                $contato_empresa[] = $dados_empresa['ddd'] . $dados_empresa['descricao'];
                                            }
                                        }

                                        if (!empty($contato_empresa)) {
                                            $contato_empresa = implode(" / ", $contato_empresa);
                                        } else {
                                            $contato_empresa = '';
                                        }

                                        $data['banner_covid']['contato'] = $contato_empresa;

                                        //verifica se tem feedback do banner vermelho
                                        if (!empty($permissao_retornoaotrabalho->feedback_vermelho_covid)) {
                                            $data['banner_covid']['titulo'] = $permissao_retornoaotrabalho->feedback_vermelho_covid;
                                        }
                                        //covid
                                        if (!empty($permissao_sintomasdiarios->feedback_vermelho_covid)) {
                                            $data['banner_covid']['titulo'] = $permissao_sintomasdiarios->feedback_vermelho_covid;
                                        }

                                        $data['banner_covid']['titulo'] .= '' . $contato_empresa;
                                    } else {
                                        $data['banner_covid']['dados_pessoais'] = $this->getDadosFuncionarioCovid($dados_funcionario);

                                        //pega o ultimo passaporte gerado para gerar o qrcode
                                        //a pedido do joao estou comentando
                                        $qr_dados = false;
                                        if (!empty($codigo_usuario)) {

                                            //verificar se ele pode gerar qr code
                                            $this->loadModel('FuncionarioLiberacaoTrabalho');
                                            $func_liberacao_trabalho = $this->FuncionarioLiberacaoTrabalho->find()->select(['codigo'])->where(['codigo_funcionario' => $funcionario->codigo])->first();

                                            if (!empty($func_liberacao_trabalho)) {
                                                $this->loadModel('ResultadoCovid');
                                                $resultadoCovid = $this->ResultadoCovid->find()->where(['codigo_usuario' => $codigo_usuario])->order(['codigo desc'])->first();

                                                if (!empty($resultadoCovid)) {
                                                    $qr_dados = $resultadoCovid->codigo;
                                                } //fim resultadoCovid

                                            } //fim funcionario liberacao

                                        } //fim codigo_usuario

                                        $data['banner_covid']['qr'] = $qr_dados;
                                        //fim qrcode

                                    }
                                } //fim isset grupo

                            } // fim permissao

                            #####################FIM Banner Covid#####################

                        } //fim codigo_funcionario

                    } //fim foreach funcionarios

                    //banner do mapa covid
                    if ($filtro_menu['mapa_covid_brasil']) {
                        $data['mapa_covid'] = [
                            "titulo" => "Confira o Mapa Covid-19",
                            "conteudo" => [
                                "subtitulo" => "",
                                "subconteudo" => "Veja no mapa o número de óbitos, casos confirmados e recuperados de Coronavírus no Brasil"
                            ]
                        ];
                    }
                } //fim usuario vinculo

            } //fim verificacao se existe vinculo


            //solicitacao_consulta
            if ($filtro_menu['consultas']) {
                $data['solicitacao_consulta'] = $solicitacao_consulta;
            }

            //dados do questionario
            if ($filtro_menu['sua_saude']) {
                $data['porcent_quest'] = $this->Questionarios->getStatusQuestionarios($usuarios_dados);
            }

            //novo_quiz
            if ($filtro_menu['sua_saude']) {
                $data['novo_quiz'] = $novo_quiz;
            }

            if ($filtro_menu['medicamentos']) {
                $usuario = $this->Auth->user('codigo');
                $this->loadModel('UsuariosMedicamentos');
                $data['medicamentos'] = $this->UsuariosMedicamentos->getListaMedicamentos($usuario);
            }
        } //fim empty dados usuario

        $this->set(compact('data'));
    } //fim home

    /**
     * [getDadosFuncionarioCovid metodo para montar os dados do funcionarios para a situação do covid dentro do lyn]
     *
     *
     * @param  [type] $dados_funcionario [description]
     * @return [type]                    [description]
     */
    public function getDadosFuncionarioCovid($dados_funcionario, $codigo_usuario = null)
    {
        if (empty($dados_funcionario)) {
            return false;
        }

        foreach ($dados_funcionario as $v) {
            $dados = array(
                // "valido_ate" => date("d/m/Y", strtotime('+1 day')), //a pedido do pablo alterado para o dia e hora 23:59:59
                "valido_ate" => date("d/m/Y") . " 23:59:59",
                "cpf" => $v['cpf'],
                "nome" => $v['nome'],
                "foto" => $v['foto'],
                "matricula" => $v['matricula'],
                "empresa" => $v['empresa']
            );
        }

        return $dados;
    } //fim getDadosFuncionarioCovid

    /**
     * [delValidarVinculo description]
     *
     * metodo para deletar o vinculo de usuario com empresa
     *
     * @param  [int] $codigo_usuario        [description]
     * @param  [int] $codigo_cliente        [description]
     * @return [boll] true/false            [description]
     */
    public function delVinculoUsuarioCliente($codigo_usuario, $codigo_cliente)
    {

        // seta para o retorno
        $data = array();
        $error = array();
        $naoExistenteMultiEmpresa = '';
        $naoExistenteUsuario = '';

        //verifica se é post
        if ($this->request->is('delete')) {

            //carrega a usuario_multi_cliente
            $this->loadModel('UsuarioMultiCliente');
            //pega os dados do usuariomulticliente
            $usuarioMultiCliente = $this->UsuarioMultiCliente->find()->where(['codigo_cliente' => $codigo_cliente, 'codigo_usuario' => $codigo_usuario])->first();

            //verifica se existe o usuario multi cliente
            if (!empty($usuarioMultiCliente)) {

                //verifica se deletou o resultado inserido
                if (!$this->UsuarioMultiCliente->delete($usuarioMultiCliente)) {
                    // debug($res->errors());
                    $error[] = "Erro ao deletar cliente (1)";
                } //fim save
                else {
                    //dados de retorno
                    $data = "Dado excluido com sucesso";
                }
            } //fim if empty multi cliente
            else {
                $naoExistenteMultiEmpresa = true;
            }

            //pega o dados do usuario para verificacao
            $usuario = $this->Usuario->find()->where(['codigo' => $codigo_usuario, 'codigo_cliente' => $codigo_cliente])->first();

            //verifica se existe o usuario
            if (!empty($usuario)) {

                /**
                 *  verificacao para fazer a jogada de nao deixar o usuario sem cliente principal caso exista na multi cliente                 *
                 */
                //pega os dados da multi cliente para o usuario
                $usuarioMultiCliente = $this->UsuarioMultiCliente->find()->where(['codigo_usuario' => $codigo_usuario])->first();

                //verifica se existe dados na multicliente
                if (!empty($usuarioMultiCliente)) {

                    //dados do usuario para atualizacao
                    $dados_usuario = array(
                        'codigo_cliente' => $usuarioMultiCliente->codigo_cliente,
                        'data_alteracao' => date('Y-m-d H:i:s'),
                    );

                    //verifica se deletou o resultado inserido
                    if (!$this->UsuarioMultiCliente->delete($usuarioMultiCliente)) {
                        // debug($res->errors());
                        $error[] = "Erro ao deletar cliente (3)";
                    } //fim save
                    else {
                        //dados de retorno
                        $data = "Dado excluido com sucesso";
                    }
                } // fim verificacao se nao for vazio o multi cliente
                else {
                    //dados do usuario para atualizacao
                    $dados_usuario = array(
                        'codigo_cliente' => null,
                        'data_alteracao' => date('Y-m-d H:i:s'),
                    );
                } //fim else multi cliente


                //seta os dados para atualizacao
                $usuario = $this->Usuario->patchEntity($usuario, $dados_usuario);

                if ($this->Usuario->save($usuario)) {
                    //dados de retorno
                    $data = "Dado excluido com sucesso";
                } //fim usuario atualizacao
                else {

                    //variavel de erro caso exista
                    $error[] = "Erro ao deletar cliente (2)";
                } //fim else
            } //fim if usuario vazio
            else {
                $naoExistenteUsuario = true;
            }


            if ($naoExistenteUsuario && $naoExistenteUsuario) {
                $error[] = "Não existem vinculo com essa empresa";
            }
        }

        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim delValidarVinculo

    /**
     *  function recuperar_senha() {
     *        if (!empty($this->data)) {
     *            $usuario = $this->Usuario->findByApelidoAndAtivo($this->data['Usuario']['apelido'],1);
     *           if (empty($usuario['Usuario']['codigo_cliente']) && empty($usuario['Usuario']['codigo_fornecedor']) && empty($usuario['Usuario']['codigo_proposta_credenciamento']))
     *              $this->BSession->setFlash('no_client_user');
     *         else {
     *            App::import('Vendor', 'encriptacao');
     *           $encriptacao = new Buonny_Encriptacao();
     *          $this->data['Usuario']['senha'] = $encriptacao->desencriptar($usuario['Usuario']['senha']);
     *        }
     *    }
     *    }
     *
     * @return void
     */
    public function recuperarSenha()
    {
        $this->request->allowMethod(['POST', 'PUT']); // aceita apenas POST

        $params = $this->request->getData();

        $params['codigo_sistema'] = (isset($params['codigo_sistema'])) ? $params['codigo_sistema'] : 1;

        if (!isset($params['apelido']) && !isset($params['codigo_sistema'])) {
            $error = "Login não encontrado";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['apelido'])) {
            $error = "Login não existente";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['codigo_sistema'])) {
            $error = "Login no sistema não existente";
            $this->set(compact('error'));
            return;
        }

        $apelido = $params['apelido'];
        $codigo_sistema = $params['codigo_sistema'];

        //de/para codigo_sistema com perfil
        $codigo_perfil = '';
        switch ($codigo_sistema) {
            case '1': //lyn
                $codigo_perfil = 9;
                break;
            case '3': //thermal care
                $codigo_perfil = 42;
                break;
            case '4': //gestao de riscos
                $codigo_perfil = 43;
                break;
        }

        // verifica se existe usuario por apelido
        $this->loadModel('Usuario');

        //verifica se vai buscar pelo perfil ou não
        if (!empty($codigo_perfil)) {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido, $codigo_perfil);
        } else {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido);
        }

        if (!isset($usuario['email']) || empty($usuario['email'])) {
            $error = 'Usuario não possui email vinculado';
            $this->set(compact('error'));
            return;
        }

        $usuario_email = $usuario['email'];
        $usuario_nome = $usuario['nome'];
        $usuario_senha = $usuario['senha'];

        $senha_desencriptada = (new Encriptacao())->desencriptar($usuario_senha);

        // obter view template email  [ envio_senha_email ]
        // template email

        $nome_usuario = $usuario_nome;
        $mensagem = $senha_desencriptada;


        $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
                        <body>
                            <div style="clear:both;">
                                <div>
                                    <img style="display:block;" src="http://portal.rhhealth.com.br/portal/img/logo-rhhealth.png" style="float:left;">
                                    <hr style="border:1px solid #EEE; display:block;" />
                                </div>
                                <div style="background: #fff; float:none; height: 10px; margin-top:5px; padding:8px 10px 0 0; width:99%;"></div>
                            </div>
                            <div style="clear:both;padding-top:50px;padding-left:50px;width:98.4%;min-height:300px;">
                                <table width="100%" style="font-family:verdana;">
                                    <tr>
                                        <td style="font-size:12px;"><strong>Nome:</strong></td>
                                        <td style="font-size:12px;">' . $nome_usuario . '</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:12px;"><strong>Senha:</strong></td>
                                        <td style="font-size:12px;">
                                            ' . $mensagem . '
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <br /><br />
                            </div>
                        </body>
                    </html> ';

        $this->loadModel('MailerOutbox');

        $conn = ConnectionManager::get('default');
        $insert = "INSERT INTO mailer_outbox ([to],[subject],[content],[from],[created],[modified]) VALUES ('" . $usuario_email . "','Recuperação de senha','" . $template . "','portal@rhhealth.com.br','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "')";
        $registro = $conn->execute($insert);

        // obter email mascarado
        $email_mascarado = $this->mascaraEmail($usuario_email);

        $data = sprintf('Link de recuperação da senha enviada ao email %s .', $email_mascarado);

        $this->set(compact('data'));
    }

    public function postRecuperarSenhaToken()
    {
        $this->request->allowMethod(['POST']); // aceita apenas POST

        $params = $this->request->getData();

        if (!isset($params['apelido']) && !isset($params['codigo_sistema'])) {
            $error = "Login não encontrado";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['apelido'])) {
            $error = "Login não existente";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['codigo_sistema'])) {
            $error = "Login no sistema não existente";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['alerta_tipo'])) {
            $error = "Alerta tipo não existente";
            $this->set(compact('error'));
            return;
        }

        $apelido = $params['apelido'];
        $codigo_sistema = $params['codigo_sistema'];

        //de/para codigo_sistema com perfil
        $codigo_perfil = '';
        switch ($codigo_sistema) {
            case '1': //lyn
                $codigo_perfil = 9;
                break;
            case '3': //thermal care
                $codigo_perfil = 42;
                break;
            case '4': //gestao de riscos
                $codigo_perfil = 43;
                break;
            case '8': //POS
                $codigo_perfil = 50;
                break;
        }

        // verifica se existe usuario por apelido
        $this->loadModel('Usuario');

        //verifica se vai buscar pelo perfil ou não
        if (!empty($codigo_perfil)) {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido, $codigo_perfil);
        } else {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido);
        }

        if (!isset($usuario['email']) || empty($usuario['email'])) {
            $error = 'Usuario não possui email vinculado';
            $this->set(compact('error'));
            return;
        }

        $codigo_usuario = $usuario['codigo'];
        $usuario_email = $usuario['email'];
        $usuario_nome = $usuario['nome'];
        $usuario_senha = $usuario['senha'];

        //Gera um token aleatório para o usuário
        $token = str_pad((string)mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        //Define data de expiração do token
        $data_token = new DateTime(date('Y-m-d H:i:s'));
        $data_token->add(new DateInterval('PT30M')); //Adiciona +30 minutos
        $tempo_validacao = $data_token->format('Y-m-d H:i:s');

        $alerta_tipo = $params['alerta_tipo'];

        $validar_token_tipo = $this->ValidarTokenTipo->find()
            ->where(['codigo' => $alerta_tipo])->first();

        $sistema_validar_token_tipo = $this->SistemaValidarTokenTipo->find()
            ->where(['codigo_validar_token_tipo' => $validar_token_tipo['codigo']])->first();

        //Defini dados para inserir em UsuarioValidarToken
        $dados = array();

        $dados['codigo_usuario'] = $codigo_usuario;
        $dados['codigo_sistema'] = $codigo_sistema;
        $dados['codigo_sistema_validar_token_tipo'] = $sistema_validar_token_tipo['codigo'];
        $dados['token'] = $token;
        $dados['destino_descricao'] = $validar_token_tipo['descricao'];
        $dados['tempo_validacao'] = $tempo_validacao;
        $dados['validado'] = 0;
        $dados['codigo_usuario_inclusao'] = $codigo_usuario;

        $entityUsuarioValidarToken = $this->UsuarioValidarToken->newEntity($dados);
        //salva os dados
        if (!$this->UsuarioValidarToken->save($entityUsuarioValidarToken)) {
            $data['message'] = 'Erro ao inserir em UsuarioValidarToken';
            $data['error'] = $entityUsuarioValidarToken->errors();
            $this->set(compact('data'));
            return;
        }

        $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
                        <body>
                            <div style="clear:both;">
                                <div>
                                    <img style="display:block;" src="http://portal.rhhealth.com.br/portal/img/logo-rhhealth.png" style="float:left;">
                                    <hr style="border:1px solid #EEE; display:block;" />
                                </div>
                                <div style="background: #fff; float:none; height: 10px; margin-top:5px; padding:8px 10px 0 0; width:99%;"></div>
                            </div>
                            <div style="clear:both;padding-top:50px;padding-left:50px;width:98.4%;min-height:300px;">
                                <table width="100%" style="font-family:verdana;">
                                    <tr>
                                        <td style="font-size:16px;width: 50px"><strong>Nome:</strong></td>
                                        <td style="font-size:16px;">' . $usuario_nome . '</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:16px;width: 50px"><strong>Token:</strong></td>
                                        <td style="font-size:16px;font-weight: bold">
                                            ' . $token . '
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <p><strong>Este token expira em 30 minutus.</strong></p>
                                <br /><br />
                            </div>
                        </body>
                    </html> ';

        $this->loadModel('MailerOutbox');

        $conn = ConnectionManager::get('default');
        $insert = "INSERT INTO mailer_outbox ([to],[subject],[content],[from],[created],[modified]) VALUES ('" . $usuario_email . "','Recuperação de senha','" . $template . "','portal@rhhealth.com.br','" . date('Y-m-d H:i:s') . "','" . date('Y-m-d H:i:s') . "')";
        $registro = $conn->execute($insert);

        // obter email mascarado
        $email_mascarado = $this->mascaraEmail($usuario_email);

        $data = sprintf('Token de recuperação da senha enviado ao e-mail %s .', $email_mascarado);

        $this->set(compact('data'));
    }

    public function putRecuperarSenhaToken()
    {
        $this->request->allowMethod(['PUT']); // aceita apenas PUT

        $params = $this->request->getData();

        if (!isset($params['apelido']) && !isset($params['codigo_sistema'])) {
            $error = "Login não encontrado";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['apelido'])) {
            $error = "Login não existente";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['codigo_sistema'])) {
            $error = "Login no sistema não existente";
            $this->set(compact('error'));
            return;
        }

        if (empty($params['token'])) {
            $error = "Token não existente";
            $this->set(compact('error'));
            return;
        }

        $apelido = $params['apelido'];
        $codigo_sistema = $params['codigo_sistema'];

        //de/para codigo_sistema com perfil
        $codigo_perfil = '';
        switch ($codigo_sistema) {
            case '1': //lyn
                $codigo_perfil = 9;
                break;
            case '3': //thermal care
                $codigo_perfil = 42;
                break;
            case '4': //gestao de riscos
                $codigo_perfil = 43;
                break;
            case '8': //POS
                $codigo_perfil = 50;
                break;
        }

        // verifica se existe usuario por apelido
        $this->loadModel('Usuario');

        //verifica se vai buscar pelo perfil ou não
        if (!empty($codigo_perfil)) {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido, $codigo_perfil);
        } else {
            $usuario = $this->Usuario->obterDadosDoUsuarioPorApelido($apelido);
        }

        if (!isset($usuario['email']) || empty($usuario['email'])) {
            $error = 'Usuario não possui email vinculado';
            $this->set(compact('error'));
            return;
        }

        // Declara variaveis
        $codigo_usuario = $usuario['codigo'];
        $token = $params['token'];

        // Busca dados dados do token do usuário
        $usuario_validar_token = $this->UsuarioValidarToken->find()
            ->where(['codigo_usuario' => $codigo_usuario, 'token' => $token, 'codigo_sistema' => $params['codigo_sistema']])->first();

        if (empty($usuario_validar_token)) {

            $error = 'Apelido ou token inválido.';
            $this->set(compact('error'));
            return;
        }

        // Trata formato da data de validação para comparar com a data atual
        $tempo_validacao = $usuario_validar_token['tempo_validacao'];

        $data_token = new DateTime($tempo_validacao);
        $data_token = $data_token->format('Y-m-d H:i:s');
        $data_now = date('Y-m-d H:i:s');

        // Compara data de expiração do token com a data atual
        if ($data_token < $data_now) {

            $data['message'] = "Token expirou!";
            $this->set(compact('data'));
        } else {

            //Defini dados para inserir em UsuarioValidarToken
            $dados = array();

            $dados['validado'] = 1;
            $dados['data_alteracao'] = date('Y-m-d H:i:s');
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;

            $entityUsuarioValidarToken = $this->UsuarioValidarToken->patchEntity($usuario_validar_token, $dados);
            //salva os dados
            if (!$this->UsuarioValidarToken->save($entityUsuarioValidarToken)) {
                $data['message'] = 'Erro ao validar em UsuarioValidarToken';
                $data['error'] = $entityUsuarioValidarToken->errors();
                $this->set(compact('data'));
                return;
            }

            $data = $entityUsuarioValidarToken;

            $this->set(compact('data'));
        }
    }

    private function mascaraEmail($email)
    {
        $fill = 4;   // qtd asterisco
        $user = strstr($email, '@', true);
        $len = strlen($user);
        if ($len > $fill + 2) {
            $fill = $len - 2;
        }
        $email_starred = substr($user, 0, 1) . str_repeat("*", $fill) . substr($user, -1) . strstr($email, '@');

        return $email_starred;
    }

    public function curl($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = [];
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        return $res;
    }

    public function getCallbackLinkedin()
    {
        $dados = $this->request->getData();

        if ($dados) {

            //Definindo os campos necessários para iniciar as requisições de callback
            $client_id = $dados['id'];
            $client_secret = $dados['secret'];
            $redirect_uri = $dados['url'];
            $csrf = random_int(1111111, 9999999);
            $scopes = "r_liteprofile";

            //É necessário o envio do código recebido pelo callback
            if (isset($dados['code'])) {
                $code = $dados['code'];
                $url = "https://www.linkedin.com/oauth/v2/accessToken";
                $params = [
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => $redirect_uri,
                    'code' => $code,
                    'grant_type' => 'authorization_code'
                ];

                //Requisição para pegar o token de acesso
                $at = $this->curl($url, http_build_query($params));
                $at = json_decode($at);
                if (!isset($at->access_token)) {
                    $error =  'Não foi possível gerar o token.';
                    $this->set(compact('error'));
                    return;
                }
                $tk = $at->access_token;


                //O linkedin separou os dados que podem ser pegos em duas APIs

                //Execução da primeira requisição para pegar nome, sobrenome e foto de perfil
                $url2 = "https://api.linkedin.com/v2/me?projection=(firstName,lastName,profilePicture(displayImage~:playableStreams))";

                //Setando configuração da requisição
                $headers[] = "Authorization: Bearer " . $tk;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $res = curl_exec($ch);

                //Retorno do primeiro resultado
                $result['nome'] = json_decode($res);

                $result['picture'] = $result['nome']->profilePicture->{'displayImage~'}->elements[3]->identifiers[0]->identifier;

                //Execução da segunda requisição para pegar email do usuário
                $url2 = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(primary,type,handle~))";

                //Setando configuração da requisição
                $headers[] = "Authorization: Bearer " . $tk;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $res = curl_exec($ch);

                //Retorno da segunda resultado
                $result['email'] = json_decode($res);

                //Setando o retorno do email corretamente de forma limpa
                $result['email'] = $result['email']->elements[0]->{'handle~'}->emailAddress;

                //Setando sobrenome
                $result['sobrenome'] = $result['nome']->lastName->localized->pt_BR;

                //Setando o retorno do nome corretamente de forma limpa
                $result['nome'] = $result['nome']->firstName->localized->pt_BR;

                //Verificação se já um usuário para este email
                $verify  = $this->verificaEmailCadastrado($result['email']);

                //Se já existe usuário:
                if ($verify) {

                    //Desencriptando senha para fazer login
                    $encriptacao = new Encriptacao();
                    $verify->senha = $encriptacao->desencriptar($verify->senha);

                    //Setando campos que devem ser enviados
                    $result['login'] = $verify->apelido;
                    $result['senha'] = $verify->senha;

                    //Efetuando login
                    $this->loginSocial($result['login'], $result['senha']);
                    return;

                    //Se não há usuário cadastrado:
                } else {
                    //Flag para dizer que não há usuário com esse email
                    $result['no_user'] = true;

                    $dados = $result;

                    // Tratamento de download da imagem; validacao para ver se há mesmo um link
                    if ($dados['picture']) {
                        $img = file_get_contents($dados['picture']);
                        $dados['picture'] = 'data:image/jpg;base64,' . base64_encode($img);
                    }

                    //Setando resultado
                    $this->set(compact('dados'));
                    return;
                }
            } else {
                $error =  'Não é possível efetuar login social sem o código.';
                $this->set(compact('error'));
                return;
            }
        } else {
            $error =  'É necessário link de redirecionamento.';
            $this->set(compact('error'));
            return;
        }
        $error =  'Erro ao fazer login.';
        $this->set(compact('error'));
        return;
    }

    public function getLinkCallbackGoogle()
    {
        //Carregando arquivo de client


        //Definindo os campos necessários para iniciar as requisições de callback
        $clientID = "1065449283729-leorslbe0fpvgctpqdlkhbhb9ntvm09p.apps.googleusercontent.com";
        $clientSecret = "S3Xu31TStGV0Kfe9PQJiNKas";

        // $clientID = "507336140320-l7ulci68tp9urc1affds2p74thhnge12.apps.googleusercontent.com";
        // $clientSecret = "AIzaSyC_Wxo0vv3tmNA_G9CLR1YtaFnZ-0lSaWQ";

        $redirectUri = "https://www.rhhealth.com.br";

        //Iniciando client
        $client = new Google_Client();

        //Setando dados necessários para requisição
        $client->setApplicationName('app-lyn');
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->setScopes(
            array(
                "https://www.googleapis.com/auth/plus.login",
                "https://www.googleapis.com/auth/userinfo.email",
                "https://www.googleapis.com/auth/userinfo.profile",
                "https://www.googleapis.com/auth/plus.me"
            )
        );

        //Criando URL para início do callback
        $url = $client->createAuthUrl();

        //Retornando URL do callback
        //$this->set(compact('url'));

        return $url;
    }

    public function getLinkCallbackFacebook()
    {
        //Carrergando arquivo do cliente

        //Setando autorização para requisição
        $fb = new \Facebook\Facebook([
            'app_id' => '165632944729802',
            'app_secret' => '4c02e08d17bb68f934b2b82c8099d9bc',
            'default_graph_version' => 'v2.10',
        ]);

        //Gerando link do callback
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl('https://example.com/');

        //$this->set(compact('loginUrl'));

        return $loginUrl;
    }

    public function getCallbackGoogle()
    {

        //Recebendo dados, garantindo que receberá o código para callback
        $dados = $this->request->getData();

        //É necessário o envio do código recebido pelo callback
        if ($dados) {

            //Definindo os campos necessários para iniciar as requisições de callback
            $clientID = $dados['id'];
            $clientSecret = $dados['secret'];
            $redirectUri = $dados['url'];

            $dados['code'] = urldecode($dados['code']);

            // debug($dados);exit;
            //Setando client do google
            $client = new Google_Client();

            //Setando client id
            $client->setClientId($clientID);

            //Setando client secret
            $client->setClientSecret($clientSecret);

            //Setando url de redirecionamento
            $client->setRedirectUri($redirectUri);

            //Autenticando com o código fornecido
            $token = $client->authenticate($dados['code']);
            // debug($token);exit;

            //Se houve sucesso ao autenticar:
            if (isset($token['access_token'])) {

                //Requisição para pegar o token de acesso
                $headers = [];
                $headers[] = "Authorization: Bearer " . $token['access_token'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $res = curl_exec($ch);

                //Pegando resultado da requisição e transformando em JSON
                $res = json_decode($res);
                $aux = $res->email;

                //Verificação se já um usuário para este email
                $verify  = $this->verificaEmailCadastrado($res->email);

                //Se já existe usuário:
                if ($verify) {

                    //Desencriptando senha para fazer login
                    $encriptacao = new Encriptacao();
                    $verify->senha = $encriptacao->desencriptar($verify->senha);

                    //Setando campos que devem ser enviados
                    $result['login'] = $verify->apelido;
                    $result['senha'] = $verify->senha;

                    //Efetuando login
                    $this->loginSocial($result['login'], $result['senha']);
                    return;

                    //Se não há usuário cadastrado:
                } else {
                    //Flag para dizer que não há usuário com esse email

                    $dados = $res;

                    // Tratamento de download da imagem; validacao para ver se há mesmo um link
                    if ($dados->picture) {
                        $img = file_get_contents($dados->picture);
                        $dados->picture = 'data:image/jpg;base64,' . base64_encode($img);
                    }

                    $dados->no_user = true;
                    //Setando resultado
                    $this->set(compact('dados'));
                    return;
                }

                //Se não foi possível pegar o token:
            } else {

                //Retornar erro e finalizar função.
                $error = 'Não foi possível pegar o token.';
                $this->set(compact('error'));
            }

            $this->set(compact('aux'));
            return;
        }
        $error = 'Não é possível efetuar login social sem o código.';
        $this->set(compact('error'));
    }

    public function getCallbackFacebook()
    {
        session_start();
        //Recebendo dados, garantindo que receberá o código para callback
        $dados = $this->request->getData();

        debug($dados);
        exit;

        if ($dados) {

            //Setando autorização para requisição
            $fb = new \Facebook\Facebook([
                'app_id' => $dados['id'],
                'app_secret' => $dados['secret'],
                'default_graph_version' => 'v3.2',
            ]);

            //Finalizado, recebendo token
            $response = $fb->get('/me?fields=name,email,picture', $dados['code']);
            $re = $response->getBody();
            $re = json_decode($re);

            //Verificação se já um usuário para este email
            $verify  = $this->verificaEmailCadastrado($re->email);

            if ($verify) {

                //Desencriptando senha para fazer login
                $encriptacao = new Encriptacao();
                $verify->senha = $encriptacao->desencriptar($verify->senha);

                //Setando campos que devem ser enviados
                $result['login'] = $verify->apelido;
                $result['senha'] = $verify->senha;

                //Efetuando login
                $this->loginSocial($result['login'], $result['senha']);
                return;

                //Se não há usuário cadastrado:
            } else {
                //Flag para dizer que não há usuário com esse email
                $dados = $re;
                $dados->picture = $dados->picture->data->url;

                // Tratamento de download da imagem; validacao para ver se há mesmo um link
                if ($dados->picture) {
                    $img = file_get_contents($dados->picture);
                    $dados->picture = 'data:image/jpg;base64,' . base64_encode($img);
                }

                $dados->no_user = true;
                //Setando resultado
                $this->set(compact('dados'));
                return;
            }

            return;
        }

        $error = 'Não é possível efetuar login social sem o código.';
        $this->set(compact('error'));
    }
    //Validando se há um usuário para o email utilizando no determino login social
    public function verificaEmailCadastrado($email)
    {
        $usuario = $this->Usuario->find()->where(['email' => $email, 'codigo_uperfil' => 9])->first();
        return $usuario;
    }

    //Padronização do login com rede social
    public function loginSocial($login, $senha)
    {
        //Iniciando client para efetuação de login
        $http = new Client();

        //Post para login
        $response = $http->post(BASE_URL . '/api/auth', [
            'apelido' => $login,
            'senha' => $senha,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);

        //Retorno de token
        $js = $response->getJson();
        $data = $js['result']['data'];
        $this->set(compact('data'));
        return;
    }

    public function getLinks()
    {
        $urls['google'] = $this->getLinkCallbackGoogle();
        $urls['facebook'] = $this->getLinkCallbackFacebook();
        $urls['linkedin'] = 'https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=770y0x77qbes55&redirect_uri=http://example.com&state=4568526&scope=r_liteprofile%20r_emailaddress';

        $this->set(compact('urls'));
    }

    /**
     * [getUsuarioFornecedor metodo para pegar os usuarios que tem vinculo com o fornecedor que o usuario que esta pesquisando]
     * @param  int    $codigo_usuario [codigo do usuario do sistema]
     * @return [type]                 [description]
     */
    public function getUsuarioFornecedor(int $codigo_usuario)
    {

        $data = array();
        try {

            $this->loadModel('UsuarioMultiFornecedor');

            //variavel auxiliar
            $codigos_fornecedores = array();

            //verifica se tem um fornecedor no usuario
            $usuario_fornecedor_princial = $this->Usuario->find()->select(['codigo_fornecedor'])->where(['codigo' => $codigo_usuario])->first()->toArray();

            //verifica se tem codigo_foencedor_principal
            if (!empty($usuario_fornecedor_princial['codigo_fornecedor'])) {
                $codigos_fornecedores[$usuario_fornecedor_princial['codigo_fornecedor']] = $usuario_fornecedor_princial['codigo_fornecedor'];
            }

            //pega os fornecedores relacioandos ao usuario logado para poder trabalhar somente com eles
            $umf = $this->UsuarioMultiFornecedor->find()
                ->select(['codigo_fornecedor'])
                ->where(['codigo_usuario' => $codigo_usuario])
                ->hydrate(false)
                ->toArray();

            //verifica se tem configuracao
            if (!empty($umf)) {
                //varre os fornecedores
                foreach ($umf as $umf_codigo) {
                    $codigos_fornecedores[$umf_codigo['codigo_fornecedor']] = $umf_codigo['codigo_fornecedor'];
                } //fim foreach

            }

            //verifica se tem valor na variavel auxiliar
            if (!empty($codigos_fornecedores)) {

                //para buscar todos os fornecedores que o usuario pode criar para outro usuario
                $codigos = implode(',', $codigos_fornecedores);

                //pega os dados dos usuarios que estao relacionados aos fornecedores
                // $data = array($this->Usuario->getUsuarioFornecedorPermissoes($codigos));
                $dadosUsuarios = $this->Usuario->getUsuarioFornecedorPermissoes($codigos);

                foreach ($dadosUsuarios as $key => $d) {

                    // debug($d);exit;

                    $dados_usuario = array();
                    $dados_comp = array();

                    $dados_usuario = array(
                        'codigo_usuario' => $d['codigo_usuario'],
                        'nome_usuario' => $d['nome_usuario'],
                        'funcao' => $d['funcao'],
                        'codigo_fornecedor' => $d['codigo_fornecedor'],
                        'codigo_medico' => $d['codigo_medico']
                    );

                    foreach ($d['Fornecedor'] as $keyF => $f) {
                        $dados_comp['Fornecedor'][] = array(
                            'codigo_fornecedor' => $f['codigo_fornecedor'],
                            'nome_fornecedor' => $f['nome_fornecedor']
                        );
                    }

                    foreach ($d['Permissao'] as $keyP => $p) {
                        $dados_comp['Permissao'][] = array(
                            'codigo_permissao' => $p['codigo_permissao'],
                            'descricao_permissoes' => $p['descricao_permissoes']
                        );
                    }

                    //
                    $data[] = array_merge($dados_usuario, $dados_comp);
                } //fim foreach

            }
        } catch (\Exception $e) {

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }

        $this->set(compact('data'));
    } //fim getUsuarioFornecedor

    public function getAllFornecedorByUser($codigo_usuario)
    {
        $data = array();

        //verifica se usuario existe
        $getUsuario = $this->Usuario->getDadosUsuario($codigo_usuario);

        if (empty($getUsuario)) {
            $data['error'] = 'Usuário não encontrado!';
            $this->set(compact('data'));
            return;
        }

        //Verifica se usuario tem fernecedores associados
        $fornecedores = $this->Usuario->getAllFornecedoresUsuario($codigo_usuario);
        //
        if (empty($fornecedores)) {
            $data['error'] = 'Não existe fornecedores relacionados a esse usuário!';
            $this->set(compact('data'));
            return;
        }

        //Verifica se usuario tem permissões
        $usuarioFornecedorPermissoes = $this->Usuario->getFornecedorPermissaoByUsuario($codigo_usuario);

        if (empty($usuarioFornecedorPermissoes)) {
            $data['error'] = 'Não existe permissões relacionadas a esse usuário!';
            $this->set(compact('data'));
            return;
        }

        //Se usuario tiver varios clientes com o mesmo fornecedor
        // remove os fornecedores repetidos para inserir no array
        $getUsuario['Fornecedor'] = array_values(array_unique($fornecedores, SORT_REGULAR));

        //inseri dados das permissões que o usuario tem no sistema
        $getUsuario['Permissao'] = $usuarioFornecedorPermissoes;

        $data[] = $getUsuario;
        $this->set(compact('data'));
    }

    /**
     * [getFornecedorFuncao metodo para pegar os perfils]
     * @return [type] [description]
     */
    public function getFornecedorFuncao()
    {

        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if (empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        //pega os perfils
        $this->loadModel('Uperfis');
        $codigos_perfils = "11,15,16,19,20,21,27";
        $perfis = $this->Uperfis->find()->select(['codigo', 'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])->where(['codigo IN (' . $codigos_perfils . ')'])->hydrate(false)->all()->toArray();

        $data = $perfis;

        $this->set(compact('data'));
    } //fim getFornecedorFuncao

    /**
     * [setUsuarioFornecedor para criar ou alterar o usuario fornecedor]
     */
    public function setUsuarioFornecedor()
    {
        //abrir transacao
        $conn = ConnectionManager::get('default');

        try {

            //abre a transacao
            $conn->begin();

            //pega os dados do token
            $dados_token = $this->getDadosToken();

            //veifica se encontrou os dados do token
            if (empty($dados_token)) {
                $error = 'Não foi possivel encontrar os dados no Token!';
                $this->set(compact('error'));
                return;
            }

            //seta o codigo usuario
            $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;


            //verifica os dados
            if (is_null($codigo_usuario)) {
                throw new Exception("Erro não encontramos o codigo_usuario no token.");
            }

            if (is_null($dados['nome']) || empty($dados['nome'])) {
                throw new Exception("nome é requerido.");
            }

            if (is_null($dados['email']) || empty($dados['email'])) {
                throw new Exception("email é requerido.");
            }

            if (is_null($dados['codigo_funcao']) || empty($dados['codigo_funcao'])) {
                throw new Exception("codigo_funcao é requerido.");
            }


            //verifica qual metodo esta passando a chamada
            if ($this->request->is(['patch', 'post', 'put'])) {

                //pega os dados que veio do post
                $dados = $this->request->getData();

                // debug($dados);exit;

                //verifica se tem um codigo do cliente caso tenha edita os dados
                $codigo = '';
                if (isset($dados['codigo'])) {
                    $codigo = $dados['codigo'];
                    //pega os dados do usuario
                    $usuario = $this->Usuario->get($codigo);
                } //fim codigo


                //variavel com os erros caso existam
                $validacoes = $this->validacao_usuario($dados, $codigo, 2); //modulo agenda
                // debug($validacoes);exit;
                if (!empty($validacoes)) {
                    throw new Exception(json_encode($validacoes['validations']));
                }

                //separa as permissoes e as unidades
                $unidades = $dados['unidades'];
                $permissoes = $dados['permissoes'];

                //seta os dados para gravar na base o novo usuario
                $dados_usuario = array(
                    "nome" => $dados['nome'],
                    "email" => $dados['email'],
                    "codigo_cliente" => null,
                    "codigo_fornecedor" => ((isset($unidades[0]['codigo'])) ? $unidades[0]['codigo'] : null),
                    'ativo' => true,
                    'codigo_uperfil' => $dados['codigo_funcao'],
                    'codigo_medico'  => $dados['codigo_medico'],
                    'admin' => 0,
                    'restringe_base_cnpj' => 0,
                    'codigo_departamento' => 1,
                    'codigo_empresa' => 1,
                    'codigo_funcionario' => null,
                );

                if (empty($usuario)) {
                    //instancia para criptografar a senha
                    $Encriptador = new Encriptacao();

                    $dados_usuario["apelido"] = $dados['login'];
                    $dados_usuario["senha"] = $Encriptador->encriptar($dados['senha']);
                    $dados_usuario['codigo_usuario_inclusao'] = $codigo_usuario;
                    $dados_usuario['data_inclusao'] = date('Y-m-d H:i:s');

                    //para criar um novo usuario
                    $usuario = $this->Usuario->newEntity($dados_usuario);
                } else {
                    $dados_usuario['codigo_usuario_alteracao'] = $codigo_usuario;
                    $dados_usuario['data_alteracao'] = date('Y-m-d H:i:s');

                    $usuario = $this->Usuario->patchEntity($usuario, $dados_usuario);
                }
                // debug($usuario->toArray());exit;
                //seta os dados para atualizacao
                if ($this->Usuario->save($usuario)) {

                    //pega o codigo do usuario
                    $novo_codigo_usuario = isset($usuario->codigo) ? $usuario->codigo : $usuario->id;

                    //verifica se tem unidades relacionadas
                    if (!empty($unidades)) {

                        //insere na tabela de multi_fornecedores
                        $this->loadModel('UsuarioMultiFornecedor');
                        //deleta todos os codigos para inserir os novos
                        $del_multi_fornecedores = $this->UsuarioMultiFornecedor->deleteAll(['codigo_usuario' => $novo_codigo_usuario]);

                        //varre os dados de cliente
                        foreach ($unidades as $fornecedor) {

                            if (empty($fornecedor['codigo'])) {
                                continue;
                            }

                            $dadosMultiFornecedor = array(
                                'codigo_usuario' => $novo_codigo_usuario,
                                'codigo_fornecedor' => $fornecedor['codigo'],
                                'codigo_usuario_inclusao' => $codigo_usuario,
                                'data_inclusao' => date('Y-m-d H:i:s')
                            );

                            //instancia para um novo registro
                            $usuario_multi_fornecedor = $this->UsuarioMultiFornecedor->newEntity($dadosMultiFornecedor);

                            if (!$this->UsuarioMultiFornecedor->save($usuario_multi_fornecedor)) {
                                // debug($usuario_multi_fornecedor->errors());
                                throw new Exception("Erro ao relacionar fornecedores ao usuario!");
                            }
                        } //fim foreach

                    } //fim unidades

                    //verifica se tem unidades relacionadas
                    if (!empty($permissoes)) {

                        //insere na tabela de multi_fornecedores
                        $this->loadModel('UsuarioFornecedorPermissoes');
                        //deleta todos os codigos para inserir os novos
                        $del_forn_permissoes = $this->UsuarioFornecedorPermissoes->deleteAll(['codigo_usuario' => $novo_codigo_usuario]);

                        //varre os dados de cliente
                        foreach ($permissoes as $permissao) {

                            if (empty($permissao['codigo'])) {
                                continue;
                            }

                            $dadosPermissao = array(
                                'codigo_usuario' => $novo_codigo_usuario,
                                'codigo_fornecedor_permissoes' => $permissao['codigo'],
                                'codigo_usuario_inclusao' => $codigo_usuario,
                                'data_inclusao' => date('Y-m-d H:i:s')
                            );

                            //instancia para um novo registro
                            $usuario_permissao = $this->UsuarioFornecedorPermissoes->newEntity($dadosPermissao);

                            if (!$this->UsuarioFornecedorPermissoes->save($usuario_permissao)) {
                                throw new Exception("Erro ao relacionar permissoes ao usuario!");
                            }
                        } //fim foreach

                    } //fim unidades

                    $data = $usuario;
                } else {
                    $error[]  = $usuario->errors();

                    $message[] = $usuario->errors();
                    throw new Exception("Erro ao criar usuario: " . print_r($message, 1));
                }
            } //fim metodo put

            $conn->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {

            //rollback da transacao
            $conn->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    } //fim setUsuarioFornecedor

    public function testeOpa()
    {
        // print phpinfo();
        // exit;

        // $headers = $this->request->getHeaders();

        // if(isset($headers['Authorization'][0])) {

        //     $token = substr($headers['Authorization'][0],7);
        //     $jwt_codificacao = array("typ"=> "JWT","alg"=> "HS256");
        //     $dados = JWT::decode($token, Security::salt(),$jwt_codificacao);

        //     debug($dados);


        // }

        // $arr_fornecedores = array('8375','8603','1114','2870','615','616','2144','1088','995','8186','8387','8742','8743','8744','8745','625','3357','997','630','8162','1101','8131','1129','640','2693','8165','3013','999','646','2182','8496','2156','654','606','2811','8152','975','2760','8260','8633','2958','1036','2894','8386','2886','2886','662','8388','1025','8213','668','670','672','8601','2796','8064','8693','1013','8379','673','2835','2906','676','2133','2130','2839','8104','1028','8572','2981','1105','1055','1092','8608','8593','8504','8203','8619','3151','3311','701','8362','702','702','8597','708','3047','710','8635','8422','1110','8702','8115','8115','1002','2671','8218','716','1115','4388','2170','732','1128','802','8470','8723','918','2705','805','8271','978','8148','8294','2175','817','1038','1038','2166','973','3064','3064','8331','8378','2914','8102','1063','2939','842','1066','843','838','2949','2949','849','850','1070','1070','2746','8277','2984','858','1011','937','911','1122','1010','869','870','2943','8236','2840','8738','6975','2946','2749','879','3087','8333','3050','998','2155','2763','885','887','8219','2934','2866','982','2682');
        // $arr_clientes = array('71551','87774','87775','87776','87777','87778','87779','87780','87781','87782','87784','87785','87787','87789','87792','87794','87796','87797','87798','87799','87800','87802','87804','87807','87808','88847','88849','88851','88853','88866','88878','88879','88908','88911','88925','88927','88962','88963','88965','88966','88967','88968','88969','88971','88984','88987','88991','88992','88995','88996','88997','89000','89002','89008','89011','89018','89020','89033','89036','89048','89051','89055','89057','89062','89063','89069','89072','89079','89080','89082','89095','89096','89097','89099','89100','89101','89108','89109','89110','89125','89129','89130','89190','89191');


        // foreach($arr_clientes as $codigo_cliente) {

        //     foreach($arr_fornecedores as $codigo_fornecedor) {
        //         $query = "insert into clientes_fornecedores (codigo_fornecedor, data_inclusao, codigo_usuario_inclusao, ativo, codigo_cliente) values ({$codigo_fornecedor}, '2020-06-03 10:00:00', 61650, 1, {$codigo_cliente});";

        //         file_put_contents("insert_cliente_fornecedor.sql", $query."\n", FILE_APPEND);
        //         // print $query."<br>";


        //     }

        // }
        //
        // $this->loadModel('ItensPedidosExames');
        // $item = $this->ItensPedidosExames->find()->select(['codigo_medico'])->where(['codigo_exame' => '27', 'codigo_pedidos_exames' => '177410'])->first();
        // debug($item->toArray());exit;

        print "terminou";
        exit;
    }

    /**
     * Description metodo coringa para descriptografar
     * @return type
     */
    public function descriptTeste()
    {

        //pega os dados que veio do post
        $dados = $this->request->getData();

        $encriptacao = new Encriptacao();
        $senha = $encriptacao->desencriptar($dados['senha']);
        // $senha = $encriptacao->encriptar($dados['senha']);

        debug($senha);
        exit;
    }

    public function configuracoesAlerta()
    {
        $this->request->allowMethod(['post']);
        try {

            $data = array();

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
            $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;

            //Inseri o codigo de usuario que esta adicionando/editando os tipos de alertas, ao array de $dados
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;

            //Verifica se o usuario que esta sendo recebendo os tipos de alerta existe na tabela usuario e o edita
            $valida_usuario = $this->verifica_existencia_usuario($dados);

            if (!empty($valida_usuario)) {
                $this->set('error', $valida_usuario);
                return;
            }

            //            $listar_tipos_alertas = $this->verifica_existencia_agrupamento();
            //
            //            foreach ($dados['alertasAgrupamentos'] as $key => $dadosAgrupamento) {
            //
            //                foreach ($listar_tipos_alertas as $agrupamento) {
            //
            //                    if ($agrupamento['codigo'] == $dadosAgrupamento['codigo']) {
            //                        $data[$key]['alertas_tipos'] = $dadosAgrupamento['alertas_tipos'];
            //                    }
            //                }
            //            }

            $verificaTipoDeExame = $this->verifica_existencia_exame($data);

            if ($verificaTipoDeExame['error']) {

                foreach ($verificaTipoDeExame as $codigo) {
                    $error[] = "Codigo exame " . $codigo . " não encontrado!";
                }

                $this->set(compact('error'));
                return;
            }

            //inclui os tipos de alertas
            $inclui = $this->incluirUsuarioAlertaTipo($dados);

            if (count($inclui) > 0) {
                $data['error'] = $inclui;
            } else {
                $data['message'] = "Tipos de alertas de usuário inseridos com sucesso!";
            }

            $this->set('message', $data['message']);
        } catch (\Exception $e) {

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function incluirUsuarioAlertaTipo($dados)
    {

        $data = [];

        $this->loadModel('UsuariosAlertasTipos');
        $usuarios_alertas_tipos = $this->UsuariosAlertasTipos->find()->select(['codigo'])->where(['codigo_usuario' => $dados['codigo']]);

        //Verifica se existe algum registro para esse usuario e remove
        if ($usuarios_alertas_tipos->count() > 0) {
            $codigos = array();

            foreach ($usuarios_alertas_tipos as $codigo) {
                $codigos[] = $codigo['codigo'];
            }

            $this->UsuariosAlertasTipos->deleteAll(['codigo IN' => $codigos]);
        }

        //Adiciona registros para esse usuario
        $data = $this->incluirAlertasTipos($dados);

        return $data;
    }

    public function incluirAlertasTipos($dados)
    {

        foreach ($dados['alertas_tipos'] as $codigo_alerta_tipo) {

            $this->loadModel('UsuariosAlertasTipos');
            $alertas_tipos = $this->UsuariosAlertasTipos->newEntity();

            $alertas_tipos->codigo_usuario = $dados['codigo'];
            $alertas_tipos->codigo_alerta_tipo = $codigo_alerta_tipo;
            $alertas_tipos->codigo_usuario_inclusao = $dados['codigo_usuario_alteracao'];

            if (!$this->UsuariosAlertasTipos->save($alertas_tipos)) {
                $error[] = "Não foi possivel inserir!";
            }
        }

        return $error;
    }

    public function verifica_existencia_exame($exames)
    {
        $this->loadModel('AlertasTipos');

        $dados = array();

        foreach ($exames as $key => $exame) {

            foreach ($exame['alertas_tipos'] as $e) {

                $exame = $this->AlertasTipos->find()->where(['codigo' => $e])->first();

                if (empty($exame)) {
                    $error[] = $e;
                }
            }
        }

        return $error;
    }

    public function verifica_existencia_agrupamento()
    {

        $this->loadModel('AlertasAgrupamento');
        $this->loadModel('AlertasTipos');
        $codigo_existentes = $this->AlertasTipos->find('all', array('group' => 'codigo_alerta_agrupamento', 'fields' => 'codigo_alerta_agrupamento'));

        $codigo_agrupamentos = array();
        foreach ($codigo_existentes as $key => $codigo_existente) {
            $codigo_agrupamentos[] = $this->AlertasAgrupamento->find()->where(['codigo' => $codigo_existente['codigo_alerta_agrupamento']])->first();
        }

        return $codigo_agrupamentos;
    }

    public function verifica_existencia_usuario($dados)
    {
        $this->loadModel('Usuario');
        $usuario = $this->Usuario->find()->where(['codigo' => $dados['codigo']]);

        $error = [];
        $arr = array();

        if ($usuario->count() <= 0) {
            $error[] = "Codigo usuario: " . $dados['codigo'] . " não encontrado na base de dados.";
        } else {

            $arr['alerta_portal'] = $dados['alerta_portal'];
            $arr['alerta_email'] = $dados['alerta_email'];
            $arr['alerta_sms'] = $dados['alerta_sms'];
            $arr['codigo_usuario_alteracao'] = $dados['codigo_usuario_alteracao'];

            $getUsuario = $usuario->first();
            $editUsuario = $this->Usuario->patchEntity($getUsuario, $arr);

            $this->Usuario->save($editUsuario);
        }
        return $error;
    }

    public function getUsuariosAlertasTipos($codigo_usuario)
    {
        $this->request->allowMethod(['get']);

        try {

            $data = array();

            $this->loadModel('UsuariosAlertasTipos');

            $alertasPorAgrupamento = $this->UsuariosAlertasTipos->getAlertasTiposPorAgrupamento();

            foreach ($alertasPorAgrupamento as $key => $alerta) {

                $alertasPorAgrupamentoUsuario = $this->UsuariosAlertasTipos->getAlertasTiposPorAgrupamentoUsuario($codigo_usuario, $alerta);

                if (!empty($alertasPorAgrupamentoUsuario)) {
                    $alertasPorAgrupamento[$key]['selecionado'] = true;
                } else {
                    $alertasPorAgrupamento[$key]['selecionado'] = false;
                }
            }

            $this->set("data", $alertasPorAgrupamento);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    /**
     * [getFotoUsuario description]
     *
     * pega a imagem para exibir pelo codigo do usuario
     *
     * @return [type] [description]
     */
    public function getFotoUsuario($codigo_usuario)
    {
        //pega a imagem no banco de dados de usuarios_dados
        $this->loadModel('UsuariosDados');
        //pesquisa os dados do usuario
        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        if (!empty($usuarios_dados->avatar)) {
            $data = $usuarios_dados->avatar;
            //$this->set(compact('data'));

        } else {
            $data = "https://api.rhhealth.com.br/ithealth/2020/05/19/F6A883A3-20C6-C7FE-3A3E-6D20BF3B1177.png";
            //$this->set(compact('error'));
        }

        $this->set(compact('data'));
    } //fim getFotoUsuario

    /**
     * [putFotoUsuario description]
     *
     * metodo para enviar a foto para o file-server
     *
     * @return [type] [description]
     */
    public function putFotoUsuario()
    {
        $data = '';
        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['put'])) {

            $this->loadModel('UsuariosDados');

            $params = $this->request->getData();

            $codigo_usuario = $params['codigo_usuario'];

            if ($codigo_usuario) {

                //pega os dados do usuario
                $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

                if (empty($usuarios_dados)) {

                    $dados = array(
                        "codigo_usuario" => $codigo_usuario,
                        "data_inclusao" => date("Y-m-d H:i:s"),
                        "codigo_usuario_inclusao" => $codigo_usuario
                    );

                    $dados = $this->UsuariosDados->newEntity($dados);

                    if ($this->UsuariosDados->save($dados)) {
                        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();
                    } else {
                        $error[]  = $dados->errors();
                        $this->set(compact('error'));
                        return;
                    }
                }

                //monta o array para enviar
                $dados = array(
                    'file'   => $params['foto'],
                    'prefix' => 'agendamento',
                    'type'   => 'base64'
                );

                //url de imagem
                $url_imagem = Comum::sendFileToServer($dados);
                //pega o caminho da imagem
                $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                // debug($caminho_image);

                //verifica se subiu corretamente a imagem
                if (!empty($caminho_image)) {

                    //seta o valor para a imagem que esta sendo criada
                    $usuarios_dados->avatar = FILE_SERVER . $caminho_image['path'];

                    //salva os dados
                    if ($this->UsuariosDados->save($usuarios_dados)) {
                        $data = $usuarios_dados->avatar;
                    } else {
                        $error[]  = $usuarios_dados->errors();
                    }
                } else {
                    $error = "Problemas em enviar a imagem para o file-server";
                }
            } else {
                $error = "Informe o código de usuário.";
            }
        } else {
            $error = "Favor enviar o metodo PUT";
        }

        // saída
        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim putFotoUsuario

    /**
     * [getDadosMinhaConta description]
     *
     * pega dados do usuario
     *
     * @return [type] [description]
     */
    public function getDadosMinhaConta($codigo_usuario)
    {
        $usuario = $this->Usuario->obterDadosDoUsuario($codigo_usuario);

        ### UNIDADE(S) E PERFIL ####
        $this->loadModel('UsuarioMultiFornecedor');
        $this->loadModel('Medicos');

        //variavel auxiliar
        $codigos_fornecedores = array();

        //verifica se tem um fornecedor no usuario
        $usuario_fornecedor_princial = $this->Usuario->find()->select(['codigo_fornecedor'])->where(['codigo' => $codigo_usuario])->first()->toArray();

        //verifica se tem codigo_foencedor_principal
        if (!empty($usuario_fornecedor_princial['codigo_fornecedor'])) {
            $codigos_fornecedores[$usuario_fornecedor_princial['codigo_fornecedor']] = $usuario_fornecedor_princial['codigo_fornecedor'];
        }

        //pega os fornecedores relacioandos ao usuario logado para poder trabalhar somente com eles
        $umf = $this->UsuarioMultiFornecedor->find()
            ->select(['codigo_fornecedor'])
            ->where(['codigo_usuario' => $codigo_usuario])
            ->hydrate(false)
            ->toArray();

        //verifica se tem configuracao
        if (!empty($umf)) {
            //varre os fornecedores
            foreach ($umf as $umf_codigo) {
                $codigos_fornecedores[$umf_codigo['codigo_fornecedor']] = $umf_codigo['codigo_fornecedor'];
            } //fim foreach

        }

        //verifica se tem valor na variavel auxiliar
        $unidade = array();
        $tipo = "";
        $medico = "";
        $conselho_profissional = "";

        if (!empty($codigos_fornecedores)) {

            //para buscar todos os fornecedores que o usuario pode criar para outro usuario
            $codigos = implode(',', $codigos_fornecedores);

            //pega os dados dos usuarios que estao relacionados aos fornecedores
            $dadosUsuarios = $this->Usuario->getUsuarioFornecedorPermissoes($codigos, $codigo_usuario);
            // debug($dadosUsuarios);exit;

            foreach ($dadosUsuarios as $key => $d) {

                $unidade = array();
                $medico = "";
                $conselho_profissional = "";

                if (!empty($dadosUsuarios[$codigo_usuario]['codigo_medico'])) {
                    $medico = $this->Medicos->find()->where(['codigo' => $dadosUsuarios[$codigo_usuario]['codigo_medico']])->first()->toArray();
                    $conselho_profissional = $this->Medicos->getConselhoProfissional($dadosUsuarios[$codigo_usuario]['codigo_medico']);
                }

                $conselho_classe_combo = $this->Medicos->getConselhoProfissional();

                $dados_usuario = array(
                    "codigo_usuario" => $d['codigo_usuario'],
                    "nome" => $d['nome'],
                    "nome_usuario" => $d['nome_usuario'],
                    "email" => $d['email'],
                    "tipo" => $d['funcao'],
                    "codigo_conselho_classe" => $conselho_profissional['codigo'],
                    "conselho_classe_combo" => $conselho_classe_combo,
                    "numero_conselho" => $medico['numero_conselho'],
                    "conselho_uf" => $medico['conselho_uf'],
                );

                foreach ($d['Fornecedor'] as $keyF => $f) {
                    $unidade['unidade'][] = array(
                        'codigo_fornecedor' => $f['codigo_fornecedor'],
                        'nome_fornecedor' => $f['nome_fornecedor']
                    );
                } //fim foreach

            } //fim foreach

        }

        $data[] = array_merge($dados_usuario, $unidade);

        $this->set(compact('data'));
    } //fim getDadosMinhaConta

    /**
     * [putDadosUsuario description]
     *
     * metodo para alterar dados do usuário
     *
     * @return [type] [description]
     */
    public function putDadosUsuario()
    {
        $data = '';

        //verifica qual metodo esta passando a chamada
        if ($this->request->is(['put'])) {

            $params = $this->request->getData();
            $codigo_usuario = $params['codigo_usuario'];

            if (!empty($params['codigo_usuario'])) {

                $usuario = $this->Usuario->find()->where(['codigo' => $codigo_usuario])->first();

                $dados = array(
                    "nome" => $params['nome'],
                    "email" => $params['email'],
                    "codigo_usuario_alteracao" => $params['codigo_usuario'],
                    "data_alteracao" => date("Y-m-d H:i:s")
                );

                //seta os dados para atualizacao
                $usuario = $this->Usuario->patchEntity($usuario, $dados);

                try {

                    //ATUALIZA usuario
                    if ($this->Usuario->save($usuario)) {

                        if (!empty($usuario['codigo_medico'])) { //se for medico

                            $this->loadModel('Medicos');
                            $medico = $this->Medicos->find()->where(['codigo' => $usuario['codigo_medico']])->first();

                            if ($medico) {

                                $dados = array(
                                    "numero_conselho" => $params['numero_conselho'],
                                    "conselho_uf" => $params['conselho_uf'],
                                    "codigo_conselho_profissional" => $params['codigo_conselho_classe'],
                                    "codigo_usuario_alteracao" => $params['codigo_usuario'],
                                    "data_alteracao" => date("Y-m-d H:i:s")
                                );

                                //seta os dados para atualizacao
                                $medico = $this->Medicos->patchEntity($medico, $dados);

                                //ATUALIZA medico
                                if ($this->Medicos->save($medico)) {
                                    $data = "Registro atualizado com sucesso!";
                                } else {
                                    $error[] = $dados->errors();
                                    //$error = "Não foi possível alterar dados do médico";
                                }
                            }
                        }

                        $data = "Registro atualizado com sucesso!";
                    } else {
                        $error[]  = $dados->errors();
                    }
                } catch (\Exception $e) {

                    $error[] = $e->getMessage();
                    $this->set(compact('error'));
                    return;
                }
            } else {

                $error = "Código do usuário logado é obrigatório.";
            }
        } else {
            $error = "Favor enviar o metodo PUT";
        }

        // saída
        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim putDadosUsuario

    /**
     * [setDisclamer metod post para gravar o usuario, data/hora e o disclamer que aceitou]
     */
    public function setDisclamerAceite()
    {
        //verifica se é post
        if ($this->request->is(['post', 'put'])) {

            //pega o token do usuario para saber qual é o codigo_usuario
            //pega os dados do token
            $dados_token = $this->getDadosToken();

            //veifica se encontrou os dados do token
            if (empty($dados_token)) {
                $error = 'Não foi possivel encontrar os dados no Token!';
                $this->set(compact('error'));
                return;
            }

            //seta o codigo usuario
            $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : null;

            if (empty($codigo_usuario)) {
                $error = 'Pedido Exame - Código de usuario não encontrado!';
                $this->set(compact('error'));
                return;
            }

            $codigo_lyn_msg = $this->request->getData('codigo_disclamer');

            // verifica se uma foto foi inforamada
            if (empty($codigo_lyn_msg)) {
                $error[] = "Código disclamer não informado, favor informar!";
                $this->set(compact('error'));
                return null;
            }

            try {
                $this->loadModel('LynMsgAceite');
                $lyn_msg_aceite = array(
                    'codigo_usuario' => $codigo_usuario,
                    'codigo_lyn_msg' => $codigo_lyn_msg,
                    'data_inclusao' => date('Y-m-d H:i:s')
                );

                $dados = $this->LynMsgAceite->newEntity($lyn_msg_aceite);
                if ($this->LynMsgAceite->save($dados)) {
                    $data['mensagem'] = "Registro atualizado com sucesso!";
                } else {
                    $error[] = "Erro ao inserir aceite do disclamer!";
                }
            } catch (Exception $e) {
                $error[] = "Não foi possível atualizar registro.";
            }
        } //fim post/put
        else {
            $error[] = "Favor passar o metodo corretamente!";
        }

        // retorno
        if (!empty($data)) {
            $this->set(compact('data'));
        } else {
            $this->set(compact('error'));
        }
    } //fim setDisclamer

    /**
     * [setQrCodeLeitura metodo para receber um post com os dados codigo_usuario,codigo_resultado_covid para gravar a data e hora da leitura do qr_code
     *  e retornar os dados do passaporte]
     */
    public function setQrCodeLeitura()
    {

        // seta para o retorno
        $data = array();
        $error = array();

        //verifica se é post
        if ($this->request->is('post')) {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            //validacao dos campos
            if (!isset($dados['codigo_usuario'])) {
                $error[] = "Faltando informação do usuario.";
            }

            if (!isset($dados['dado_qr_code'])) {
                $error[] = "Faltando dados do qr code.";
            }
            //verifica se tem algum erro
            if (empty($error)) {

                $codigo_resultado_covid = $dados['dado_qr_code'];

                //grava a requisição de leitura do qr_code
                $this->loadModel('QrCodeLeitura');
                $this->QrCodeLeitura->setQrCodeLeitura($dados);

                $this->loadModel('ResultadoCovid');
                $dados_response = $this->ResultadoCovid->getDadosResultadoCovid($codigo_resultado_covid);

                //verifica se tenho os dados para responder
                if (!empty($dados_response)) {
                    $data = $dados_response->toArray();
                } else {
                    $error = 'Qr Code expirado!';
                }
            } //fim error


        } //fim post
        else {

            //erro de metodo
            $error = 'Erro do envio do verbo';
        } //fim else post


        //log
        $entrada = json_encode((!empty($this->request->getData()) ? $this->request->getData() : 'ERRO getData'));
        $log_type = "MOBILE_API_POST_QR_CODE_LEITURA";

        if (!empty($data)) {

            //componente para log da api
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
            $retorno = $data;

            $this->set(compact('data'));
        } else {
            //componente para log da api
            $log_status = '0';
            $ret_mensagem = 'ERROR';
            $retorno = $error;

            $this->set(compact('error'));
        }

        //componente para log da api
        $this->log_api($entrada, $retorno, $log_status, $ret_mensagem, $log_type);
    } //fim setQrCodeLeitura

    /**
     * Relaciona codigo usuário com o tipo de função de Gestão de risco
     *
     * Colaborador: 1
     * Operador: 2
     * Tec de segurança EHS: 3
     * Gestor de Operação: 4
     *
     */
    public function postPutUsuarioFuncao()
    {
        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario = $this->getAuthUser();

            $usuario = $this->Usuario->find()->where(['codigo' => $dados['codigo_usuario']])->first();

            if (empty($usuario)) {
                $error = 'Código do Usuario não encontrado';
                $this->set(compact('error'));
                return;
            }

            $this->loadModel('UsuarioFuncao');

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de UsuarioFuncao é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getUsuarioFuncao = $this->UsuarioFuncao->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getUsuarioFuncao)) {
                    $error = 'Não foi encontrado UsuarioFuncao para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityUsuarioFuncao = $this->UsuarioFuncao->patchEntity($getUsuarioFuncao, $dados);
            } else {

                $dados['ativo'] = 1;
                $dados['codigo_usuario_inclusao'] = $codigo_usuario;

                $entityUsuarioFuncao = $this->UsuarioFuncao->newEntity($dados);
            }

            //salva os dados
            if (!$this->UsuarioFuncao->save($entityUsuarioFuncao)) {
                $data['message'] = 'Erro ao inserir em UsuarioFuncao';
                $data['error'] = $entityUsuarioFuncao->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityUsuarioFuncao;

            // Salvar dados
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

    public function getFuncaoTipo()
    {
        $this->loadModel('FuncaoTipo');

        $data = $this->FuncaoTipo->find()
            ->select(['codigo', 'descricao']);

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

    public function usuarioGestor($codigo_usuario)
    {
        $this->request->allowMethod(['patch', 'put']); // aceita apenas PATC

        //Abre a transação
        $this->connect->begin();

        try {

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $usuario = $this->Usuario->find()->where(['codigo' => $codigo_usuario])->first();

            $usuario['usuario_alteracao'] = $this->getAuthUser();
            $usuario['data_alteracao'] = date('Y-m-d H:i:s');

            $usuarioEntity = $this->Usuario->patchEntity($usuario, $dados);

            //salva os dados
            if (!$this->Usuario->save($usuarioEntity)) {
                $data['message'] = 'Erro ao inserir em Usuario';
                $data['error'] = $usuarioEntity->errors();
                $this->set(compact('data'));
                return;
            }

            $data = array(
                "codigo" => $usuarioEntity['codigo'],
                "nome" => $usuarioEntity['nome'],
                "apelido" => $usuarioEntity['apelido'],
                "ativo" => $usuarioEntity['ativo'],
                "codigo_gestor" => $usuarioEntity['codigo_gestor'],
                "codigo_usuario_inclusao" => $usuarioEntity['codigo_usuario_inclusao'],
                "codigo_usuario_alteracao" => $usuarioEntity['codigo_usuario_alteracao'],
                "data_inclusao" => $usuarioEntity['data_inclusao'],
                "data_alteracao" => $usuarioEntity['data_alteracao'],
            );

            // Salvar dados
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

    public function getUsuarioTecnicoSegurancaoEHS($codigo_cliente)
    {
        $data = array();

        $this->loadModel('UsuarioFuncao');

        $data = $this->UsuarioFuncao->getUsuarioTecnicoEHS($codigo_cliente);

        $this->set(compact('data'));
    }

    public function getUsuarioGestorOperacao($codigo_cliente)
    {
        $data = array();

        $this->loadModel('UsuarioFuncao');

        $data = $this->UsuarioFuncao->getUsuarioGestor($codigo_cliente);

        $this->set(compact('data'));
    }

    public function getUsuarioByCliente($codigo_cliente)
    {
        $data = array();

        $this->loadModel('UsuarioFuncao');

        $data = $this->UsuarioFuncao->getUsuarioCliente($codigo_cliente);

        $this->set(compact('data'));
    }

    public function getUsuarioMeuTime($codigo_usuario)
    {
        $data = array();

        $this->loadModel('UsuarioFuncao');

        $data = $this->UsuarioFuncao->getUsuarioMeuTime($codigo_usuario);

        $this->set(compact('data'));
    }

    ############################# TESTE CRIPT #############################
    public function testeCript()
    {

        $texto = '{
    "id":"imc",
    "valor": {
        "altura": 12,
        "peso": 198
    }
}';

        $encriptacao = new Encriptacao();
        $encript = $encriptacao->encriptar($texto);

        debug($encript);
        exit;
    }

    public function testeDecript()
    {
        $dados = $this->request->getData();
        $texto = $dados['texto'];

        // debug($texto);
        // exit;


        $encriptacao = new Encriptacao();
        $dencript = $encriptacao->desencriptar($texto);

        debug($dencript);
        exit;
    }

    ############################# FIM TESTE CRIPT #############################


    /**
     * Obter Lista de onboarding por sistema e cliente
     *
     * @param integer $codigo_sistema
     * @param integer $codigo_cliente
     * @param boolean $inativos
     * @return array
     */
    private function obterOnboarding(int $codigo_cliente, bool $inativos)
    {

        $data = [];

        // se não existe um código cliente associado, vai retornar Onboarding padrão
        if (empty($codigo_cliente) || $codigo_cliente === 0) {
            $this->loadModel('Onboarding');
            $OnboardingData = $this->Onboarding->obterLista(3, $inativos);
        } else {
            // se existir um codigo cliente associado verifica a existência de imagens associadas ao cliente
            $this->loadModel('OnboardingCliente');
            $OnboardingData = $this->OnboardingCliente->avaliarListaPorCliente(3, $codigo_cliente, $inativos);
        }

        foreach ($OnboardingData->toArray() as $entity) {

            $tmp = [];

            if (isset($entity->codigo)) {

                // tratar endereço da imagem
                // $imagemData = $this->avaliaUrlImagem($entity->imagem);
                $imagemData = $entity->imagem;

                $tmp['codigo'] = $entity->codigo;
                $tmp['titulo'] = $entity->titulo;
                $tmp['descricao'] = $entity->texto;
                $tmp['imagem'] = $imagemData;
                $tmp['ativo'] = $entity->ativo;

                array_push($data, $tmp);
            }
        }

        return $data;
    }

    public function usuarioClientes($codigo_usuario)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        //Busca pelo usuario
        $usuario = $this->Usuario->find()->select(['codigo'])->where(['codigo' => $codigo_usuario])->first();

        if (empty($usuario)) {
            $error = "Usuario não existe!";
            $this->set(compact('error'));
            return;
        }

        //pega os clientes
        $usuario = $this->Usuario->usuarioClientes($codigo_usuario);

        $this->set(compact('usuario'));
    }

    public function getUserClients($userCustomId = null, $clientId = null)
    {
        $this->request->allowMethod(['GET']);

        $userId = ($userCustomId) ? (int) $userCustomId : $this->getAuthenticatedUser();
        $user = $this->Usuario->find()->where(['codigo' => (int) $userId])->first();

        $accessesReleasedUserData = [];
        $accessesReleasedUser = ($clientId) ? [$clientId] : [];

        if (!is_null($user->codigo_cliente) && is_null($clientId)) {
            array_push($accessesReleasedUser, (int) $user->codigo_cliente);
        }

        if (!$clientId) {
            $this->UsuarioUnidades = TableRegistry::get('UsuarioUnidades');

            /*
                Coletar todas as unidades em que o usuário tem acesso, se não vier nenhum dado
                o usuário terá acesso a todas as unidades cadastradas no multi-cliente
            */
            $accessesReleasedUserData = $this->UsuarioUnidades->find()
                ->where([
                    'codigo_usuario' => $userId,
                ])
                ->all()
                ->toArray();

            foreach ($accessesReleasedUserData as $value) {
                array_push($accessesReleasedUser, (int) $value['codigo_cliente']);
            }
        }

        $economicGroups = $this->GruposEconomicos->getEconomicGroups($userId, $accessesReleasedUser, $user->codigo_cliente);

        $data = $this->GruposEconomicosClientes->getClients($economicGroups, $userId, $clientId);

        $this->set(compact('data'));
    }

    public function getEmployeesUserClients()
    {
        $this->request->allowMethod(['GET']); //aceita apenas GET

        $queryParams = $this->request->getQueryParams(); //pega os parametros da requisição

        $userId = $this->getAuthenticatedUser(); //pega o usuario logado
        $user = $this->Usuario->find()->where(['codigo' => (int) $userId])->first(); //pega o usuario logado

        $this->UsuarioUnidades = TableRegistry::get('UsuarioUnidades'); //carrega a tabela de unidades do usuario

        $data = $this->Usuario->getEmployeesUserClients( //pega os funcionarios do usuario logado
            $userId,
            (isset($queryParams['interno'])) ? (int) $queryParams['interno'] : null,
            $user->codigo_cliente,
            (isset($queryParams['permissao_usuario'])) ? (int) $queryParams['permissao_usuario'] : null,
        );

        $this->set(compact('data')); //retorna os funcionarios
    }

    public function obterLocalidade($codigoUsuario = null)
    {
        try {

            $this->request->allowMethod(['GET']); //aceita apenas GET

            $userId = ($codigoUsuario) ? (int) $codigoUsuario : $this->getAuthenticatedUser();

            $data = $this->Usuario->obterLocalidade($userId);

            $dadosResponse = [
                'status' => 200,
                'result' => [
                    'data' => $data,
                ]
            ];
        } catch (BadRequestException $e) {

            $dadosResponse = [
                "status" => 400,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_ds/conciliacao_duplicatas' => $e->getMessage()
                    ]
                ]
            ];
        } catch (\Exception $e) {
            $dadosResponse = [
                "status" => 500,
                "result" => [
                    "data"      => 'Erro',
                    "message" => [
                        'cliente_ds/conciliacao_duplicatas' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]
                ]
            ];
        } finally {

            return $this->response->withStatus($dadosResponse['status'])
                ->withType('application/json')
                ->withStringBody(json_encode($dadosResponse));
        }
    }
}
