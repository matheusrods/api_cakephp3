<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnderecoCepFixture
 */
class EnderecoCepFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'endereco_cep';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco_pais' => ['type' => 'tinyinteger', 'length' => 3, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'cep' => ['type' => 'string', 'length' => 8, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_indexes' => [
            'ix_endereco_cep__codigo__inc__cep' => ['type' => 'index', 'columns' => ['codigo', 'cep'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'uk_endereco_cep__codigo_postal_pais' => ['type' => 'unique', 'columns' => ['cep', 'codigo_endereco_pais'], 'length' => []],
            'fk_endereco_cep__endereco_pais' => ['type' => 'foreign', 'columns' => ['codigo_endereco_pais'], 'references' => ['endereco_pais', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_cep__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'cep' => 'Lorem ',
                'data_inclusao' => 1600253965,
                'codigo_usuario_inclusao' => 1,
            ],
        ];
        parent::init();
    }
}
