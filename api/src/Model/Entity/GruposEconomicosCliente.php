<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GruposEconomicosCliente Entity
 *
 * @property int $codigo
 * @property int $codigo_grupo_economico
 * @property int $codigo_cliente
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_empresa
 * @property bool|null $bloqueado
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class GruposEconomicosCliente extends Entity
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
        'codigo_grupo_economico' => true,
        'codigo_cliente' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_empresa' => true,
        'bloqueado' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
    ];
}
