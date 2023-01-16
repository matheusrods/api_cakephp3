<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PerigosRiscosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PerigosRiscosTipoTable Test Case
 */
class PerigosRiscosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PerigosRiscosTipoTable
     */
    public $PerigosRiscosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PerigosRiscosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PerigosRiscosTipo') ? [] : ['className' => PerigosRiscosTipoTable::class];
        $this->PerigosRiscosTipo = TableRegistry::getTableLocator()->get('PerigosRiscosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PerigosRiscosTipo);

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
