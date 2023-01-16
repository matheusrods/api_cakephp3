<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedicamentosTable;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * App\Model\Table\MedicamentosTable Test Case
 */
class MedicamentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedicamentosTable
     */
    public $Medicamentos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Medicamentos',
        'app.Funcionarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Medicamentos') ? [] : ['className' => MedicamentosTable::class];
//        $connection = ConnectionManager::get('test');
//        $time = new Time('2020-11-19');
//        $timeexpiracao = new Time('2020-12-19');
//        $results = $connection->execute('
//    drop table usuario;
//    create table usuario
//(
//    codigo                         int identity
//        primary key,
//    nome                           nvarchar(256)            not null,
//    apelido                        nvarchar(256)            not null,
//    senha                          nvarchar(172)  default NULL,
//    email                          nvarchar(1000) default NULL,
//    ativo                          bit            default 0,
//    data_inclusao                  datetime                 not null,
//    codigo_usuario_inclusao        int                      not null,
//    codigo_uperfil                 int            default NULL,
//    alerta_portal                  bit            default 0 not null,
//    alerta_email                   bit            default 0 not null,
//    alerta_sms                     bit            default 0 not null,
//    celular                        nvarchar(12)   default NULL,
//    token                          nvarchar(172)  default NULL,
//    fuso_horario                   int            default NULL,
//    horario_verao                  bit            default 0,
//    cracha                         int            default NULL,
//    data_senha_expiracao           datetime       default NULL,
//    admin                          bit            default 0,
//    codigo_usuario_alteracao       int            default NULL,
//    data_alteracao                 datetime       default NULL,
//    codigo_usuario_pai             int            default NULL,
//    restringe_base_cnpj            smallint       default NULL,
//    codigo_cliente                 int            default NULL,
//    codigo_departamento            smallint                 not null,
//    codigo_filial                  int            default NULL,
//    codigo_proposta_credenciamento int            default NULL,
//    codigo_fornecedor              int            default NULL,
//    codigo_empresa                 int            default NULL,
//    usuario_dados_id               int            default NULL,
//    codigo_funcionario             int            default NULL,
//    usuario_multi_empresa          bit            default 0,
//    codigo_corretora               int            default NULL,
//    alerta_sm_usuario              bit            default 0
//)
//');
//        $connection->execute('
//            insert into usuario values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,? ,?,?,?,?,?,?,?,?,?,?,?)', [
//            'Jose',
//             'jose',
//             'qNylvPIC1B/r0RzWDs/9s5NGqRmNNNvd5bMif5zD8duFHrhgy20L7Ohhd8a0cldSNooqXgomjaKTaHl/zkQtTXkk1uhxdPKN7daI6a7oL2n7MHts77n2MOgOe5EUaacwPpi6qZCzLuNMM9u4daFV+ejCG7mnZ8tt3DGaOgdcmxo=',
//             'jose@dominio.com',
//             1,
//           '2020-06-09 00:00:00',
//             1,
//            1,
//             1,
//            1,
//          1,
//           'Lorem ipsu',
//           'sdyrty34wter$n13',
//           1,
//            1,
//           1,
//            '2020-06-09 00:00:00',
//           1,
//            1,
//            '2020-06-09 00:00:00',
//           1,
//           1,
//            1,
//           1,
//           1,
//            1,
//            1,
//            1,
//            1,
//            1,
//           1,
//           1,
//            1
//        ]);

        $this->Medicamentos = TableRegistry::getTableLocator()->get('Medicamentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Medicamentos);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertEquals(
            'codigo',
            $this->Medicamentos->getPrimaryKey(),
            'The [App]Table default primary key is expected to be `codigo`.'
        );

    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $validator = new Validator();
        $validator = $this->Medicamentos->validationDefault($validator);
        $this->assertTrue($validator->hasField('codigo'));
        $this->assertTrue($validator->hasField('descricao'));
        $this->assertTrue($validator->hasField('principio_ativo'));
        $this->assertTrue($validator->hasField('codigo_laboratorio'));
        $this->assertTrue($validator->hasField('codigo_barras'));
        $this->assertTrue($validator->hasField('data_inclusao'));
        $this->assertTrue($validator->hasField('codigo_usuario_inclusao'));
        $this->assertTrue($validator->hasField('ativo'));
        $this->assertTrue($validator->hasField('codigo_empresa'));
        $this->assertTrue($validator->hasField('codigo_apresentacao'));
        $this->assertTrue($validator->hasField('posologia'));
    }

    public function testMedicamentosData() {
        $time = new Time('2020-11-03');
        $query = $this->Medicamentos->find();
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'codigo' => 1,
                'descricao' => 'CEWIN',
                'principio_ativo' => 'ÁCIDO ASCÓRBICO',
                'codigo_laboratorio' => 1,
                'codigo_barras' => null,
                'data_inclusao' => $time,
                'codigo_usuario_inclusao' => 1,
                'ativo' => true,
                'codigo_empresa' => 1,
                'codigo_apresentacao' => 1,
                'posologia' => '1 G'
            ],
            [
                'codigo' => 2,
                'descricao' => 'CEWIN',
                'principio_ativo' => 'ÁCIDO ASCÓRBICO',
                'codigo_laboratorio' => 1,
                'codigo_barras' => null,
                'data_inclusao' => $time,
                'codigo_usuario_inclusao' => 1,
                'ativo' => true,
                'codigo_empresa' => 1,
                'codigo_apresentacao' => 1,
                'posologia' => '2 G'
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
