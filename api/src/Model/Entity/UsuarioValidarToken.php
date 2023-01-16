<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuarioValidarToken Entity
 *
 * @property int $codigo
 * @property int $codigo_sistema
 * @property int $codigo_sistema_validar_token_tipo
 * @property string $token
 * @property \Cake\I18n\FrozenTime|null $tempo_validacao
 * @property bool $validado
 * @property string $destino_descricao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $codigo_usuario
 */
class UsuarioValidarToken extends Entity
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
        'codigo_sistema' => true,
        'codigo_sistema_validar_token_tipo' => true,
        'token' => true,
        'tempo_validacao' => true,
        'validado' => true,
        'destino_descricao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];
}
