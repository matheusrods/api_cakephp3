<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedorPermisso Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 *
 * @property \App\Model\Entity\Usuario[] $usuario
 */
class FornecedorPermisso extends Entity
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
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'usuario' => true
    ];
}
