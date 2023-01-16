<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use App\Utils\Encriptacao;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 * 
 * @OA\Schema()
 * @OA\Info(
 *     title="API's da plataforma IT.Health", 
 *     version="1.0-0.0",
 *     @OA\Contact(
 *         email="williansbuonny@gmail.com"
 *     )
 * )
 * 
 * 
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        //verfica se precisa descriptografar o valor passado
        $var_input = $this->request->input('json_decode');
        if(isset($var_input->payload)) {
            $dados = $this->setDecript($var_input->payload);
            $this->request->data = json_decode($dados,true);
        }
        else if(isset($this->request->getData()['payload'])) {
            // debug('aqui');exit;
            $payload = $this->request->getData()['payload'];
            $dados = $this->setDecript($this->request->getData()['payload']);
            $this->request->data = json_decode($dados,true);
            $this->request->data['payload'] = $payload;
        }
        
        $filtro = ['Usuario.ativo' => 1];
        if(isset($this->request->getData()['codigo_perfil'])) {
            $filtro = ['Usuario.ativo' => 1,'Usuario.codigo_uperfil = '. $this->request->getData()['codigo_perfil']];
        }
        else if(isset($var_input->codigo_perfil)) {
            $filtro = ['Usuario.ativo' => 1,'Usuario.codigo_uperfil = '. $var_input->codigo_perfil];
        }

        // debug($this->request->input('json_decode'));
        // debug($this->request->input('apelido'));
        // debug($filtro);
        // exit;

        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'loginAction' => [
                'controller' => 'Usuario',
                'action' => 'login'
            ],
            'authError' => 'Did you really think you are allowed to see that?',
            'authenticate' => [
                'Form' => [
                    'passwordHasher' => [
                        'className' => 'Buonny',
                    ],
                    'scope' => $filtro,
                    'fields' => [
                        'username' => 'apelido',
                        'password' => 'senha'
                    ],
                    'userModel' => 'Usuario',
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'parameter' => 'token',
                    'userModel' => 'Usuario',
                    'scope' => ['Usuario.ativo' => 1],
                    'fields' => [
                        'username' => 'apelido'
                    ],
                    'queryDatasource' => true
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize'
        ]);

    }

    /**
     * [getUsuario metodo para pegar os dados do token]'
     * @return [type] [description]
     */
    public function getDadosToken()
    {
        $headers = $this->request->getHeaders();
        $dados = array();

        if(isset($headers['Authorization'][0])) {

            $token = substr($headers['Authorization'][0],7);
            $jwt_codificacao = array("typ"=> "JWT","alg"=> "HS256");
            $dados = JWT::decode($token, Security::salt(),$jwt_codificacao);
        }

        return $dados;
    }//fim getDadosToken

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Api');
        return null;
    }

    /**
     * [setDecript para descriptografar a chamada]
     * @param [type] $encript [description]
     */
    public function setDecript($encript)
    {
        $encriptacao = new Encriptacao();
        $payload_dencript = $encriptacao->desencriptar($encript);

        return $payload_dencript;
    }
}
