<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClienteQuestionariosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClienteQuestionariosTable Test Case
 */
class ClienteQuestionariosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClienteQuestionariosTable
     */
    public $ClienteQuestionarios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ClienteQuestionarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ClienteQuestionarios') ? [] : ['className' => ClienteQuestionariosTable::class];
        $this->ClienteQuestionarios = TableRegistry::getTableLocator()->get('ClienteQuestionarios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClienteQuestionarios);

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
