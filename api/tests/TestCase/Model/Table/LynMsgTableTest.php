<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LynMsgTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LynMsgTable Test Case
 */
class LynMsgTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LynMsgTable
     */
    public $LynMsg;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LynMsg',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LynMsg') ? [] : ['className' => LynMsgTable::class];
        $this->LynMsg = TableRegistry::getTableLocator()->get('LynMsg', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LynMsg);

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
