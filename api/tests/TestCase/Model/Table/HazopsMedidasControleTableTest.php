<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsMedidasControleTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsMedidasControleTable Test Case
 */
class HazopsMedidasControleTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsMedidasControleTable
     */
    public $HazopsMedidasControle;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsMedidasControle',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsMedidasControle') ? [] : ['className' => HazopsMedidasControleTable::class];
        $this->HazopsMedidasControle = TableRegistry::getTableLocator()->get('HazopsMedidasControle', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsMedidasControle);

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
