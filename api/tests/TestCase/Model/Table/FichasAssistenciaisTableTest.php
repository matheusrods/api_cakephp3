<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasAssistenciaisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasAssistenciaisTable Test Case
 */
class FichasAssistenciaisTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasAssistenciaisTable
     */
    public $FichasAssistenciais;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasAssistenciais',
        'app.Questoes',
        'app.Respostas',
        'app.TipoUso'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasAssistenciais') ? [] : ['className' => FichasAssistenciaisTable::class];
        $this->FichasAssistenciais = TableRegistry::getTableLocator()->get('FichasAssistenciais', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasAssistenciais);

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
