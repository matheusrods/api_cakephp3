<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AprhoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AprhoTable Test Case
 */
class AprhoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AprhoTable
     */
    public $Aprho;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Aprho',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Aprho') ? [] : ['className' => AprhoTable::class];
        $this->Aprho = TableRegistry::getTableLocator()->get('Aprho', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Aprho);

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
