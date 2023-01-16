<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HazopsKeywordTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HazopsKeywordTipoTable Test Case
 */
class HazopsKeywordTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\HazopsKeywordTipoTable
     */
    public $HazopsKeywordTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.HazopsKeywordTipo',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HazopsKeywordTipo') ? [] : ['className' => HazopsKeywordTipoTable::class];
        $this->HazopsKeywordTipo = TableRegistry::getTableLocator()->get('HazopsKeywordTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HazopsKeywordTipo);

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
