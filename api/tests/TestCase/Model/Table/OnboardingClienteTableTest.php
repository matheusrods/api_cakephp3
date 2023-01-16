<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OnboardingClienteTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OnboardingClienteTable Test Case
 */
class OnboardingClienteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OnboardingClienteTable
     */
    public $OnboardingCliente;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OnboardingCliente',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OnboardingCliente') ? [] : ['className' => OnboardingClienteTable::class];
        $this->OnboardingCliente = TableRegistry::getTableLocator()->get('OnboardingCliente', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OnboardingCliente);

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
