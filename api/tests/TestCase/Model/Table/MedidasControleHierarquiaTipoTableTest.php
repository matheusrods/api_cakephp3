<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MedidasControleHierarquiaTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MedidasControleHierarquiaTipoTable Test Case
 */
class MedidasControleHierarquiaTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MedidasControleHierarquiaTipoTable
     */
    public $MedidasControleHierarquiaTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MedidasControleHierarquiaTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MedidasControleHierarquiaTipo') ? [] : ['className' => MedidasControleHierarquiaTipoTable::class];
        $this->MedidasControleHierarquiaTipo = TableRegistry::getTableLocator()->get('MedidasControleHierarquiaTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MedidasControleHierarquiaTipo);

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
