<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioEnderecoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\UsuarioEnderecoTable Test Case
 */
class UsuarioEnderecoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioEnderecoTable
     */
    public $UsuarioEnderecoTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuarioEndereco'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuarioEndereco') ? [] : ['className' => UsuarioEnderecoTable::class];
        $this->UsuarioEnderecoTable = TableRegistry::getTableLocator()->get('UsuarioEndereco', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuarioEnderecoTable);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertEquals(
            'codigo',
            $this->UsuarioEnderecoTable->getPrimaryKey(),
            'The [App]Table default primary key is expected to be `codigo`.'
        );
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidacaoTabelaUsuarioEndereco()
    {
        $validator = new Validator();
        $validator = $this->UsuarioEnderecoTable->validationDefault($validator);

        $fields = [
            'codigo',
            'codigo_usuario_endereco_tipo',
            'codigo_usuario',
            'complemento',
            'numero',
            'data_inclusao',
            'codigo_usuario_inclusao',
            'latitude',
            'longitude',
            'codigo_empresa',
            'cep',
            'logradouro',
            'bairro',
            'cidade',
            'estado_descricao',
            'estado_abreviacao',
            'codigo_usuario_alteracao',
            'data_alteracao'
        ];

        foreach ($fields as $field) {
            $this->assertTrue($validator->hasField($field));
            fwrite(STDERR, print_r($field.': contém na tabela'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r(PHP_EOL, true));

    }

    public function testUsuarioEnderecoData()
    {
        $query = $this->UsuarioEnderecoTable->find();
        $this->assertInstanceOf('Cake\ORM\Query', $query);
        $result = $query->enableHydration(false)->toArray();
    }
}
