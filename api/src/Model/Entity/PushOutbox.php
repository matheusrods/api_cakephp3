<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * PushOutbox Entity
 *
 * @property int $codigo
 * @property int $codigo_key
 * @property string $token
 * @property string $fone_para
 * @property string $titulo
 * @property string|null $mensagem
 * @property string $extra_data
 * @property \Cake\I18n\FrozenTime|null $data_envio
 * @property \Cake\I18n\FrozenTime|null $liberar_envio_em
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string $sistema_origem
 * @property string|null $modulo_origem
 * @property string|null $platform
 * @property string|null $observacao
 * @property int|null $foreign_key
 * @property string|null $model
 * @property int|null $codigo_usuario
 */
class PushOutbox extends AppEntity
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
        'codigo_key' => true,
        'token' => true,
        'fone_para' => true,
        'titulo' => true,
        'mensagem' => true,
        'extra_data' => true,
        'data_envio' => true,
        'liberar_envio_em' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'sistema_origem' => true,
        'modulo_origem' => true,
        'platform' => true,
        'observacao' => true,
        'foreign_key' => true,
        'model' => true,
        'codigo_usuario' => true,
        'msg_lida' =>true,
        'link' => true
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
