<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FontesExposicaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FontesExposicaoTable Test Case
 */
class FontesExposicaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FontesExposicaoTable
     */
    public $FontesExposicao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FontesExposicao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FontesExposicao') ? [] : ['className' => FontesExposicaoTable::class];
        $this->FontesExposicao = TableRegistry::getTableLocator()->get('FontesExposicao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FontesExposicao);

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
