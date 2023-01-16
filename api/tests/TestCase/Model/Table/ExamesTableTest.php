<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\ExamesTable Test Case
 */
class ExamesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ExamesTable
     */
    public $ExamesTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Exames',

    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Exames') ? [] : ['className' => ExamesTable::class];
        $this->ExamesTable = TableRegistry::getTableLocator()->get('Exames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ExamesTable);

        parent::tearDown();
    }

    public function testValidacaoTabelaExames()
    {
        $validator = new Validator();
        $validator = $this->ExamesTable->validationDefault($validator);

        $fields = [
            'codigo',
            'codigo_servico',
        ];

        foreach ($fields as $field) {
            $this->assertTrue($validator->hasField($field));
            fwrite(STDERR, print_r($field.': contém na tabela'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r(PHP_EOL, true));

    }
}
