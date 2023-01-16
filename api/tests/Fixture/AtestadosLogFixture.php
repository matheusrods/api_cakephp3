<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AtestadosLogFixture
 */
class AtestadosLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'atestados_log';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_atestado' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cliente_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_medico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_afastamento_periodo' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_retorno_periodo' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'afastamento_em_horas' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_afastamento_hr' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'hora_afastamento' => ['type' => 'time', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'hora_retorno' => ['type' => 'time', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_motivo_esocial' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_motivo_licenca' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'restricao' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_cid_contestato' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'imprimi_cid_atestado' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'acidente_trajeto' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'endereco' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'numero' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'complemento' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'bairro' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cep' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_estado' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cidade' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_tipo_local_atendimento' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'latitude' => ['type' => 'float', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'longitude' => ['type' => 'float', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'afastamento_em_dias' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'habilita_afastamento_em_horas' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'acao_sistema' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'estado' => ['type' => 'string', 'fixed' => true, 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'cidade' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
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
                'codigo_atestado' => 1,
                'codigo_cliente_funcionario' => 1,
                'codigo_medico' => 1,
                'data_afastamento_periodo' => '2020-06-04',
                'data_retorno_periodo' => '2020-06-04',
                'afastamento_em_horas' => 'Lorem ip',
                'data_afastamento_hr' => '2020-06-04',
                'hora_afastamento' => '20:02:36',
                'hora_retorno' => '20:02:36',
                'codigo_motivo_esocial' => 1,
                'codigo_motivo_licenca' => 1,
                'restricao' => 'L',
                'codigo_cid_contestato' => 1,
                'imprimi_cid_atestado' => 1,
                'acidente_trajeto' => 1,
                'endereco' => 'L',
                'numero' => 'L',
                'complemento' => 'L',
                'bairro' => 'L',
                'cep' => 'L',
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1591311756,
                'codigo_estado' => 1,
                'codigo_cidade' => 1,
                'codigo_tipo_local_atendimento' => 1,
                'latitude' => 1,
                'longitude' => 1,
                'afastamento_em_dias' => 1,
                'habilita_afastamento_em_horas' => 1,
                'acao_sistema' => 1,
                'estado' => 'Lo',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1591311756,
                'codigo_empresa' => 1,
                'ativo' => 1,
            ],
        ];
        parent::init();
    }
}
