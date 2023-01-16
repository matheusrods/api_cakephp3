<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LogsIntegracoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\LogsIntegracoesTable Test Case
 */
class LogsIntegracoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LogsIntegracoesTable
     */
    public static $LogsIntegracoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LogsIntegracoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
//        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LogsIntegracoes') ? [] : ['className' => LogsIntegracoesTable::class];
        self::$LogsIntegracoes = TableRegistry::getTableLocator()->get('LogsIntegracoes', $config);
    }

    /**
     * tearDown method
     * @depends MedicamentosControllerTest::testGetMedicamentos
     * @return void
     */
    public static function tearDownAfterClass()
    {
//        unset($LogsIntegracoes);
        self::$LogsIntegracoes = null;
//        parent::tearDown();
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
            self::$LogsIntegracoes->getPrimaryKey(),
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
        $validator = self::$LogsIntegracoes->validationDefault($validator);
        $this->assertTrue($validator->hasField('codigo'));
        $this->assertTrue($validator->hasField('arquivo'));
        $this->assertTrue($validator->hasField('conteudo'));
        $this->assertTrue($validator->hasField('retorno'));
        $this->assertTrue($validator->hasField('data_inclusao'));
        $this->assertTrue($validator->hasField('sistema_origem'));
        $this->assertTrue($validator->hasField('status'));
        $this->assertTrue($validator->hasField('descricao'));
        $this->assertTrue($validator->hasField('tipo_operacao'));
        $this->assertTrue($validator->hasField('reprocessado'));
        $this->assertTrue($validator->hasField('finalizado'));
        $this->assertTrue($validator->hasField('data_arquivo'));
    }
}
