<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedidasControleAnexosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedidasControleAnexosTable Test Case
 */
class MedidasControleAnexosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedidasControleAnexosTable
     */
    public $MedidasControleAnexos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MedidasControleAnexos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MedidasControleAnexos') ? [] : ['className' => MedidasControleAnexosTable::class];
        $this->MedidasControleAnexos = TableRegistry::getTableLocator()->get('MedidasControleAnexos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MedidasControleAnexos);

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
