<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsAgentesRiscosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsAgentesRiscosTable Test Case
 */
class HazopsAgentesRiscosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsAgentesRiscosTable
     */
    public $HazopsAgentesRiscos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsAgentesRiscos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsAgentesRiscos') ? [] : ['className' => HazopsAgentesRiscosTable::class];
        $this->HazopsAgentesRiscos = TableRegistry::getTableLocator()->get('HazopsAgentesRiscos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsAgentesRiscos);

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
