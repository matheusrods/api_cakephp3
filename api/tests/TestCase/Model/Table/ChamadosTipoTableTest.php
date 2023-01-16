<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChamadosTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChamadosTipoTable Test Case
 */
class ChamadosTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ChamadosTipoTable
     */
    public $ChamadosTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ChamadosTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ChamadosTipo') ? [] : ['className' => ChamadosTipoTable::class];
        $this->ChamadosTipo = TableRegistry::getTableLocator()->get('ChamadosTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChamadosTipo);

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
