<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QrCodeLeituraFixture
 */
class QrCodeLeituraFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'qr_code_leitura';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_resultado_covid' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_codigo_usuario__qr_code_leitura' => ['type' => 'foreign', 'columns' => ['codigo_usuario'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_codigo_grupo_covid__qr_code_leitura' => ['type' => 'foreign', 'columns' => ['codigo_resultado_covid'], 'references' => ['resultado_covid', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_usuario' => 1,
                'codigo_resultado_covid' => 1,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1600968240,
            ],
        ];
        parent::init();
    }
}
