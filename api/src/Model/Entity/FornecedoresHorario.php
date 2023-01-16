<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FornecedoresHorario Entity
 *
 * @property int $codigo
 * @property int $codigo_fornecedor
 * @property int $de_hora
 * @property int $ate_hora
 * @property string|null $dias_semana
 * @property int|null $codigo_empresa
 */
class FornecedoresHorario extends AppEntity
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
        'codigo_fornecedor' => true,
        'de_hora' => true,
        'ate_hora' => true,
        'dias_semana' => true,
        'codigo_empresa' => true
    ];
}
