<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EquipamentosInspecaoTipo Entity
 *
 * @property int $codigo
 * @property int $codigo_unidade_medicao
 * @property float|null $valor
 * @property float|null $limite_tolerancia
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $descricao
 * @property int $codigo_cliente
 */
class EquipamentosInspecaoTipo extends Entity
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
        'codigo_unidade_medicao' => true,
        'valor' => true,
        'limite_tolerancia' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'descricao' => true,
        'codigo_cliente' => true
    ];
}
