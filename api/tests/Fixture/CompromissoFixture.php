<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CompromissoFixture
 */
class CompromissoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'compromisso';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_medico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hora_inicio' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'hora_fim' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'titulo' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'descricao' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_compromisso__codigo_medico' => ['type' => 'foreign', 'columns' => ['codigo_medico'], 'references' => ['medicos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_medico' => 1,
                'hora_inicio' => 1,
                'hora_fim' => 1,
                'titulo' => 'Lorem ipsum dolor sit amet',
                'descricao' => 'Lorem ipsum dolor sit amet',
                'ativo' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1587573789,
                'data_alteracao' => 1587573789,
                'data' => '2020-04-22'
            ],
        ];
        parent::init();
    }
}
