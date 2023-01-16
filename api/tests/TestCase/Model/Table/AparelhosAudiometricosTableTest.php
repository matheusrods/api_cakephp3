<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AparelhosAudiometricosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AparelhosAudiometricosTable Test Case
 */
class AparelhosAudiometricosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AparelhosAudiometricosTable
     */
    public $AparelhosAudiometricos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AparelhosAudiometricos',
        'app.Resultados'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AparelhosAudiometricos') ? [] : ['className' => AparelhosAudiometricosTable::class];
        $this->AparelhosAudiometricos = TableRegistry::getTableLocator()->get('AparelhosAudiometricos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AparelhosAudiometricos);

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
