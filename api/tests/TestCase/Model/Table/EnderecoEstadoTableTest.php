<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoEstadoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoEstadoTable Test Case
 */
class EnderecoEstadoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoEstadoTable
     */
    public $EnderecoEstado;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EnderecoEstado'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EnderecoEstado') ? [] : ['className' => EnderecoEstadoTable::class];
        $this->EnderecoEstado = TableRegistry::getTableLocator()->get('EnderecoEstado', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EnderecoEstado);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
