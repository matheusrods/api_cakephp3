<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Uperfi Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_cliente
 * @property bool|null $perfil_cliente
 * @property int|null $codigo_tipo_perfil
 * @property int|null $codigo_pai
 * @property int|null $codigo_empresa
 */
class Uperfi extends Entity
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
        'perfil_cliente' => true,
        'codigo_tipo_perfil' => true,
        'codigo_pai' => true,
        'codigo_empresa' => true
    ];
}