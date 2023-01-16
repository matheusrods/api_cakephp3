<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\UsuarioEnderecoTipoController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\UsuarioEnderecoTipoController Test Case
 *
 * @uses \App\Controller\Api\UsuarioEnderecoTipoController
 */
class UsuarioEnderecoTipoControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioEnderecoTipo'
    ];

    protected function login(){
        $data = [
            'apelido' => '07329099708',
            'senha' => '1234567'
        ];
        $this->post('/api/auth/', $data);
        $token = json_decode($this->_response->getBody())->result->data->token;
        return $token;
    }

    public function testGetTipoEndereco()
    {
        fwrite(STDERR, print_r('Iniciando teste para pegar os tipos de endereço', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);

        fwrite(STDERR, print_r('.', true));

        // Efetuando requisição, executando API
        $this->get('api/endereco/tipo');
        fwrite(STDERR, print_r('.', true));
        
        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao pegar tipos de endereço de usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }

        // Efetuando teste
        $this->assertResponseOk();
    }
}
