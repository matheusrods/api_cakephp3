<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HazopsMedidasControleFixture
 */
class HazopsMedidasControleFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'hazops_medidas_controle';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_hazop_agente_risco' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_hazop_medida_controle_tipo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'descricao' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_hmc_codigo_hazop_agente_risco' => ['type' => 'foreign', 'columns' => ['codigo_hazop_agente_risco'], 'references' => ['hazops_agentes_riscos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_hmc_codigo_hazop_medida_controle_tipo' => ['type' => 'foreign', 'columns' => ['codigo_hazop_medida_controle_tipo'], 'references' => ['hazops_medidas_controle_tipo', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_hazop_agente_risco' => 1,
                'codigo_hazop_medida_controle_tipo' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1600632102,
                'data_alteracao' => 1600632102,
            ],
        ];
        parent::init();
    }
}
