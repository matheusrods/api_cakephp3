<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResultadoCovidTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResultadoCovidTable Test Case
 */
class ResultadoCovidTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ResultadoCovidTable
     */
    public $ResultadoCovid;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ResultadoCovid'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ResultadoCovid') ? [] : ['className' => ResultadoCovidTable::class];
        $this->ResultadoCovid = TableRegistry::getTableLocator()->get('ResultadoCovid', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResultadoCovid);

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
