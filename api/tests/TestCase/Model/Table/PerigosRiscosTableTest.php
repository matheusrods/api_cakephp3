<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PerigosRiscosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PerigosRiscosTable Test Case
 */
class PerigosRiscosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PerigosRiscosTable
     */
    public $PerigosRiscos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PerigosRiscos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PerigosRiscos') ? [] : ['className' => PerigosRiscosTable::class];
        $this->PerigosRiscos = TableRegistry::getTableLocator()->get('PerigosRiscos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PerigosRiscos);

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
