<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FichasClinicasRespostasFixture
 */
class FichasClinicasRespostasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_ficha_clinica_questao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'resposta' => ['type' => 'string', 'length' => 5000, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'campo_livre' => ['type' => 'string', 'length' => 5000, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_ficha_clinica' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'parentesco' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        '_indexes' => [
            'idx_codigo_ficha_clinica' => ['type' => 'index', 'columns' => ['codigo_ficha_clinica'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_fichas_clinicas_respostas__codigo_ficha_clinica_questao' => ['type' => 'foreign', 'columns' => ['codigo_ficha_clinica_questao'], 'references' => ['fichas_clinicas_questoes', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_fichas_clinicas_respostas__codigo_fichas_clinicas' => ['type' => 'foreign', 'columns' => ['codigo_ficha_clinica'], 'references' => ['fichas_clinicas', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_ficha_clinica_questao' => 1,
                'resposta' => 'Lorem ipsum dolor sit amet',
                'campo_livre' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1586268789,
                'codigo_ficha_clinica' => 1,
                'parentesco' => 'Lorem ipsum dolor sit amet'
            ],
        ];
        parent::init();
    }
}
