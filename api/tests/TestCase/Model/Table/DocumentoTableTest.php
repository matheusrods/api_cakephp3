<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocumentoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocumentoTable Test Case
 */
class DocumentoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DocumentoTable
     */
    public $Documento;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Documento',
        'app.Fornecedores',
        'app.PropostasCredenciamento',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Documento') ? [] : ['className' => DocumentoTable::class];
        $this->Documento = TableRegistry::getTableLocator()->get('Documento', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Documento);

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
