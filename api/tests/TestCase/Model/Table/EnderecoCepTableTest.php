<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoCepTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoCepTable Test Case
 */
class EnderecoCepTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoCepTable
     */
    public $EnderecoCep;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EnderecoCep',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EnderecoCep') ? [] : ['className' => EnderecoCepTable::class];
        $this->EnderecoCep = TableRegistry::getTableLocator()->get('EnderecoCep', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EnderecoCep);

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
