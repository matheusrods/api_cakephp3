<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UperfisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UperfisTable Test Case
 */
class UperfisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UperfisTable
     */
    public $Uperfis;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Uperfis'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Uperfis') ? [] : ['className' => UperfisTable::class];
        $this->Uperfis = TableRegistry::getTableLocator()->get('Uperfis', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Uperfis);

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
