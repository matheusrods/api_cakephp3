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
use Cake\Auth\DefaultPasswordHasher;
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

        $this->Auth->allow(['index']);
    }

    public function index() {

        $this->request->allowMethod(['post']);
        
        $user = $this->Auth->identify();

        if (!$user) {
            //@TODO - Auth gerencia internamente a exception, ver depois de 
            // obter o controle e apresentar mensagem
            
            // $this->response = $this->response->withStatus(404);
            // $message[] = 'Usuário ou senha inválidos';
            // $this->set(compact('message'));
    
            throw new UnauthorizedException('Usuário ou senha inválida');
        }

        // @TODO - retorno de codigo_cliente, codigo_empresa...etc
        $user = $this->getUserExtras($user);

        $this->Auth->setUser($user);

        $this->set([
            'data' => [
                'token' => $token = JWT::encode([
                    'sub' => $user['apelido'],
                    'exp' => time() + 604800,
                    // 'role' => $user['codigo']
                ], Security::salt()),
                'codigo_usuario' => $user['codigo']
            ]
        ]);
        // @TODO - gestão do token
        // $this->debug($token);
       
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

}
