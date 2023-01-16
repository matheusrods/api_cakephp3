<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasAssistenciaisRespostasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasAssistenciaisRespostasTable Test Case
 */
class FichasAssistenciaisRespostasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasAssistenciaisRespostasTable
     */
    public $FichasAssistenciaisRespostas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasAssistenciaisRespostas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasAssistenciaisRespostas') ? [] : ['className' => FichasAssistenciaisRespostasTable::class];
        $this->FichasAssistenciaisRespostas = TableRegistry::getTableLocator()->get('FichasAssistenciaisRespostas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasAssistenciaisRespostas);

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
