<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioAgentesRiscosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuarioAgentesRiscosTable Test Case
 */
class UsuarioAgentesRiscosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioAgentesRiscosTable
     */
    public $UsuarioAgentesRiscos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioAgentesRiscos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioAgentesRiscos') ? [] : ['className' => UsuarioAgentesRiscosTable::class];
        $this->UsuarioAgentesRiscos = TableRegistry::getTableLocator()->get('UsuarioAgentesRiscos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioAgentesRiscos);

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
