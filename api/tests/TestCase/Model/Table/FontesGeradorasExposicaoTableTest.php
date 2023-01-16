<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FontesGeradorasExposicaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FontesGeradorasExposicaoTable Test Case
 */
class FontesGeradorasExposicaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FontesGeradorasExposicaoTable
     */
    public $FontesGeradorasExposicao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FontesGeradorasExposicao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FontesGeradorasExposicao') ? [] : ['className' => FontesGeradorasExposicaoTable::class];
        $this->FontesGeradorasExposicao = TableRegistry::getTableLocator()->get('FontesGeradorasExposicao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FontesGeradorasExposicao);

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
