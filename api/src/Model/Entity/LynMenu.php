<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LynMenu Entity
 *
 * @property int $codigo
 * @property string|null $descricao
 * @property int|null $ativo
 *
 * @property \App\Model\Entity\Cliente[] $cliente
 * @property \App\Model\Entity\ClienteLog[] $cliente_log
 */
class LynMenu extends Entity
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
        'ativo' => true,
        'cliente' => true,
        'cliente_log' => true,
    ];
}
