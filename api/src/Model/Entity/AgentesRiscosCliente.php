<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AgentesRiscosCliente Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property int $codigo_arrtpa_ri
 * @property int $codigo_agente_risco
 */
class AgentesRiscosCliente extends Entity
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
        'codigo_arrtpa_ri' => true,
        'codigo_agente_risco' => true,
    ];
}
