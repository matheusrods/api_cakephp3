<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosTipoMetodosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosTipoMetodosTable Test Case
 */
class AgentesRiscosTipoMetodosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosTipoMetodosTable
     */
    public $AgentesRiscosTipoMetodos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscosTipoMetodos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscosTipoMetodos') ? [] : ['className' => AgentesRiscosTipoMetodosTable::class];
        $this->AgentesRiscosTipoMetodos = TableRegistry::getTableLocator()->get('AgentesRiscosTipoMetodos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscosTipoMetodos);

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
