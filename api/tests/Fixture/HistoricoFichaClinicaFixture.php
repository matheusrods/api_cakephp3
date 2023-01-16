<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HistoricoFichaClinicaFixture
 */
class HistoricoFichaClinicaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'historico_ficha_clinica';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_nexo' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_pedido_exame_nexo' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cnpj_grupo_economico' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cnpj_unidade' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'setor' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cargo' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'funcionario_matricula' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cpf' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'idade' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'sexo' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'exame_ocupacional' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'tipo_atendimento' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cd_usu' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'medico' => ['type' => 'string', 'length' => 250, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_atendimento' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'observacoes' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
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
                'codigo_nexo' => 'Lorem ipsum dolor sit amet',
                'codigo_pedido_exame_nexo' => 'Lorem ipsum dolor sit amet',
                'cnpj_grupo_economico' => 'Lorem ipsum dolor sit amet',
                'cnpj_unidade' => 'Lorem ipsum dolor sit amet',
                'setor' => 'Lorem ipsum dolor sit amet',
                'cargo' => 'Lorem ipsum dolor sit amet',
                'funcionario_matricula' => 'Lorem ipsum dolor sit amet',
                'cpf' => 'Lorem ipsum dolor sit amet',
                'idade' => 'Lorem ipsum dolor sit amet',
                'sexo' => 'Lorem ipsum dolor sit amet',
                'exame_ocupacional' => 'Lorem ipsum dolor sit amet',
                'tipo_atendimento' => 'Lorem ipsum dolor sit amet',
                'cd_usu' => 'Lorem ipsum dolor sit amet',
                'medico' => 'Lorem ipsum dolor sit amet',
                'data_atendimento' => '2020-04-07',
                'observacoes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
            ],
        ];
        parent::init();
    }
}
