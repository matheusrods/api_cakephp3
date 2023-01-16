<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioGrupoCovidLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuarioGrupoCovidLogTable Test Case
 */
class UsuarioGrupoCovidLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioGrupoCovidLogTable
     */
    public $UsuarioGrupoCovidLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioGrupoCovidLog'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioGrupoCovidLog') ? [] : ['className' => UsuarioGrupoCovidLogTable::class];
        $this->UsuarioGrupoCovidLog = TableRegistry::getTableLocator()->get('UsuarioGrupoCovidLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioGrupoCovidLog);

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
