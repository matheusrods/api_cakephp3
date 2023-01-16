<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuariosMedicamentosFixture
 */
class UsuariosMedicamentosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
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
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
//            'FK_usuarios_medicamentos_usuarios_medicamentos' => ['type' => 'foreign', 'columns' => ['codigo'], 'references' => ['usuarios_medicamentos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
//            'fk_usuarios_medicamentos_medicamentos' => ['type' => 'foreign', 'columns' => ['codigo_medicamentos'], 'references' => ['medicamentos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
//            'fk_usuarios_medicamentos_usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
//            'FK_usuarios_medicamentos_apresentacoes' => ['type' => 'foreign', 'columns' => ['codigo_apresentacao'], 'references' => ['apresentacoes', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_medicamentos' => 1,
                'codigo_usuario' => 1,
                'frequencia_dias' => 1,
                'frequencia_horarios' => 1,
                'uso_continuo' => 1,
                'dias_da_semana' => "seg, ter",
                'frequencia_uso' => 1,
                'horario_inicio_uso' => '10:00',
                'quantidade' => 1,
                'recomendacao_medica' => 'Tomar o medicamento após as principais refeições.',
                'foto_receita' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAA',
                'frequencia_dias_intercalados' => 1,
                'periodo_tratamento_inicio' => '2019-11-19',
                'periodo_tratamento_termino' => '2019-11-19',
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1574196525,
                'data_inclusao' => 1574196525,
                'codigo_apresentacao' => 1
            ],
        ];
        parent::init();
    }
}
