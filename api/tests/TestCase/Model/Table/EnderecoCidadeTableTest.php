<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoCidadeTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoCidadeTable Test Case
 */
class EnderecoCidadeTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoCidadeTable
     */
    public $EnderecoCidade;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EnderecoCidade',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EnderecoCidade') ? [] : ['className' => EnderecoCidadeTable::class];
        $this->EnderecoCidade = TableRegistry::getTableLocator()->get('EnderecoCidade', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EnderecoCidade);

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
