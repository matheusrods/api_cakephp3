<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PdaTemaPdaTemaAco Entity
 *
 * @property int $codigo
 * @property int $codigo_empresa
 * @property int|null $codigo_pda_tema
 * @property int|null $codigo_pda_tema_acoes
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 */
class PdaTemaPdaTemaAco extends Entity
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
        'codigo_empresa' => true,
        'codigo_pda_tema' => true,
        'codigo_pda_tema_acoes' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
    ];
}
