<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncionariosFixture
 */
class FuncionariosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'nome' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_nascimento' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'rg' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'rg_orgao' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cpf' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'sexo' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'status' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'ctps' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ctps_data_emissao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'gfip' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'rg_data_emissao' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'nit' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ctps_serie' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cns' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ctps_uf' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'email' => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'estado_civil' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'deficiencia' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'rg_uf' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'nome_mae' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_indexes' => [
            'ix_funcionarios__cpf' => ['type' => 'index', 'columns' => ['cpf'], 'length' => []],
            'ix_funcionarios__nome' => ['type' => 'index', 'columns' => ['nome'], 'length' => []],
            'ix_funcionarios__data_nascimento' => ['type' => 'index', 'columns' => ['data_nascimento'], 'length' => []],
        ],
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
//                'codigo' => 1,
                'nome' => 'Lorem ipsum dolor sit amet',
                'data_nascimento' => '2019-09-23',
                'rg' => 'Lorem ipsum dolor ',
                'rg_orgao' => 'Lorem',
                'cpf' => 'Lorem ipsum dolor sit a',
                'sexo' => 'Lo',
                'status' => 1,
                'data_inclusao' => 1569258583,
                'ctps' => 'Lorem ipsum dolor sit a',
                'ctps_data_emissao' => 1569258583,
                'gfip' => 'Lorem ipsum dolor sit a',
                'rg_data_emissao' => 'Lorem ipsum dolor sit a',
                'nit' => 'Lorem ipsum dolor sit a',
                'ctps_serie' => 'Lorem ipsum dolor sit a',
                'cns' => 'Lorem ipsum dolor sit a',
                'ctps_uf' => 'Lorem ipsum dolor sit a',
                'codigo_empresa' => 1,
                'email' => 'Lorem ipsum dolor sit amet',
                'estado_civil' => 1,
                'deficiencia' => 1,
                'rg_uf' => 'Lorem ipsum dolor sit a',
                'nome_mae' => 'Lorem ipsum dolor sit amet',
                'data_alteracao' => 1569258583,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1
            ],
        ];
        parent::init();
    }
}
