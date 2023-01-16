<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnderecoCidadeFixture
 */
class EnderecoCidadeFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'endereco_cidade';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco_estado' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco_cep' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_correio' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'descricao' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'abreviacao' => ['type' => 'string', 'length' => 64, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'invalido' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'ibge' => ['type' => 'string', 'length' => 7, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        '_indexes' => [
            'ix_endereco_cidade__descricao' => ['type' => 'index', 'columns' => ['descricao'], 'length' => []],
            'ix_endereco_cidade__codigo__codigo_endereco_estado__inc__desc' => ['type' => 'index', 'columns' => ['codigo', 'codigo_endereco_estado', 'descricao'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_endereco_cidade__endereco_estado' => ['type' => 'foreign', 'columns' => ['codigo_endereco_estado'], 'references' => ['endereco_estado', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_cidade__endereco_cep' => ['type' => 'foreign', 'columns' => ['codigo_endereco_cep'], 'references' => ['endereco_cep', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_cidade__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_endereco_estado' => 1,
                'codigo_endereco_cep' => 1,
                'codigo_correio' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1600252533,
                'codigo_usuario_inclusao' => 1,
                'abreviacao' => 'Lorem ipsum dolor sit amet',
                'invalido' => 1,
                'ibge' => 'Lorem',
            ],
        ];
        parent::init();
    }
}
