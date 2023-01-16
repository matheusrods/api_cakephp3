<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LynMsgAceiteTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LynMsgAceiteTable Test Case
 */
class LynMsgAceiteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LynMsgAceiteTable
     */
    public $LynMsgAceite;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LynMsgAceite',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LynMsgAceite') ? [] : ['className' => LynMsgAceiteTable::class];
        $this->LynMsgAceite = TableRegistry::getTableLocator()->get('LynMsgAceite', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LynMsgAceite);

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
