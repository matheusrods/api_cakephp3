<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioFornecedorPermissoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuarioFornecedorPermissoesTable Test Case
 */
class UsuarioFornecedorPermissoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioFornecedorPermissoesTable
     */
    public $UsuarioFornecedorPermissoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioFornecedorPermissoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioFornecedorPermissoes') ? [] : ['className' => UsuarioFornecedorPermissoesTable::class];
        $this->UsuarioFornecedorPermissoes = TableRegistry::getTableLocator()->get('UsuarioFornecedorPermissoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioFornecedorPermissoes);

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
