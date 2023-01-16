<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * UsuarioContatoEmergencium Entity
 *
 * @property int $codigo
 * @property int|null $codigo_usuario
 * @property int $nome
 * @property string|null $telefone
 * @property \Cake\I18n\FrozenTime|null $celular
 * @property int|null $ativo
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_alteracao
 */
// class UsuarioContatoEmergencium extends AppEntity
class UsuarioContatoEmergencia extends AppEntity
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
        'nome' => true,
        'telefone' => true,
        'celular' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'grau_parentesco' => true,
        'email' => true
    ];
}
