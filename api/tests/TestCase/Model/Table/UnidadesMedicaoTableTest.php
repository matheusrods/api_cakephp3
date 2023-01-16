<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnidadesMedicaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnidadesMedicaoTable Test Case
 */
class UnidadesMedicaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UnidadesMedicaoTable
     */
    public $UnidadesMedicao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UnidadesMedicao',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UnidadesMedicao') ? [] : ['className' => UnidadesMedicaoTable::class];
        $this->UnidadesMedicao = TableRegistry::getTableLocator()->get('UnidadesMedicao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UnidadesMedicao);

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
