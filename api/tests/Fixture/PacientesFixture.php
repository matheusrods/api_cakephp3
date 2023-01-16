<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PacientesFixture
 */
class PacientesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'nome' => ['type' => 'string', 'length' => 60, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cpf' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'rg' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_nascimento' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'email' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'telefone' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ativo' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
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
                'nome' => 'Lorem ipsum dolor sit amet',
                'cpf' => 'L',
                'rg' => 'L',
                'data_nascimento' => 1586460441,
                'email' => 'Lorem ipsum dolor sit amet',
                'telefone' => 'Lorem ipsum d',
                'ativo' => 1,
                'codigo_empresa' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1586460441,
                'data_alteracao' => 1586460441,
            ],
        ];
        parent::init();
    }
}
