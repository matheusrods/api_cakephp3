<?php
/**
 *
 * @todo Trazer roles
 * @todo gestão dos tokens em banco ex. quando logar novamente invalidar o token anterior
 *
 */

namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Model\Entity\Auth;
use App\Utils\Encriptacao;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Exception\Exception;
use Cake\Utility\Security as Security;
use Firebase\JWT\JWT;
use Cake\Network\Exception\UnauthorizedException;


class AuthController extends ApiController
{
    /**
     * regras de entrada de parametros na api, limpar/sanitizar variáveis
     * Preencha aqui os campos aceitos usados para trafegar na url ou post de um form.
     * criado para uso no metodo Search, mas pode ser reutilizado em outros ou criar outra propriedade
     *
     * @requires https://github.com/Wixel/GUMP
     * @var array
     */
    private $searchAllowedSFields = [
        'apelido' => 'trim|sanitize_string',
        'senha' => 'trim', //todo
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('Auth');
        $this->loadModel('UsuariosDados');
        $this->loadModel('Usuario');
        $this->loadModel('Funcionarios');

        $this->Auth->allow(['index']);
    }

    public function index() {

        $this->request->allowMethod(['post']);

        try {

            $data = array();
            //pega os dados que veio do post
            $dados = $this->request->getData();

            $user = $this->Auth->identify();
            
            if (!$user) {
                //@TODO - Auth gerencia internamente a exception, ver depois de
                // obter o controle e apresentar mensagem

                // $this->response = $this->response->withStatus(404);
                // $this->set(compact('message'));
                throw new UnauthorizedException('Usuário ou senha inválida');
            }
            $codigo_usuario = $user['codigo'];

            //verifica se foi passado o codigo_perfil e tem o codigo_sistema
            //isso identifica que não foi feito pelo login da agenda e sim dos apps (lyn,thermal care, gestão de risco)
            
            if (isset($dados['codigo_sistema'])) {

                $this->Auth->setUser($user);

                $this->loadModel('Sistema');
                $codigo_sistema = $this->Sistema->find()->select(['codigo'])->where(['codigo' => $dados['codigo_sistema']])->first();

                if ($codigo_sistema['codigo'] != 2) {
                    $data['error'] = 'Codigo do sistema inválido!';
                    $this->set(compact('data'));
                    return;
                }

                //Verifica se usuario tem fernecedores associados
                $this->loadModel('Usuario');
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
            }
            else if(isset($dados['codigo_perfil'])) {
                
                //verifica se o codigo_perfil tem o mesmo codigo_uperfil
                if($user['codigo_uperfil'] != $dados['codigo_perfil']) {
                    throw new UnauthorizedException('Codigo perfil não corresponde para este login!');
                }
                $this->Auth->setUser($user);

            }
            else if(!isset($dados['codigo_perfil'])) {

                //seta o codigo do perfil do usuario para passar na validação até todos os apps terem este tipo de login corretamente com o codigo perfil
                // $dados['codigo_perfil'] = 9;
                $dados['codigo_perfil'] = $user['codigo_uperfil'];

                //verifica se o codigo_perfil tem o mesmo codigo_uperfil
                if($user['codigo_uperfil'] != $dados['codigo_perfil']) {
                    throw new UnauthorizedException('Codigo perfil não corresponde para este login!');
                }

                $this->Auth->setUser($user);
            }
            else {
                throw new UnauthorizedException('Erro ao autenticar usuário');
            }

            $message = 'Sucesso Login';
            $this->addHistoricoUser($dados, $user, $message);

            $this->set([
                'data' => [
                    'token' => $token = JWT::encode([
                        'sub' => $user['apelido'],
                        // 'exp' => time() + 604800, // 7 dias
                        'exp' => time() + (604800 * 52), // 365 dias
                        'codigo_usuario' => $user['codigo']
                        // 'role' => $user['codigo']
                    ], Security::getSalt()),
                    'codigo_usuario' => $user['codigo']
                ]
            ]);
            // @TODO - gestão do token
            // $this->debug($token);
        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    public function links()
    {
        $this->request->allowMethod(['post']);

        $this->Usuario->getLinks();
    }

    /**
     * Implementar retorno de dados adicionais para o usuario autenticado, ex codigo_cliente
     *
     * @param [type] $user
     * @return void
     */
	private function getUserExtras($user){

        return $user;
    }

    public function addHistoricoUser($dados, $user, $message = null){

        //seta o codigo_sistema
        if(!isset($dados['codigo_sistema'])) {
            switch ($dados['codigo_perfil']) {
                case '9': // lyn
                    $dados['codigo_sistema'] = 1;
                    break;
                case '42': // thermal care
                    $dados['codigo_sistema'] = 3;
                    break;
                default:
                    $dados['codigo_sistema'] = 1;
                    break;
            }
        }

        //carrega model
        $this->loadModel('UsuariosHistoricos');
        //informacoes
        $dados_incluir = [
            'codigo_usuario' => $user['codigo'],
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'message' => $message,
            'codigo_empresa' => $user['codigo_empresa'],
            'data_logout' => null,
            'fail' => 1,
            'codigo_sistema' => $dados['codigo_sistema'],
            'data_inclusao' => date('Y-m-d H:i:s'),
        ];
        //
        $dados_usuario_historico = $this->UsuariosHistoricos->newEntity($dados_incluir);
        $this->UsuariosHistoricos->save($dados_usuario_historico);
    }

}
