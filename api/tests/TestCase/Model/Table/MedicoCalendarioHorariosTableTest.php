<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedicoCalendarioHorariosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedicoCalendarioHorariosTable Test Case
 */
class MedicoCalendarioHorariosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedicoCalendarioHorariosTable
     */
    public $MedicoCalendarioHorarios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MedicoCalendarioHorarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MedicoCalendarioHorarios') ? [] : ['className' => MedicoCalendarioHorariosTable::class];
        $this->MedicoCalendarioHorarios = TableRegistry::getTableLocator()->get('MedicoCalendarioHorarios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MedicoCalendarioHorarios);

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
