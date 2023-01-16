<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasAssistenciaisQuestoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasAssistenciaisQuestoesTable Test Case
 */
class FichasAssistenciaisQuestoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasAssistenciaisQuestoesTable
     */
    public $FichasAssistenciaisQuestoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasAssistenciaisQuestoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasAssistenciaisQuestoes') ? [] : ['className' => FichasAssistenciaisQuestoesTable::class];
        $this->FichasAssistenciaisQuestoes = TableRegistry::getTableLocator()->get('FichasAssistenciaisQuestoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasAssistenciaisQuestoes);

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
