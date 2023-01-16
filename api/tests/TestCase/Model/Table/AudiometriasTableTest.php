<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AudiometriasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AudiometriasTable Test Case
 */
class AudiometriasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AudiometriasTable
     */
    public $Audiometrias;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Audiometrias'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Audiometrias') ? [] : ['className' => AudiometriasTable::class];
        $this->Audiometrias = TableRegistry::getTableLocator()->get('Audiometrias', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Audiometrias);

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
