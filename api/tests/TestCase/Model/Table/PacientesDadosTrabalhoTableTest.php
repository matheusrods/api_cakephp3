<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PacientesDadosTrabalhoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PacientesDadosTrabalhoTable Test Case
 */
class PacientesDadosTrabalhoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PacientesDadosTrabalhoTable
     */
    public $PacientesDadosTrabalho;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PacientesDadosTrabalho',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PacientesDadosTrabalho') ? [] : ['className' => PacientesDadosTrabalhoTable::class];
        $this->PacientesDadosTrabalho = TableRegistry::getTableLocator()->get('PacientesDadosTrabalho', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PacientesDadosTrabalho);

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
