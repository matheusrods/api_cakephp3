<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SistemaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SistemaTable Test Case
 */
class SistemaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SistemaTable
     */
    public $Sistema;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Sistema',
        'app.Usuario',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Sistema') ? [] : ['className' => SistemaTable::class];
        $this->Sistema = TableRegistry::getTableLocator()->get('Sistema', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sistema);

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
