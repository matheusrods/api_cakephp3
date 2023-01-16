<?php
namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * MedicamentosFixture
 */
class MedicamentosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'descricao' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null,'precision' => null, 'comment' => null, 'fixed' => null],
        'principio_ativo' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_laboratorio' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_barras' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null,  'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_apresentacao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'posologia' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null,  'precision' => null, 'comment' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
//            'fk__medicamentos__apresentacoes' => ['type' => 'foreign', 'columns' => ['codigo_apresentacao'], 'references' => ['apresentacoes', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
//            'fk_medicamentos__codigo_medicamento' => ['type' => 'foreign', 'columns' => ['codigo_laboratorio'], 'references' => ['laboratorios', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
    ];
//     @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $time = new Time('2020-11-03');
        $this->records = [
            [
                'descricao' => 'CEWIN',
                'principio_ativo' => 'ÁCIDO ASCÓRBICO',
                'codigo_laboratorio' => 1,
                'codigo_barras' => null,
                'data_inclusao' => $time,
                'codigo_usuario_inclusao' => 1,
                'ativo' => true,
                'codigo_empresa' => 1,
                'codigo_apresentacao' => 1,
                'posologia' => '1 G'
            ],
            [
                'descricao' => 'CEWIN',
                'principio_ativo' => 'ÁCIDO ASCÓRBICO',
                'codigo_laboratorio' => 1,
                'codigo_barras' => null,
                'data_inclusao' => $time,
                'codigo_usuario_inclusao' => 1,
                'ativo' => true,
                'codigo_empresa' => 1,
                'codigo_apresentacao' => 1,
                'posologia' => '2 G'
            ],
        ];
        parent::init();
    }
}
