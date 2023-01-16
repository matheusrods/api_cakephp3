<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichaPsicossocialRespostasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichaPsicossocialRespostasTable Test Case
 */
class FichaPsicossocialRespostasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichaPsicossocialRespostasTable
     */
    public $FichaPsicossocialRespostas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichaPsicossocialRespostas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichaPsicossocialRespostas') ? [] : ['className' => FichaPsicossocialRespostasTable::class];
        $this->FichaPsicossocialRespostas = TableRegistry::getTableLocator()->get('FichaPsicossocialRespostas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichaPsicossocialRespostas);

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
