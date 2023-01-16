<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TipoNotificacaoValoresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TipoNotificacaoValoresTable Test Case
 */
class TipoNotificacaoValoresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TipoNotificacaoValoresTable
     */
    public $TipoNotificacaoValores;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TipoNotificacaoValores',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TipoNotificacaoValores') ? [] : ['className' => TipoNotificacaoValoresTable::class];
        $this->TipoNotificacaoValores = TableRegistry::getTableLocator()->get('TipoNotificacaoValores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TipoNotificacaoValores);

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
