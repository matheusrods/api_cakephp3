<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mensagem Entity
 *
 * @property int $codigo
 * @property string $titulo
 * @property string $mensagem
 * @property \Cake\I18n\FrozenTime $data_inicio
 * @property \Cake\I18n\FrozenTime $data_fim
 * @property string|null $link
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
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
        'titulo' => true,
        'descricao' => true,
        'data_inicio' => true,
        'data_fim' => true,
        'link' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'data_remocao' => true,
        'ativo' => true,
    ];
}
