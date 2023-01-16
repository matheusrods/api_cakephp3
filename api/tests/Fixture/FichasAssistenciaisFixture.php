<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FichasAssistenciaisFixture
 */
class FichasAssistenciaisFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_pedido_exame' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_medico' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pa_sistolica' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pa_diastolica' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pulso' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'circunferencia_abdominal' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'circunferencia_quadril' => ['type' => 'decimal', 'length' => 5, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'peso_kg' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'peso_gr' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'altura_mt' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'altura_cm' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'imc' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'parecer' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'parecer_altura' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'parecer_espaco_confinado' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_atestado' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hora_inicio_atendimento' => ['type' => 'time', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'hora_fim_atendimento' => ['type' => 'time', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_fichas_assistenciais__codigo_pedido_exame' => ['type' => 'foreign', 'columns' => ['codigo_pedido_exame'], 'references' => ['pedidos_exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_fichas_assistenciais__codigo_medico' => ['type' => 'foreign', 'columns' => ['codigo_medico'], 'references' => ['medicos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_pedido_exame' => 1,
                'codigo_medico' => 1,
                'pa_sistolica' => 1,
                'pa_diastolica' => 1,
                'pulso' => 1,
                'circunferencia_abdominal' => 1.5,
                'circunferencia_quadril' => 1.5,
                'peso_kg' => 1,
                'peso_gr' => 1,
                'altura_mt' => 1,
                'altura_cm' => 1,
                'imc' => 1,
                'parecer' => 1,
                'parecer_altura' => 1,
                'parecer_espaco_confinado' => 1,
                'codigo_atestado' => 1,
                'ativo' => 1,
                'codigo_empresa' => 1,
                'data_inclusao' => 1586459796,
                'codigo_usuario_inclusao' => 1,
                'hora_inicio_atendimento' => '16:16:36',
                'hora_fim_atendimento' => '16:16:36'
            ],
        ];
        parent::init();
    }
}
