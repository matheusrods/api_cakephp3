<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PacientesDadosTrabalhoFixture
 */
class PacientesDadosTrabalhoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'pacientes_dados_trabalho';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_paciente' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_setor' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_pacientes_categoria' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_fornecedor' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'tinyinteger', 'length' => 3, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_pdt_codigo_paciente' => ['type' => 'foreign', 'columns' => ['codigo_paciente'], 'references' => ['pacientes', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_pdt_codigo_pacientes_categoria' => ['type' => 'foreign', 'columns' => ['codigo_pacientes_categoria'], 'references' => ['pacientes_categoria', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_pdt_codigo_fornecedor' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_paciente' => 1,
                'codigo_cliente' => 1,
                'codigo_setor' => 1,
                'codigo_pacientes_categoria' => 1,
                'codigo_fornecedor' => 1,
                'codigo_empresa' => 1,
                'ativo' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1586461398,
                'data_alteracao' => 1586461398,
            ],
        ];
        parent::init();
    }
}
