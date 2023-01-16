<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosMedicamentosLogFixture
 */
class UsuariosMedicamentosLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'usuarios_medicamentos_log';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuarios_medicamentos' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_medicamentos' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'frequencia_dias' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'frequencia_horarios' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'uso_continuo' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'dias_da_semana' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'frequencia_uso' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'horario_inicio_uso' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'quantidade' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'recomendacao_medica' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'foto_receita' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'frequencia_dias_intercalados' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'periodo_tratamento_inicio' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'periodo_tratamento_termino' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_apresentacao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'acao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
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
                'codigo_usuarios_medicamentos' => 1,
                'codigo_medicamentos' => 1,
                'codigo_usuario' => 1,
                'frequencia_dias' => 1,
                'frequencia_horarios' => 1,
                'uso_continuo' => 1,
                'dias_da_semana' => 'Lorem ipsum dolor sit amet',
                'frequencia_uso' => 1,
                'horario_inicio_uso' => 'Lor',
                'quantidade' => 1,
                'recomendacao_medica' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'foto_receita' => 'Lorem ipsum dolor sit amet',
                'frequencia_dias_intercalados' => 1,
                'periodo_tratamento_inicio' => '2020-07-24',
                'periodo_tratamento_termino' => '2020-07-24',
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1595595203,
                'data_inclusao' => 1595595203,
                'codigo_apresentacao' => 1,
                'ativo' => 1,
                'acao' => 1,
            ],
        ];
        parent::init();
    }
}
