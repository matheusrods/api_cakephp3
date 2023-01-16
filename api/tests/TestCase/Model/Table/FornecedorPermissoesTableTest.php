<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedorPermissoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedorPermissoesTable Test Case
 */
class FornecedorPermissoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedorPermissoesTable
     */
    public $FornecedorPermissoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedorPermissoes',
        'app.Usuario'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedorPermissoes') ? [] : ['className' => FornecedorPermissoesTable::class];
        $this->FornecedorPermissoes = TableRegistry::getTableLocator()->get('FornecedorPermissoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedorPermissoes);

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
