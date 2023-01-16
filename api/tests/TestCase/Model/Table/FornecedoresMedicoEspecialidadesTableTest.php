<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresMedicoEspecialidadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresMedicoEspecialidadesTable Test Case
 */
class FornecedoresMedicoEspecialidadesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresMedicoEspecialidadesTable
     */
    public $FornecedoresMedicoEspecialidades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresMedicoEspecialidades'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresMedicoEspecialidades') ? [] : ['className' => FornecedoresMedicoEspecialidadesTable::class];
        $this->FornecedoresMedicoEspecialidades = TableRegistry::getTableLocator()->get('FornecedoresMedicoEspecialidades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresMedicoEspecialidades);

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
