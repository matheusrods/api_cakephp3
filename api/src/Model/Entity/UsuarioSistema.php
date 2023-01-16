<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * UsuarioSistema Entity
 *
 * @property int $codigo
 * @property int|null $codigo_usuario
 * @property int $codigo_sistema
 * @property string|null $senha
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property string|null $token_push
 * @property string|null $token
 * @property string|null $platform
 * @property int|null $ativo
 * @property int|null $cod_verificacao
 * @property string|null $token_chamadas
 * @property string|null $celular
 * @property string|null $model
 * @property int|null $foreign_key
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class UsuarioSistema extends AppEntity
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
        'codigo_sistema' => true,
        'senha' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'token_push' => true,
        'token' => true,
        'platform' => true,
        'ativo' => true,
        'cod_verificacao' => true,
        'token_chamadas' => true,
        'celular' => true,
        'model' => true,
        'foreign_key' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token'
    ];
}
