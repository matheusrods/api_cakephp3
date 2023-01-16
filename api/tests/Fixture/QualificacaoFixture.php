<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * QualificacaoFixture
 */
class QualificacaoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'qualificacao';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_arrtpa_ri' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'qualitativo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'quantitativo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_metodo_tipo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_equipamento_adotado' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_q_codigo_arrtpa_ri' => ['type' => 'foreign', 'columns' => ['codigo_arrtpa_ri'], 'references' => ['arrtpa_ri', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_q_codigo_metodo_tipo' => ['type' => 'foreign', 'columns' => ['codigo_metodo_tipo'], 'references' => ['metodos_tipo', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_q_codigo_equipamento_adotado' => ['type' => 'foreign', 'columns' => ['codigo_equipamento_adotado'], 'references' => ['equipamentos_adotados', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_arrtpa_ri' => 1,
                'qualitativo' => 1,
                'quantitativo' => 1,
                'codigo_metodo_tipo' => 1,
                'codigo_equipamento_adotado' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1597863373,
                'data_alteracao' => 1597863373,
            ],
        ];
        parent::init();
    }
}
