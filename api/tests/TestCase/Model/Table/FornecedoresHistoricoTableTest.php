<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresHistoricoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresHistoricoTable Test Case
 */
class FornecedoresHistoricoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresHistoricoTable
     */
    public $FornecedoresHistorico;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresHistorico',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresHistorico') ? [] : ['className' => FornecedoresHistoricoTable::class];
        $this->FornecedoresHistorico = TableRegistry::getTableLocator()->get('FornecedoresHistorico', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresHistorico);

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
