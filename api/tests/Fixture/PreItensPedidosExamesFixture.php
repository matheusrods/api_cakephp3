<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PreItensPedidosExamesFixture
 */
class PreItensPedidosExamesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_exame' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'valor' => ['type' => 'decimal', 'length' => 14, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'codigo_fornecedor' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipo_atendimento' => ['type' => 'smallinteger', 'length' => 5, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'data_agendamento' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'hora_agendamento' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_tipos_exames_pedidos' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tipo_agendamento' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_cliente_assinatura' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_realizacao_exame' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'compareceu' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'recebimento_digital' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'recebimento_enviado' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'data_notificacao_nc' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'valor_custo' => ['type' => 'decimal', 'length' => 14, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_status_itens_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hora_realizacao_exame' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'laudo' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'observacao' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'codigo_medico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_cliente_pagador' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_servico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'valor_receita' => ['type' => 'decimal', 'length' => 14, 'precision' => 2, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'data_inicio_triagem' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_fim_triagem' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_inicio_realizacao_exame' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'respondido_lyn' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'FK__pre_itens__codig__3FDC3078' => ['type' => 'foreign', 'columns' => ['codigo_pedidos_exames'], 'references' => ['pre_pedidos_exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK__pre_itens__codig__40D054B1' => ['type' => 'foreign', 'columns' => ['codigo_exame'], 'references' => ['exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_pre_itens_pedidos_exames__codigo_fornecedor' => ['type' => 'foreign', 'columns' => ['codigo_fornecedor'], 'references' => ['fornecedores', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_pre_itens_pedidos_exames__codigo_tipos_exames_pedidos' => ['type' => 'foreign', 'columns' => ['codigo_tipos_exames_pedidos'], 'references' => ['tipos_exames_pedidos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK__pre_itens__codig__41C478EA' => ['type' => 'foreign', 'columns' => ['codigo_status_itens_pedidos_exames'], 'references' => ['status_itens_pedidos_exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_pedidos_exames' => 1,
                'codigo_exame' => 1,
                'valor' => 1.5,
                'codigo_fornecedor' => 1,
                'tipo_atendimento' => 1,
                'data_agendamento' => '2021-02-03',
                'hora_agendamento' => 'Lor',
                'codigo_tipos_exames_pedidos' => 1,
                'tipo_agendamento' => 1,
                'data_inclusao' => 1612371992,
                'codigo_cliente_assinatura' => 1,
                'data_realizacao_exame' => '2021-02-03',
                'compareceu' => 1,
                'recebimento_digital' => 1,
                'recebimento_enviado' => 1,
                'data_notificacao_nc' => 1612371992,
                'valor_custo' => 1.5,
                'codigo_usuario_inclusao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1612371992,
                'codigo_status_itens_pedidos_exames' => 1,
                'hora_realizacao_exame' => 'Lor',
                'laudo' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'observacao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'codigo_medico' => 1,
                'codigo_cliente_pagador' => 1,
                'codigo_servico' => 1,
                'valor_receita' => 1.5,
                'data_inicio_triagem' => 1612371992,
                'data_fim_triagem' => 1612371992,
                'data_inicio_realizacao_exame' => 1612371992,
                'respondido_lyn' => 1,
            ],
        ];
        parent::init();
    }
}
