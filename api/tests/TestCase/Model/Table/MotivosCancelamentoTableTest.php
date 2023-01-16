<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MotivosCancelamentoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MotivosCancelamentoTable Test Case
 */
class MotivosCancelamentoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MotivosCancelamentoTable
     */
    public $MotivosCancelamento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MotivosCancelamento',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MotivosCancelamento') ? [] : ['className' => MotivosCancelamentoTable::class];
        $this->MotivosCancelamento = TableRegistry::getTableLocator()->get('MotivosCancelamento', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MotivosCancelamento);

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
