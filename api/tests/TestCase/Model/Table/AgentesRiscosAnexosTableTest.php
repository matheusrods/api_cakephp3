<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosAnexosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosAnexosTable Test Case
 */
class AgentesRiscosAnexosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosAnexosTable
     */
    public $AgentesRiscosAnexos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscosAnexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscosAnexos') ? [] : ['className' => AgentesRiscosAnexosTable::class];
        $this->AgentesRiscosAnexos = TableRegistry::getTableLocator()->get('AgentesRiscosAnexos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscosAnexos);

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
