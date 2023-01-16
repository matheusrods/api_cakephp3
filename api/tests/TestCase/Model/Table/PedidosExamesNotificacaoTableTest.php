<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PedidosExamesNotificacaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PedidosExamesNotificacaoTable Test Case
 */
class PedidosExamesNotificacaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PedidosExamesNotificacaoTable
     */
    public $PedidosExamesNotificacao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PedidosExamesNotificacao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PedidosExamesNotificacao') ? [] : ['className' => PedidosExamesNotificacaoTable::class];
        $this->PedidosExamesNotificacao = TableRegistry::getTableLocator()->get('PedidosExamesNotificacao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PedidosExamesNotificacao);

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
