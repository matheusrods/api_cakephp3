<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\AtestadosBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\AtestadosBehavior Test Case
 */
class AtestadosBehaviorTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Behavior\AtestadosBehavior
     */
    public $Atestados;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Atestados = new AtestadosBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Atestados);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
