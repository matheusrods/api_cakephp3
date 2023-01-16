<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuncaoTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuncaoTipoTable Test Case
 */
class FuncaoTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FuncaoTipoTable
     */
    public $FuncaoTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FuncaoTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FuncaoTipo') ? [] : ['className' => FuncaoTipoTable::class];
        $this->FuncaoTipo = TableRegistry::getTableLocator()->get('FuncaoTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FuncaoTipo);

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
