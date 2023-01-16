<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichasClinicasResposta Entity
 *
 * @property int $codigo
 * @property int $codigo_ficha_clinica_questao
 * @property string|null $resposta
 * @property string|null $campo_livre
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_ficha_clinica
 * @property string|null $parentesco
 */
class FichasClinicasResposta extends AppEntity
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
        'codigo_ficha_clinica_questao' => true,
        'resposta' => true,
        'campo_livre' => true,
        'data_inclusao' => true,
        'codigo_ficha_clinica' => true,
        'parentesco' => true
    ];
}
