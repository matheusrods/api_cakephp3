<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Laboratorio Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property bool|null $ativo
 * @property int|null $codigo_empresa
 */
class Laboratorio extends Entity
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
        'ativo' => true,
        'codigo_empresa' => true
    ];
}
