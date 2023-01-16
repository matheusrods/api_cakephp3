<?php

use App\Controller\Api\QuestionariosController;
use App\Model\Entity\Questoes;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->fallbacks(DashedRoute::class);

    // $routes->extensions(['json', 'xml']);
    $routes->setExtensions(['json', 'xml']);

    $routes->connect(
        'auth',
        ['controller' => 'Auth'],
        ['action' => 'index']
    );

    $routes->connect(
        'link',
        ['controller' => 'Auth'],
        ['action' => 'links']
    );

    // $routes->connect('step', ['controller' => 'AntecedentesPessoaisDoencas','action' => 'index'])->_method('GET');
    Router::prefix('api', function ($routes) {
        $routes->fallbacks(DashedRoute::class);

        // $routes->resources('Usuario');

        $routes->connect(
            '/status',
            ['controller' => 'Status', 'action' => 'index', '_method' => 'GET']
        );

        $routes->connect(
            '/usuario/teste/',
            ['controller' => 'Usuario', 'action' => 'testeOpa', '_method' => 'GET']
        );

        ################################ TESTE ENCRIPT###############################
        $routes->connect(
            '/usuario/testeCript/',
            ['controller' => 'Usuario', 'action' => 'testeCript', '_method' => 'GET']
        );

        $routes->connect(
            '/usuario/testeDescript',
            ['controller' => 'Usuario', 'action' => 'testeDecript', '_method' => 'POST']
        );
        ################################ TESTE ENCRIPT###############################

        $routes->connect(
            '/usuario/descript/',
            ['controller' => 'Usuario', 'action' => 'descriptTeste', '_method' => 'POST']
        );

        //get usuario_codigo_cliente :cpf
        $routes->connect(
            '/usuario/cliente/*',
            ['controller' => 'Usuario', 'action' => 'getCodigoCliente', '_method' => 'GET']
        );

        $routes->connect(
            '/usuario/clientes',
            ['controller' => 'Usuario', 'action' => 'getUserClients', '_method' => 'GET']
        );

        $routes->connect(
            '/usuario/:codigo_usuario/clientes',
            ['controller' => 'Usuario', 'action' => 'getUserClients', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/usuario/:codigo_usuario/clientes/:codigo_cliente',
            ['controller' => 'Usuario', 'action' => 'getUserClients', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'codigo_cliente' => '\d+', 'pass' => ['codigo_usuario', 'codigo_cliente']]
        );

        $routes->connect(
            '/usuario-localidade/:codigo_usuario',
            [
                'controller' => 'Usuario',
                'action' => 'obterLocalidade',
                '_method' => 'GET'
            ],
            [
                'codigo_usuario' => '\d+',
                'pass' => ['codigo_usuario']
            ]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/clientes/funcionarios',
            ['controller' => 'Usuario', 'action' => 'getEmployeesUserClients', '_method' => 'GET']
        );

        //delete para vincular o codigo_usuario e codigo_cliente do nina passado
        $routes->connect(
            '/usuario/cliente/:codigo_usuario/:codigo_cliente',
            ['controller' => 'Usuario', 'action' => 'delVinculoUsuarioCliente', '_method' => 'DELETE'],
            ['codigo_usuario' => '\d+', 'codigo_cliente' => '\d+', 'pass' => ['codigo_usuario', 'codigo_cliente']]
        );

        //post para vincular o codigo do nina passado
        $routes->connect(
            '/validar/vinculo/*',
            ['controller' => 'Usuario', 'action' => 'getValidarVinculo', '_method' => 'POST']
        );

        //get usuario :codigo_usuario
        $routes->connect(
            '/home/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'home', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/qr_code/leitura',
            ['controller' => 'Usuario', 'action' => 'setQrCodeLeitura', '_method' => 'POST']
        );

        //get usuario :codigo_usuario
        $routes->connect(
            '/disclamer/aceite',
            ['controller' => 'Usuario', 'action' => 'setDisclamerAceite', '_method' => 'POST']
        );

        ##############################################
        ##################//exames####################
        ##############################################
        // FICHA CLINICẠ
        $routes->connect(
            '/fichaclinica/questoes/:codigo_usuario/:codigo_pedido_exame',
            ['controller' => 'FichasClinicas', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'codigo_pedido_exame' => '\d+', 'pass' => ['codigo_usuario', 'codigo_pedido_exame']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fichaclinica/incluir',
            ['controller' => 'FichasClinicas', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/fichaclinica/:codigo_pedido_exame',
            ['controller' => 'FichasClinicas', 'action' => 'getDadosFichaClinica'],
            ['codigo_pedido_exame' => '\d+', 'pass' => ['codigo_pedido_exame']]
        )->setMethods(['GET']);

        //PARECER
        $routes->connect(
            '/parecer/questoes/:codigo_pedido_exame',
            ['controller' => 'FichasClinicas', 'action' => 'viewParecer'],
            ['codigo_pedido_exame' => '\d+', 'pass' => ['codigo_pedido_exame']]
        )->setMethods(['GET']);

        $routes->connect(
            '/parecer/incluir',
            ['controller' => 'FichasClinicas', 'action' => 'addParecer']
        )->setMethods(['POST']);

        // AUDIOMETRIA
        $routes->connect(
            '/audiometria/questoes/:codigo_usuario/:codigo_item_pedido_exame',
            ['controller' => 'Audiometrias', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'codigo_item_pedido_exame' => '\d+', 'pass' => ['codigo_usuario', 'codigo_item_pedido_exame']]
        )->setMethods(['GET']);

        $routes->connect(
            '/audiometria/incluir',
            ['controller' => 'Audiometrias', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/audiometria/:codigo_pedido_exame',
            ['controller' => 'Audiometrias', 'action' => 'getDatadosAudiometrias'],
            ['codigo_pedido_exame' => '\d+', 'pass' => ['codigo_pedido_exame']]
        )->setMethods(['GET']);

        // FICHA PSICOSSOCIAL
        $routes->connect(
            '/psicossocial/questoes/:codigo_usuario/:codigo_pedido_exame',
            ['controller' => 'FichaPsicossocial', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'codigo_pedido_exame' => '\d+', 'pass' => ['codigo_usuario', 'codigo_pedido_exame']]
        )->setMethods(['GET']);

        $routes->connect(
            '/psicossocial/incluir',
            ['controller' => 'FichaPsicossocial', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/psicossocial/:codigo_pedido_exame',
            ['controller' => 'FichaPsicossocial', 'action' => 'getDatadosFichaPsicossocial'],
            ['codigo_pedido_exame' => '\d+', 'pass' => ['codigo_pedido_exame']]
        )->setMethods(['GET']);

        // FICHA Assistencial
        $routes->connect(
            '/assistencial/questoes/:codigo_usuario/:codigo_pedido_exame',
            ['controller' => 'FichasAssistenciais', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'codigo_pedido_exame' => '\d+', 'pass' => ['codigo_usuario', 'codigo_pedido_exame']]
        )->setMethods(['GET']);

        $routes->connect(
            '/assistencial/incluir',
            ['controller' => 'FichasAssistenciais', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/assistencial/:codigo_pedido_exame',
            ['controller' => 'FichasAssistenciais', 'action' => 'getDatadosFichaAssistencial'],
            ['codigo_pedido_exame' => '\d+', 'pass' => ['codigo_pedido_exame']]
        )->setMethods(['GET']);

        ##############################################
        ###################fim exames#################
        ##############################################

        //get usuario :codigo_usuario
        $routes->connect(
            '/usuario/fornecedor/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getAllFornecedorByUser', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        #########################################################
        ###################GESTAO USUARIOS CREDENCIADOS##########
        #########################################################

        //get usuario :codigo_usuario
        $routes->connect(
            '/gestao_usuario/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getUsuarioFornecedor', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/gestao_usuario/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getAllFornecedorByUser', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/gestao_usuario/permissoes',
            ['controller' => 'FornecedorPermissoes', 'action' => 'getFornecedorPermissoes', '_method' => 'GET']
        );

        $routes->connect(
            '/gestao_usuario/funcao',
            ['controller' => 'Usuario', 'action' => 'getFornecedorFuncao', '_method' => 'GET']
        );

        //post|put atestados :codigo_usuario || :codigo_atestado
        $routes->connect(
            '/gestao_usuario',
            ['controller' => 'Usuario', 'action' => 'setUsuarioFornecedor'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['PUT', 'POST']);

        #########################################################
        ################FIM GESTAO USUARIOS CREDENCIADOS################
        #########################################################

        #########################################################
        ###################CALENDARIO#################
        #########################################################

        //get usuario :codigo_usuario
        $routes->connect(
            '/credenciamento/calendario/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'getFornecedorMedicoCalendario', '_method' => 'GET'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        );

        //post|put atestados :codigo_usuario || :codigo_atestado
        $routes->connect(
            '/credenciamento/calendario',
            ['controller' => 'Fornecedores', 'action' => 'setFornecedoresMedicosCalendario']
        )->setMethods(['PUT', 'POST']);

        $routes->connect(
            '/credenciamento/calendario/dias_semana',
            ['controller' => 'Fornecedores', 'action' => 'getDiasSemana', '_method' => 'GET']
        );

        #########################################################
        ################FIM CALENDARIO################
        #########################################################

        //get atestados/ativos :codigo_usuario
        $routes->connect(
            '/atestados/ativos/:codigo_usuario',
            ['controller' => 'Atestados', 'action' => 'getAtestadosAtivos', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get usuario :codigo_usuario
        $routes->connect(
            '/atestados/historico/:codigo_usuario',
            ['controller' => 'Atestados', 'action' => 'getAtestadosHistorico', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //post|put atestados :codigo_usuario || :codigo_atestado
        $routes->connect(
            '/atestados/:codigo_usuario',
            ['controller' => 'Atestados', 'action' => 'salvarAtestado'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['PUT', 'POST']);

        //POST | add atestado paciente
        $routes->connect(
            '/atestado/paciente',
            ['controller' => 'Atestados', 'action' => 'salvarAtestadoPaciente']
        )->setMethods(['POST']);

        //POST | editar atestado paciente
        $routes->connect(
            '/atestado/paciente/editar',
            ['controller' => 'Atestados', 'action' => 'editarAtestadoPaciente']
        )->setMethods(['POST']);

        //GET atestado paciente
        $routes->connect(
            '/atestado/paciente/:codigo_paciente/:codigo_medico',
            ['controller' => 'Atestados', 'action' => 'getAtestadoPorId', '_method' => 'GET'],
            ['codigo_paciente' => '\d+', 'codigo_medico' => '\d+', 'pass' => ['codigo_paciente', 'codigo_medico']]
        );

        //post|put contatos emergencia :codigo_usuario
        $routes->connect(
            '/contato/emergencia/:codigo_usuario',
            ['controller' => 'UsuarioContatoEmergencia', 'action' => 'add'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['PUT', 'POST']);

        //put token_push :codigo_usuario
        $routes->connect(
            '/token/push/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'tokenPush', '_method' => 'PUT'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/token/push/:codigo_sistema',
            ['controller' => 'Usuario', 'action' => 'removeTokenPush', '_method' => 'DELETE'],
            ['codigo_sistema' => '\d+', 'pass' => ['codigo_sistema']]
        );

        //get notificacao :codigo_usuario
        $routes->connect(
            '/notificacao/:codigo_usuario',
            ['controller' => 'PushOutbox', 'action' => 'view', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //put notificacao :codigo_usuario
        $routes->connect(
            '/notificacao/lida/:codigo_push',
            ['controller' => 'PushOutbox', 'action' => 'setMsgLida', '_method' => 'PUT'],
            ['codigo_push' => '\d+', 'pass' => ['codigo_push']]
        );

        //get indicadores :codigo_usuario
        $routes->connect(
            '/indicadores/:codigo_usuario',
            ['controller' => 'Indicadores', 'action' => 'getInfos', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //post/put indicadores :codigo_usuario
        $routes->connect(
            '/indicadores/:codigo_usuario',
            ['controller' => 'Indicadores', 'action' => 'setInfos', '_method' => ['POST', 'PUT']],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //delete indicadores :codigo_usuario
        $routes->connect(
            '/indicadores/:codigo_usuario',
            ['controller' => 'Indicadores', 'action' => 'delIndicador', '_method' => 'DELETE'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //post usuario
        $routes->connect(
            '/usuario/',
            ['controller' => 'Usuario', 'action' => 'add', '_method' => 'POST']
        );

        //put usuario :codigo_usuario
        $routes->connect(
            '/usuario/*',
            ['controller' => 'Usuario', 'action' => 'edit', '_method' => 'PUT']
        );

        //put usuario :codigo_usuario
        $routes->connect(
            '/therma/usuario/*',
            ['controller' => 'Usuario', 'action' => 'editTherma', '_method' => 'PUT']
        );

        //patch usuario gestor de operações
        $routes->connect(
            '/usuario/gestor/*',
            ['controller' => 'Usuario', 'action' => 'usuarioGestor', '_method' => 'PUT']
        );
        //get therma/usuario/:codigo_usuario
        $routes->connect(
            '/therma/usuario/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'viewTherma'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        //get foto :codigo_usuario
        $routes->connect(
            '/foto/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getFoto', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );
        //put foto :codigo_usuario
        $routes->connect('/foto/*', ['controller' => 'Usuario', 'action' => 'enviaFoto', '_method' => 'PUT']);

        //get consultas histórico :codigo_usuario
        $routes->connect(
            '/consultas/historico/:codigo_usuario',
            ['controller' => 'PedidosExames', 'action' => 'getConsultaHistorico', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get consultas :codigo_usuario
        $routes->connect(
            '/consultas/agendadas/:codigo_usuario',
            ['controller' => 'PedidosExames', 'action' => 'getConsultasAgendadas', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get para consultar o detalhe :codigo_item_pedido_exame
        $routes->connect(
            '/consultas/detalhe/:codigo_item_pedido_exame',
            ['controller' => 'PedidosExames', 'action' => 'consultasDetalhes', '_method' => 'GET'],
            ['codigo_item_pedido_exame' => '\d+', 'pass' => ['codigo_item_pedido_exame']]
        );

        //get endereco :endereco - autocomplete dos endereços
        $routes->connect(
            '/clinicas/endereco/*',
            ['controller' => 'PedidosExames', 'action' => 'getClinicaEndereco', '_method' => 'GET']
        );

        //get endereco :endereco - autocomplete dos endereços
        $routes->connect(
            '/avaliar/clinicas/*',
            ['controller' => 'FornecedoresAvaliacoes', 'action' => 'postAvaliar', '_method' => 'POST']
        );

        //get endereco :lat :long
        $routes->connect(
            '/endereco/*',
            ['controller' => 'PedidosExames', 'action' => 'getEndereco', '_method' => 'GET']
        );

        //post clinicas/proximas
        $routes->connect(
            '/clinicas/proximas/*',
            ['controller' => 'PedidosExames', 'action' => 'getClinicasProximas', '_method' => 'POST']
        )->setMethods(['GET', 'POST']);

        //get clinica/disponibilidade
        $routes->connect(
            '/clinica/disponibilidade/*',
            ['controller' => 'PedidosExames', 'action' => 'getClinicaDisponibilidade', '_method' => 'GET']
        );

        //get agendamentos :codigo_usuario
        $routes->connect(
            '/agendamentos/:codigo_usuario',
            ['controller' => 'PedidosExames', 'action' => 'getAgendamentos', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get agendamentos/historico :codigo_usuario :cpf
        $routes->connect(
            '/agendamentos/historico/:codigo_usuario/*',
            ['controller' => 'PedidosExames', 'action' => 'getAgendamentosHistorico', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get agendamento/detalhe :codigo_id_agendamento (codigo_item_pedido_exame)
        $routes->connect(
            '/agendamento/detalhe/:codigo_id_agendamento',
            ['controller' => 'PedidosExames', 'action' => 'getAgendamentosDetalhe', '_method' => 'GET'],
            ['codigo_id_agendamento' => '\d+', 'pass' => ['codigo_id_agendamento']]
        );

        //get criar_agendamento :codigo_usuario/:documento
        $routes->connect(
            '/criar/agendamento/:codigo_usuario/*',
            ['controller' => 'PedidosExames', 'action' => 'getCriarAgendamento', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //post criar_agendamento
        $routes->connect(
            '/agendamento',
            ['controller' => 'PedidosExames', 'action' => 'setCriarAgendamento', '_method' => 'POST']
        );

        //get cancelar
        $routes->connect(
            '/agendamento/cancelar/*',
            ['controller' => 'PedidosExames', 'action' => 'setCancelarAgendamento', '_method' => 'GET']
        );

        //get pedido/exames :codigo_pedido
        $routes->connect(
            '/pedido/exames/:codigo_pedido/:codigo_fornecedor',
            ['controller' => 'PedidosExames', 'action' => 'getPedidoExames', '_method' => 'GET'],
            ['codigo_pedido' => '\d+', 'codigo_fornecedor' => '\d+', 'pass' => ['codigo_pedido', 'codigo_fornecedor']]
        );

        $routes->connect(
            '/pedidos/exames/:codigo_fornecedor/*',
            ['controller' => 'PedidosExames', 'action' => 'getListaPedidos', '_method' => 'GET'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        );

        $routes->connect(
            '/pedidos/exames/imprimir/*',
            ['controller' => 'PedidosExames', 'action' => 'imprimirKit', '_method' => 'GET']
        );

        //get agendamento/funcionarios :codigo_fornecedor/:busca
        $routes->connect(
            '/funcionarios/busca/*',
            ['controller' => 'Funcionarios', 'action' => 'getFuncionariosPorFornecedor', '_method' => 'GET']
        );

        //get funcionario/historico :codigo ou :cpf
        $routes->connect(
            '/funcionario/historico/:codigo',
            ['controller' => 'PedidosExames', 'action' => 'getHistoricoFuncionario', '_method' => 'GET'],
            ['codigo' => '\d+', 'pass' => ['codigo']]
        );

        //get agendamento/medicos :codigo_fornecedor/:especialidade
        $routes->connect(
            '/medicos/:codigo_fornecedor/*',
            ['controller' => 'PedidosExames', 'action' => 'getMedicos', '_method' => 'GET'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        );

        //get agendamento/especialidades :codigo_fornecedor
        $routes->connect(
            '/especialidades/:codigo_fornecedor',
            ['controller' => 'PedidosExames', 'action' => 'getEspecialidades'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        //get /paciente/detalhe :codigo
        $routes->connect(
            '/paciente/detalhe/:codigo',
            ['controller' => 'Funcionarios', 'action' => 'view'],
            ['codigo' => '\d+', 'pass' => ['codigo']]
        )->setMethods(['GET']);

        //get /colaborador/detalhe :codigo
        $routes->connect(
            '/colaborador/detalhe/:codigo',
            ['controller' => 'Funcionarios', 'action' => 'getColaboradorDetalhe'],
            ['codigo' => '\d+', 'pass' => ['codigo']]
        )->setMethods(['GET']);

        //post|put compromisso
        $routes->connect(
            '/compromisso',
            ['controller' => 'Compromisso', 'action' => 'setCompromisso', '_method' => ['POST', 'PUT']]
        );

        //get enviar/exames :codigo_id_agendamento (codigo_item_pedido_exame)
        $routes->connect(
            '/enviar/exames/:codigo_id_agendamento',
            ['controller' => 'PedidosExames', 'action' => 'getEnviarExames', '_method' => 'GET'],
            ['codigo_id_agendamento' => '\d+', 'pass' => ['codigo_id_agendamento']]
        );

        //get /exames :codigo_agendamento (codigo_item_pedido_exame)
        $routes->connect(
            '/exame/detalhe/:codigo_id_agendamento',
            ['controller' => 'Exames', 'action' => 'getExameDetalhe', '_method' => 'GET'],
            ['codigo_id_agendamento' => '\d+', 'pass' => ['codigo_id_agendamento']]
        );

        //get /exame/exames -> pegar a lista de exames
        $routes->connect(
            '/exame/exames',
            ['controller' => 'Exames', 'action' => 'getAllExames', '_method' => 'GET']
        );

        //get estabelecimentos pelo endereço ?end=nononn
        $routes->connect(
            '/exame/estabelecimento',
            ['controller' => 'Exames', 'action' => 'getEstabelecimentosEndereco']
        )->setMethods(['GET']);

        //post enviar os seguintes campos: codigo_fornecedor, local, data e imagem_exame
        $routes->connect(
            '/exame/salvar',
            ['controller' => 'Exames', 'action' => 'salvarExame', '_method' => 'POST']
        );

        //post adicionar endereço ao usuário
        $routes->connect(
            '/usuario/endereco',
            ['controller' => 'UsuarioEndereco', 'action' => 'endereco', '_method' => 'POST']
        );

        $routes->connect(
            '/endereco/tipo',
            ['controller' => 'UsuarioEnderecoTipo', 'action' => 'getAllTipoEndereco', '_method' => 'GET']
        );

        //put para alterar endereço do usuário
        $routes->connect(
            '/usuario/endereco/:codigo_endereco',
            ['controller' => 'UsuarioEndereco', 'action' => 'alterarEndereco', '_method' => 'PUT'],
            ['codigo_endereco' => '\d+', 'pass' => ['codigo_endereco']]
        );

        //delete para alterar endereço do usuário
        $routes->connect(
            '/usuario/del_endereco/:codigo_endereco',
            ['controller' => 'UsuarioEndereco', 'action' => 'deletarEndereco', '_method' => 'DELETE'],
            ['codigo_endereco' => '\d+', 'pass' => ['codigo_endereco']]
        );

        //get de endereço do usuário
        $routes->connect(
            '/usuario/get_endereco/:codigo_usuario',
            ['controller' => 'UsuarioEndereco', 'action' => 'getEndereco', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //get consultas/pontuais :codigo_usuario
        $routes->connect(
            '/consultas/pontuais/:codigo_usuario/:codigo_cliente',
            ['controller' => 'PedidosExames', 'action' => 'getConsultasPontuais', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'codigo_cliente' => '\d+', 'pass' => ['codigo_usuario', 'codigo_cliente']]
        );

        //get motivo/afastamento
        $routes->connect(
            '/motivo/afastamento/*',
            ['controller' => 'MotivosAfastamento', 'action' => 'lista', '_method' => 'GET']
        );

        //get motivo/esocial
        $routes->connect(
            '/motivo/esocial',
            ['controller' => 'MotivosAfastamento', 'action' => 'listaESocial', '_method' => 'GET']
        );

        //get cid ?nome=nononn
        $routes->connect(
            '/cid',
            ['controller' => 'Cid', 'action' => 'obterCid']
        )->setMethods(['GET']);

        //get estabelecimentos ?nome=nononn&codigo_profissional=1111
        $routes->connect(
            '/estabelecimento',
            ['controller' => 'Atestados', 'action' => 'obterNomeEstabelecimento']
        )->setMethods(['GET']);

        // :codigo_pedido
        // $routes->connect('/consultas/pedido/:codigo_pedido',
        //     ['controller' => 'PedidosExames', 'action' => 'consultasPedido', '_method' => 'GET'],
        //     ['codigo_pedido' => '\d+', 'pass' => ['codigo_pedido']]
        // );

        // Psicossocial
        // - Listar todas as Questoes(perguntas e respostas) de um Questionario por codigo_usuario e codigo_questionario
        // - Responder Questoes de um Questionario por codigo_usuario e codigo_questionario

        $routes->connect(
            '/psicossocial/perguntas/:codigo_usuario',
            ['controller' => 'Psicossocial', 'action' => 'perguntasRespostas'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/psicossocial/responder/:codigo_usuario',
            ['controller' => 'Psicossocial', 'action' => 'salvarResposta'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['PUT', 'POST']);

        // Questionarios
        // - Listar todos os Questionarios disponiveis por codigo_usuario
        // - Listar todas as Questoes(perguntas e respostas) de um Questionario por codigo_usuario e codigo_questionario
        // - Responder Questoes de um Questionario por codigo_usuario e codigo_questionario

        //get dos questionarios pegando o codigo usuario para saber o sexo
        $routes->connect(
            '/questionarios/:codigo_usuario',
            ['controller' => 'Questionarios', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/questionarios/perguntas/:codigo_usuario/:codigo_questionario',
            ['controller' => 'Questionarios', 'action' => 'perguntasRespostas', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'codigo_questionario' => '\d+', 'pass' => ['codigo_usuario', 'codigo_questionario']]
        );

        $routes->connect(
            '/questionarios/responde/:codigo_usuario/:codigo_questionario',
            ['controller' => 'Questionarios', 'action' => 'salvarRespostaUsuario'],
            ['codigo_usuario' => '\d+', 'codigo_questionario' => '\d+', 'pass' => ['codigo_usuario', 'codigo_questionario']]
        )->setMethods(['PUT', 'POST']);

        //get do profissional por codigo_usuario
        $routes->connect(
            '/profissional/:codigo_usuario',
            ['controller' => 'Profissional', 'action' => 'obterProfissionais', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        // USUARIO
        $routes->connect(
            '/usuario/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'view'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // USUARIO
        $routes->connect(
            '/therma/usuario/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'viewTherma'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        //Auth com google
        $routes->connect(
            '/auth/google',
            ['controller' => 'Usuario', 'action' => 'authGoogle'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST']);

        //Auth com linkedin
        $routes->connect(
            '/auth/linkedin',
            ['controller' => 'Usuario', 'action' => 'authLinkedin'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST']);

        //Link para callback google
        $routes->connect(
            '/auth/google/link',
            ['controller' => 'Usuario', 'action' => 'getLinkCallbackGoogle'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        //Link para callback facebook
        $routes->connect(
            '/auth/facebook/link',
            ['controller' => 'Usuario', 'action' => 'getLinkCallbackFacebook'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        //Callback linkedin
        $routes->connect(
            '/auth/callback/linkedin',
            ['controller' => 'Usuario', 'action' => 'getCallbackLinkedin'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST']);

        //Callback google
        $routes->connect(
            '/auth/callback/google',
            ['controller' => 'Usuario', 'action' => 'getCallbackGoogle'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST']);

        //Callback facebook
        $routes->connect(
            '/auth/callback/facebook',
            ['controller' => 'Usuario', 'action' => 'getCallbackFacebook'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST', 'GET']);

        //Links para os callbacks
        $routes->connect(
            '/auth/login/links',
            ['controller' => 'Usuario', 'action' => 'getLinks']
        )->setMethods(['GET']);

        // troca da senha
        $routes->connect(
            '/usuario/credencial',
            ['controller' => 'Usuario', 'action' => 'atualizaSenha']
        )->setMethods(['PUT']);

        $routes->connect(
            '/usuario/credencial/trocar-senha',
            ['controller' => 'Usuario', 'action' => 'updatePassword']
        )->setMethods(['PUT']);

        // esqueceu / recuperar senha - POST Solicita
        // esqueceu / recuperar senha - PUT Valida
        $routes->connect(
            '/usuario/credencial/recuperar',
            ['controller' => 'Usuario', 'action' => 'recuperarSenha']
        )->setMethods(['POST', 'PUT']);

        // esqueceu: Envia token por e-mail | sms | push - POST, PUT
        $routes->connect(
            '/usuario/credencial/recuperar-senha-token',
            ['controller' => 'Usuario', 'action' => 'postRecuperarSenhaToken']
        )->setMethods(['POST']);

        $routes->connect(
            '/usuario/credencial/recuperar-senha-token',
            ['controller' => 'Usuario', 'action' => 'putRecuperarSenhaToken']
        )->setMethods(['PUT']);

        // esqueceu / recuperar senha - POST Valida
        $routes->connect(
            '/usuario/credencial/redefinir',
            ['controller' => 'Usuario', 'action' => 'redefinirSenha']
        )->setMethods(['POST']);

        $routes->connect(
            '/agendamento/exame/foto',
            ['controller' => 'PedidosExames', 'action' => 'setAgendamentoExameFoto', '_method' => ['POST', 'PUT']]
        );
        $routes->connect(
            '/agendamento/exame/foto',
            ['controller' => 'PedidosExames', 'action' => 'deleteAgendamentoExameFoto', '_method' => ['DELETE']]
        );

        $routes->connect(
            '/agendamento/exame/triagem',
            ['controller' => 'PedidosExames', 'action' => 'putAgendamentoExameTriagem', '_method' => ['PUT']]
        );

        $routes->connect(
            '/agendamento/exame',
            ['controller' => 'PedidosExames', 'action' => 'setAgendamentoExame', '_method' => ['POST', 'PUT']]
        );

        $routes->connect(
            '/agendamento/fichaclinica/foto',
            ['controller' => 'PedidosExames', 'action' => 'setAgendamentoFichaClinicaFoto', '_method' => 'PUT']
        )->setMethods(['PUT']);

        //MODULO CREDENCIAMENTO
        $routes->connect(
            '/credenciamento/dados-bancarios/:codigo_fornecedor',
            ['controller' => 'credenciamento', 'action' => 'getDadosBancarios'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        $routes->connect(
            '/credenciamento/dados-bancarios',
            ['controller' => 'credenciamento', 'action' => 'dadosBancarios']
        )->setMethods(['PUT']);

        $routes->connect(
            '/credenciamento/bancos',
            ['controller' => 'credenciamento', 'action' => 'getBancos']
        )->setMethods(['GET']);

        //FIM MODULO CREDENCIAMENTO

        //ALERTAS DE USUARIO
        $routes->connect(
            '/usuario/tipos-alertas',
            ['controller' => 'usuario', 'action' => 'configuracoesAlerta']
        )->setMethods(['POST']);

        $routes->connect(
            '/usuario/tipos-alertas/:codigo_usuario',
            ['controller' => 'usuario', 'action' => 'getUsuariosAlertasTipos'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        //ALERTAS DE USUARIO

        $routes->connect(
            '/exames/historico/:codigo_usuario',
            ['controller' => 'Exames', 'action' => 'historico'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // Exames Histórico de Ocupacionais e Assistenciais
        $routes->connect(
            '/exames/historicocompleto/:codigo_usuario',
            ['controller' => 'Exames', 'action' => 'historicoExamesOcupacionaisAssistenciais'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // Exames Histórico - Interna Ocupacional
        $routes->connect(
            '/exames/historicocompleto/ocupacional/:codigo_exame',
            ['controller' => 'Exames', 'action' => 'exameOcupacional'],
            ['codigo_exame' => '\d+', 'pass' => ['codigo_exame']]
        )->setMethods(['GET']);

        // Exames Histórico - Interna Assistencial
        $routes->connect(
            '/exames/historicocompleto/assistencial/:codigo_exame',
            ['controller' => 'Exames', 'action' => 'exameAssistencial'],
            ['codigo_exame' => '\d+', 'pass' => ['codigo_exame']]
        )->setMethods(['GET']);

        // api/fornecedor/imagens/8396/0
        $routes->connect(
            '/fornecedor/imagens/:codigo_fornecedor/:codigo_imagem',
            ['controller' => 'Fornecedores', 'action' => 'obterImagens'],
            ['codigo_fornecedor' => Router::ID, 'codigo_imagem' => Router::ID, 'pass' => ['codigo_fornecedor', 'codigo_imagem']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fornecedor/unidades/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'getFornecedorUnidades'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        // LOGS
        $routes->connect(
            '/devops/logs',
            ['controller' => 'Logs', 'action' => 'obterLogs']
        )->setMethods(['GET']);

        $routes->connect(
            '/devops/email',
            ['controller' => 'Test', 'action' => 'email']
        )->setMethods(['GET']);

        // Medicamentos
        $routes->connect(
            '/medicamentos/busca/*',
            ['controller' => 'Medicamentos', 'action' => 'getMedicamentos']
        )->setMethods(['GET']);

        $routes->connect(
            '/medicamentos/busca/apresentacao/*',
            ['controller' => 'Medicamentos', 'action' => 'getMedicamentosApresentacao']
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/medicamento/status/adicionar',
            ['controller' => 'UsuariosMedicamentosStatus', 'action' => 'addMedicamecaoTomada']
        )->setMethods(['POST']);

        $routes->connect(
            '/usuario/medicamento/programacao/listar',
            ['controller' => 'Medicamentos', 'action' => 'getProgramacaoMedicamentos']
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/medicamento/programacao/adicionar',
            ['controller' => 'Medicamentos', 'action' => 'addProgramacaoMedicamentos']
        )->setMethods(['POST']);

        $routes->connect(
            '/usuario/medicamento/programacao/alterar/:codigo_usuarios_medicamentos',
            ['controller' => 'Medicamentos', 'action' => 'updateProgramacaoMedicamentos'],
            //['codigo_usuarios_medicamentos' => Router::ID, 'pass' => ['codigo_usuarios_medicamentos']]
        )->setMethods(['PUT']);
        ############delete medicamento############
        $routes->connect(
            '/usuario/medicamento/:codigo_usuarios_medicamentos',
            ['controller' => 'Medicamentos', 'action' => 'deleteProgramacaoMedicamentos'],
            ['codigo_usuarios_medicamentos' => '\d+', 'pass' => ['codigo_usuarios_medicamentos']]
        )->setMethods(['DELETE']);
        ############delete medicamento############

        $routes->connect(
            '/avaliacao/tipo',
            ['controller' => 'FornecedoresAvaliacoes', 'action' => 'getAvalicaoTipo']
        )->setMethods(['GET']);

        $routes->connect(
            '/medicamentos/frequencia_uso',
            ['controller' => 'Medicamentos', 'action' => 'getFrequenciaUso']
        )->setMethods(['GET']);

        $routes->connect(
            '/upload/imagens_indicadores',
            ['controller' => 'Upload', 'action' => 'imagensIndicadores']
        )->setMethods(['PUT']);

        //get termos, rota para direcionar para pegar os termos de uso e politica de privacidade
        $routes->connect(
            '/termos/*',
            ['controller' => 'TermoUso', 'action' => 'view', '_method' => 'GET']
        );

        //listagem de agendamento dos medicos
        $routes->connect(
            '/agendamento/list/',
            ['controller' => 'agendamento', 'action' => 'index', '_method' => 'POST']
        );

        //listagem de agendamento de exames
        $routes->connect(
            '/agendamento/ocupacional/:codigo_usuario/*',
            ['controller' => 'agendamento', 'action' => 'getAgendamentoOcupacional', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        //listagem de agenda ocupacional
        $routes->connect(
            '/agendamento/list/ocupacional',
            ['controller' => 'agendamento', 'action' => 'listaAgendamentoExames', '_method' => 'POST']
        );

        //Criar novo agendamento ocupacional
        $routes->connect(
            '/agendamento/exame/criar',
            ['controller' => 'agendamento', 'action' => 'criarAgendamento', '_method' => ['POST']]
        );

        //check list para tela de atendimento
        $routes->connect(
            '/agendamento/check-exame',
            ['controller' => 'agendamento', 'action' => 'conferirExame', '_method' => 'POST']
        );

        //get buscar pela data e medico as horas que ele pode agendar
        $routes->connect(
            //'/agendamento/data-exame',
            '/agendamento/reagendamento/*',
            ['controller' => 'agendamento', 'action' => 'getReagendamento', '_method' => 'GET'],
        );

        //put para alterar status do agendamento
        $routes->connect(
            //'/agendamento/data-exame',
            '/agendamento/reagendamento',
            ['controller' => 'agendamento', 'action' => 'updateDataExame', '_method' => 'PUT'],
        );

        //Criar novo paciente
        $routes->connect(
            '/paciente',
            ['controller' => 'pacientes', 'action' => 'add', '_method' => 'POST']
        );

        //Editar paciente
        $routes->connect(
            '/paciente',
            ['controller' => 'pacientes', 'action' => 'edit', '_method' => 'PUT']
        );

        //Retornar Exames do paciente
        $routes->connect(
            '/paciente/exames',
            ['controller' => 'pacientes', 'action' => 'getExamePaciente', '_method' => 'POST']
        );

        //Atualizar status do agendamento
        $routes->connect(
            '/paciente/status-agendamento',
            ['controller' => 'pacientes', 'action' => 'putAgendamentoStatus', '_method' => 'PUT']
        );

        //Retorna todas as empresas cadastradas
        $routes->connect(
            '/empresas/:codigo_fornecedor',
            ['controller' => 'Empresa', 'action' => 'view', '_method' => 'GET'],
            ['pass' => ['codigo_fornecedor']]
        );

        //Retorna todos os setores referente a uma empresa
        $routes->connect(
            '/setores/:codigo_cliente',
            ['controller' => 'Empresa', 'action' => 'getSetores', '_method' => 'GET'],
            ['codigo_cliente' => '\d+', 'pass' => ['codigo_cliente']]
        );

        //Retorna o setor referente a uma empresa
        $routes->connect(
            '/setores/:codigo_setor/cliente/:codigo_cliente',
            ['controller' => 'Empresa', 'action' => 'getSetor', '_method' => 'GET'],
            ['codigo_setor' => Router::ID, 'codigo_cliente' => Router::ID, 'pass' => ['codigo_setor', 'codigo_cliente']]
        );

        //Retorna todos os cargos referente a uma empresa
        $routes->connect(
            '/cargos/:codigo_cliente',
            ['controller' => 'Empresa', 'action' => 'getCargos', '_method' => 'GET'],
            ['codigo_cliente' => '\d+', 'pass' => ['codigo_cliente']]
        );

        //Retorna todos os cargos referente a um setor e a uma empresa
        $routes->connect(
            '/cargos/cliente/:codigo_cliente/setor/:codigo_setor',
            ['controller' => 'Empresa', 'action' => 'getCargosPorEmpresaSetor', '_method' => 'GET'],
            ['codigo_cliente' => Router::ID, 'codigo_setor' => Router::ID, 'pass' => ['codigo_cliente', 'codigo_setor']]
        );

        //Retorna o cargo referente a uma empresa
        $routes->connect(
            '/cargos/:codigo_cargo/cliente/:codigo_cliente',
            ['controller' => 'Empresa', 'action' => 'getCargo', '_method' => 'GET'],
            ['codigo_cargo' => Router::ID, 'codigo_cliente' => Router::ID, 'pass' => ['codigo_cargo', 'codigo_cliente']]
        );

        //Retorna todas as categoria do paciente referente a uma empresa
        $routes->connect(
            '/categorias/:codigo_empresa',
            ['controller' => 'Empresa', 'action' => 'getCategorias', '_method' => 'GET'],
            ['codigo_empresa' => '\d+', 'pass' => ['codigo_empresa']]
        );

        //Retorna todos ou um Status resultado exame
        $routes->connect(
            '/status/resultados',
            ['controller' => 'PedidosExames', 'action' => 'getStatusResultados', '_method' => 'GET'],
        );

        #########################################################
        # MINHA CONTA
        #########################################################
        //get minha-conta/foto/:codigo_usuario
        $routes->connect(
            '/minha-conta/foto/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getFotoUsuario', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );
        //put minha-conta/foto/:codigo_usuario
        $routes->connect(
            '/minha-conta/foto',
            ['controller' => 'Usuario', 'action' => 'putFotoUsuario', '_method' => 'PUT'],
        );

        //get minha-conta/foto/:codigo_usuario
        $routes->connect(
            '/minha-conta/dados/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getDadosMinhaConta', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );
        //put minha-conta/dados/:codigo_usuario
        $routes->connect(
            '/minha-conta/dados',
            ['controller' => 'Usuario', 'action' => 'putDadosUsuario', '_method' => 'PUT'],
        );
        #########################################################
        #FIM MINHA CONTA#
        #########################################################

        ########RELATORIOS#######

        $routes->resources('Jasper');
        $routes->post('/jasper/request_report', ['controller' => 'Jasper', 'action' => 'request_report']);

        ##########FIM RELATORIOS#######

        //FORNECEDOR
        // /api/fornecedor/dados_da_empresa/8350
        $routes->connect(
            '/fornecedor/dados_da_empresa/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'dadosDaEmpresaGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        // /api/fornecedor/dados_da_empresa_combos
        $routes->connect(
            '/fornecedor/dados_da_empresa_combos',
            ['controller' => 'Fornecedores', 'action' => 'combos']
        )->setMethods(['GET']);

        // /api/fornecedor/dados_da_empresa/
        $routes->connect(
            '/fornecedor/dados_da_empresa',
            ['controller' => 'Fornecedores', 'action' => 'dadosDaEmpresaPut']
        )->setMethods(['PUT']);

        // /api/fornecedor/dados_gerais/8350
        $routes->connect(
            '/fornecedor/dados_gerais/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'dadosGeraisGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        // /api/fornecedor/dados_gerais/
        $routes->connect(
            '/fornecedor/dados_gerais',
            ['controller' => 'Fornecedores', 'action' => 'dadosGeraisPut']
        )->setMethods(['PUT']);

        //FORNECEDORES UNIDADES
        $routes->connect(
            '/fornecedor-unidades',
            ['controller' => 'Fornecedores', 'action' => 'inserirEditarUnidades']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/fornecedor-unidades/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'getUnidades'],
            ['codigo_fornecedor' => '\d+', 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fornecedor-unidades/:codigo_fornecedor/:codigo_unidade',
            ['controller' => 'Fornecedores', 'action' => 'getUnidade'],
            ['codigo_fornecedor' => '\d+', 'codigo_unidade' => '\d+', 'pass' => ['codigo_fornecedor', 'codigo_unidade']]
        )->setMethods(['GET']);

        // /api/fornecedor/responsavel_administrativo/8350
        $routes->connect(
            '/fornecedor/responsavel_administrativo/:codigo_fornecedor',
            ['controller' => 'Fornecedores', 'action' => 'responsavelAdministrativoGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        // /api/fornecedor/responsavel_administrativo/
        $routes->connect(
            '/fornecedor/responsavel_administrativo',
            ['controller' => 'Fornecedores', 'action' => 'responsavelAdministrativoPut']
        )->setMethods(['PUT']);

        // /api/fornecedor/contatos/8350
        $routes->connect(
            '/fornecedor/contatos/:codigo_fornecedor',
            ['controller' => 'FornecedoresContato', 'action' => 'listaGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fornecedor/contatos/:codigo_fornecedor/:codigo_contato',
            ['controller' => 'FornecedoresContato', 'action' => 'contatoGet'],
            ['codigo_fornecedor' => Router::ID, 'codigo_contato' => Router::ID, 'pass' => ['codigo_fornecedor', 'codigo_contato']]
        )->setMethods(['GET']);

        // /api/fornecedor/contatos
        $routes->connect(
            '/fornecedor/contatos/',
            ['controller' => 'FornecedoresContato', 'action' => 'contatoPost']
        )->setMethods(['POST']);

        // /api/fornecedor/contatos
        $routes->connect(
            '/fornecedor/contatos/',
            ['controller' => 'FornecedoresContato', 'action' => 'contatoPut']
        )->setMethods(['PUT']);

        $routes->connect(
            '/fornecedor/contatos_tipos_retorno/',
            ['controller' => 'FornecedoresContato', 'action' => 'getTiposContatoRetorno']
        )->setMethods(['GET']);

        // /api/fornecedor/documentos/8350
        $routes->connect(
            '/fornecedor/documentos/:codigo_fornecedor',
            ['controller' => 'FornecedoresDocumentos', 'action' => 'listaGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fornecedor/documentos',
            ['controller' => 'FornecedoresDocumentos', 'action' => 'uploadPost'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['POST']);

        $routes->connect(
            '/fornecedor/documentos',
            ['controller' => 'FornecedoresDocumentos', 'action' => 'excluirDelete'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/fornecedor/historico/:codigo_fornecedor',
            ['controller' => 'FornecedoresHistorico', 'action' => 'listaGet'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['GET']);

        $routes->connect(
            '/fornecedor/historico/:codigo_fornecedor',
            ['controller' => 'FornecedoresDocumentos', 'action' => 'cadastrarPost'],
            ['codigo_fornecedor' => Router::ID, 'pass' => ['codigo_fornecedor']]
        )->setMethods(['POST']);

        //MODULO GESTÃO DE RISCO
        $routes->connect(
            '/chamado',
            ['controller' => 'LevantamentoChamado', 'action' => 'postChamado']
        )->setMethods(['POST']);

        $routes->connect(
            '/chamado',
            ['controller' => 'LevantamentoChamado', 'action' => 'putChamado']
        )->setMethods(['PUT']);

        $routes->connect(
            '/levantamento/:codigo_cliente/*',
            ['controller' => 'LevantamentoChamado', 'action' => 'view'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/levantamento/cliente/:codigo_cliente/responsavel/:codigo_responsavel',
            ['controller' => 'LevantamentoChamado', 'action' => 'getLevantamentosPorResponsavel'],
            ['codigo_cliente' => Router::ID, 'codigo_responsavel' => Router::ID, 'pass' => ['codigo_cliente', 'codigo_responsavel']]
        )->setMethods(['GET']);

        $routes->connect(
            '/levantamento/:codigo_cliente/:codigo_levantamento_chamado',
            ['controller' => 'LevantamentoChamado', 'action' => 'edit'],
            ['codigo_cliente' => Router::ID, 'codigo_levantamento_chamado' => Router::ID, 'pass' => ['codigo_cliente', 'codigo_levantamento_chamado']]
        )->setMethods(['PUT']);

        $routes->connect(
            '/levantamento/avaliacao',
            ['controller' => 'LevantamentoChamado', 'action' => 'editAvaliacao']
        )->setMethods(['PUT']);

        $routes->connect(
            '/levantamento/all/:codigo_cliente',
            ['controller' => 'LevantamentoChamado', 'action' => 'viewAll'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        //Rotas para Processos
        $routes->connect(
            '/processos/:codigo_processo',
            ['controller' => 'Processos', 'action' => 'edit'],
            ['codigo_processo' => Router::ID, 'pass' => ['codigo_processo']]
        )->setMethods(['PUT', 'PATCH']);

        $routes->connect(
            '/processos/*',
            ['controller' => 'Processos', 'action' => 'view']
        )->setMethods(['GET']);

        $routes->connect(
            '/processos/tipo',
            ['controller' => 'Processos', 'action' => 'getTipos']
        )->setMethods(['GET']);

        //Rotas para Processos ETAPA
        $routes->connect(
            '/processos/etapa',
            ['controller' => 'Processos', 'action' => 'postPutProcessoEtapa']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/processos/:codigo_processo/etapas',
            ['controller' => 'Processos', 'action' => 'getProcessoEtapas'],
            ['codigo_processo' => Router::ID, 'pass' => ['codigo_processo']]
        )->setMethods(['GET']);

        //Rotas para Processos HazOp
        $routes->connect(
            '/processos/hazop',
            ['controller' => 'Processos', 'action' => 'postPutProcessoHazop']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/processos/:codigo_processo/hazop',
            ['controller' => 'Processos', 'action' => 'getProcessoHazop'],
            ['codigo_processo' => Router::ID, 'pass' => ['codigo_processo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/processos/hazop-nos',
            ['controller' => 'Processos', 'action' => 'postPutProcessoHazopNos']
        )->setMethods(['POST', 'PUT']);

        //Rotas para Processos fotos
        $routes->connect(
            '/processos/fotos',
            ['controller' => 'Processos', 'action' => 'postPutFotosProcesso']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/processos/:codigo_processo/fotos/*',
            ['controller' => 'Processos', 'action' => 'getFotosProcessos'],
            ['codigo_processo' => Router::ID, 'pass' => ['codigo_processo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/processos/:codigo_processo/fotos/*',
            ['controller' => 'Processos', 'action' => 'deleteFotosProcessos'],
            ['codigo_processo' => Router::ID, 'pass' => ['codigo_processo']]
        )->setMethods(['DELETE']);

        //Rotas para GHE
        $routes->connect(
            '/ghe',
            ['controller' => 'Ghe', 'action' => 'index']
        )->setMethods(['GET']);

        $routes->connect(
            '/ghe/cliente/:codigo_cliente',
            ['controller' => 'Ghe', 'action' => 'index'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/ghe/:codigo',
            ['controller' => 'Ghe', 'action' => 'view'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/ghe',
            ['controller' => 'Ghe', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/ghe',
            ['controller' => 'Ghe', 'action' => 'edit']
        )->setMethods(['PUT']);

        $routes->connect(
            '/ghe/:codigo',
            ['controller' => 'Ghe', 'action' => 'delete'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        //=====[ROTAS PARA AGENTES DE RISCOS]======
        $routes->connect(
            '/agentes-riscos/etapas',
            ['controller' => 'AgentesRiscos', 'action' => 'addEtapas']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/hazops',
            ['controller' => 'AgentesRiscos', 'action' => 'addHazops']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle-tipo',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutHazopsMedidasControleTipo']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle-tipo',
            ['controller' => 'AgentesRiscos', 'action' => 'getHazopsMedidasControleTipo']
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutHazopsMedidasControle']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle/:codigo_hazop_agente_risco',
            ['controller' => 'AgentesRiscos', 'action' => 'getHazopsMedidasControle'],
            ['codigo_hazop_agente_risco' => Router::ID, 'pass' => ['codigo_hazop_agente_risco']]
        )->setMethods(['GET']);

        //Rotas para Medidas de controle Hazops fotos
        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle/fotos',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutFotosHazopsMedidasControle']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle/:codigo_hazop_medida_controle/fotos/*',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteFotosHazopsMedidasControle'],
            ['codigo_hazop_medida_controle' => Router::ID, 'pass' => ['codigo_hazop_medida_controle']]
        )->setMethods(['DELETE']);

        // Rotas para Hazops - Keyword
        $routes->connect(
            '/agentes-riscos/hazops/keyword-tipo',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutHazopsKeywordTipo']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/keyword-tipo',
            ['controller' => 'AgentesRiscos', 'action' => 'getHazopsKeywordTipo']
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/hazops/keyword-tipo/:codigo',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteHazopsKeywordTipo'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/agentes-riscos/hazops/keyword',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutHazopsKeyword']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/keyword/:codigo_hazop_agente_risco',
            ['controller' => 'AgentesRiscos', 'action' => 'getHazopsKeyword'],
            ['codigo_hazop_agente_risco' => Router::ID, 'pass' => ['codigo_hazop_agente_risco']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle/fotos',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutFotosHazopsMedidasControle']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/hazops/medidas-controle/:codigo',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteHazopsMedidasControle'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/agentes-riscos/hazops',
            ['controller' => 'AgentesRiscos', 'action' => 'putHazopsAgentesRiscos']
        )->setMethods(['PUT']);

        $routes->connect(
            '/agentes-riscos',
            ['controller' => 'AgentesRiscos', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/all',
            ['controller' => 'AgentesRiscos', 'action' => 'postAgentesRiscos']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/:codigo_agente_risco',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteAgentesRiscos'],
            ['codigo_agente_risco' => Router::ID, 'pass' => ['codigo_agente_risco']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/agentes-riscos/all/hazop',
            ['controller' => 'AgentesRiscos', 'action' => 'postAgentesRiscosHazop']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/:codigo_ar_rt',
            ['controller' => 'AgentesRiscos', 'action' => 'getAgentesRiscos'],
            ['codigo_ar_rt' => Router::ID, 'pass' => ['codigo_ar_rt']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/fontes-exposicao',
            ['controller' => 'AgentesRiscos', 'action' => 'getFontesExposixao']
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/descricao',
            ['controller' => 'AgentesRiscos', 'action' => 'postDescricao']
        )->setMethods(['POST']);

        $routes->connect(
            '/agentes-riscos/descricao',
            ['controller' => 'AgentesRiscos', 'action' => 'putDescricao']
        )->setMethods(['PUT']);

        //Rotas para Descrição de riscos fotos
        $routes->connect(
            '/agentes-riscos/descricao/fotos',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutFotosDescricao']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/descricao/:codigo_risco_impacto_selecionado/fotos/*',
            ['controller' => 'AgentesRiscos', 'action' => 'getFotosDescricao'],
            ['codigo_risco_impacto_selecionado' => Router::ID, 'pass' => ['codigo_risco_impacto_selecionado']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/descricao/:codigo_risco_impacto_selecionado/fotos/*',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteFotosDescricao'],
            ['codigo_risco_impacto_selecionado' => Router::ID, 'pass' => ['codigo_risco_impacto_selecionado']]
        )->setMethods(['DELETE']);

        // [INICIO] Selects para tipos de riscos
        $routes->connect(
            '/agentes-riscos/tipos-riscos/:codigo_cliente',
            ['controller' => 'AgentesRiscos', 'action' => 'getRiscosTipo'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/perigos-aspectos/:codigo_cliente',
            ['controller' => 'AgentesRiscos', 'action' => 'getPerigosAspectos'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/riscos-impactos/:codigo_cliente',
            ['controller' => 'AgentesRiscos', 'action' => 'getRiscosImpactos'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        // [FIM] Selects para tipos de riscos

        //Rotas para MEDIDAS DE CONTROLE

        $routes->connect(
            '/agentes-riscos/medidas-controle/hierarquia',
            ['controller' => 'AgentesRiscos', 'action' => 'getMedidasDeControleHierarquia']
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/medidas-controle',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutMedidasControle']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/medidas-controle/:codigo_medida_controle',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteMedidasControle'],
            ['codigo_medida_controle' => Router::ID, 'pass' => ['codigo_medida_controle']]
        )->setMethods(['DELETE']);

        //Rotas para Medidas de controle fotos
        $routes->connect(
            '/agentes-riscos/medidas-controle/fotos',
            ['controller' => 'AgentesRiscos', 'action' => 'postPutFotosMedidasControle']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/agentes-riscos/medidas-controle/:codigo_medida_controle/fotos/*',
            ['controller' => 'AgentesRiscos', 'action' => 'deleteFotosMedidasControle'],
            ['codigo_medida_controle' => Router::ID, 'pass' => ['codigo_medida_controle']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/agentes-riscos/medidas-controle/:codigo_medida_controle/fotos/*',
            ['controller' => 'AgentesRiscos', 'action' => 'getFotosMedidasControle'],
            ['codigo_medida_controle' => Router::ID, 'pass' => ['codigo_medida_controle']]
        )->setMethods(['GET']);

        // FIM Rotas para MEDIDAS DE CONTROLE

        // [QUALIFICAÇÃO]
        //Rotas para FERRAMENTAS DE ANALISE TIPO
        $routes->connect(
            '/qualificacao/metodos-tipo',
            ['controller' => 'Qualificacao', 'action' => 'getMetodosTipo']
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/ferramentas-analise-tipo',
            ['controller' => 'Qualificacao', 'action' => 'postPutFerramentasAnaliseTipo']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/ferramentas-analise-tipo/:codigo_cliente',
            ['controller' => 'Qualificacao', 'action' => 'getFerramentasAnaliseTipo'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/ferramentas-analise-tipo/:codigo',
            ['controller' => 'Qualificacao', 'action' => 'deleteFerramentasAnaliseTipo'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        // Rotas para UNIDADE MEDIÇÃO
        $routes->connect(
            '/qualificacao/unidade-medicao',
            ['controller' => 'Qualificacao', 'action' => 'postPutUnidadeMedicao']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/unidade-medicao',
            ['controller' => 'Qualificacao', 'action' => 'getUnidadesMedicao']
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/unidade-medicao/:codigo',
            ['controller' => 'Qualificacao', 'action' => 'deleteUnidadesMedicao'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        // Rotas para EQUIPAMENTOS INSPEÇÃO TIPO
        $routes->connect(
            '/qualificacao/equipamentos-inspecao-tipo',
            ['controller' => 'Qualificacao', 'action' => 'postPutEquipamentosInspecaoTipo']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/equipamentos-inspecao-tipo/:codigo_cliente',
            ['controller' => 'Qualificacao', 'action' => 'getEquipamentosInspecaoTipo'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/equipamentos-inspecao-tipo/:codigo',
            ['controller' => 'Qualificacao', 'action' => 'deleteEquipamentosInspecaoTipo'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        // Rotas para EQUIPAMENTOS ADOTADOS
        $routes->connect(
            '/qualificacao/equipamentos-adotados',
            ['controller' => 'Qualificacao', 'action' => 'postPutEquipamentosAdotados']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/equipamentos-adotados',
            ['controller' => 'Qualificacao', 'action' => 'getEquipamentosAdotados']
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/equipamentos-adotados/:codigo',
            ['controller' => 'Qualificacao', 'action' => 'deleteEquipamentosAdotados'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        // Rotas para QUALIFICAÇÃO
        $routes->connect(
            '/qualificacao',
            ['controller' => 'Qualificacao', 'action' => 'postPutQualificacao']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/*',
            ['controller' => 'Qualificacao', 'action' => 'getQualificacao']
        )->setMethods(['GET']);

        // Rotas para FERRAMENTAS DE ANALISE
        $routes->connect(
            '/qualificacao/ferramentas-analise',
            ['controller' => 'Qualificacao', 'action' => 'postPutFerramentasAnalise']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/ferramentas-analise',
            ['controller' => 'Qualificacao', 'action' => 'getFerramentasAnalise']
        )->setMethods(['GET']);

        $routes->connect(
            '/qualificacao/ferramentas-analise/:codigo',
            ['controller' => 'Qualificacao', 'action' => 'deleteFerramentasAnalise'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        //Rotas para APRHO
        $routes->connect(
            '/qualificacao/aprho',
            ['controller' => 'Qualificacao', 'action' => 'postPutAprho']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/qualificacao/aprho/*',
            ['controller' => 'Qualificacao', 'action' => 'getAprho']
        )->setMethods(['GET']);

        ############### SWT ################

        //rota da quantidade limite de participantes
        $routes->connect(
            '/swt/meta/grafico/:codigo_unidade',
            ['controller' => 'PosMetas', 'action' => 'grafico'],
            ['codigo_unidade' => '\d+', 'pass' => ['codigo_unidade']]
        )->setMethods(['GET']);

        //rota da quantidade limite de participantes
        $routes->connect(
            '/swt/qtd/participantes/:codigo_unidade',
            ['controller' => 'PosQtdParticipantes', 'action' => 'view'],
            ['codigo_unidade' => '\d+', 'pass' => ['codigo_unidade']]
        )->setMethods(['GET']);

        //rota do get de questoes do formulario
        $routes->connect(
            '/swt/form/:codigo_unidade/:form_tipo',
            ['controller' => 'PosSwtForm', 'action' => 'getQuestionsForm'],
            ['codigo_unidade' => '\d+', 'form_tipo' => '\d+', 'pass' => ['codigo_unidade', 'form_tipo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/swt/form',
            ['controller' => 'PosSwtFormRespondido', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/swt/home',
            ['controller' => 'PosSwtFormRespondido', 'action' => 'getHome']
        )->setMethods(['POST']);

        $routes->connect(
            '/swt/detalhes/:codigo_swt',
            ['controller' => 'PosSwtFormRespondido', 'action' => 'getDetalhes'],
            ['codigo_swt' => '\d+', 'pass' => ['codigo_swt']]
        )->setMethods(['GET']);

        $routes->connect(
            '/swt/observador/ehs/:codigo_unidade',
            ['controller' => 'PosSwtForm', 'action' => 'getDadosObservadorEhs'],
            ['codigo_unidade' => '\d+', 'pass' => ['codigo_unidade']]
        )->setMethods(['GET']);


        //configuracao pelas chaves dos produtos
        $routes->connect(
            '/config/getConfiguracao/*',
            ['controller' => 'Configuracao', 'action' => 'getConfiguracaoChave'],
        )->setMethods(['GET']);

        ############### FIM SWT ################

        ############### PDA ################

        $routes->connect(
            '/pda/test/regra/*',
            ['controller' => 'Test', 'action' => 'testPdaConfigRegra']
        )->setMethods(['GET']);

        $routes->connect(
            '/limpa',
            ['controller' => 'Test', 'action' => 'clear_cache']
        )->setMethods(['GET']);

        $routes->connect(
            '/limpacache',
            ['controller' => 'Test', 'action' => 'limparcache', '_method' => 'GET']
        );

        //Plano de Ação
        $routes->connect(
            '/plano-acao/acoes-pendentes/*',
            ['controller' => 'PlanoAcao', 'action' => 'acoesPendentes']
        )->setMethods(['GET']);

        $routes->connect(
            '/plano-acao/usuario/clientes/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'usuarioClientes'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/pos',
            ['controller' => 'POSController', 'action' => 'getRegisters']
        )->setMethods(['GET']);

        $routes->connect(
            '/acao-melhoria',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAllActions']
        )
            ->setMethods(['GET']);

        $routes->connect(
            '/acao-melhoria/:codigo_acao_melhoria',
            ['controller' => 'AcoesMelhorias', 'action' => 'getActionById']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_acao_melhoria' => '\d+'])
            ->setPass(['codigo_acao_melhoria']);

        $routes->connect(
            '/acao-melhoria/pendentes/:status',
            ['controller' => 'AcoesMelhorias', 'action' => 'getPendingImprovementActions']
        )
            ->setMethods(['GET'])
            ->setPatterns(['status' => '\d+'])
            ->setPass(['status']);

        $routes->connect(
            '/acao-melhoria',
            ['controller' => 'AcoesMelhorias', 'action' => 'postImprovementActions']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/regras_gerais',
            ['controller' => 'AcoesMelhorias', 'action' => 'regrasGeraisAcoes']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/regras_gerais_timer',
            ['controller' => 'AcoesMelhorias', 'action' => 'regrasGeraisAcoesTimer']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/:codigo_acao_melhoria',
            ['controller' => 'AcoesMelhorias', 'action' => 'putImprovementAction']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria' => '\d+'])
            ->setPass(['codigo_acao_melhoria']);

        $routes->connect(
            '/acao-melhoria/:codigo_acao_melhoria',
            ['controller' => 'AcoesMelhorias', 'action' => 'deleteImprovementAction']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria' => '\d+'])
            ->setPass(['codigo_acao_melhoria']);

        $routes->connect(
            '/acao-melhoria/associada/:codigo_acao_melhoria_associada',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAssociationById']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_acao_melhoria_associada' => '\d+'])
            ->setPass(['codigo_acao_melhoria_associada']);

        $routes->connect(
            '/acao-melhoria/associada/:codigo_acao_melhoria_associada',
            ['controller' => 'AcoesMelhorias', 'action' => 'putActionAssociation']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_associada' => '\d+'])
            ->setPass(['codigo_acao_melhoria_associada']);

        $routes->connect(
            '/acao-melhoria/:codigo_acao_melhoria/anexos',
            ['controller' => 'AcoesMelhorias', 'action' => 'getFilesByActionId']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_acao_melhoria' => '\d+'])
            ->setPass(['codigo_acao_melhoria']);

        $routes->connect(
            '/acao-melhoria/anexos',
            ['controller' => 'AcoesMelhorias', 'action' => 'postFile']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/anexos/:codigo_acao_melhoria_anexo',
            ['controller' => 'AcoesMelhorias', 'action' => 'putFile']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_anexo' => '\d+'])
            ->setPass(['codigo_acao_melhoria_anexo']);

        $routes->connect(
            '/acao-melhoria/anexos/:codigo_acao_melhoria_anexo',
            ['controller' => 'AcoesMelhorias', 'action' => 'deleteFile']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria_anexo' => '\d+'])
            ->setPass(['codigo_acao_melhoria_anexo']);

        $routes->connect(
            '/acao-melhoria/status',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAllStatus']
        )
            ->setMethods(['GET']);

        $routes->connect(
            '/acao-melhoria/status',
            ['controller' => 'AcoesMelhorias', 'action' => 'postStatus']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/status/:codigo_acao_melhoria_status',
            ['controller' => 'AcoesMelhorias', 'action' => 'putStatus']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_status' => '\d+'])
            ->setPass(['codigo_acao_melhoria_status']);

        $routes->connect(
            '/acao-melhoria/status/:codigo_acao_melhoria_status',
            ['controller' => 'AcoesMelhorias', 'action' => 'deleteStatus']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria_status' => '\d+'])
            ->setPass(['codigo_acao_melhoria_status']);

        $routes->connect(
            '/acao-melhoria/tipos',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAllTypes']
        )
            ->setMethods(['GET']);

        $routes->connect(
            '/acao-melhoria/tipos/:codigo_cliente',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAllTypes']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_cliente' => '\d+'])
            ->setPass(['codigo_cliente']);

        $routes->connect(
            '/acao-melhoria/tipos',
            ['controller' => 'AcoesMelhorias', 'action' => 'postType']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/tipos/:codigo_acao_melhoria_tipo',
            ['controller' => 'AcoesMelhorias', 'action' => 'putType']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_tipo' => '\d+'])
            ->setPass(['codigo_acao_melhoria_tipo']);

        $routes->connect(
            '/acao-melhoria/tipos/:codigo_acao_melhoria_tipo',
            ['controller' => 'AcoesMelhorias', 'action' => 'deleteType']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria_tipo' => '\d+'])
            ->setPass(['codigo_acao_melhoria_tipo']);

        $routes->connect(
            '/acao-melhoria/criticidades/:codigo_cliente/:codigo_pos_ferramenta',
            ['controller' => 'AcoesMelhorias', 'action' => 'getAllCriticisms']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_cliente' => '\d+', 'codigo_pos_ferramenta' => '\d+'])
            ->setPass(['codigo_cliente', 'codigo_pos_ferramenta']);

        $routes->connect(
            '/acao-melhoria/criticidades',
            ['controller' => 'AcoesMelhorias', 'action' => 'postCriticality']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/criticidades/:codigo_acao_melhoria_criticidade',
            ['controller' => 'AcoesMelhorias', 'action' => 'putCriticality']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_criticidade' => '\d+'])
            ->setPass(['codigo_acao_melhoria_criticidade']);

        $routes->connect(
            '/acao-melhoria/criticidades/:codigo_acao_melhoria_criticidade',
            ['controller' => 'AcoesMelhorias', 'action' => 'deleteCriticality']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria_criticidade' => '\d+'])
            ->setPass(['codigo_acao_melhoria_criticidade']);

        $routes->connect(
            '/acao-melhoria/:codigo_acao_melhoria/solicitacoes',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'getRequestsByActionId']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_acao_melhoria' => '\d+'])
            ->setPass(['codigo_acao_melhoria']);

        $routes->connect(
            '/acao-melhoria/solicitacoes',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'postRequest']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/:codigo_acao_melhoria_solicitacao',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'putRequest']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_acao_melhoria_solicitacao' => '\d+'])
            ->setPass(['codigo_acao_melhoria_solicitacao']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/:codigo_acao_melhoria_solicitacao',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'deleteRequest']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_acao_melhoria_solicitacao' => '\d+'])
            ->setPass(['codigo_acao_melhoria_solicitacao']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/tipos',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'geAllRequestTypes']
        )
            ->setMethods(['GET']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/tipos',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'postRequestType']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/tipos/:codigo_tipo',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'putRequestType']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_tipo' => '\d+'])
            ->setPass(['codigo_tipo']);

        $routes->connect(
            '/acao-melhoria/solicitacoes/tipos/:codigo_tipo',
            ['controller' => 'AcoesMelhoriasSolicitacoes', 'action' => 'deleteRequestType']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_tipo' => '\d+'])
            ->setPass(['codigo_tipo']);

        $routes->connect(
            '/origem-ferramenta',
            ['controller' => 'OrigemFerramentas', 'action' => 'getAllOriginToolsByClient']
        )
            ->setMethods(['GET']);

        $routes->connect(
            '/origem-ferramenta/cliente/:codigo_cliente',
            ['controller' => 'OrigemFerramentas', 'action' => 'getAllOriginToolsByClient']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_cliente' => '\d+'])
            ->setPass(['codigo_cliente']);

        $routes->connect(
            '/origem-ferramenta/:codigo_origem_ferramenta',
            ['controller' => 'OrigemFerramentas', 'action' => 'getOriginToolById']
        )
            ->setMethods(['GET'])
            ->setPatterns(['codigo_origem_ferramenta' => '\d+'])
            ->setPass(['codigo_origem_ferramenta']);

        $routes->connect(
            '/origem-ferramenta',
            ['controller' => 'OrigemFerramentas', 'action' => 'postOriginTool']
        )
            ->setMethods(['POST']);

        $routes->connect(
            '/origem-ferramenta/:codigo_origem_ferramenta',
            ['controller' => 'OrigemFerramentas', 'action' => 'putOriginTool']
        )
            ->setMethods(['PUT'])
            ->setPatterns(['codigo_origem_ferramenta' => '\d+'])
            ->setPass(['codigo_origem_ferramenta']);

        $routes->connect(
            '/origem-ferramenta/:codigo_origem_ferramenta',
            ['controller' => 'OrigemFerramentas', 'action' => 'deleteOriginTool']
        )
            ->setMethods(['DELETE'])
            ->setPatterns(['codigo_origem_ferramenta' => '\d+'])
            ->setPass(['codigo_origem_ferramenta']);

        //Rotas para usuario função (Gestão de risco)
        $routes->connect(
            '/usuario/funcao',
            ['controller' => 'Usuario', 'action' => 'postPutUsuarioFuncao']
        )->setMethods(['POST', 'PUT']);

        $routes->connect(
            '/usuario/funcao-tipo',
            ['controller' => 'Usuario', 'action' => 'getFuncaoTipo']
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/meus-riscos/:codigo_usuario',
            ['controller' => 'AgentesRiscos', 'action' => 'getUsuarioMeusRiscos'],
            ['codigo_usuario' => Router::ID, 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/tecnico-ehs/:codigo_cliente',
            ['controller' => 'Usuario', 'action' => 'getUsuarioTecnicoSegurancaoEHS'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/gestor-operacao/:codigo_cliente',
            ['controller' => 'Usuario', 'action' => 'getUsuarioGestorOperacao'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/funcao/cliente/:codigo_cliente',
            ['controller' => 'Usuario', 'action' => 'getUsuarioByCliente'],
            ['codigo_cliente' => Router::ID, 'pass' => ['codigo_cliente']]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/meu-time/:codigo_usuario',
            ['controller' => 'Usuario', 'action' => 'getUsuarioMeuTime'],
            ['codigo_usuario' => Router::ID, 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/usuario/meu-time/riscos/:codigo_usuario',
            ['controller' => 'AgentesRiscos', 'action' => 'getMeuTimeRiscos'],
            ['codigo_usuario' => Router::ID, 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agentes-riscos/assinatura',
            ['controller' => 'AgentesRiscos', 'action' => 'pustUsuarioAgenteRisco']
        )->setMethods(['POST']);

        $routes->connect(
            '/mensagens/usuario/:codigo_usuario',
            ['controller' => 'Mensagens', 'action' => 'index'],
            ['codigo_usuario' => Router::ID, 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/mensagens/:codigo',
            ['controller' => 'Mensagens', 'action' => 'view'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/mensagens',
            ['controller' => 'Mensagens', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/mensagens',
            ['controller' => 'Mensagens', 'action' => 'edit']
        )->setMethods(['PUT']);

        $routes->connect(
            '/mensagens/:codigo',
            ['controller' => 'Mensagens', 'action' => 'delete'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/agendas/usuario/:codigo_usuario',
            ['controller' => 'Agendas', 'action' => 'index'],
            ['codigo_usuario' => Router::ID, 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agendas/:codigo',
            ['controller' => 'Agendas', 'action' => 'view'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/agendas',
            ['controller' => 'Agendas', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/agendas',
            ['controller' => 'Agendas', 'action' => 'edit']
        )->setMethods(['PUT']);

        $routes->connect(
            '/agendas/:codigo',
            ['controller' => 'Agendas', 'action' => 'delete'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        $routes->connect(
            '/acoes-melhorias',
            ['controller' => 'ChamadosMelhorias', 'action' => 'index']
        )->setMethods(['GET']);

        $routes->connect(
            '/acoes-melhorias/:codigo',
            ['controller' => 'ChamadosMelhorias', 'action' => 'view'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['GET']);

        $routes->connect(
            '/acoes-melhorias',
            ['controller' => 'ChamadosMelhorias', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/acoes-melhorias',
            ['controller' => 'ChamadosMelhorias', 'action' => 'edit']
        )->setMethods(['PUT']);

        $routes->connect(
            '/acoes-melhorias/:codigo',
            ['controller' => 'ChamadosMelhorias', 'action' => 'delete'],
            ['codigo' => Router::ID, 'pass' => ['codigo']]
        )->setMethods(['DELETE']);

        // MAPA COVID

        $routes->connect(
            '/mapa-covid/navegacao/:codigo_usuario',
            ['controller' => 'MapaCovid', 'action' => 'obterMapa', '_method' => 'POST'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/mapa-covid/onboarding/:codigo_usuario',
            ['controller' => 'MapaCovid', 'action' => 'obterOnBoard', '_method' => 'GET'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        $routes->connect(
            '/mapa-covid/pesquisa/:codigo_usuario',
            ['controller' => 'MapaCovid', 'action' => 'obterPesquisa', '_method' => 'POST'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        );

        // LOCALIDADES

        // localidades/paises                               | GET | Obter lista de Países

        // localidades/estados                              | GET | Obter lista de Estados
        // localidades/estados/{`codigo`}                   | GET | Obter um estado por codigo
        // localidades/estados?q={`argumento`}              | GET | Ira buscar por nome ou descrição, usado para campos autocompletar

        // localidades/cidades                              | GET | Obter cidades
        // localidades/cidades/{`codigo`}                   | GET | Obter uma cidade
        // localidades/cidades?codigo_estado={`codigo`}     | GET | Obter cidades de um estado
        // localidades/cidades?q={`argumento`}              | GET | Obter cidades por nome ou descricao de acordo com o argumento preenchido

        // localidades/endereco?cep={`cep`}                 | GET | Obter informações de um cep
        // localidades/endereco?logradouro={`Av Paulista`}  | GET | Obter informações de um endereço
        // localidades/endereco?lat={`lat`}&long={`long`}   | GET | Obter informações de um endereço por lat long

        $routes->connect(
            '/localidades/paises',
            ['controller' => 'Localidades', 'action' => 'obterPaises']
        )->setMethods(['GET']);

        $routes->connect(
            '/localidades/estados',
            ['controller' => 'Localidades', 'action' => 'obterEstados']
        )->setMethods(['GET'])
            ->setPatterns([
                'q' => '[a-z]',
            ]);

        $routes->connect(
            '/localidades/estados/:codigo_estado',
            ['controller' => 'Localidades', 'action' => 'obterEstado']
        )->setMethods(['GET'])
            ->setPatterns(['codigo_estado' => '\d+'])
            ->setPass(['codigo_estado']);

        $routes->connect(
            '/localidades/cidades',
            ['controller' => 'Localidades', 'action' => 'obterCidades']
        )->setMethods(['GET'])
            ->setPatterns([
                'q' => '[a-z]',
                'codigo_estado' => '\d+',
            ]);

        $routes->connect(
            '/localidades/cidades/:codigo_cidade',
            ['controller' => 'Localidades', 'action' => 'obterCidade']
        )->setMethods(['GET'])
            ->setPatterns(['codigo_cidade' => '\d+'])
            ->setPass(['codigo_cidade']);

        $routes->connect(
            '/localidades/bairros',
            ['controller' => 'Localidades', 'action' => 'obterBairros']
        )->setMethods(['GET'])
            ->setPatterns([
                'q' => '[a-z]',
                'codigo_cidade' => '\d+',
            ]);

        $routes->connect(
            '/localidades/bairros/:codigo_bairro',
            ['controller' => 'Localidades', 'action' => 'obterBairro']
        )->setMethods(['GET'])
            ->setPatterns(['codigo_bairro' => '\d+'])
            ->setPass(['codigo_bairro']);
        # THERMAL-CARE - ROTAS
        # endpoints relacionados ao aplicativo Thermal-Care
        # /api/thermal-care/<:controller>
        #########################################################

        // GET api/thermal-care/status
        $routes->connect(
            '/thermal-care/status',
            ['controller' => 'ThermalCareStatus', 'action' => 'index']
        )->setMethods(['GET']);

        // GET api/thermal-care/usuario/:codigo_usuario
        $routes->connect(
            '/thermal-care/usuario/:codigo_usuario',
            ['controller' => 'ThermalCareUsuario', 'action' => 'obterUsuario'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // GET api/thermal-care/triagem/medicoes/:codigo_usuario
        // GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020
        // GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020&mes=12
        // GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020&mes=12&dia=15
        // GET api/thermal-care/triagem/medicoes/:codigo_usuario? ... limit=20
        // GET api/thermal-care/triagem/medicoes/:codigo_usuario? ... limit=20&page=2
        $routes->connect(
            '/thermal-care/triagem/medicoes/:codigo_usuario',
            ['controller' => 'ThermalCareTriagem', 'action' => 'obterListagemMedicoes'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // GET api/thermal-care/triagem/parametros/:codigo_usuario
        $routes->connect(
            '/thermal-care/triagem/parametros/:codigo_usuario',
            ['controller' => 'ThermalCareTriagem', 'action' => 'obterParametrosAlertas'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // POST api/thermal-care/triagem/medicoes/:codigo_usuario
        $routes->connect(
            '/thermal-care/triagem/medicoes/:codigo_usuario',
            ['controller' => 'ThermalCareTriagem', 'action' => 'registrarMedicao'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['POST']);

        // GET api/thermal-care/funcionario
        // api/thermal-care/funcionario/:codigo_usuario?q=888.888.888-88
        // api/thermal-care/funcionario/:codigo_usuario?q=88888888888
        $routes->connect(
            '/thermal-care/funcionario/:codigo_usuario',
            ['controller' => 'ThermalCareFuncionario', 'action' => 'buscarFuncionarioPorCpfOuNome'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // GET api/thermal-care/menu
        $routes->connect(
            '/thermal-care/menu/:codigo_usuario',
            ['controller' => 'ThermalCareMenu', 'action' => 'obterLista'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);

        // GET api/thermal-care/home/:codigo_usario
        $routes->connect(
            '/thermal-care/home/:codigo_usuario',
            ['controller' => 'ThermalCareHome', 'action' => 'index'],
            ['codigo_usuario' => '\d+', 'pass' => ['codigo_usuario']]
        )->setMethods(['GET']);


        # OBSERVADOR EHS - ROTAS
        # endpoints relacionados ao aplicativo Observador
        # /api/observador/<:controller>
        #########################################################

        // GET api/observador/tipos-observacao                             | GET | Obter tipos de observações
        // GET api/observador/tipos-observacao?q={`argumento`}             | GET | Obter tipos por nome ou descricao de acordo com o argumento preenchido
        $routes->connect(
            '/observador/tipos-observacao',
            [
                'controller' => 'PosObsObservacoesTipos',
                'action' => 'obterLista'
            ]
        )->setMethods(['GET'])
            ->setPatterns([
                'codigo_unidade' => '\d+',
                'q' => '[a-z]'
            ])
            ->setPass(['codigo_unidade', 'q']);

        // GET api/observador/observacoes                                   | GET | Obter observações
        // GET api/observador/observacoes?status={`argumento`}              | GET | Obter registros por situações, ex. observações aguardando análise
        $routes->connect(
            '/observador/observacoes',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'obterLista'
            ]
        )->setMethods(['GET'])
            ->setPatterns([
                'codigo_unidade' => '\d+',
                'q' => '[a-z]',
                'status' => '\d+'
            ])
            ->setPass(['codigo_unidade', 'q', 'status']);

        $routes->connect(
            '/observador/observacoes-lista',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'obterListagem'
            ]
        )->setMethods(['GET']);

        $routes->connect(
            '/observador/observacoes-timer',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'obterListaTimer'
            ]
        )->setMethods(['GET'])
            ->setPatterns([
                'codigo_unidade' => '\d+',
                'q' => '[a-z]',
                'status' => '\d+'
            ])
            ->setPass(['codigo_unidade', 'q', 'status']);

        // GET api/observador/observacoes/:codigo_observacao                | GET | Obter uma observação por código
        $routes->connect(
            '/observador/observacoes/:codigo_observacao',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'obter'
            ]
        )->setMethods(['GET'])
            ->setPatterns([
                'codigo_observacao' => '\d+'
            ])
            ->setPass(['codigo_observacao']);

        // POST api/observador/observacoes                                  | POST | Salvar observação
        $routes->connect(
            '/observador/observacoes',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'salvar'
            ]
        )->setMethods(['POST']);

        // DELETE api/observador/observacoes/:codigo_observacao             | DELETE | Cancelar observação
        $routes->connect(
            '/observador/observacoes/:codigo_observacao',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'cancelar'
            ]
        )->setMethods(['DELETE'])
            ->setPatterns([
                'codigo_observacao' => '\d+'
            ])
            ->setPass(['codigo_observacao']);

        // PUT api/observador/observacoes/:codigo_observacao                | PUT | Alterar observação
        $routes->connect(
            '/observador/observacoes/:codigo_observacao',
            [
                'controller' => 'PosObsObservacoes',
                'action' => 'salvar'
            ]
        )->setMethods(['PUT'])
            ->setPatterns([
                'codigo_observacao' => '\d+'
            ])
            ->setPass(['codigo_observacao']);

        // POST api/observador/classificacoes-risco                         | POST | Salvar Classificação de risco
        $routes->connect(
            '/observador/classificacoes-risco',
            [
                'controller' => 'PosObsClassificacoesRiscos',
                'action' => 'salvar'
            ]
        )->setMethods(['POST']);

        // GET api/pos/configuracoes/?codigo_ferramenta=3&codigo_cliente=777 | GET | Obter Configurações
        $routes->connect(
            '/pos/configuracoes',
            [
                'controller' => 'PosConfiguracao',
                'action'     => 'obter'
            ]
        )->setMethods(['GET']);

        // GET api/pos/obs/locais/?codigo_cliente=10011 | GET | Obter Locais de observação
        $routes->connect(
            '/pos/obs/locais',
            [
                'controller' => 'PosObsLocal',
                'action'     => 'obter'
            ]
        )->setMethods(['GET']);


        ############################ESOCIAL############################

        //enviar certificado para a tecnospeed
        $routes->connect(
            '/esocial/enviar_certificado',
            ['controller' => 'MensageriaEsocial', 'action' => 'setEnviarCertificado']
        )->setMethods(['POST']);


        #############################FIM ESOCIAL############################

        #############################CONCILIACAO DUPLICATAS CLIENTES########

        $routes->connect(
            '/cliente_ds/conciliacao_duplicatas',
            [
                'controller' => 'ClienteDsConciliacao',
                'action'     => 'conciliacaoDuplicatas'
            ]
        )->setMethods(['GET']);

        $routes->connect(
            '/cliente_bu/conciliacao_duplicatas',
            [
                'controller' => 'ClienteBuConciliacao',
                'action'     => 'conciliacaoDuplicatas'
            ]
        )->setMethods(['GET']);

        $routes->connect(
            '/cliente_opco/conciliacao_duplicatas',
            [
                'controller' => 'ClienteOpcoConciliacao',
                'action'     => 'conciliacaoDuplicatas'
            ]
        )->setMethods(['GET']);

        ##########################FIM CONCILIACAO DUPLICATAS CLIENTES########
    });

    $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));

    $routes->applyMiddleware('csrf');
});
