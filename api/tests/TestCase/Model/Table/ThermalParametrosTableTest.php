<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThermalParametrosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThermalParametrosTable Test Case
 */
class ThermalParametrosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ThermalParametrosTable
     */
    public $ThermalParametros;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ThermalParametros',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ThermalParametros') ? [] : ['className' => ThermalParametrosTable::class];
        $this->ThermalParametros = TableRegistry::getTableLocator()->get('ThermalParametros', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThermalParametros);

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
