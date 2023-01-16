<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QrCodeLeituraTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QrCodeLeituraTable Test Case
 */
class QrCodeLeituraTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QrCodeLeituraTable
     */
    public $QrCodeLeitura;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.QrCodeLeitura',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('QrCodeLeitura') ? [] : ['className' => QrCodeLeituraTable::class];
        $this->QrCodeLeitura = TableRegistry::getTableLocator()->get('QrCodeLeitura', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->QrCodeLeitura);

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
