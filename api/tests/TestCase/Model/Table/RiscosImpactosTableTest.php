<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RiscosImpactosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RiscosImpactosTable Test Case
 */
class RiscosImpactosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RiscosImpactosTable
     */
    public $RiscosImpactos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RiscosImpactos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RiscosImpactos') ? [] : ['className' => RiscosImpactosTable::class];
        $this->RiscosImpactos = TableRegistry::getTableLocator()->get('RiscosImpactos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RiscosImpactos);

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
