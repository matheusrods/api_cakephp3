<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LynMenuClienteLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LynMenuClienteLogTable Test Case
 */
class LynMenuClienteLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LynMenuClienteLogTable
     */
    public $LynMenuClienteLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LynMenuClienteLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LynMenuClienteLog') ? [] : ['className' => LynMenuClienteLogTable::class];
        $this->LynMenuClienteLog = TableRegistry::getTableLocator()->get('LynMenuClienteLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LynMenuClienteLog);

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
