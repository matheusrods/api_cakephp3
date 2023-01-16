<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresMedicosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresMedicosTable Test Case
 */
class FornecedoresMedicosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresMedicosTable
     */
    public $FornecedoresMedicos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresMedicos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresMedicos') ? [] : ['className' => FornecedoresMedicosTable::class];
        $this->FornecedoresMedicos = TableRegistry::getTableLocator()->get('FornecedoresMedicos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresMedicos);

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
