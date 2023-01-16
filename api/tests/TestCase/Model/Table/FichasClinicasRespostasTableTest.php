<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FichasClinicasRespostasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FichasClinicasRespostasTable Test Case
 */
class FichasClinicasRespostasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FichasClinicasRespostasTable
     */
    public $FichasClinicasRespostas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.FichasClinicasRespostas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('FichasClinicasRespostas') ? [] : ['className' => FichasClinicasRespostasTable::class];
        $this->FichasClinicasRespostas = TableRegistry::getTableLocator()->get('FichasClinicasRespostas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FichasClinicasRespostas);

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
