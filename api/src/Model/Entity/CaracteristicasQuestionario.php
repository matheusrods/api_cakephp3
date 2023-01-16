<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * CaracteristicasQuestionario Entity
 *
 * @property int $codigo
 * @property int $codigo_caracteristica
 * @property int $codigo_questionario
 */
class CaracteristicasQuestionario extends AppEntity
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
        'codigo_caracteristica' => true,
        'codigo_questionario' => true
    ];
}
