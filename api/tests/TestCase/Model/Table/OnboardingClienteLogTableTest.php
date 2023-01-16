<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OnboardingClienteLogTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OnboardingClienteLogTable Test Case
 */
class OnboardingClienteLogTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OnboardingClienteLogTable
     */
    public $OnboardingClienteLog;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OnboardingClienteLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OnboardingClienteLog') ? [] : ['className' => OnboardingClienteLogTable::class];
        $this->OnboardingClienteLog = TableRegistry::getTableLocator()->get('OnboardingClienteLog', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OnboardingClienteLog);

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
