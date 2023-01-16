<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosHazopsNosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosHazopsNosTable Test Case
 */
class ProcessosHazopsNosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosHazopsNosTable
     */
    public $ProcessosHazopsNos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosHazopsNos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosHazopsNos') ? [] : ['className' => ProcessosHazopsNosTable::class];
        $this->ProcessosHazopsNos = TableRegistry::getTableLocator()->get('ProcessosHazopsNos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosHazopsNos);

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
