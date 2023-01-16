<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosTable Test Case
 */
class AgentesRiscosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosTable
     */
    public $AgentesRiscos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscos') ? [] : ['className' => AgentesRiscosTable::class];
        $this->AgentesRiscos = TableRegistry::getTableLocator()->get('AgentesRiscos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscos);

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
