<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DeparaQuestoesFixture
 */
class DeparaQuestoesFixture extends TestFixture
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
        'codigo_questao_ficha_clinica' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_indexes' => [
            'idx_codigo_questao_ficha_clinica' => ['type' => 'index', 'columns' => ['codigo_questao_ficha_clinica'], 'length' => []],
            'idx_codigo_questao_questionario' => ['type' => 'index', 'columns' => ['codigo_questao_questionario'], 'length' => []],
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
                'codigo_questao_ficha_clinica' => 1
            ],
        ];
        parent::init();
    }
}
