<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EquipamentosAdotadosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EquipamentosAdotadosTable Test Case
 */
class EquipamentosAdotadosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EquipamentosAdotadosTable
     */
    public $EquipamentosAdotados;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EquipamentosAdotados',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EquipamentosAdotados') ? [] : ['className' => EquipamentosAdotadosTable::class];
        $this->EquipamentosAdotados = TableRegistry::getTableLocator()->get('EquipamentosAdotados', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EquipamentosAdotados);

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
