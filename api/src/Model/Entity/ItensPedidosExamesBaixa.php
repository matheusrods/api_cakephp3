<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * ItensPedidosExamesBaixa Entity
 *
 * @property int $codigo
 * @property int $codigo_itens_pedidos_exames
 * @property int $resultado
 * @property \Cake\I18n\FrozenDate|null $data_validade
 * @property string|null $descricao
 * @property \Cake\I18n\FrozenDate|null $data_realizacao_exame
 * @property int|null $codigo_aparelho_audiometrico
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property bool $fornecedor_particular
 * @property bool $pedido_importado
 * @property int|null $codigo_usuario_alteracao
 * @property bool|null $integracao_cliente
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class ItensPedidosExamesBaixa extends AppEntity
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
        'codigo_itens_pedidos_exames' => true,
        'resultado' => true,
        'data_validade' => true,
        'descricao' => true,
        'data_realizacao_exame' => true,
        'codigo_aparelho_audiometrico' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'fornecedor_particular' => true,
        'pedido_importado' => true,
        'codigo_usuario_alteracao' => true,
        'integracao_cliente' => true,
        'data_alteracao' => true
    ];
}
