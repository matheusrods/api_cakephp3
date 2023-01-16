<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnderecoBairroFixture
 */
class EnderecoBairroFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'endereco_bairro';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_endereco_cidade' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_correio' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'descricao' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_endereco_distrito' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'abreviacao' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        '_indexes' => [
            'ix_endereco_bairro__codigo' => ['type' => 'index', 'columns' => ['codigo'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'uk_endereco_bairro__codigo_endereco_cidade__codigo_correio__descricao' => ['type' => 'unique', 'columns' => ['codigo_endereco_cidade', 'codigo_correio', 'descricao'], 'length' => []],
            'fk_endereco_bairro__endereco_cidade' => ['type' => 'foreign', 'columns' => ['codigo_endereco_cidade'], 'references' => ['endereco_cidade', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_bairro__usuario' => ['type' => 'foreign', 'columns' => ['codigo_usuario_inclusao'], 'references' => ['usuario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_endereco_bairro__endereco_distrito' => ['type' => 'foreign', 'columns' => ['codigo_endereco_distrito'], 'references' => ['endereco_distrito', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_endereco_cidade' => 1,
                'codigo_correio' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1600253267,
                'codigo_usuario_inclusao' => 1,
                'codigo_endereco_distrito' => 1,
                'abreviacao' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
