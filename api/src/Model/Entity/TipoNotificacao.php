<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

use App\Model\Entity\AppEntity;

/**
 * TipoNotificacao Entity
 *
 * @property int $codigo
 * @property string|null $tipo
 * @property bool|null $notificacao_especifica
 */

class TipoNotificacao extends Entity

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
        'tipo' => true,
        'notificacao_especifica' => true,
    ];
}
