<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AudiometriasFixture
 */
class AudiometriasFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null],
        'codigo_funcionario' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_exame' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'tipo_exame' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'resultado' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'aparelho' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'ref_seq' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'fabricante' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'calibracao' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'esq_va_025' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_050' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_1' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_2' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_3' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_4' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_6' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_va_8' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_025' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_050' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_1' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_2' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_3' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_4' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_6' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_va_8' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_025' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_050' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_1' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_2' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_3' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_4' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_6' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'esq_vo_8' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_025' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_050' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_1' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_2' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_3' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_4' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_6' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'dir_vo_8' => ['type' => 'decimal', 'length' => 3, 'precision' => 0, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'codigo_usuario_inclusao' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'data_inclusao' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null],
        'em_analise' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'ocupacional' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'agravamento' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'estavel' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => 0, 'precision' => null, 'comment' => null],
        'ouve_bem' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'zumbido_ouvido' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'trauma_ouvidos' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'doenca_auditiva' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'local_ruidoso' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'realizou_exame' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'repouso_auditivo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'precision' => null, 'comment' => null],
        'horas_repouso_auditivo' => ['type' => 'decimal', 'length' => 3, 'precision' => 1, 'null' => true, 'default' => null, 'comment' => null, 'unsigned' => null],
        'observacoes' => ['type' => 'string', 'length' => 500, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'meatoscopia_od' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'meatoscopia_oe' => ['type' => 'integer', 'length' => 10, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        'str_od_dbna' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'str_oe_dbna' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'irf_od' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'irf_oe' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'laf_od_dbna' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'laf_oe_dbna' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'observacoes2' => ['type' => 'string', 'length' => 500, 'null' => true, 'default' => null, 'collate' => 'SQL_Latin1_General_CP1_CI_AS', 'precision' => null, 'comment' => null, 'fixed' => null],
        'codigo_itens_pedidos_exames' => ['type' => 'integer', 'length' => 10, 'null' => false, 'default' => null, 'precision' => null, 'comment' => null, 'unsigned' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'fk_audiometrias__codigo_itens_pedidos_exames' => ['type' => 'foreign', 'columns' => ['codigo_itens_pedidos_exames'], 'references' => ['itens_pedidos_exames', 'codigo'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
                'codigo_funcionario' => 1,
                'data_exame' => '2020-04-08',
                'tipo_exame' => 1,
                'resultado' => 1,
                'aparelho' => 'Lorem ipsum dolor sit amet',
                'ref_seq' => 'Lorem ipsum dolor sit amet',
                'fabricante' => 'Lorem ipsum dolor sit amet',
                'calibracao' => '2020-04-08',
                'esq_va_025' => 1.5,
                'esq_va_050' => 1.5,
                'esq_va_1' => 1.5,
                'esq_va_2' => 1.5,
                'esq_va_3' => 1.5,
                'esq_va_4' => 1.5,
                'esq_va_6' => 1.5,
                'esq_va_8' => 1.5,
                'dir_va_025' => 1.5,
                'dir_va_050' => 1.5,
                'dir_va_1' => 1.5,
                'dir_va_2' => 1.5,
                'dir_va_3' => 1.5,
                'dir_va_4' => 1.5,
                'dir_va_6' => 1.5,
                'dir_va_8' => 1.5,
                'esq_vo_025' => 1.5,
                'esq_vo_050' => 1.5,
                'esq_vo_1' => 1.5,
                'esq_vo_2' => 1.5,
                'esq_vo_3' => 1.5,
                'esq_vo_4' => 1.5,
                'esq_vo_6' => 1.5,
                'esq_vo_8' => 1.5,
                'dir_vo_025' => 1.5,
                'dir_vo_050' => 1.5,
                'dir_vo_1' => 1.5,
                'dir_vo_2' => 1.5,
                'dir_vo_3' => 1.5,
                'dir_vo_4' => 1.5,
                'dir_vo_6' => 1.5,
                'dir_vo_8' => 1.5,
                'codigo_usuario_inclusao' => 1,
                'data_inclusao' => 1586369013,
                'em_analise' => 1,
                'ocupacional' => 1,
                'agravamento' => 1,
                'estavel' => 1,
                'ouve_bem' => 1,
                'zumbido_ouvido' => 1,
                'trauma_ouvidos' => 1,
                'doenca_auditiva' => 1,
                'local_ruidoso' => 1,
                'realizou_exame' => 1,
                'repouso_auditivo' => 1,
                'horas_repouso_auditivo' => 1.5,
                'observacoes' => 'Lorem ipsum dolor sit amet',
                'meatoscopia_od' => 1,
                'meatoscopia_oe' => 1,
                'str_od_dbna' => 'Lorem ipsum dolor sit amet',
                'str_oe_dbna' => 'Lorem ipsum dolor sit amet',
                'irf_od' => 'Lorem ipsum dolor sit amet',
                'irf_oe' => 'Lorem ipsum dolor sit amet',
                'laf_od_dbna' => 'Lorem ipsum dolor sit amet',
                'laf_oe_dbna' => 'Lorem ipsum dolor sit amet',
                'observacoes2' => 'Lorem ipsum dolor sit amet',
                'codigo_itens_pedidos_exames' => 1
            ],
        ];
        parent::init();
    }
}
