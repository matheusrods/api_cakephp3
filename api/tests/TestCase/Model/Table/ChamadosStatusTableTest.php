<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChamadosStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChamadosStatusTable Test Case
 */
class ChamadosStatusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ChamadosStatusTable
     */
    public $ChamadosStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ChamadosStatus',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ChamadosStatus') ? [] : ['className' => ChamadosStatusTable::class];
        $this->ChamadosStatus = TableRegistry::getTableLocator()->get('ChamadosStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ChamadosStatus);

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
