<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresAgendasDatasBloqueada Entity
 *
 * @property int $codigo
 * @property \Cake\I18n\FrozenDate $data
 * @property string|null $horarios
 * @property int|null $bloqueado_dia_inteiro
 * @property int $codigo_fornecedor
 * @property int $codigo_lista_de_preco_produto_servico
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $ativo
 */
class FornecedoresAgendasDatasBloqueada extends Entity
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
        'data' => true,
        'horarios' => true,
        'bloqueado_dia_inteiro' => true,
        'codigo_fornecedor' => true,
        'codigo_lista_de_preco_produto_servico' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'ativo' => true
    ];
}
