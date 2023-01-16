<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresEnderecoLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresEnderecoLogTable Test Case
 */
class FornecedoresEnderecoLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresEnderecoLogTable
     */
    public $FornecedoresEnderecoLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresEnderecoLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresEnderecoLog') ? [] : ['className' => FornecedoresEnderecoLogTable::class];
        $this->FornecedoresEnderecoLog = TableRegistry::getTableLocator()->get('FornecedoresEnderecoLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresEnderecoLog);

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
