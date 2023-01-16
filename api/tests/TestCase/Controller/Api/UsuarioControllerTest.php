<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\UsuarioController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\UsuarioController Test Case
 *
 * @uses \App\Controller\Api\UsuarioController
 */
class UsuarioControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Usuario',
    ];

    public function testGetLinks()
    {
        fwrite(STDERR, print_r('Iniciando teste para pegar os links de callback', true));

        // Efetuando requisição, executando API
        $this->get('api/auth/login/links');
        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando resposta e vendo o que foi recebido
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('Facebook: ', true));
        if ($res->result->urls->facebook) {
            fwrite(STDERR, print_r('OK'.PHP_EOL, true));
            $this->assertResponseOk();
        } else {
            fwrite(STDERR, print_r('ERRO'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r('Google: ', true));
        if ($res->result->urls->google) {
            fwrite(STDERR, print_r('OK'.PHP_EOL, true));
            $this->assertResponseOk();
        } else {
            fwrite(STDERR, print_r('ERRO'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r('Linkedin: ', true));
        if ($res->result->urls->linkedin) {
            fwrite(STDERR, print_r('OK'.PHP_EOL, true));
            $this->assertResponseOk();
        } else {
            fwrite(STDERR, print_r('ERRO'.PHP_EOL, true));
        }

        if ($res->status == 200) {
            fwrite(STDERR, print_r('Resultado: Obteve os links de callback'.PHP_EOL, true));
            $this->assertResponseOk();
        }
    }

}
