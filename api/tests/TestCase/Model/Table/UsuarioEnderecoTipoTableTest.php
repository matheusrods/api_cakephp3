<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioEnderecoTipoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\UsuarioEnderecoTipoTable Test Case
 */
class UsuarioEnderecoTipoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioEnderecoTipoTable
     */
    public $UsuarioEnderecoTipo;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioEnderecoTipo'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioEnderecoTipo') ? [] : ['className' => UsuarioEnderecoTipoTable::class];
        
        $this->UsuarioEnderecoTipo = TableRegistry::getTableLocator()->get('UsuarioEnderecoTipo', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioEnderecoTipo);

        parent::tearDown();
    }


    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidacaoTabelaUsuarioEnderecoTipo()
    {
        $validator = new Validator();
        $validator = $this->UsuarioEnderecoTipo->validationDefault($validator);

        $fields = [
            'codigo',
            'tipo',
            'descricao'
        ];

        foreach ($fields as $field) {
            $this->assertTrue($validator->hasField($field));
            fwrite(STDERR, print_r($field.': contém na tabela'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r(PHP_EOL, true));

    }
}
