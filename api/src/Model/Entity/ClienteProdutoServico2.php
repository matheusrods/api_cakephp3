<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClienteProdutoServico2 Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente_produto
 * @property int $codigo_servico
 * @property float $valor
 * @property int|null $codigo_cliente_pagador
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $qtd_premio_minimo
 * @property float $valor_premio_minimo
 * @property float|null $valor_maximo
 * @property string|null $ip
 * @property string|null $browser
 * @property bool $consulta_embarcador
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property float|null $valor_unit_premio_minimo
 * @property int|null $quantidade
 * @property int|null $codigo_empresa
 */
class ClienteProdutoServico2 extends Entity
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
        'codigo' => true,
        'codigo_cliente_produto' => true,
        'codigo_servico' => true,
        'valor' => true,
        'codigo_cliente_pagador' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'qtd_premio_minimo' => true,
        'valor_premio_minimo' => true,
        'valor_maximo' => true,
        'ip' => true,
        'browser' => true,
        'consulta_embarcador' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'valor_unit_premio_minimo' => true,
        'quantidade' => true,
        'codigo_empresa' => true
    ];
}
