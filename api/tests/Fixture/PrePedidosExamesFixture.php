<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PrePedidosExamesFixture
 */
class PrePedidosExamesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_cliente_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => 'getdate', 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'endereco_parametro_busca' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_admissional' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_periodico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_demissional' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_retorno' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_mudanca' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'qualidade_vida' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_status_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'portador_deficiencia' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pontual' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_notificacao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'data_solicitacao' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_pedidos_lote' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'em_emissao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_motivo_cancelamento' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_func_setor_cargo' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_monitoracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_paciente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_motivo_conclusao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'descricao_motivo_conclusao' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null],
        'aso_embarcados' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'FK__pre_pedid__codig__346A7DCC' => ['type' => 'foreign', 'columns' => ['codigo_cliente_funcionario'], 'references' => ['cliente_funcionario', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK__pre_pedid__codig__355EA205' => ['type' => 'foreign', 'columns' => ['codigo_empresa'], 'references' => ['multi_empresa', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk__pre_pedidos_exames__cliente' => ['type' => 'foreign', 'columns' => ['codigo_cliente'], 'references' => ['cliente', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk__pre_pedidos_exames__funcionarios' => ['type' => 'foreign', 'columns' => ['codigo_funcionario'], 'references' => ['funcionarios', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'pre_pedidos_exames_codigo_status_pedidos_exames' => ['type' => 'foreign', 'columns' => ['codigo_status_pedidos_exames'], 'references' => ['status_pedidos_exames', 'codigo'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'fk_pre_pedidos_exames__codigo_motivo_cancelamento' => ['type' => 'foreign', 'columns' => ['codigo_motivo_cancelamento'], 'references' => ['motivos_cancelamento', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'FK_pre_pedidos_exames__codigo_func_setor_cargo' => ['type' => 'foreign', 'columns' => ['codigo_func_setor_cargo'], 'references' => ['funcionario_setores_cargos', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_pre_pedidos_exames__codigo_motivo_conclusao' => ['type' => 'foreign', 'columns' => ['codigo_motivo_conclusao'], 'references' => ['motivos_conclusao_parcial', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_cliente_funcionario' => 1,
                'codigo_empresa' => 1,
                'data_inclusao' => 1612370966,
                'codigo_usuario_inclusao' => 1,
                'endereco_parametro_busca' => 'Lorem ipsum dolor sit amet',
                'codigo_cliente' => 1,
                'codigo_funcionario' => 1,
                'exame_admissional' => 1,
                'exame_periodico' => 1,
                'exame_demissional' => 1,
                'exame_retorno' => 1,
                'exame_mudanca' => 1,
                'qualidade_vida' => 1,
                'codigo_status_pedidos_exames' => 1,
                'portador_deficiencia' => 1,
                'pontual' => 1,
                'data_notificacao' => 1612370966,
                'data_solicitacao' => '2021-02-03',
                'codigo_pedidos_lote' => 1,
                'em_emissao' => 1,
                'codigo_motivo_cancelamento' => 1,
                'codigo_func_setor_cargo' => 1,
                'codigo_usuario_alteracao' => 1,
                'exame_monitoracao' => 1,
                'data_alteracao' => 1612370966,
                'codigo_paciente' => 1,
                'codigo_motivo_conclusao' => 1,
                'descricao_motivo_conclusao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'aso_embarcados' => 1,
            ],
        ];
        parent::init();
    }
}
