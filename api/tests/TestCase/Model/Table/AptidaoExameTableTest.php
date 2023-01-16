<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AptidaoExameTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AptidaoExameTable Test Case
 */
class AptidaoExameTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AptidaoExameTable
     */
    public $AptidaoExame;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AptidaoExame',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AptidaoExame') ? [] : ['className' => AptidaoExameTable::class];
        $this->AptidaoExame = TableRegistry::getTableLocator()->get('AptidaoExame', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AptidaoExame);

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
