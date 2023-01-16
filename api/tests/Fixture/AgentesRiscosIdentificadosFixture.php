<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgentesRiscosIdentificadosFixture
 */
class AgentesRiscosIdentificadosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_agente_risco' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_agente_risco_tipo' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_ari_codigo_agente_risco' => ['type' => 'foreign', 'columns' => ['codigo_agente_risco'], 'references' => ['agentes_riscos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_ari_codigo_agente_risco_tipo' => ['type' => 'foreign', 'columns' => ['codigo_agente_risco_tipo'], 'references' => ['agentes_riscos_tipo', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_agente_risco' => 1,
                'codigo_agente_risco_tipo' => 1,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_inclusao' => 1596467222,
                'data_alteracao' => 1596467222,
            ],
        ];
        parent::init();
    }
}
