<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosClientesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosClientesTable Test Case
 */
class AgentesRiscosClientesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosClientesTable
     */
    public $AgentesRiscosClientes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscosClientes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscosClientes') ? [] : ['className' => AgentesRiscosClientesTable::class];
        $this->AgentesRiscosClientes = TableRegistry::getTableLocator()->get('AgentesRiscosClientes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscosClientes);

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
