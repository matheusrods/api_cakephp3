<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeparaQuestoesRespostasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeparaQuestoesRespostasTable Test Case
 */
class DeparaQuestoesRespostasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DeparaQuestoesRespostasTable
     */
    public $DeparaQuestoesRespostas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DeparaQuestoesRespostas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DeparaQuestoesRespostas') ? [] : ['className' => DeparaQuestoesRespostasTable::class];
        $this->DeparaQuestoesRespostas = TableRegistry::getTableLocator()->get('DeparaQuestoesRespostas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DeparaQuestoesRespostas);

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
