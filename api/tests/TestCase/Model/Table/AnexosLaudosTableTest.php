<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnexosLaudosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnexosLaudosTable Test Case
 */
class AnexosLaudosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnexosLaudosTable
     */
    public $AnexosLaudos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AnexosLaudos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AnexosLaudos') ? [] : ['className' => AnexosLaudosTable::class];
        $this->AnexosLaudos = TableRegistry::getTableLocator()->get('AnexosLaudos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnexosLaudos);

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
