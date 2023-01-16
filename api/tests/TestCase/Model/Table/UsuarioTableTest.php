<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\UsuarioTable Test Case
 */
class UsuarioTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var UsuarioTable
     */
    public $UsuarioTable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Usuario',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Usuarios') ? [] : ['className' => UsuarioTable::class];
        $this->Usuario = TableRegistry::getTableLocator()->get('Usuarios', $config);
    }


    public function testValidacaoTabelaUsuarios()
    {
        $validator = new Validator();
        $this->Usuario->validationDefault($validator);

        $fields = [
            'codigo',
            'nome',
            'apelido',
            'senha',
            'email',
            'ativo',
            'data_inclusao',
            'codigo_usuario_inclusao',
            'codigo_uperfil',
            'alerta_portal',
            'alerta_email',
            'alerta_sms',
            'celular',
            'token',
            'fuso_horario',
            'horario_verao',
            'cracha',
            'data_senha_expiracao',
            'admin',
            'codigo_usuario_alteracao',
            'data_alteracao',
            'codigo_usuario_pai',
            'restringe_base_cnpj',
            'codigo_cliente',
            'codigo_departamento',
            'codigo_filial',
            'codigo_proposta_credenciamento',
            'codigo_fornecedor',
            'codigo_empresa',
            
        ];

        foreach ($fields as $field) {
            $this->assertTrue($validator->hasField($field));
            fwrite(STDERR, print_r($field.': contém na tabela'.PHP_EOL, true));
        }

        fwrite(STDERR, print_r(PHP_EOL, true));

    }


}
