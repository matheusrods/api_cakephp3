<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresAvaliaco Entity
 *
 * @property int $codigo
 * @property int|null $codigo_fornecedor
 * @property int|null $codigo_fornecedor_tipo_avaliacao
 * @property int|null $codigo_usuario
 * @property int|null $codigo_funcionario
 * @property int|null $codigo_item_pedido_exame
 * @property int|null $avaliacao
 * @property string|null $comentario
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 */
class FornecedoresAvaliaco extends Entity
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
        'codigo_fornecedor' => true,
        'codigo_fornecedor_tipo_avaliacao' => true,
        'codigo_usuario' => true,
        'codigo_funcionario' => true,
        'codigo_item_pedido_exame' => true,
        'avaliacao' => true,
        'comentario' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true
    ];
}
