<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UperfisFixture
 */
class UperfisFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'descricao' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'perfil_cliente' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'codigo_tipo_perfil' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_pai' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_uperfis__codigo_pai' => ['type' => 'foreign', 'columns' => ['codigo_pai'], 'references' => ['uperfis', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'descricao' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1589281512,
                'codigo_usuario_inclusao' => 1,
                'codigo_cliente' => 1,
                'perfil_cliente' => 1,
                'codigo_tipo_perfil' => 1,
                'codigo_pai' => 1,
                'codigo_empresa' => 1
            ],
        ];
        parent::init();
    }
}
