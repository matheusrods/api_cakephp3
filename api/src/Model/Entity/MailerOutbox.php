<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * MailerOutbox Entity
 *
 * @property int $id
 * @property string $to
 * @property string $subject
 * @property string $content
 * @property \Cake\I18n\FrozenTime|null $sent
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $liberar_envio_em
 * @property string $from
 * @property string|null $cc
 * @property string|null $model
 * @property int|null $foreign_key
 * @property string|null $attachments
 * @property int|null $codigo_empresa
 */
class MailerOutbox extends AppEntity
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
        // 'id' => true,
        'to' => true,
        'subject' => true,
        'content' => true,
        'sent' => true,
        'created' => true,
        'modified' => true,
        'liberar_envio_em' => true,
        'from' => true,
        'cc' => true,
        'model' => true,
        'foreign_key' => true,
        'attachments' => true,
        'codigo_empresa' => true
    ];
}
