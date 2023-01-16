<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResultadoCovidLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResultadoCovidLogTable Test Case
 */
class ResultadoCovidLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ResultadoCovidLogTable
     */
    public $ResultadoCovidLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ResultadoCovidLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ResultadoCovidLog') ? [] : ['className' => ResultadoCovidLogTable::class];
        $this->ResultadoCovidLog = TableRegistry::getTableLocator()->get('ResultadoCovidLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResultadoCovidLog);

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
