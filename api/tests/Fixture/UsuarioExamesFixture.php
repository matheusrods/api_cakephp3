<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuarioExamesFixture
 */
class UsuarioExamesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_exames' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'endereco_clinica' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_realizacao' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            // 'fk_usuario_exames__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            // 'fk_usuario_exames__exames' => ['type' => 'foreign', 'columns' => ['codigo_exames'], 'references' => ['exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                //'codigo' => 1,
                'codigo_usuario' => 1,
                'codigo_exames' => 1,
                'endereco_clinica' => 'Lorem ipsum dolor sit amet',
                'data_realizacao' => '2020-01-08',
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1578494804,
                'data_inclusao' => 1578494804
            ],
        ];
        parent::init();
    }
}
