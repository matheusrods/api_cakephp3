<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuariosMedicamentosStatus Entity
 *
 * @property int $codigo
 * @property int|null $codigo_usuario_medicamento
 * @property \Cake\I18n\FrozenTime $data_hora_uso
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 */
class UsuariosMedicamentosStatus extends Entity
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
        'codigo_usuario_medicamento' => true,
        'data_hora_uso' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true
    ];
}
