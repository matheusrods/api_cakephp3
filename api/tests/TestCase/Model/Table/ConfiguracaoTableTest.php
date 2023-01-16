<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfiguracaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfiguracaoTable Test Case
 */
class ConfiguracaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfiguracaoTable
     */
    public $Configuracao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Configuracao'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Configuracao') ? [] : ['className' => ConfiguracaoTable::class];
        $this->Configuracao = TableRegistry::getTableLocator()->get('Configuracao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Configuracao);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
