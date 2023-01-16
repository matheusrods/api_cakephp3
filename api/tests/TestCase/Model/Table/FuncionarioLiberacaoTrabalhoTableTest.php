<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuncionarioLiberacaoTrabalhoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuncionarioLiberacaoTrabalhoTable Test Case
 */
class FuncionarioLiberacaoTrabalhoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FuncionarioLiberacaoTrabalhoTable
     */
    public $FuncionarioLiberacaoTrabalho;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FuncionarioLiberacaoTrabalho',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FuncionarioLiberacaoTrabalho') ? [] : ['className' => FuncionarioLiberacaoTrabalhoTable::class];
        $this->FuncionarioLiberacaoTrabalho = TableRegistry::getTableLocator()->get('FuncionarioLiberacaoTrabalho', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FuncionarioLiberacaoTrabalho);

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
