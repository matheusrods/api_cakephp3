<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeparaQuestoesRespostasFixture
 */
class DeparaQuestoesRespostasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_questao_questionario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_resposta_questionario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'resposta_ficha_clinica' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        '_indexes' => [
            'idx_codigo_questao_questionario' => ['type' => 'index', 'columns' => ['codigo_questao_questionario'], 'length' => []],
            'idx_codigo_resposta_questionario' => ['type' => 'index', 'columns' => ['codigo_resposta_questionario'], 'length' => []],
            'idx_resposta_ficha_clinica' => ['type' => 'index', 'columns' => ['resposta_ficha_clinica'], 'length' => []],
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
                'codigo_questao_questionario' => 1,
                'codigo_resposta_questionario' => 1,
                'resposta_ficha_clinica' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
