<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SistemaValidarTokenTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SistemaValidarTokenTipoTable Test Case
 */
class SistemaValidarTokenTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SistemaValidarTokenTipoTable
     */
    public $SistemaValidarTokenTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.SistemaValidarTokenTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SistemaValidarTokenTipo') ? [] : ['className' => SistemaValidarTokenTipoTable::class];
        $this->SistemaValidarTokenTipo = TableRegistry::getTableLocator()->get('SistemaValidarTokenTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SistemaValidarTokenTipo);

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
