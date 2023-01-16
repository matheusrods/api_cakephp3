<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsMedidasControleTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsMedidasControleTipoTable Test Case
 */
class HazopsMedidasControleTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsMedidasControleTipoTable
     */
    public $HazopsMedidasControleTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsMedidasControleTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsMedidasControleTipo') ? [] : ['className' => HazopsMedidasControleTipoTable::class];
        $this->HazopsMedidasControleTipo = TableRegistry::getTableLocator()->get('HazopsMedidasControleTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsMedidasControleTipo);

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
