<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosStatusTable Test Case
 */
class ProcessosStatusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosStatusTable
     */
    public $ProcessosStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosStatus',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosStatus') ? [] : ['className' => ProcessosStatusTable::class];
        $this->ProcessosStatus = TableRegistry::getTableLocator()->get('ProcessosStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosStatus);

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
