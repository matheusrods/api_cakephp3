<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClienteContatoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClienteContatoTable Test Case
 */
class ClienteContatoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClienteContatoTable
     */
    public $ClienteContato;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ClienteContato'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ClienteContato') ? [] : ['className' => ClienteContatoTable::class];
        $this->ClienteContato = TableRegistry::getTableLocator()->get('ClienteContato', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClienteContato);

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
