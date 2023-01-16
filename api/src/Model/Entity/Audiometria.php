<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Audiometria Entity
 *
 * @property int $codigo
 * @property int $codigo_funcionario
 * @property \Cake\I18n\FrozenDate $data_exame
 * @property int $tipo_exame
 * @property int $resultado
 * @property string $aparelho
 * @property string $ref_seq
 * @property string $fabricante
 * @property \Cake\I18n\FrozenDate $calibracao
 * @property float|null $esq_va_025
 * @property float|null $esq_va_050
 * @property float|null $esq_va_1
 * @property float|null $esq_va_2
 * @property float|null $esq_va_3
 * @property float|null $esq_va_4
 * @property float|null $esq_va_6
 * @property float|null $esq_va_8
 * @property float|null $dir_va_025
 * @property float|null $dir_va_050
 * @property float|null $dir_va_1
 * @property float|null $dir_va_2
 * @property float|null $dir_va_3
 * @property float|null $dir_va_4
 * @property float|null $dir_va_6
 * @property float|null $dir_va_8
 * @property float|null $esq_vo_025
 * @property float|null $esq_vo_050
 * @property float|null $esq_vo_1
 * @property float|null $esq_vo_2
 * @property float|null $esq_vo_3
 * @property float|null $esq_vo_4
 * @property float|null $esq_vo_6
 * @property float|null $esq_vo_8
 * @property float|null $dir_vo_025
 * @property float|null $dir_vo_050
 * @property float|null $dir_vo_1
 * @property float|null $dir_vo_2
 * @property float|null $dir_vo_3
 * @property float|null $dir_vo_4
 * @property float|null $dir_vo_6
 * @property float|null $dir_vo_8
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property bool|null $em_analise
 * @property bool|null $ocupacional
 * @property bool|null $agravamento
 * @property bool|null $estavel
 * @property int|null $ouve_bem
 * @property int|null $zumbido_ouvido
 * @property int|null $trauma_ouvidos
 * @property int|null $doenca_auditiva
 * @property int|null $local_ruidoso
 * @property int|null $realizou_exame
 * @property bool $repouso_auditivo
 * @property float|null $horas_repouso_auditivo
 * @property string|null $observacoes
 * @property int|null $meatoscopia_od
 * @property int|null $meatoscopia_oe
 * @property string|null $str_od_dbna
 * @property string|null $str_oe_dbna
 * @property string|null $irf_od
 * @property string|null $irf_oe
 * @property string|null $laf_od_dbna
 * @property string|null $laf_oe_dbna
 * @property string|null $observacoes2
 * @property int $codigo_itens_pedidos_exames
 */
class Audiometria extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'codigo_funcionario' => true,
        'data_exame' => true,
        'tipo_exame' => true,
        'resultado' => true,
        'aparelho' => true,
        'ref_seq' => true,
        'fabricante' => true,
        'calibracao' => true,
        'esq_va_025' => true,
        'esq_va_050' => true,
        'esq_va_1' => true,
        'esq_va_2' => true,
        'esq_va_3' => true,
        'esq_va_4' => true,
        'esq_va_6' => true,
        'esq_va_8' => true,
        'dir_va_025' => true,
        'dir_va_050' => true,
        'dir_va_1' => true,
        'dir_va_2' => true,
        'dir_va_3' => true,
        'dir_va_4' => true,
        'dir_va_6' => true,
        'dir_va_8' => true,
        'esq_vo_025' => true,
        'esq_vo_050' => true,
        'esq_vo_1' => true,
        'esq_vo_2' => true,
        'esq_vo_3' => true,
        'esq_vo_4' => true,
        'esq_vo_6' => true,
        'esq_vo_8' => true,
        'dir_vo_025' => true,
        'dir_vo_050' => true,
        'dir_vo_1' => true,
        'dir_vo_2' => true,
        'dir_vo_3' => true,
        'dir_vo_4' => true,
        'dir_vo_6' => true,
        'dir_vo_8' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'em_analise' => true,
        'ocupacional' => true,
        'agravamento' => true,
        'estavel' => true,
        'ouve_bem' => true,
        'zumbido_ouvido' => true,
        'trauma_ouvidos' => true,
        'doenca_auditiva' => true,
        'local_ruidoso' => true,
        'realizou_exame' => true,
        'repouso_auditivo' => true,
        'horas_repouso_auditivo' => true,
        'observacoes' => true,
        'meatoscopia_od' => true,
        'meatoscopia_oe' => true,
        'str_od_dbna' => true,
        'str_oe_dbna' => true,
        'irf_od' => true,
        'irf_oe' => true,
        'laf_od_dbna' => true,
        'laf_oe_dbna' => true,
        'observacoes2' => true,
        'codigo_itens_pedidos_exames' => true
    ];
}
