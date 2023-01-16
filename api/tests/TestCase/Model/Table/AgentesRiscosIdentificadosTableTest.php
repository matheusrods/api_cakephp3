<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgentesRiscosIdentificadosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgentesRiscosIdentificadosTable Test Case
 */
class AgentesRiscosIdentificadosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AgentesRiscosIdentificadosTable
     */
    public $AgentesRiscosIdentificados;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AgentesRiscosIdentificados',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AgentesRiscosIdentificados') ? [] : ['className' => AgentesRiscosIdentificadosTable::class];
        $this->AgentesRiscosIdentificados = TableRegistry::getTableLocator()->get('AgentesRiscosIdentificados', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AgentesRiscosIdentificados);

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
