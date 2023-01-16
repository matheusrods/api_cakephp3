<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LynMenuTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LynMenuTable Test Case
 */
class LynMenuTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LynMenuTable
     */
    public $LynMenu;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LynMenu',
        'app.Cliente',
        'app.ClienteLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LynMenu') ? [] : ['className' => LynMenuTable::class];
        $this->LynMenu = TableRegistry::getTableLocator()->get('LynMenu', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LynMenu);

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
