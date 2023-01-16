<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * QrCodeLeitura Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario
 * @property int $codigo_resultado_covid
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 */
class QrCodeLeitura extends AppEntity
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
        'codigo_usuario' => true,
        'codigo_resultado_covid' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
    ];
}
