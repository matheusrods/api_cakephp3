<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LynMenuClienteTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LynMenuClienteTable Test Case
 */
class LynMenuClienteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\LynMenuClienteTable
     */
    public $LynMenuCliente;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.LynMenuCliente',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LynMenuCliente') ? [] : ['className' => LynMenuClienteTable::class];
        $this->LynMenuCliente = TableRegistry::getTableLocator()->get('LynMenuCliente', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LynMenuCliente);

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
