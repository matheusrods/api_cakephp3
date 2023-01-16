<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Qualificacao Entity
 *
 * @property int $codigo
 * @property int $codigo_arrtpa_ri
 * @property int|null $qualitativo
 * @property int|null $quantitativo
 * @property int|null $codigo_metodo_tipo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acidente_registrado
 * @property string|null $partes_afetadas
 * @property string|null $resultado_ponderacao
 */
class Qualificacao extends Entity
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
        'codigo_arrtpa_ri' => true,
        'qualitativo' => true,
        'quantitativo' => true,
        'codigo_metodo_tipo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'acidente_registrado' => true,
        'partes_afetadas' => true,
        'resultado_ponderacao' => true,
    ];
}
