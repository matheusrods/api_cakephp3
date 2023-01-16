<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * LynMsgAceite Entity
 *
 * @property int $codigo
 * @property int|null $codigo_lyn_msg
 * @property int|null $codigo_usuario
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 */
class LynMsgAceite extends AppEntity
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
        'codigo_lyn_msg' => true,
        'codigo_usuario' => true,
        'data_inclusao' => true,
    ];
}
