<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasClinicasGrupoQuestoesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasClinicasGrupoQuestoesTable Test Case
 */
class FichasClinicasGrupoQuestoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasClinicasGrupoQuestoesTable
     */
    public $FichasClinicasGrupoQuestoes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasClinicasGrupoQuestoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasClinicasGrupoQuestoes') ? [] : ['className' => FichasClinicasGrupoQuestoesTable::class];
        $this->FichasClinicasGrupoQuestoes = TableRegistry::getTableLocator()->get('FichasClinicasGrupoQuestoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasClinicasGrupoQuestoes);

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
