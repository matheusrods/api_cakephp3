<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EstadoCidadeLatLong Entity
 *
 * @property int $codigo
 * @property string|null $estado
 * @property string|null $cidade
 * @property string|null $lat
 * @property string|null $long
 * @property string|null $cep
 * @property string|null $ibge
 */
class EstadoCidadeLatLong extends Entity
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
        'codigo' => true,
        'estado' => true,
        'cidade' => true,
        'lat' => true,
        'long' => true,
        'cep' => true,
        'ibge' => true,
    ];
}
