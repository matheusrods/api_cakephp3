<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OnboardingTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OnboardingTable Test Case
 */
class OnboardingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OnboardingTable
     */
    public $Onboarding;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Onboarding',
        'app.Cliente',
        'app.ClienteLog',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Onboarding') ? [] : ['className' => OnboardingTable::class];
        $this->Onboarding = TableRegistry::getTableLocator()->get('Onboarding', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Onboarding);

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
