<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ExamesFixture
 */
class ExamesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_servico' => ['type' => 'smallinteger', 'length' => 5, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_rh' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'descricao' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'periodo_meses' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'periodo_apos_demissao' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_tabela_amb' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_tuss' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_ch' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'empresa_cliente' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_auto' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'laboral' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'tela_resultado' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'referencia' => ['type' => 'string', 'length' => 1000, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'unidade_medida' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'recomendacoes' => ['type' => 'string', 'length' => 1000, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'sexo' => ['type' => 'string', 'length' => 1, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'conduta_exame' => ['type' => 'string', 'length' => 1000, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'controla_validacoes' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_esocial' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'material_biologico' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'interpretacao_exame' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_incio_monitoracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'exame_excluido_convocacao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_excluido_ppp' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_excluido_aso' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_excluido_pcmso' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_excluido_anual' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_excluido_rac' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_admissional' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_periodico' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_demissional' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_retorno' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'exame_mudanca' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'periodo_idade' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'qtd_periodo_idade' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_empresa' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'qualidade_vida' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'periodo_idade_2' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'qtd_periodo_idade_2' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'periodo_idade_3' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'qtd_periodo_idade_3' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'periodo_idade_4' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'qtd_periodo_idade_4' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'exame_audiometria' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'exame_monitoracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'codigo_usuario_alteracao' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_alteracao' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'codigo_esocial_27' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_indexes' => [
            'ix_exames__descricao' => ['type' => 'index', 'columns' => ['descricao'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            // 'fk_exames__codigo_servico' => ['type' => 'foreign', 'columns' => ['codigo_servico'], 'references' => ['servico', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_servico' => 1,
                'codigo_rh' => 1,
                'descricao' => 'Lorem ipsum dolor sit amet',
                'periodo_meses' => 'Lor',
                'periodo_apos_demissao' => 'Lor',
                'codigo_tabela_amb' => 1,
                'codigo_tuss' => 1,
                'codigo_ch' => 1,
                'empresa_cliente' => 1,
                'exame_auto' => 1,
                'laboral' => 1,
                'tela_resultado' => 'Lorem ipsum dolor sit amet',
                'referencia' => 'Lorem ipsum dolor sit amet',
                'unidade_medida' => 'Lorem ip',
                'recomendacoes' => 'Lorem ipsum dolor sit amet',
                'sexo' => 'L',
                'conduta_exame' => 'Lorem ipsum dolor sit amet',
                'controla_validacoes' => 1,
                'codigo_esocial' => 'Lorem ipsum dolor sit amet',
                'material_biologico' => 'Lorem ip',
                'interpretacao_exame' => 'Lorem ip',
                'data_incio_monitoracao' => 1578428911,
                'exame_excluido_convocacao' => 1,
                'exame_excluido_ppp' => 1,
                'exame_excluido_aso' => 1,
                'exame_excluido_pcmso' => 1,
                'exame_excluido_anual' => 1,
                'exame_excluido_rac' => 1,
                'exame_admissional' => 1,
                'exame_periodico' => 1,
                'exame_demissional' => 1,
                'exame_retorno' => 1,
                'exame_mudanca' => 1,
                'periodo_idade' => 'Lorem ipsum dolor sit amet',
                'data_inclusao' => 1578428911,
                'codigo_usuario_inclusao' => 1,
                'ativo' => 1,
                'qtd_periodo_idade' => 'Lor',
                'codigo_empresa' => 1,
                'qualidade_vida' => 1,
                'periodo_idade_2' => 'Lor',
                'qtd_periodo_idade_2' => 'Lor',
                'periodo_idade_3' => 'Lor',
                'qtd_periodo_idade_3' => 'Lor',
                'periodo_idade_4' => 'Lor',
                'qtd_periodo_idade_4' => 'Lor',
                'exame_audiometria' => 1,
                'exame_monitoracao' => 1,
                'codigo_usuario_alteracao' => 1,
                'data_alteracao' => 1578428911,
                'codigo_esocial_27' => 1
            ],
        ];
        parent::init();
    }
}
