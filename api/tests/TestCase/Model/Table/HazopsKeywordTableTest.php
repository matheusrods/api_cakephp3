<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsKeywordTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsKeywordTable Test Case
 */
class HazopsKeywordTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsKeywordTable
     */
    public $HazopsKeyword;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsKeyword',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsKeyword') ? [] : ['className' => HazopsKeywordTable::class];
        $this->HazopsKeyword = TableRegistry::getTableLocator()->get('HazopsKeyword', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsKeyword);

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
