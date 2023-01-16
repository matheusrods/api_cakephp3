<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioGrupoCovidTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuarioGrupoCovidTable Test Case
 */
class UsuarioGrupoCovidTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioGrupoCovidTable
     */
    public $UsuarioGrupoCovid;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioGrupoCovid'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioGrupoCovid') ? [] : ['className' => UsuarioGrupoCovidTable::class];
        $this->UsuarioGrupoCovid = TableRegistry::getTableLocator()->get('UsuarioGrupoCovid', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioGrupoCovid);

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
