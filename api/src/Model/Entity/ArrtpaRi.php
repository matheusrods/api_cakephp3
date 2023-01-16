<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArrtpaRi Entity
 *
 * @property int $codigo
 * @property int|null $codigo_arrt_pa
 * @property int|null $codigo_risco_impacto
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $e_hazop
 * @property string|null $acao_requerida
 * @property int|null $codigo_agente_risco
 */
class ArrtpaRi extends Entity
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
        'codigo_arrt_pa' => true,
        'codigo_risco_impacto' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'e_hazop' => true,
        'acao_requerida' => true,
        'codigo_agente_risco' => true
    ];
}
