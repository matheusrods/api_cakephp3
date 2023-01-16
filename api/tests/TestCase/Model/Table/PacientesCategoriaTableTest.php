<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PacientesCategoriaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PacientesCategoriaTable Test Case
 */
class PacientesCategoriaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PacientesCategoriaTable
     */
    public $PacientesCategoria;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PacientesCategoria',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PacientesCategoria') ? [] : ['className' => PacientesCategoriaTable::class];
        $this->PacientesCategoria = TableRegistry::getTableLocator()->get('PacientesCategoria', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PacientesCategoria);

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
