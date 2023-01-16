<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FerramentasAnaliseTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FerramentasAnaliseTable Test Case
 */
class FerramentasAnaliseTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FerramentasAnaliseTable
     */
    public $FerramentasAnalise;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FerramentasAnalise',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FerramentasAnalise') ? [] : ['className' => FerramentasAnaliseTable::class];
        $this->FerramentasAnalise = TableRegistry::getTableLocator()->get('FerramentasAnalise', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FerramentasAnalise);

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
