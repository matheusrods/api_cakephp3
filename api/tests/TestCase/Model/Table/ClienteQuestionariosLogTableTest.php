<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClienteQuestionariosLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClienteQuestionariosLogTable Test Case
 */
class ClienteQuestionariosLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClienteQuestionariosLogTable
     */
    public $ClienteQuestionariosLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ClienteQuestionariosLog'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ClienteQuestionariosLog') ? [] : ['className' => ClienteQuestionariosLogTable::class];
        $this->ClienteQuestionariosLog = TableRegistry::getTableLocator()->get('ClienteQuestionariosLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClienteQuestionariosLog);

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
