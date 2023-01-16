<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GruposEconomico Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_cliente
 * @property int|null $codigo_empresa
 * @property int|null $vias_aso
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_medico_pcmso_padrao
 * @property int|null $exames_dias_a_vencer
 * @property bool $exibir_centro_custo_per_capita
 * @property bool $exibir_nome_fantasia_aso
 * @property bool|null $exibir_rqe_aso
 */
class GruposEconomico extends Entity
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
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_cliente' => true,
        'codigo_empresa' => true,
        'vias_aso' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_medico_pcmso_padrao' => true,
        'exames_dias_a_vencer' => true,
        'exibir_centro_custo_per_capita' => true,
        'exibir_nome_fantasia_aso' => true,
        'exibir_rqe_aso' => true,
        'codigo_idioma' => true,
        'descricao_idioma' => true,
        'aso_embarcado' => true,
        'aso_exame_linha' => true,
        'exame_atraves_lyn' => true,
    ];
}
