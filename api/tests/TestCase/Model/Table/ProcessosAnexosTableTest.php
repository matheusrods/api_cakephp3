<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosAnexosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosAnexosTable Test Case
 */
class ProcessosAnexosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosAnexosTable
     */
    public $ProcessosAnexos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosAnexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosAnexos') ? [] : ['className' => ProcessosAnexosTable::class];
        $this->ProcessosAnexos = TableRegistry::getTableLocator()->get('ProcessosAnexos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosAnexos);

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
