<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FerramentasAnalise Entity
 *
 * @property int $codigo
 * @property int|null $codigo_qualificacao
 * @property int|null $codigo_ferramenta_analise_tipo
 * @property string|null $ferramenta_analise_resultado
 * @property string|null $ferramenta_analise_level
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class FerramentasAnalise extends Entity
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
        'codigo_qualificacao' => true,
        'codigo_ferramenta_analise_tipo' => true,
        'ferramenta_analise_resultado' => true,
        'ferramenta_analise_level' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
    ];
}
