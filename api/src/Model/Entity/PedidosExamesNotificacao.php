<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * PedidosExamesNotificacao Entity
 *
 * @property int $codigo
 * @property int|null $codigo_pedido_exame
 * @property string|null $funcionario_email
 * @property string|null $clinica_email
 * @property string|null $cliente_email
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $codigo_funcionario
 * @property int|null $codigo_cliente
 * @property int|null $codigo_fornecedor
 * @property int|null $codigo_pedido_exame_log
 */
class PedidosExamesNotificacao extends AppEntity
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
        'codigo_pedido_exame' => true,
        'funcionario_email' => true,
        'clinica_email' => true,
        'cliente_email' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_funcionario' => true,
        'codigo_cliente' => true,
        'codigo_fornecedor' => true,
        'codigo_pedido_exame_log' => true,
    ];
}
