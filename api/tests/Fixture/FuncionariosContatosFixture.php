<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncionariosContatosFixture
 */
class FuncionariosContatosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_tipo_contato' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_tipo_retorno' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'ddi' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'ddd' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'descricao' => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'nome' => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ramal' => ['type' => 'smallinteger', 'length' => 5, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'autoriza_envio_sms' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'autoriza_envio_email' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_indexes' => [
            'ix__funcionarios_contatos__funcionario__tipo' => ['type' => 'index', 'columns' => ['codigo_funcionario', 'codigo_tipo_contato', 'codigo_tipo_retorno'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_funcionarios_contatos__codigo_funcionario' => ['type' => 'foreign', 'columns' => ['codigo_funcionario'], 'references' => ['funcionarios', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionarios_contatos__codigo_tipo_contato' => ['type' => 'foreign', 'columns' => ['codigo_tipo_contato'], 'references' => ['tipo_contato', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_funcionarios_contatos__codigo_tipo_retorno' => ['type' => 'foreign', 'columns' => ['codigo_tipo_retorno'], 'references' => ['tipo_retorno', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_funcionario' => 1,
                'codigo_tipo_contato' => 1,
                'codigo_tipo_retorno' => 1,
                'ddi' => 1,
                'ddd' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'nome' => 'Lorem ipsum dolor sit amet',
                'ramal' => 1,
                'autoriza_envio_sms' => 1,
                'autoriza_envio_email' => 1,
                'data_inclusao' => 1589032712,
                'codigo_usuario_inclusao' => 1,
                'codigo_empresa' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1589032712
            ],
        ];
        parent::init();
    }
}
