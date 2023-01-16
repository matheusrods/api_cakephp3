<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedicoCalendarioTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedicoCalendarioTable Test Case
 */
class MedicoCalendarioTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedicoCalendarioTable
     */
    public $MedicoCalendario;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MedicoCalendario'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MedicoCalendario') ? [] : ['className' => MedicoCalendarioTable::class];
        $this->MedicoCalendario = TableRegistry::getTableLocator()->get('MedicoCalendario', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MedicoCalendario);

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

    /**
     * Test getMedicosCalendario method
     *
     * @return void
     */
    public function testGetMedicosCalendario()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
