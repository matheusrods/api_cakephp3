<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FichaPsicossocialRespostasFixture
 */
class FichaPsicossocialRespostasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'resposta' => ['type' => 'string', 'length' => 500, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ativo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ordem' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_ficha_psicossocial' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_ficha_psicossocial_perguntas' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'codigo_ficha_psicossocial' => ['type' => 'foreign', 'columns' => ['codigo_ficha_psicossocial'], 'references' => ['ficha_psicossocial', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'codigo_ficha_psicossocial_perguntas' => ['type' => 'foreign', 'columns' => ['codigo_ficha_psicossocial_perguntas'], 'references' => ['ficha_psicossocial_perguntas', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'resposta' => 'Lorem ipsum dolor sit amet',
                'ativo' => 1,
                'ordem' => 1,
                'data_inclusao' => 1586443015,
                'codigo_ficha_psicossocial' => 1,
                'codigo_ficha_psicossocial_perguntas' => 1
            ],
        ];
        parent::init();
    }
}
