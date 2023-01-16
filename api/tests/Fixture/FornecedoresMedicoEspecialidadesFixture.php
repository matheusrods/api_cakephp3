<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FornecedoresMedicoEspecialidadesFixture
 */
class FornecedoresMedicoEspecialidadesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_medico' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_especialidade' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_fornecedores_medico_especialidades__codigo_fornecedor' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_fornecedores_medico_especialidades_medicos' => ['type' => 'foreign', 'columns' => ['codigo_medico'], 'references' => ['medicos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_fornecedores_medico_especialidades_especialidades' => ['type' => 'foreign', 'columns' => ['codigo_especialidade'], 'references' => ['especialidades', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_fornecedor' => 1,
                'codigo_medico' => 1,
                'codigo_especialidade' => 1,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1590013872,
                'codigo_empresa' => 1
            ],
        ];
        parent::init();
    }
}
