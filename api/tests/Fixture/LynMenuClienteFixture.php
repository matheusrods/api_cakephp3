<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LynMenuClienteFixture
 */
class LynMenuClienteFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'lyn_menu_cliente';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_lyn_menu' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_lyn_menu_cliente__codigo_lyn_menu' => ['type' => 'foreign', 'columns' => ['codigo_lyn_menu'], 'references' => ['lyn_menu', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_lyn_menu_cliente__codigo_cliente' => ['type' => 'foreign', 'columns' => ['codigo_cliente'], 'references' => ['cliente', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_lyn_menu_cliente__codigo_usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_lyn_menu' => 1,
                'codigo_cliente' => 1,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1600169723,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1600169723,
            ],
        ];
        parent::init();
    }
}
