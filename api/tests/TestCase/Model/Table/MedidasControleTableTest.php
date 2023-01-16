<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedidasControleTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedidasControleTable Test Case
 */
class MedidasControleTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedidasControleTable
     */
    public $MedidasControle;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MedidasControle',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MedidasControle') ? [] : ['className' => MedidasControleTable::class];
        $this->MedidasControle = TableRegistry::getTableLocator()->get('MedidasControle', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MedidasControle);

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
