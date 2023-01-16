<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosTiposTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosTiposTable Test Case
 */
class ProcessosTiposTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosTiposTable
     */
    public $ProcessosTipos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosTipos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosTipos') ? [] : ['className' => ProcessosTiposTable::class];
        $this->ProcessosTipos = TableRegistry::getTableLocator()->get('ProcessosTipos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosTipos);

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
