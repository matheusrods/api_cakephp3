<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TipoNotificacaoValore Entity
 *
 * @property int $codigo
 * @property bool|null $campo_funcionario
 * @property bool|null $campo_cliente
 * @property bool|null $campo_fornecedor
 * @property int $codigo_tipo_notificacao
 * @property int|null $codigo_pedidos_exames
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $vias_aso
 */

class TipoNotificacaoValore extends Entity
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
        'campo_funcionario' => true,
        'campo_cliente' => true,
        'campo_fornecedor' => true,
        'codigo_tipo_notificacao' => true,
        'codigo_pedidos_exames' => true,
        'data_inclusao' => true,
        'vias_aso' => true,
    ];
}
