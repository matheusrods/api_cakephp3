<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FontesGeradorasExposicaoTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FontesGeradorasExposicaoTipoTable Test Case
 */
class FontesGeradorasExposicaoTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FontesGeradorasExposicaoTipoTable
     */
    public $FontesGeradorasExposicaoTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FontesGeradorasExposicaoTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FontesGeradorasExposicaoTipo') ? [] : ['className' => FontesGeradorasExposicaoTipoTable::class];
        $this->FontesGeradorasExposicaoTipo = TableRegistry::getTableLocator()->get('FontesGeradorasExposicaoTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FontesGeradorasExposicaoTipo);

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
