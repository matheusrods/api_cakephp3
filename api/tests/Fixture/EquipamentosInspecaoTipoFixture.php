<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EquipamentosInspecaoTipoFixture
 */
class EquipamentosInspecaoTipoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'equipamentos_inspecao_tipo';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_unidade_medicao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'valor' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'limite_tolerancia' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_eit_codigo_unidade_medicao' => ['type' => 'foreign', 'columns' => ['codigo_unidade_medicao'], 'references' => ['unidades_medicao', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_unidade_medicao' => 1,
                'valor' => 1.5,
                'limite_tolerancia' => 1.5,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1597864610,
                'data_alteracao' => 1597864610,
            ],
        ];
        parent::init();
    }
}
