<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuncionariosContatosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuncionariosContatosTable Test Case
 */
class FuncionariosContatosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FuncionariosContatosTable
     */
    public $FuncionariosContatos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FuncionariosContatos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FuncionariosContatos') ? [] : ['className' => FuncionariosContatosTable::class];
        $this->FuncionariosContatos = TableRegistry::getTableLocator()->get('FuncionariosContatos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FuncionariosContatos);

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
