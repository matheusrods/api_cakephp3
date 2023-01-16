<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PerigosAspectosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PerigosAspectosTable Test Case
 */
class PerigosAspectosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PerigosAspectosTable
     */
    public $PerigosAspectos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.PerigosAspectos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PerigosAspectos') ? [] : ['className' => PerigosAspectosTable::class];
        $this->PerigosAspectos = TableRegistry::getTableLocator()->get('PerigosAspectos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PerigosAspectos);

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
