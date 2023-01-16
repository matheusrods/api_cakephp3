<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsMedidasControleAnexosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsMedidasControleAnexosTable Test Case
 */
class HazopsMedidasControleAnexosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsMedidasControleAnexosTable
     */
    public $HazopsMedidasControleAnexos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsMedidasControleAnexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsMedidasControleAnexos') ? [] : ['className' => HazopsMedidasControleAnexosTable::class];
        $this->HazopsMedidasControleAnexos = TableRegistry::getTableLocator()->get('HazopsMedidasControleAnexos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsMedidasControleAnexos);

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
