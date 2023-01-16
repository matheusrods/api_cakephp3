<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoBairroTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoBairroTable Test Case
 */
class EnderecoBairroTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoBairroTable
     */
    public $EnderecoBairro;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EnderecoBairro',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EnderecoBairro') ? [] : ['className' => EnderecoBairroTable::class];
        $this->EnderecoBairro = TableRegistry::getTableLocator()->get('EnderecoBairro', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EnderecoBairro);

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
