<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FontesExposicaoTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FontesExposicaoTipoTable Test Case
 */
class FontesExposicaoTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FontesExposicaoTipoTable
     */
    public $FontesExposicaoTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FontesExposicaoTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FontesExposicaoTipo') ? [] : ['className' => FontesExposicaoTipoTable::class];
        $this->FontesExposicaoTipo = TableRegistry::getTableLocator()->get('FontesExposicaoTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FontesExposicaoTipo);

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
