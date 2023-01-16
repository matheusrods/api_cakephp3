<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FontesGeradorasExposicao Entity
 *
 * @property int $codigo
 * @property int $codigo_risco_impacto_selecionado_descricao
 * @property int $codigo_fonte_geradora_exposicao_tipo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class FontesGeradorasExposicao extends Entity
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
        'codigo_risco_impacto_selecionado_descricao' => true,
        'codigo_fonte_geradora_exposicao_tipo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
    ];
}
