<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AptidaoExame Entity
 *
 * @property int $codigo
 * @property int $codigo_itens_pedidos_exames
 * @property int|null $check_telefone
 * @property int|null $check_email
 * @property int|null $check_requisitos
 */
class AptidaoExame extends Entity
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
        'codigo_itens_pedidos_exames' => true,
        'check_telefone' => true,
        'check_email' => true,
        'check_requisitos' => true,
    ];
}
