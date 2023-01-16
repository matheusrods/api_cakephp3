<?php

namespace App\Test\TestCase\Controller\Api;

use ADmad\JwtAuth\Auth\JwtAuthenticate;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Http\Client;


/**
 * App\Controller\Api\MedicamentosController Test Case
 *
 * @uses \App\Controller\Api\MedicamentosController
 */
class MedicamentosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Medicamentos',
        'app.Funcionarios'
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

    /**
     * Test index method
     *
     * @return void
     *
     * @throws \PHPUnit\Exception
     */
    public function testIndex()
    {
        // Teste de acesso da api sem autenticação
        $this->get('/api/medicamentos/busca');
        $this->assertResponseNotContains('500');

        $this->get('/api/medicamentos/busca/apresentacao');
        $this->assertResponseNotContains('500');
    }

    /**
     * Test getMedicamentos method
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testGetMedicamentos()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);
        $this->get('/api/medicamentos/busca');
        $this->assertResponseOk();
    }

    public function testGetMedicamentosResponse()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();
        $time = new Time('2020-11-03');

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/medicamentos/busca');

        $expected = [
            [
                'codigo_apresentacao' => 1,
                'descricao' => 'CEWIN',
                'posologia' => '1 G',
                'medicamento' => 'CEWIN 1 G',
                'codigo_medicamento' => 1
            ],
            [
                'codigo_apresentacao' => 1,
                'descricao' => 'CEWIN',
                'posologia' => '2 G',
                'medicamento' => 'CEWIN 2 G',
                'codigo_medicamento' => 2
            ],
        ];
        $response = json_decode($this->_response->getBody(), true)['result']['data'];
        $this->assertEquals($expected, $response);
    }


    public function testGetMedicamentosResponseParametrosCorretos()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();
        $time = new Time('2020-11-03');

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/medicamentos/busca?medicamento=CEWIN');

        $expected = [
            [
                'codigo_apresentacao' => 1,
                'descricao' => 'CEWIN',
                'posologia' => '1 G',
                'medicamento' => 'CEWIN 1 G',
                'codigo_medicamento' => 1
            ],
            [
                'codigo_apresentacao' => 1,
                'descricao' => 'CEWIN',
                'posologia' => '2 G',
                'medicamento' => 'CEWIN 2 G',
                'codigo_medicamento' => 2
            ],
        ];
        $response = json_decode($this->_response->getBody(), true)['result']['data'];
        $this->assertEquals($expected, $response);
    }

    public function testGetMedicamentosResponseParametrosIncorretos()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/medicamentos/busca?medicamento=teste');

        $this->assertResponseContains('404');
    }

    /**
     * Test getMedicamentosApresentacao method
     *
     * @return void
     */
    public function testGetMedicamentosApresentacao()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        // Pega a apresentação correta
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/medicamentos/busca/apresentacao/?descricao=CEWIN&posologia=2 G');
        $this->assertResponseContains('COMPRIMIDO');

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        // Verifica conteúdo da descrição
        $this->get('/api/medicamentos/busca/apresentacao/?descricao=&posologia=2 G');
        $this->assertResponseContains('pode estar vazia.');

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        // Verifica falha ao encontrar apresentação
        $this->get('/api/medicamentos/busca/apresentacao/?descricao=teste&posologia=2 G');
        $this->assertResponseContains('encontrar');

    }

    public function testGetFrequenciaUso()
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/medicamentos/frequencia_uso');

        $this->assertResponseContains('hora');
    }


}
