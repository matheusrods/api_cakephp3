<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IndicadoresImagensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IndicadoresImagensTable Test Case
 */
class IndicadoresImagensTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\IndicadoresImagensTable
     */
    public $IndicadoresImagens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.IndicadoresImagens'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('IndicadoresImagens') ? [] : ['className' => IndicadoresImagensTable::class];
        $this->IndicadoresImagens = TableRegistry::getTableLocator()->get('IndicadoresImagens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->IndicadoresImagens);

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
