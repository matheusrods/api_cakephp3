<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MotivosConclusaoParcialTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MotivosConclusaoParcialTable Test Case
 */
class MotivosConclusaoParcialTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MotivosConclusaoParcialTable
     */
    public $MotivosConclusaoParcial;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MotivosConclusaoParcial',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MotivosConclusaoParcial') ? [] : ['className' => MotivosConclusaoParcialTable::class];
        $this->MotivosConclusaoParcial = TableRegistry::getTableLocator()->get('MotivosConclusaoParcial', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MotivosConclusaoParcial);

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
