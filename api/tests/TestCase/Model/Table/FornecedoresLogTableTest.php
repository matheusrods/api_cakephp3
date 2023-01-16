<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresLogTable Test Case
 */
class FornecedoresLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresLogTable
     */
    public $FornecedoresLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresLog') ? [] : ['className' => FornecedoresLogTable::class];
        $this->FornecedoresLog = TableRegistry::getTableLocator()->get('FornecedoresLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresLog);

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
