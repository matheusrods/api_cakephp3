<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MetodosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MetodosTipoTable Test Case
 */
class MetodosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MetodosTipoTable
     */
    public $MetodosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MetodosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MetodosTipo') ? [] : ['className' => MetodosTipoTable::class];
        $this->MetodosTipo = TableRegistry::getTableLocator()->get('MetodosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MetodosTipo);

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
