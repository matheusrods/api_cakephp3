<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DocumentoFixture
 */
class DocumentoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'documento';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'string', 'length' => 14, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_pais' => ['type' => 'tinyinteger', 'length' => 3, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'tipo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_documento__endereco_pais' => ['type' => 'foreign', 'columns' => ['codigo_pais'], 'references' => ['endereco_pais', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_documento__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo' => '5e2ec9ee-761b-4e75-858a-9fdadcc3f383',
                'codigo_pais' => 1,
                'tipo' => 1,
                'data_inclusao' => 1592412455,
                'codigo_usuario_inclusao' => 1,
                'codigo_empresa' => 1,
            ],
        ];
        parent::init();
    }
}
