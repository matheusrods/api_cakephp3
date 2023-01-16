<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnderecoEstadoFixture
 */
class EnderecoEstadoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'endereco_estado';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco_pais' => ['type' => 'tinyinteger', 'length' => 3, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'abreviacao' => ['type' => 'string', 'fixed' => true, 'length' => 2, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'descricao' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'uk_endereco_estado__descricao' => ['type' => 'unique', 'columns' => ['descricao'], 'length' => []],
            'uk_endereco_estado__codigo_pais_abreviacao' => ['type' => 'unique', 'columns' => ['codigo_endereco_pais', 'abreviacao'], 'length' => []],
            'fk_endereco_estado__endereco_pais' => ['type' => 'foreign', 'columns' => ['codigo_endereco_pais'], 'references' => ['endereco_pais', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_estado__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_endereco_pais' => 1,
                'abreviacao' => 'Lo',
                'descricao' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1586465431,
                'codigo_usuario_inclusao' => 1
            ],
        ];
        parent::init();
    }
}
