<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncionarioLiberacaoTrabalhoFixture
 */
class FuncionarioLiberacaoTrabalhoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'funcionario_liberacao_trabalho';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_setor' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cargo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inicio_previsao' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_fim_previsao' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_func_setor_cargo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_funcionario_liberacao_trabalho__codigo_cliente' => ['type' => 'foreign', 'columns' => ['codigo_cliente'], 'references' => ['cliente', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionario_liberacao_trabalho__codigo_setor' => ['type' => 'foreign', 'columns' => ['codigo_setor'], 'references' => ['setores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionario_liberacao_trabalho__codigo_cargo' => ['type' => 'foreign', 'columns' => ['codigo_cargo'], 'references' => ['cargos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionario_liberacao_trabalho__codigo_funcionario' => ['type' => 'foreign', 'columns' => ['codigo_funcionario'], 'references' => ['funcionarios', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionario_liberacao_trabalho__codigo_usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_cliente' => 1,
                'codigo_setor' => 1,
                'codigo_cargo' => 1,
                'codigo_funcionario' => 1,
                'data_inicio_previsao' => '2020-10-09',
                'data_fim_previsao' => '2020-10-09',
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1602298305,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1602298305,
                'codigo_func_setor_cargo' => 1,
            ],
        ];
        parent::init();
    }
}
