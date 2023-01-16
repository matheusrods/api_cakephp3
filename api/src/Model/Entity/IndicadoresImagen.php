<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IndicadoresImagen Entity
 *
 * @property int $codigo
 * @property string|null $sexo
 * @property string|null $imagem
 * @property string|null $categoria
 * @property string|null $valor_inicial
 * @property string|null $valor_final
 */
class IndicadoresImagen extends Entity
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
        'sexo' => true,
        'imagem' => true,
        'categoria' => true,
        'valor_inicial' => true,
        'valor_final' => true
    ];
}
