<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrigemFerramenta Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property string $descricao
 * @property string $formulario
 * @property bool $ativo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 */
class OrigemFerramenta extends Entity
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
        'formulario' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_remocao' => true,
    ];

    protected function _getDescricao($descricao)
    {
        return !is_null($descricao) ? utf8_decode(mb_convert_encoding($descricao, 'UTF-8')) : null;
    }
}
