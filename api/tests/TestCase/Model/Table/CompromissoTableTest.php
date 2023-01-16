<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompromissoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompromissoTable Test Case
 */
class CompromissoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CompromissoTable
     */
    public $Compromisso;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Compromisso'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Compromisso') ? [] : ['className' => CompromissoTable::class];
        $this->Compromisso = TableRegistry::getTableLocator()->get('Compromisso', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Compromisso);

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
