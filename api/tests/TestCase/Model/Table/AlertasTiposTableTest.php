<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlertasTiposTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertasTiposTable Test Case
 */
class AlertasTiposTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AlertasTiposTable
     */
    public $AlertasTipos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AlertasTipos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AlertasTipos') ? [] : ['className' => AlertasTiposTable::class];
        $this->AlertasTipos = TableRegistry::getTableLocator()->get('AlertasTipos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AlertasTipos);

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
