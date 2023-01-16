<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgentesRiscosClientesFixture
 */
class AgentesRiscosClientesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_arrtpa_ri' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_agente_risco' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_arc_codigo_arrtpa_ri' => ['type' => 'foreign', 'columns' => ['codigo_arrtpa_ri'], 'references' => ['arrtpa_ri', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_arc_codigo_agente_risco' => ['type' => 'foreign', 'columns' => ['codigo_agente_risco'], 'references' => ['agentes_riscos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_cliente' => 1,
                'codigo_arrtpa_ri' => 1,
                'codigo_agente_risco' => 1,
            ],
        ];
        parent::init();
    }
}
