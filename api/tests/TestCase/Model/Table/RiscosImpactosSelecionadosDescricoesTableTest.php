<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RiscosImpactosSelecionadosDescricoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RiscosImpactosSelecionadosDescricoesTable Test Case
 */
class RiscosImpactosSelecionadosDescricoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RiscosImpactosSelecionadosDescricoesTable
     */
    public $RiscosImpactosSelecionadosDescricoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RiscosImpactosSelecionadosDescricoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RiscosImpactosSelecionadosDescricoes') ? [] : ['className' => RiscosImpactosSelecionadosDescricoesTable::class];
        $this->RiscosImpactosSelecionadosDescricoes = TableRegistry::getTableLocator()->get('RiscosImpactosSelecionadosDescricoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RiscosImpactosSelecionadosDescricoes);

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
