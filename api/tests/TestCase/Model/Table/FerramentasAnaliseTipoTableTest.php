<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FerramentasAnaliseTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FerramentasAnaliseTipoTable Test Case
 */
class FerramentasAnaliseTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FerramentasAnaliseTipoTable
     */
    public $FerramentasAnaliseTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FerramentasAnaliseTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FerramentasAnaliseTipo') ? [] : ['className' => FerramentasAnaliseTipoTable::class];
        $this->FerramentasAnaliseTipo = TableRegistry::getTableLocator()->get('FerramentasAnaliseTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FerramentasAnaliseTipo);

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
