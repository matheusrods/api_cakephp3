<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ValidarTokenTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ValidarTokenTipoTable Test Case
 */
class ValidarTokenTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ValidarTokenTipoTable
     */
    public $ValidarTokenTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ValidarTokenTipo',
        'app.Sistema',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ValidarTokenTipo') ? [] : ['className' => ValidarTokenTipoTable::class];
        $this->ValidarTokenTipo = TableRegistry::getTableLocator()->get('ValidarTokenTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ValidarTokenTipo);

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
