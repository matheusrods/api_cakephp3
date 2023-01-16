<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EquipamentosInspecaoTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EquipamentosInspecaoTipoTable Test Case
 */
class EquipamentosInspecaoTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EquipamentosInspecaoTipoTable
     */
    public $EquipamentosInspecaoTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EquipamentosInspecaoTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EquipamentosInspecaoTipo') ? [] : ['className' => EquipamentosInspecaoTipoTable::class];
        $this->EquipamentosInspecaoTipo = TableRegistry::getTableLocator()->get('EquipamentosInspecaoTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EquipamentosInspecaoTipo);

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
