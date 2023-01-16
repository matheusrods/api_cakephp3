<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasClinicasQuestoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasClinicasQuestoesTable Test Case
 */
class FichasClinicasQuestoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasClinicasQuestoesTable
     */
    public $FichasClinicasQuestoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasClinicasQuestoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasClinicasQuestoes') ? [] : ['className' => FichasClinicasQuestoesTable::class];
        $this->FichasClinicasQuestoes = TableRegistry::getTableLocator()->get('FichasClinicasQuestoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasClinicasQuestoes);

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
