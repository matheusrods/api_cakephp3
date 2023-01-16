<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoricoFichaClinicaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoricoFichaClinicaTable Test Case
 */
class HistoricoFichaClinicaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoricoFichaClinicaTable
     */
    public $HistoricoFichaClinica;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HistoricoFichaClinica'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HistoricoFichaClinica') ? [] : ['className' => HistoricoFichaClinicaTable::class];
        $this->HistoricoFichaClinica = TableRegistry::getTableLocator()->get('HistoricoFichaClinica', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoricoFichaClinica);

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
