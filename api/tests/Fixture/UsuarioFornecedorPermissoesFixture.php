<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuarioFornecedorPermissoesFixture
 */
class UsuarioFornecedorPermissoesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor_permissoes' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_codigo_fornecedor_permissoes__usuario_fornecedor_permissoes' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor_permissoes'], 'references' => ['fornecedor_permissoes', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_usuario__usuario_fornecedor_permissoes' => ['type' => 'foreign', 'columns' => ['codigo_usuario'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'codigo' => 1,
                'codigo_fornecedor_permissoes' => 1,
                'codigo_usuario' => 1,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1589205804
            ],
        ];
        parent::init();
    }
}
