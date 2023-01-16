<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GruposEconomicosClientesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GruposEconomicosClientesTable Test Case
 */
class GruposEconomicosClientesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GruposEconomicosClientesTable
     */
    public $GruposEconomicosClientes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GruposEconomicosClientes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GruposEconomicosClientes') ? [] : ['className' => GruposEconomicosClientesTable::class];
        $this->GruposEconomicosClientes = TableRegistry::getTableLocator()->get('GruposEconomicosClientes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GruposEconomicosClientes);

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
