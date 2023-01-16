<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PreAgendamentoExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PreAgendamentoExamesTable Test Case
 */
class PreAgendamentoExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PreAgendamentoExamesTable
     */
    public $PreAgendamentoExames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PreAgendamentoExames',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PreAgendamentoExames') ? [] : ['className' => PreAgendamentoExamesTable::class];
        $this->PreAgendamentoExames = TableRegistry::getTableLocator()->get('PreAgendamentoExames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PreAgendamentoExames);

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
