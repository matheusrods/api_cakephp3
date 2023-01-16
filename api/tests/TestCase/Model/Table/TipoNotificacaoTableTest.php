<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TipoNotificacaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TipoNotificacaoTable Test Case
 */
class TipoNotificacaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TipoNotificacaoTable
     */
    public $TipoNotificacao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TipoNotificacao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TipoNotificacao') ? [] : ['className' => TipoNotificacaoTable::class];
        $this->TipoNotificacao = TableRegistry::getTableLocator()->get('TipoNotificacao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TipoNotificacao);

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
