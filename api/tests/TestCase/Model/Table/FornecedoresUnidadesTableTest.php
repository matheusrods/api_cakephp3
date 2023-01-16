<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FornecedoresUnidadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FornecedoresUnidadesTable Test Case
 */
class FornecedoresUnidadesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FornecedoresUnidadesTable
     */
    public $FornecedoresUnidades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FornecedoresUnidades',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FornecedoresUnidades') ? [] : ['className' => FornecedoresUnidadesTable::class];
        $this->FornecedoresUnidades = TableRegistry::getTableLocator()->get('FornecedoresUnidades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FornecedoresUnidades);

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
