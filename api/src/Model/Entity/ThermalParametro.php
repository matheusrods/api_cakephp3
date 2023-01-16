<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * ThermalParametro Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property int|null $codigo_usuario
 * @property string $titulo
 * @property string $valor
 * @property int $ativo
 */
class ThermalParametro extends AppEntity
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
        'codigo_cliente' => true,
        'codigo_usuario' => true,
        'titulo' => true,
        'valor' => true,
        'ativo' => true,
    ];
}
