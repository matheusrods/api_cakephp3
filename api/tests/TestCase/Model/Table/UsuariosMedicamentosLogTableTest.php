<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosMedicamentosLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuariosMedicamentosLogTable Test Case
 */
class UsuariosMedicamentosLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuariosMedicamentosLogTable
     */
    public $UsuariosMedicamentosLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuariosMedicamentosLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuariosMedicamentosLog') ? [] : ['className' => UsuariosMedicamentosLogTable::class];
        $this->UsuariosMedicamentosLog = TableRegistry::getTableLocator()->get('UsuariosMedicamentosLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuariosMedicamentosLog);

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
