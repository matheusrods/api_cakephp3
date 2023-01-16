<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuarioExame Entity
 *
 * @property int $codigo
 * @property int|null $codigo_usuario
 * @property int|null $codigo_exames
 * @property string|null $endereco_clinica
 * @property \Cake\I18n\FrozenDate|null $data_realizacao
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 */
class UsuarioExame extends Entity
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
        'codigo_usuario' => true,
        'codigo_exames' => true,
        'endereco_clinica' => true,
        'data_realizacao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'data_inclusao' => true
    ];
}
