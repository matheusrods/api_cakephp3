<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItensPedidosExamesBaixaLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItensPedidosExamesBaixaLogTable Test Case
 */
class ItensPedidosExamesBaixaLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ItensPedidosExamesBaixaLogTable
     */
    public $ItensPedidosExamesBaixaLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ItensPedidosExamesBaixaLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ItensPedidosExamesBaixaLog') ? [] : ['className' => ItensPedidosExamesBaixaLogTable::class];
        $this->ItensPedidosExamesBaixaLog = TableRegistry::getTableLocator()->get('ItensPedidosExamesBaixaLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ItensPedidosExamesBaixaLog);

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
