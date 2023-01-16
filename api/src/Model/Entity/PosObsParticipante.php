<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity as Entity;

/**
 * PosObsParticipante Entity
 *
 * @property int $codigo
 * @property int $codigo_pos_obs_observacao
 * @property int $codigo_usuario
 * @property string $cpf
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property bool $ativo
 *
 * @property \App\Model\Entity\UsuariosDado $dados
 */
class PosObsParticipante extends Entity
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
        'codigo_pos_obs_observacao' => true,
        'codigo_usuario' => true,
        'cpf' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'ativo' => true,
        'dados' => true,
    ];
}
