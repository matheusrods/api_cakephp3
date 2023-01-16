<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstadoCidadeLatLongTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstadoCidadeLatLongTable Test Case
 */
class EstadoCidadeLatLongTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EstadoCidadeLatLongTable
     */
    public $EstadoCidadeLatLong;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EstadoCidadeLatLong',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EstadoCidadeLatLong') ? [] : ['className' => EstadoCidadeLatLongTable::class];
        $this->EstadoCidadeLatLong = TableRegistry::getTableLocator()->get('EstadoCidadeLatLong', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EstadoCidadeLatLong);

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
