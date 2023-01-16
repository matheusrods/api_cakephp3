<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FornecedoresUnidadesFixture
 */
class FornecedoresUnidadesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor_matriz' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_fornecedor_unidade' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_fornecedores_unidades__codigo_fornecedor_matriz' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor_matriz'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_fornecedores_unidades__codigo_fornecedor_unidade' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor_unidade'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_fornecedor_matriz' => 1,
                'codigo_fornecedor_unidade' => 1,
                'data_inclusao' => 1592261743,
                'codigo_usuario_inclusao' => 1,
                'codigo_empresa' => 1,
                'ativo' => 1,
            ],
        ];
        parent::init();
    }
}
