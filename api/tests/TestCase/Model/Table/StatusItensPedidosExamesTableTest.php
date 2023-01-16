<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatusItensPedidosExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatusItensPedidosExamesTable Test Case
 */
class StatusItensPedidosExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StatusItensPedidosExamesTable
     */
    public $StatusItensPedidosExames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.StatusItensPedidosExames',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('StatusItensPedidosExames') ? [] : ['className' => StatusItensPedidosExamesTable::class];
        $this->StatusItensPedidosExames = TableRegistry::getTableLocator()->get('StatusItensPedidosExames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StatusItensPedidosExames);

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
