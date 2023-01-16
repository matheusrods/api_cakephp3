<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LynMenuCliente Entity
 *
 * @property int $codigo
 * @property int|null $codigo_lyn_menu
 * @property int|null $codigo_cliente
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class LynMenuCliente extends Entity
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
        'codigo_lyn_menu' => true,
        'codigo_cliente' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
    ];
}
