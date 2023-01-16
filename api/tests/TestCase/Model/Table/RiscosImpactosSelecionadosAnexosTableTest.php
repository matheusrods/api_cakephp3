<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RiscosImpactosSelecionadosAnexosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RiscosImpactosSelecionadosAnexosTable Test Case
 */
class RiscosImpactosSelecionadosAnexosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RiscosImpactosSelecionadosAnexosTable
     */
    public $RiscosImpactosSelecionadosAnexos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RiscosImpactosSelecionadosAnexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RiscosImpactosSelecionadosAnexos') ? [] : ['className' => RiscosImpactosSelecionadosAnexosTable::class];
        $this->RiscosImpactosSelecionadosAnexos = TableRegistry::getTableLocator()->get('RiscosImpactosSelecionadosAnexos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RiscosImpactosSelecionadosAnexos);

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
