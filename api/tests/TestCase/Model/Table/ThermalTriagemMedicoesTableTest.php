<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThermalTriagemMedicoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThermalTriagemMedicoesTable Test Case
 */
class ThermalTriagemMedicoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ThermalTriagemMedicoesTable
     */
    public $ThermalTriagemMedicoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ThermalTriagemMedicoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ThermalTriagemMedicoes') ? [] : ['className' => ThermalTriagemMedicoesTable::class];
        $this->ThermalTriagemMedicoes = TableRegistry::getTableLocator()->get('ThermalTriagemMedicoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThermalTriagemMedicoes);

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
