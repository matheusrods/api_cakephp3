<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\UsuarioEnderecoController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\UsuarioEnderecoController Test Case
 *
 * @uses \App\Controller\Api\UsuarioEnderecoController
 */
class UsuarioEnderecoControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioEndereco'
    ];

    protected function login()
    {
        $data = [
            'apelido' => '07329099708',
            'senha' => '1234567'
        ];
        $this->post('/api/auth/', $data);
        $token = json_decode($this->_response->getBody())->result->data->token;
        return $token;
    }

    public function testGetEnderecoValido()
    {

        fwrite(STDERR, print_r('Iniciando teste para pegar um endereço válido', true));

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
        $this->get('api/usuario/get_endereco/1');
        fwrite(STDERR, print_r('.', true));
        
        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao pegar os endereços de um usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }

        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testGetEnderecoInvalido()
    {
        fwrite(STDERR, print_r('Iniciando teste para pegar um endereço inválido', true));

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
        $this->get('api/usuario/get_endereco/547');
        fwrite(STDERR, print_r('.', true));
        
        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));
        
        // Tratando status da resposta
        if ($res->status === 404) {
            fwrite(STDERR, print_r('Resultado: Erro ao pegar endereço de usuário inválido'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }

        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testAdicionarEndereco()
    {
        fwrite(STDERR, print_r('Iniciando teste para adicionar um endereço', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);

        fwrite(STDERR, print_r('.', true));
        
        // Objeto para envio do endereço
        $endereco = [
            'codigo_usuario' => 1,
            'codigo_usuario_endereco_tipo' => 1,
            'numero' => 49,
            'logradouro' => 'Rua Brás Cubas',
            'cep' => '00000000',
            'bairro' => 'Jardim do teste',
            'cidade' => 'Vila Teste',
            'estado_descricao' => 'Rio de Janeiro',
            'codigo_empresa' => 1,
            'complemento' => 'teste de complemento'
        ];

        fwrite(STDERR, print_r('.', true));

        // Efetuando requisição, executando API
        $this->post('api/usuario/endereco/', $endereco);

        fwrite(STDERR, print_r('.', true));

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao adicionar endereço ao usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
        
        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testAlterarEndereco()
    {
        fwrite(STDERR, print_r('Iniciando teste para alterar um endereço válido', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);

        // Objeto para envio do endereço
        $endereco = [
            'codigo_usuario' => 1,
            'codigo_usuario_endereco_tipo' => 1,
            'numero' => 49,
            'logradouro' => 'Rua Brás Cubas',
            'cep' => '99999999',
            'bairro' => 'Jardim do teste',
            'cidade' => 'Vila Teste',
            'estado_descricao' => 'Rio de Janeiro',
            'codigo_empresa' => 1,
            'complemento' => 'teste de complemento'
        ];
        fwrite(STDERR, print_r('.', true));

        $this->put('api/usuario/endereco/1', $endereco);
        fwrite(STDERR, print_r('.', true));

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao alterar endereço do usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
                
        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testAlterarEnderecoInvalido()
    {
        fwrite(STDERR, print_r('Iniciando teste para alterar um endereço inválido', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);

        // Objeto para envio do endereço
        $endereco = [
            'codigo_usuario' => 1,
            'codigo_usuario_endereco_tipo' => 1,
            'numero' => 49,
            'logradouro' => 'Rua Brás Cubas',
            'cep' => '99999999',
            'bairro' => 'Jardim do teste',
            'cidade' => 'Vila Teste',
            'estado_descricao' => 'Rio de Janeiro',
            'codigo_empresa' => 1,
            'complemento' => 'teste de complemento'
        ];
        fwrite(STDERR, print_r('.', true));

        $this->put('api/usuario/endereco/547', $endereco);
        fwrite(STDERR, print_r('.', true));

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 404) {
            fwrite(STDERR, print_r('Resultado: Erro ao alterar endereço inválido do usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
                
        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testDeletarEndereco()
    {
        fwrite(STDERR, print_r('Iniciando teste para deletar um endereço válido', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);
        fwrite(STDERR, print_r('.', true));

        $this->delete('api/usuario/del_endereco/1');
        fwrite(STDERR, print_r('.', true));

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao deletar endereço do usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
                
        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testDeletarEnderecoInvalido()
    {
        fwrite(STDERR, print_r('Iniciando teste para deletar um endereço inválido', true));

        // Configurando segurança para os testes
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $this->login()
            ]
        ]);
        fwrite(STDERR, print_r('.', true));

        $this->delete('api/usuario/del_endereco/1');
        fwrite(STDERR, print_r('.', true));

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Erro ao deletar endereço inválido do usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
                
        // Efetuando teste
        $this->assertResponseOk();
    }

}
