<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TiposLocaisAtendimentoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TiposLocaisAtendimentoTable Test Case
 */
class TiposLocaisAtendimentoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TiposLocaisAtendimentoTable
     */
    public $TiposLocaisAtendimento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TiposLocaisAtendimento'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TiposLocaisAtendimento') ? [] : ['className' => TiposLocaisAtendimentoTable::class];
        $this->TiposLocaisAtendimento = TableRegistry::getTableLocator()->get('TiposLocaisAtendimento', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TiposLocaisAtendimento);

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
