<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosTipoTable Test Case
 */
class ProcessosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosTipoTable
     */
    public $ProcessosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosTipo') ? [] : ['className' => ProcessosTipoTable::class];
        $this->ProcessosTipo = TableRegistry::getTableLocator()->get('ProcessosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosTipo);

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
