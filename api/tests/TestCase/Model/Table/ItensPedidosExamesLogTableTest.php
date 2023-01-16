<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ItensPedidosExamesLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ItensPedidosExamesLogTable Test Case
 */
class ItensPedidosExamesLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ItensPedidosExamesLogTable
     */
    public $ItensPedidosExamesLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ItensPedidosExamesLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ItensPedidosExamesLog') ? [] : ['className' => ItensPedidosExamesLogTable::class];
        $this->ItensPedidosExamesLog = TableRegistry::getTableLocator()->get('ItensPedidosExamesLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ItensPedidosExamesLog);

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
