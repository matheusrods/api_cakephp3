<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FornecedoresEnderecoLogFixture
 */
class FornecedoresEnderecoLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'fornecedores_endereco_log';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor_endereco' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_fornecedor' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_tipo_contato' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'complemento' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'numero' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'latitude' => ['type' => 'decimal', 'length' => 10, 'precision' => 8, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'longitude' => ['type' => 'decimal', 'length' => 10, 'precision' => 8, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'cep' => ['type' => 'string', 'fixed' => true, 'length' => 8, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'logradouro' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'bairro' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'cidade' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'estado_descricao' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'estado_abreviacao' => ['type' => 'string', 'fixed' => true, 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'acao_sistema' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_fornecedores_endereco_log__codigo_fornecedor' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_fornecedor_endereco' => 1,
                'codigo_fornecedor' => 1,
                'codigo_tipo_contato' => 1,
                'codigo_endereco' => 1,
                'complemento' => 'Lorem ipsum dolor sit amet',
                'numero' => 'Lorem ip',
                'data_inclusao' => 1592417076,
                'codigo_usuario_inclusao' => 1,
                'codigo_empresa' => 1,
                'latitude' => 1.5,
                'longitude' => 1.5,
                'cep' => 'Lorem ',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'cidade' => 'Lorem ipsum dolor sit amet',
                'estado_descricao' => 'Lorem ipsum dolor sit amet',
                'estado_abreviacao' => 'Lo',
                'acao_sistema' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1592417076,
            ],
        ];
        parent::init();
    }
}
