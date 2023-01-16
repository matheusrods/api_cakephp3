<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuarioEnderecoFixture
 */
class UsuarioEnderecoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'usuario_endereco';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_endereco_tipo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'complemento' => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'numero' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'latitude' => ['type' => 'float', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'longitude' => ['type' => 'float', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'cep' => ['type' => 'string', 'fixed' => true, 'length' => 8, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'logradouro' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'bairro' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cidade' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'estado_descricao' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'estado_abreviacao' => ['type' => 'string', 'fixed' => true, 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
//            'fk_usuario_endereco__usuario_endereco_tipo' => ['type' => 'foreign', 'columns' => ['codigo_usuario_endereco_tipo'], 'references' => ['usuario_endereco_tipo', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
//            'fk_usuario_endereco__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_usuario_endereco_tipo' => 1,
                'codigo_usuario' => 1,
                'complemento' => 'Lorem ipsum dolor sit amet',
                'numero' => 1,
                'data_inclusao' => 1574343528,
                'codigo_usuario_inclusao' => 1,
                'latitude' => 1,
                'longitude' => 1,
                'codigo_empresa' => 1,
                'cep' => 'Lorem ',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'estado_descricao' => 'Lorem ipsum dolor sit amet',
                'estado_abreviacao' => 'Lo',
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1574343528
            ],
        ];
        parent::init();
    }
}
