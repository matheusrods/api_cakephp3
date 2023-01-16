<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GruposEconomicosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GruposEconomicosTable Test Case
 */
class GruposEconomicosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GruposEconomicosTable
     */
    public $GruposEconomicos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.GruposEconomicos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('GruposEconomicos') ? [] : ['className' => GruposEconomicosTable::class];
        $this->GruposEconomicos = TableRegistry::getTableLocator()->get('GruposEconomicos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GruposEconomicos);

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
