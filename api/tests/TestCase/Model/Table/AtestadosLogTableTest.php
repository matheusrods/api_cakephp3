<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AtestadosLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AtestadosLogTable Test Case
 */
class AtestadosLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AtestadosLogTable
     */
    public $AtestadosLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AtestadosLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AtestadosLog') ? [] : ['className' => AtestadosLogTable::class];
        $this->AtestadosLog = TableRegistry::getTableLocator()->get('AtestadosLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AtestadosLog);

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
