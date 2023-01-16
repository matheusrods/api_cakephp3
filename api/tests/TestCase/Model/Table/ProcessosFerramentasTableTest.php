<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosFerramentasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProcessosFerramentasTable Test Case
 */
class ProcessosFerramentasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosFerramentasTable
     */
    public $ProcessosFerramentas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ProcessosFerramentas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ProcessosFerramentas') ? [] : ['className' => ProcessosFerramentasTable::class];
        $this->ProcessosFerramentas = TableRegistry::getTableLocator()->get('ProcessosFerramentas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProcessosFerramentas);

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
