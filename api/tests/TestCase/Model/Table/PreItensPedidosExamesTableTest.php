<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PreItensPedidosExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PreItensPedidosExamesTable Test Case
 */
class PreItensPedidosExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PreItensPedidosExamesTable
     */
    public $PreItensPedidosExames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PreItensPedidosExames',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PreItensPedidosExames') ? [] : ['className' => PreItensPedidosExamesTable::class];
        $this->PreItensPedidosExames = TableRegistry::getTableLocator()->get('PreItensPedidosExames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PreItensPedidosExames);

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
