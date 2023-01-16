<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\ExamesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Datasource\ConnectionManager;

/**
 * App\Controller\Api\ExamesController Test Case
 *
 * @uses \App\Controller\Api\ExamesController
 */
class ExamesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Exames',
    ];

    protected function criaBancoUsuariosExames()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'usuario_exames\', \'U\') IS NULL
                create table usuario_exames
            (
                codigo                     int identity,
                codigo_usuario             int,
                codigo_exames              int,
                endereco_clinica           varchar(255),
                data_realizacao            date,
                codigo_usuario_inclusao    int,
                data_inclusao              datetime
            )
        ');
    }

    protected function excluiBancoUsuariosExames()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'usuarios_exames\', \'U\') IS NOT NULL
        DROP TABLE usuarios_exames;');
    }

    protected function criaBancoUsuariosExamesImagens()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'usuario_exames_imagens\', \'U\') IS NULL
                create table usuario_exames_imagens
            (
                codigo                     int identity,
                codigo_usuario_exames      int,
                imagem                     varchar(255),
                codigo_usuario_inclusao    int,
                data_inclusao              datetime
            )
        ');
    }

    protected function excluiBancoUsuariosExamesImagens()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'usuarios_exames_imagens\', \'U\') IS NOT NULL
        DROP TABLE usuarios_exames_imagens;');
    }

    protected function criaBancoClienteFuncionario()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'cliente_funcionario\', \'U\') IS NULL
                create table cliente_funcionario
            (
                codigo                      int identity,
                codigo_cliente              int,
                codigo_funcionario          int,
                codigo_setor                int,
                codigo_cargo                int,
                admissao                    date,
                ativo                       int,
                data_inclusao               datetime,
                codigo_usuario_inclusao     int,
                codigo_empresa              int,
                matricula                   varchar(11),
                data_demissao               date,
                centro_custo                varchar(256),
                data_ultima_aso             date,
                aptidao                     int,
                turno                       int,
                codigo_cliente_matricula    int,
                data_alteracao              datetime,
                codigo_usuario_alteracao    int,
            )
        ');
        $this->insereDadosClienteFuncionario();
    }

    protected function insereDadosClienteFuncionario()
    {
        $connection = ConnectionManager::get('test');
        $time = '2020-01-01';
        $connection->execute('
                    insert into cliente_funcionario values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            1, 1, 1, 1, $time, 1, '2020-01-01 12:00:00', 1, 1, '111111', null, '111111', null, null, null, 1, '2020-01-01 12:00:00', null
        ]);
    }

    protected function excluiBancoClienteFuncionario()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'cliente_funcionario\', \'U\') IS NOT NULL
        DROP TABLE cliente_funcionario;');
    }

    protected function criaBancoPedidosExames()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('
            IF OBJECT_ID(\'pedidos_exames\', \'U\') IS NULL
                create table pedidos_exames
            (
                codigo                          int identity,
                codigo_cliente_funcionario      int,
                codigo_empresa                  int,
                data_inclusao                   datetime,
                codigo_usuario_inclusao         int,
                endereco_parametro_busca        varchar(255),
                codigo_cliente                  int,
                codigo_funcionario              int,
                exame_admissional               int,
                exame_periodico                 int,
                exame_demissional               int,
                exame_retorno                   int,
                exame_mudanca                   int,
                qualidade_vida                  int,
                codigo_status_pedidos_exames    int,
                portador_deficiencia            int,
                pontual                         int,
                data_notificacao                datetime,
                data_solicitacao                date,
                codigo_pedidos_lote             int,
                em_emissao                      int,
                codigo_motivo_cancelamento      int,
                codigo_func_setor_cargo         int,
                codigo_usuario_alteracao        int,
                exame_monitoracao               int,
                data_alteracao                  datetime
            )
        ');
        $this->insereDadosPedidosExames();
    }

    protected function insereDadosPedidosExames()
    {
        $connection = ConnectionManager::get('test');
        $time = '2020-01-01';
        $datetime = '2020-01-01 12:00:00';
        $connection->execute('
                    insert into pedidos_exames values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            1, 1, $datetime, 1, 'local', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, $datetime, $time, 1, 1, 1, 1, 1, 1, $datetime
        ]);
    }

    protected function excluiBancoPedidosExames()
    {
        $connection = ConnectionManager::get('test');
        $connection->execute('IF OBJECT_ID(\'pedidos_exames\', \'U\') IS NOT NULL
        DROP TABLE pedidos_exames;');
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
     * Test getAllExames method
     *
     * @return void
     */
    public function testGetAllExames()
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
        $this->get('api/exame/exames?nome=Lorem');
        fwrite(STDERR, print_r('.', true));
                
        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('.'.PHP_EOL, true));
        
        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao buscar um exame pelo nome'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }
        
        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testGetAllExamesInvalido()
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
        $this->get('api/exame/exames?nome=Teste');
        fwrite(STDERR, print_r('.', true));
        
        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 404) {
            fwrite(STDERR, print_r('Resultado: Nenhum resultado encontrado'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
        }

        // Efetuando teste
        $this->assertResponseOk();
    }

    public function testSalvarExame()
    {
        $this->criaBancoUsuariosExames();
        
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
        $exame = [
            'codigo_exame' => 1,
            'local' => 'teste de local',
            'data' => '13/11/2019',
            'codigo_usuario' => '1'
        ];
        
        fwrite(STDERR, print_r('.', true));
        
        // Efetuando requisição, executando API
        $this->post('api/exame/salvar/', $exame);

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao adicionar um exame de um usuário'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
            $this->assertResponseOk();
        }
    }

    public function testSalvarExameComImagem()
    {
        $this->criaBancoUsuariosExames();
        $this->criaBancoUsuariosExamesImagens();
        
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
        $exame = [
            'codigo_exame' => 1,
            'local' => 'teste de local',
            'data' => '13/11/2019',
            'codigo_usuario' => '1',
            'imagem_exame' => [
                0 => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAATYAAACjCAMAAAA3vsLfAAAApVBMVEX///+yIjQ8O26vDCbXnaK2MECxHjGwEinQh466P02wFSvJdX3CYWqwG
                        S06OW02NWtua4wzMmkuPXK5IC4dG18tLGYpKGQmJGJLSngiIGHp6e5AP3HGxtLw8PMjImExL2jZ2eGvr8BfXoV/fpyZma9TUn3Pz9l2dZW5ucgTEFsYFl2goLWRkKloZ4
                        uIiKJcW4MAAFZMTHiyssILCFm2CB+9MT3CQUsHJ+aUAAAJwElEQVR4nO1da3OrOBLtZWb2vZKVa4F5g40xMRjHyez+/5+2AoQtya4pp7e2XOLqVF3nusP5oFNCHHW3CHi
                        vw9uPP9kKcLJh4GRDQZGNPBgZeRh8pAGCvQTZCC3zuyHSw4HeBfOS3smBYi9BNrqC4E4N3jT8TqEAVndqoNjWy0Z48NXAaR2oU4b6wUeafgS+KlIerE/QfAVcEQnLtl02
                        sqmjAiCNopLdxn2IoghAfCi3GivTKAUoomJz1Q3Ntl02j7AEBlS+MjXYOR1i6VadRH43XlgzdbYh2dbL5nnvw8gP6rjFjFkPY1zrC5l/ELHkXYsh2QuQLUwhhVJfwel2G
                        PhWHzgvxYVpqMuGY9svG9nC7viZyvkib0DeRHEczU9DGfTT5riD7fRNGjMk++33X2yFlI32m9wLdvtxPEQu96zyCfGraZ2XQbLvAy/f9OMkInFM8Gwv/9VazDfp6EGlEe
                        WJnBvDyk3k05Ek07whdP4Qv+97hmd74+JnKTwDhJAcdkzfFxHCdpATM+jXtW/GnmUvSzYiLOkOkqNqZ72Y8GMCO2FnlWjMuBj3mrMYw16YbKyV8VpRI65lsFX87CqdYqm
                        y1XqavTTZPL4d5ahCdWqE1RBLt6rFoJPJrRnFsJcmm5evRLRZGyNsRHClj5u8D/x3/c57mr002XgCYhqZflZ4e0gMP3sQW04wckNPsxcjm7SuDHbrTbqb1JBPP7pLN+td
                        Kjeis59NmiBrEo5jL0U2upWOdC8eeXw1fblcpp8r8WzM99LPyg0TWYltgb8iOPZCZCNxEdymCJmd/uzylWBQxFKq6weGvQTZKOUdeIyqCxWhNCyKkGopcEqZBx3XLsSyr
                        ZdtczidC2i3p9VtkGS7Om0BRGyrBEWsheJ8OmxuYmDZ4avH/j9gmgWl/KY8F8mlmGLF5TZwepAXlsrMwrLf/v2brZA3qT/a1JqqPp5ko02tMvU2Y2zw/OlWS0ki2W8//m
                        wrrmnKwfLv9KId8YeBGxWpfCdiiWnMUGz705SeD1EFdaCNkfXQNNDrO8mghioCPf+NZNsvG+uSzL9EcgMulyhe92HW11wLxtHFD5NOcRZotv2ykb0/WPzp8Uilyfc24rb
                        L5SNzDm7E1p340sduZ5OLYS9AtmnLI9ehLN0TM0j2aXZ3ZV7KFgYUewmyXcFyvoE2y3O1EJrnWQsbnqsLFc3z9yh6z3OKYS9MNlq1bSIW+LZUy+5lKxbypG2rm0T0ULYt
                        QNuWilt7mr002chG2tSDMjfY4d66ElbO1pV8n7002TwSDontgml3FGODHK2WsyX+WD8++QTDXppsXj70aRSmnx0G3hl+Nh5kiw0/+yx7kO33v9iKe9mCKD0noFeZiAfJO
                        Y10P5tX0PdQ5Ti2APurtbjJJou+5JL4NOwq3c9WXUj9RC5O1zrymfO9Wjf+DttbQOJoHI6sx8WDDjKBfW0jHavrfNoGsFY+EQcB5gr799nLkI2eIDPvoodtpF4Gp/smVA
                        zbetkI948lnDNfbyP1hzZSX28j9bMzlEdfb0LFsW2XjWzquk4hqgutjbSoa4C6LtQ20raoI0jF5VoTKo5tu2xDBWX88qlOolzaVDWPRvhkcqONOtuQbOtl88jYRrrTUrE
                        eGxONXLOuJBsTjXotHsm2XzYvjMT/jTZSsh8u2Ot+dpwwkelnUWz7ZRNjrLI61ZO2eZme96lxvMVP66Az1ECy7ZeNdltOskqOR26YWMkoZdKQkVgqVIWEbzu9Topj2y+b
                        N1Z8pU3lzdxGev0QF0gPNlpXaXLpTiZtUewlyKaCm21E3ljivLeuPLlvI3qevSjZ8uy4guYjVPNBxA8/GlgdM83PBuEHwEcYUAx7wNt//mYr7ppQh/MWIP5ph6oKGdT87
                        HCEYzicoR2qepI9yWZ/efkKf7Kpn6oJI+FkU7XjQWwyuVHMMOxJtldnG9F40E05tpEe9eDxURPq1yDGl2HMnmUvTrYGikdtpAUY+Qy6gjQ1j+Q+zV6MbLKkydP2uDXUEA
                        pt3yvgRhNqkee10YT6NHspshHZJLoXdxO9VtHnSrvwWvw0XyGzkH1ASDC36X6XvRDZSFxPSzZRektZJ9s1ptg0YL9+1IT6bfYCZBuOSvVA7w5VBXUd3B2qotDrB6iwbNt
                        lI/EmZjX0JN4rY9xsYgIgfqf0m3r7mPRQM0G4GTMs23rZvETuGbqbCSPb+VCV0n3LOnlhcouh2bbLJtOHwtur+x+SfU7WVds9Te0Ku1C7EMd++/3vtkJrQj0YZffpJQFG
                        2X18ScCjJtRvs9k/rMWcpgwgLSHRtz9sB3UNO30n6SdQpkZLLpJtfwaE9gXNTqkU49pG2oZha7SRkvSU0aLXD1Xh2PbLRrY+8Wh8mYYoTT45izHzM9GCF7F1J/71hBXBs
                        xcgm95GOpfz1DbSTfSgCbVtHzShPstegmxXUJbHUPlMfxsP8yuIc+2gsgiui2KtXfg8e2Gy0b7rG4h2fXfrfSSXrt9F0IhfKaeDVl3fA/R9p5y0epq9NNmuNrVT20ilTV
                        Wtqzeb3ATHXpZsHg2GnGKkWVdhU4f6caOVDbzJ5O4yHHthsnliVw5Qm352OGLWG36WDnzjhYtPs5cmW1DAoQCjWsKgOEBhHKrqoG212/E77MXINr/EqSD5ujTbSMswp3M
                        5SorC65Xvr2Y/+132UmSj07wh8ZC8zmXzwekkGxTyIeE9JYvYtZOB3jpxEexFyEbO8liUulbln59aVmP8DNMzMWMoNv2ntZibUPm6hH2gdaNRzr/S9Itz7VAVD/ZQrrne
                        hIpjv/34l62QTahN85lC0STqu58OSSPcRNMkWhtp0hSQfjaNWnZHsq1PU9LptaVqzlYeaAHj+AqZTG6qvnMSy7ZeNo+O1qrT/RbfDGPc6GXhcJCjDvVCKI5tv2xTG2lr+
                        Nlp4IafHc6iPWxC/TbbftnEGEtamIeqWlitTDWCqKCloQaSbb9srDoEJCz1lty8iRmLG23gZP8ZEv9Q6ScfcWz7ZZvaSM1d43B0m9wF56sV4NgLkO0VcLL9dLLR18HmXc
                        LqdTi9emeJx/83T+Dg4ODg4ODg4ODg4ODg8Ad4dc+1nYBXd/jbCXh1ws9OONlQcLKh4GRDwcmGgpMNBScbCk42FJxsKMCr3xZkJ+DV76ayE6/OJDg4ODg4ODg4ODg4ODj
                        8xHj1X4yyE/Dqv09mJ1yaEgUnGwpONhScbCg42VBwsqHgZEPByYaCkw0FV15GAX5zQODVmQQHBwcHBwcHBwcHBweHnxi/OiAAvzgg4NKUKDjZUHCyoeBkQ8HJhoKTDQUn
                        GwpONhScbCg42VBwsqHwX1JBcKA89Rm5AAAAAElFTkSuQmCC'
            ]
        ];
        
        fwrite(STDERR, print_r('.', true));
        
        // Efetuando requisição, executando API
        $this->post('api/exame/salvar/', $exame);

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('.'.PHP_EOL, true));

        // Tratando status da resposta
        if ($res->status === 200 && $res->result->data->imagens) {
            fwrite(STDERR, print_r('Resultado: Sucesso ao adicionar um exame de um usuário com imagem'.PHP_EOL, true));
            fwrite(STDERR, print_r(PHP_EOL, true));
            $this->assertResponseOk();
        }
    }

    public function testHistorico()
    {
        $this->excluiBancoClienteFuncionario();
        $this->excluiBancoPedidosExames();

        $this->criaBancoClienteFuncionario();
        $this->criaBancoPedidosExames();

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

        $dados = [
            'codigo_usuario' => 1
        ];

        fwrite(STDERR, print_r('.', true));
        
        // Efetuando requisição, executando API
        $this->get('api/exames/historico/1');

        // Pegando resultado da requisição
        $res = json_decode($this->_response->getBody());

        fwrite(STDERR, print_r('Resultado: Efetuado teste de historico de pedido de exames'.PHP_EOL, true));

        $this->assertResponseOk();
    }
}
