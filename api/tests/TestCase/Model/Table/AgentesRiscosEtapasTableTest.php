<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosEtapasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosEtapasTable Test Case
 */
class AgentesRiscosEtapasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosEtapasTable
     */
    public $AgentesRiscosEtapas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscosEtapas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscosEtapas') ? [] : ['className' => AgentesRiscosEtapasTable::class];
        $this->AgentesRiscosEtapas = TableRegistry::getTableLocator()->get('AgentesRiscosEtapas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscosEtapas);

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
