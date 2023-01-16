<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mensagem Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario
 * @property int|null $codigo_usuario_from
 * @property string $mensagem
 * @property \Cake\I18n\FrozenTime|null $data_leitura
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 * @property string|null $link
 * @property int|null $ativo
 */
class Mensagem extends Entity
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
        'codigo_usuario_from' => true,
        'mensagem' => true,
        'data_leitura' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'data_remocao' => true,
        'link' => true,
        'ativo' => true,
    ];
}
