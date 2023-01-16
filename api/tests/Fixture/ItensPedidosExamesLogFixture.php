<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ItensPedidosExamesLogFixture
 */
class ItensPedidosExamesLogFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'itens_pedidos_exames_log';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_itens_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_exame' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'valor' => ['type' => 'decimal', 'length' => 14, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipo_atendimento' => ['type' => 'smallinteger', 'length' => 5, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'data_agendamento' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'hora_agendamento' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_tipos_exames_pedidos' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipo_agendamento' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_cliente_assinatura' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_realizacao_exame' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'compareceu' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'recebimento_digital' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'recebimento_enviado' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'acao_sistema' => ['type' => 'tinyinteger', 'length' => 3, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_notificacao_nc' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'valor_custo' => ['type' => 'decimal', 'length' => 14, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
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
                'codigo_itens_pedidos_exames' => 1,
                'codigo_pedidos_exames' => 1,
                'codigo_exame' => 1,
                'valor' => 1.5,
                'codigo_fornecedor' => 1,
                'tipo_atendimento' => 1,
                'data_agendamento' => '2021-01-06',
                'hora_agendamento' => 'Lor',
                'codigo_tipos_exames_pedidos' => 1,
                'tipo_agendamento' => 1,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1609954121,
                'codigo_cliente_assinatura' => 1,
                'data_realizacao_exame' => '2021-01-06',
                'compareceu' => 1,
                'recebimento_digital' => 1,
                'recebimento_enviado' => 1,
                'acao_sistema' => 1,
                'data_alteracao' => 1609954121,
                'codigo_usuario_alteracao' => 1,
                'data_notificacao_nc' => 1609954121,
                'valor_custo' => 1.5,
            ],
        ];
        parent::init();
    }
}
