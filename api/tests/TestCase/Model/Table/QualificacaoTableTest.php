<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QualificacaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QualificacaoTable Test Case
 */
class QualificacaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QualificacaoTable
     */
    public $Qualificacao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Qualificacao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Qualificacao') ? [] : ['className' => QualificacaoTable::class];
        $this->Qualificacao = TableRegistry::getTableLocator()->get('Qualificacao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Qualificacao);

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
