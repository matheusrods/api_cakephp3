<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlertasAgrupamentoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertasAgrupamentoTable Test Case
 */
class AlertasAgrupamentoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AlertasAgrupamentoTable
     */
    public $AlertasAgrupamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AlertasAgrupamento',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AlertasAgrupamento') ? [] : ['className' => AlertasAgrupamentoTable::class];
        $this->AlertasAgrupamento = TableRegistry::getTableLocator()->get('AlertasAgrupamento', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AlertasAgrupamento);

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
