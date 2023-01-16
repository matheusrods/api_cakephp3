<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PrePedidosExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PrePedidosExamesTable Test Case
 */
class PrePedidosExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PrePedidosExamesTable
     */
    public $PrePedidosExames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PrePedidosExames',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PrePedidosExames') ? [] : ['className' => PrePedidosExamesTable::class];
        $this->PrePedidosExames = TableRegistry::getTableLocator()->get('PrePedidosExames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PrePedidosExames);

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
