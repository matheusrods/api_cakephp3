<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnexosExamesLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnexosExamesLogTable Test Case
 */
class AnexosExamesLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnexosExamesLogTable
     */
    public $AnexosExamesLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AnexosExamesLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AnexosExamesLog') ? [] : ['className' => AnexosExamesLogTable::class];
        $this->AnexosExamesLog = TableRegistry::getTableLocator()->get('AnexosExamesLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnexosExamesLog);

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
