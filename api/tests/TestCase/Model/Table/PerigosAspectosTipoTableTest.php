<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PerigosAspectosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PerigosAspectosTipoTable Test Case
 */
class PerigosAspectosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PerigosAspectosTipoTable
     */
    public $PerigosAspectosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PerigosAspectosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PerigosAspectosTipo') ? [] : ['className' => PerigosAspectosTipoTable::class];
        $this->PerigosAspectosTipo = TableRegistry::getTableLocator()->get('PerigosAspectosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PerigosAspectosTipo);

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
