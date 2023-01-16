<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Subperfil Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property int $codigo_cliente
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property bool|null $ativo
 * @property bool|null $interno
 *
 * @property \App\Model\Entity\Aco[] $acoes
 * @property \App\Model\Entity\Usuario[] $usuario
 */
class Subperfil extends Entity
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
        'codigo_cliente' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'ativo' => true,
        'interno' => true,
        'acoes' => true,
        'usuario' => true,
    ];
}
