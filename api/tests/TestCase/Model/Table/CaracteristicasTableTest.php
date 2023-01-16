<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CaracteristicasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CaracteristicasTable Test Case
 */
class CaracteristicasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CaracteristicasTable
     */
    public $Caracteristicas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Caracteristicas',
        'app.Questionarios',
        'app.Questoes',
        'app.Setores'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Caracteristicas') ? [] : ['className' => CaracteristicasTable::class];
        $this->Caracteristicas = TableRegistry::getTableLocator()->get('Caracteristicas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Caracteristicas);

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
