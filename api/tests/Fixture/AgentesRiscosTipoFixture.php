<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgentesRiscosTipoFixture
 */
class AgentesRiscosTipoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'agentes_riscos_tipo';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'descricao' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_agente_risco_tipo_metodo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_perigo_risco' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_risco_tipo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_art_codigo_agente_risco_tipo_metodo' => ['type' => 'foreign', 'columns' => ['codigo_agente_risco_tipo_metodo'], 'references' => ['agentes_riscos_tipo_metodos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_art_codigo_perigo_risco' => ['type' => 'foreign', 'columns' => ['codigo_perigo_risco'], 'references' => ['perigos_riscos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_art_codigo_risco_tipo' => ['type' => 'foreign', 'columns' => ['codigo_risco_tipo'], 'references' => ['perigos_riscos_tipo', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'descricao' => 'Lorem ipsum dolor sit amet',
                'codigo_agente_risco_tipo_metodo' => 1,
                'codigo_perigo_risco' => 1,
                'codigo_risco_tipo' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1596467948,
                'data_alteracao' => 1596467948,
            ],
        ];
        parent::init();
    }
}
