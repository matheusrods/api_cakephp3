<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Alerta Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property string|null $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_tratamento
 * @property string|null $observacao_tratamento
 * @property int|null $codigo_usuario_tratamento
 * @property bool|null $email_agendados
 * @property bool|null $sms_agendados
 * @property int $codigo_alerta_tipo
 * @property string|null $descricao_email
 * @property string|null $model
 * @property int|null $foreign_key
 * @property bool $ws_agendados
 * @property string|null $assunto
 */
class Alerta extends Entity
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
        'codigo_cliente' => true,
        'descricao' => true,
        'data_inclusao' => true,
        'data_tratamento' => true,
        'observacao_tratamento' => true,
        'codigo_usuario_tratamento' => true,
        'email_agendados' => true,
        'sms_agendados' => true,
        'codigo_alerta_tipo' => true,
        'descricao_email' => true,
        'model' => true,
        'foreign_key' => true,
        'ws_agendados' => true,
        'assunto' => true,
    ];
}
