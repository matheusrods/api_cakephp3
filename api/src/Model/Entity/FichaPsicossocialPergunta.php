<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FichaPsicossocialPergunta Entity
 *
 * @property int $codigo
 * @property string|null $pergunta
 * @property int $ativo
 * @property int|null $ordem
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 */
class FichaPsicossocialPergunta extends Entity
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
        'pergunta' => true,
        'ativo' => true,
        'ordem' => true,
        'data_inclusao' => true
    ];
}
