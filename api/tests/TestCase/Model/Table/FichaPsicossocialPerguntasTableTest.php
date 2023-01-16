<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichaPsicossocialPerguntasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichaPsicossocialPerguntasTable Test Case
 */
class FichaPsicossocialPerguntasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichaPsicossocialPerguntasTable
     */
    public $FichaPsicossocialPerguntas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichaPsicossocialPerguntas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichaPsicossocialPerguntas') ? [] : ['className' => FichaPsicossocialPerguntasTable::class];
        $this->FichaPsicossocialPerguntas = TableRegistry::getTableLocator()->get('FichaPsicossocialPerguntas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichaPsicossocialPerguntas);

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
