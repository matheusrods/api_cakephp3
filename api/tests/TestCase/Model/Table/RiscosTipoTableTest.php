<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RiscosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RiscosTipoTable Test Case
 */
class RiscosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RiscosTipoTable
     */
    public $RiscosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RiscosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RiscosTipo') ? [] : ['className' => RiscosTipoTable::class];
        $this->RiscosTipo = TableRegistry::getTableLocator()->get('RiscosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RiscosTipo);

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
