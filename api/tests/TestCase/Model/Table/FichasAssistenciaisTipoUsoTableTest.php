<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasAssistenciaisTipoUsoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasAssistenciaisTipoUsoTable Test Case
 */
class FichasAssistenciaisTipoUsoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasAssistenciaisTipoUsoTable
     */
    public $FichasAssistenciaisTipoUso;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasAssistenciaisTipoUso'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasAssistenciaisTipoUso') ? [] : ['className' => FichasAssistenciaisTipoUsoTable::class];
        $this->FichasAssistenciaisTipoUso = TableRegistry::getTableLocator()->get('FichasAssistenciaisTipoUso', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasAssistenciaisTipoUso);

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
