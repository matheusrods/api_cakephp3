<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MedicoCalendarioHorario Entity
 *
 * @property int $codigo
 * @property int $codigo_medico_calendario
 * @property int $dia_semana
 * @property string $hora_inicio_manha
 * @property string $hora_fim_manha
 * @property string $hora_inicio_tarde
 * @property string $hora_fim_tarde
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $ativo
 */
class MedicoCalendarioHorario extends Entity
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
        'codigo_medico_calendario' => true,
        'dia_semana' => true,
        'hora_inicio_manha' => true,
        'hora_fim_manha' => true,
        'hora_inicio_tarde' => true,
        'hora_fim_tarde' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'ativo' => true
    ];
}
