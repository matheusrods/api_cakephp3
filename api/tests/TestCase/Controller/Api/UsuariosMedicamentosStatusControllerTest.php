<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\UsuariosMedicamentosStatusController;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\UsuariosMedicamentosStatusController Test Case
 *
 * @uses \App\Controller\Api\UsuariosMedicamentosStatusController
 */
class UsuariosMedicamentosStatusControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuariosMedicamentosStatus'
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

    protected function criaBancoUsuariosMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'usuarios_medicamentos\', \'U\') IS NULL
            create table usuarios_medicamentos
                (
                    codigo                       int identity,
                    codigo_medicamentos          int,
                    codigo_usuario               int,
                    frequencia_dias              tinyint,
                    frequencia_horarios          tinyint,
                    uso_continuo                 tinyint,
                    dias_da_semana               varchar(50),
                    frequencia_uso               tinyint,
                    horario_inicio_uso           varchar(5),
                    quantidade                   int,
                    recomendacao_medica          text,
                    foto_receita                 varchar(255),
                    frequencia_dias_intercalados tinyint,
                    periodo_tratamento_inicio    date,
                    periodo_tratamento_termino   date,
                    codigo_usuario_inclusao      int,
                    codigo_usuario_alteracao     int,
                    data_alteracao               datetime,
                    data_inclusao                datetime,
                    codigo_apresentacao          int
                )');

        $this->insereDadosUsuariosMedicamentos();

    }

    protected function insereDadosUsuariosMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $time = '2020-11-19';
        $connection->execute('
                    insert into usuarios_medicamentos values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            1, 1, 1, 1, 1, 'seg, ter', 1, '10:00', 2, 'Recomendação', 'data:aszVHDcxx', '1', '', '', 1, 1, $time, $time, 1
        ]);
    }

    protected function excluiBancoUsuariosMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'usuarios_medicamentos\', \'U\') IS NOT NULL
        DROP TABLE usuarios_medicamentos;');
    }

    public function testIndex()
    {
        $this->get('/api/usuario/medicamento/status/adicionar');
        $this->assertResponseError();
    }

    public function testAddMedicamecaoTomada()
    {
        $this->criaBancoUsuariosMedicamentos();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        // Testa uma requisição correta
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
          'codigo_usuario_medicamento' => 1
        ];

        $this->post('/api/usuario/medicamento/status/adicionar', $data);
        $this->assertResponseContains('tomada salva com sucesso.');

        // Testa uma requisição com erro ao encontrar o codigo_usuario_medicamento
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            'codigo_usuario_medicamento' => 200
        ];

        $this->post('/api/usuario/medicamento/status/adicionar', $data);
        $this->assertResponseContains('Usuario com esse medicamento');

        // Testa uma requisição com erro sem enviar o codigo
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->post('/api/usuario/medicamento/status/adicionar');
        $this->assertResponseContains('Este campo');

        $this->excluiBancoUsuariosMedicamentos();
    }
}
