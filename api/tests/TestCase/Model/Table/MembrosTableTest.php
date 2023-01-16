<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembrosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembrosTable Test Case
 */
class MembrosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MembrosTable
     */
    public $Membros;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Membros',
        'app.Exames'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Membros') ? [] : ['className' => MembrosTable::class];
        $this->Membros = TableRegistry::getTableLocator()->get('Membros', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Membros);

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
