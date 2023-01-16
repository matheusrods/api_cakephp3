<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\UsuariosMedicamentosController;
use App\Test\TestCase\Model\Table\MedicamentosTableTest;
use Cake\Controller\ComponentRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\UsuariosMedicamentosController Test Case
 *
 * @uses \App\Controller\Api\UsuariosMedicamentosController
 */
class UsuariosMedicamentosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuariosMedicamentos'
    ];

    protected function criaBancoUsuariosMedicamentosStatus()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'usuarios_medicamentos_status\', \'U\') IS NULL
                create table usuarios_medicamentos_status
            (
                codigo                     int identity,
                codigo_usuario_medicamento int,
                data_hora_uso              datetime not null,
                codigo_usuario_inclusao    int,
                data_inclusao              datetime
            )
        ');

//        $this->insereDadosUsuariosMedicamentosStatus();
    }

    protected function insereDadosUsuariosMedicamentosStatus()
    {
        $connection = ConnectionManager::get('test');
        $time = new Time('2020-11-19 00:00');
        $connection->execute('
                    insert into usuarios_dados values (?, ?, ?, ?, ?)', [
            1, 1, $time, 1, $time
        ]);
    }

    protected function excluiBancoUsuariosMedicamentosStatus()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'usuarios_medicamentos_status\', \'U\') IS NOT NULL
        DROP TABLE usuarios_medicamentos_status;');
    }

    protected function criaBancoUsuariosDados()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'usuarios_dados\', \'U\') IS NULL
                create table usuarios_dados
            (
                codigo          int identity,
                cpf             varchar(14) not null,
                endereco        varchar(100),
                bairro          varchar(100),
                cidade          varchar(100),
                altura          real,
                peso            real,
                data_inclusao   datetime    not null,
                codigo_usuario  int,
                sexo            char,
                descricao       text,
                cep             varchar(9),
                numero          varchar(11),
                complemento     varchar(100),
                estado          varchar(50),
                data_nascimento date
            )
        ');

        $this->insereDadosUsuariosDados();
    }

    protected function insereDadosUsuariosDados()
    {
        $connection = ConnectionManager::get('test');
        $time = '2020-11-19';
        $connection->execute('
                    insert into usuarios_dados values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            '11111111111', null, null, null, null, null, $time, 1, "M", null, null, null, null, null, null
        ]);
    }

    protected function excluiBancoUsuariosDados()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'usuarios_dados\', \'U\') IS NOT NULL
        DROP TABLE usuarios_dados;');
    }

    protected function criaBancoMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'medicamentos\', \'U\') IS NULL
            create table medicamentos
                (
                    codigo                  int identity
                        constraint pk_medicamentos__codigo
                            primary key,
                    descricao               varchar(255) not null,
                    principio_ativo         varchar(255) not null,
                    codigo_laboratorio      int          not null,
                    codigo_barras           varchar(50),
                    data_inclusao           datetime     not null,
                    codigo_usuario_inclusao int          not null,
                    ativo                   bit          not null,
                    codigo_empresa          int,
                    codigo_apresentacao     int          not null
                        constraint fk__medicamentos__apresentacoes
                            references apresentacoes,
                    posologia               varchar(255)
                )');
        $this->insereDadosMedicamentos();
    }

    protected function insereDadosMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $time = '2020-11-19';
        $connection->execute('
                    insert into medicamentos values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'CEWIN', 'ÁCIDO ASCÓRBICO', 209, null, $time, 1, true, 1, 1, '500 MG'
        ]);
    }

    protected function excluiBancoMedicamentos()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'medicamentos\', \'U\') IS NOT NULL
        DROP TABLE medicamentos;');
    }

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

    /**
     * Testa as rotas sem autenticação
     **/
    public function testIndex()
    {
        $this->get('/api/usuario/medicamento/programacao/adicionar');
        $this->assertResponseNotContains('500');
        $this->get('/api/usuario/medicamento/programacao/alterar/:codigo_usuarios_medicamentos');
        $this->assertResponseNotContains('500');
        $this->get('/api/usuario/medicamento/programacao/listar');
        $this->assertResponseNotContains('500');
    }

    public function testAddProgramacaoMedicamentos()
    {
        $this->criaBancoMedicamentos();

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        // Testa requisição correta
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseOk();

        //Testa requisição com falha ao buscar medicamento e apresentação
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "4",
            "codigo_apresentacao"=> "2",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falha em busca em frequencia dias
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        //Testa falha no frequencia dias intercalados
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "2",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // testa falha em frequencia horario
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falha no uso contínuo
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falha em frequência de uso
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falha em falta de periodo de tratamento
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "2",
            "frequencia_uso"=> "1",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falha ao buscar os dias da semana
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falta de horario inicio uso
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falta de quantidade
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falta da recomendação médica
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        // Testa falta da foto da receita
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
        ];

        $this->post('/api/usuario/medicamento/programacao/adicionar', $data);
        $this->assertResponseContains('404');

        $this->excluiBancoMedicamentos();
    }

    public function testUpdateProgramacaoMedicamentos()
    {
        $this->criaBancoMedicamentos();

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        // Testa requisição correta
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseOk();

        //Testa requisição com falha ao buscar medicamento e apresentação
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "4",
            "codigo_apresentacao"=> "2",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falha em busca em frequencia dias
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        //Testa falha no frequencia dias intercalados
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "2",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // testa falha em frequencia horario
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falha no uso contínuo
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falha em frequência de uso
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falha em falta de periodo de tratamento
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "2",
            "frequencia_uso"=> "1",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falha ao buscar os dias da semana
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falta de horario inicio uso
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falta de quantidade
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "recomendacao_medica"=> "Teste",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falta da recomendação médica
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "foto_receita"=> "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA..."
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa falta da foto da receita
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/1', $data);
        $this->assertResponseContains('404');

        // Testa do codigo incorreto para atualização
        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $data = [
            "codigo_medicamentos"=> "1",
            "codigo_apresentacao"=> "1",
            "frequencia_dias"=> "1",
            "frequencia_dias_intercalados"=> "",
            "frequencia_horarios"=> "2",
            "uso_continuo"=> "1",
            "frequencia_uso"=> "1",
            "periodo_tratamento_inicio"=> "",
            "periodo_tratamento_termino"=> "",
            "dias_da_semana"=> ["seg, ter"],
            "horario_inicio_uso"=> "10:00",
            "quantidade"=> "1",
            "recomendacao_medica"=> "Teste",
        ];

        $this->put('/api/usuario/medicamento/programacao/alterar/22', $data);
        $this->assertResponseContains('de medicamento');

        $this->excluiBancoMedicamentos();
    }

    public function testGetProgramacaoMedicamentos()
    {
        $this->criaBancoMedicamentos();
        $this->criaBancoUsuariosDados();
        $this->criaBancoUsuariosMedicamentosStatus();

        $this->enableCsrfToken();
        $this->enableSecurityToken();
        $token = $this->login();

        $this->configRequest([
            'headers' => [
                'authorization' => 'Bearer '. $token
            ]
        ]);

        $this->get('/api/usuario/medicamento/programacao/listar');
        $this->assertResponseContains('200');

        $this->excluiBancoMedicamentos();
        $this->excluiBancoUsuariosDados();
        $this->excluiBancoUsuariosMedicamentosStatus();
    }
}
