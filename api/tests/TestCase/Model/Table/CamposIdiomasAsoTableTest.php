<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CamposIdiomasAsoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CamposIdiomasAsoTable Test Case
 */
class CamposIdiomasAsoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CamposIdiomasAsoTable
     */
    public $CamposIdiomasAso;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CamposIdiomasAso',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CamposIdiomasAso') ? [] : ['className' => CamposIdiomasAsoTable::class];
        $this->CamposIdiomasAso = TableRegistry::getTableLocator()->get('CamposIdiomasAso', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CamposIdiomasAso);

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
