<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsNosAgentesRiscosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsNosAgentesRiscosTable Test Case
 */
class HazopsNosAgentesRiscosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsNosAgentesRiscosTable
     */
    public $HazopsNosAgentesRiscos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsNosAgentesRiscos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsNosAgentesRiscos') ? [] : ['className' => HazopsNosAgentesRiscosTable::class];
        $this->HazopsNosAgentesRiscos = TableRegistry::getTableLocator()->get('HazopsNosAgentesRiscos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsNosAgentesRiscos);

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
