<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TipoRetorno Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool|null $cliente
 * @property bool|null $proprietario
 * @property bool|null $profissional
 * @property bool|null $usuario_interno
 */
class TipoRetorno extends Entity
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
        'cliente' => true,
        'proprietario' => true,
        'profissional' => true,
        'usuario_interno' => true,
    ];
}
