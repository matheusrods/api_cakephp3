<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cargo Entity
 *
 * @property int $codigo
 * @property string|null $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool $ativo
 * @property string|null $codigo_cbo
 * @property int|null $codigo_cliente
 * @property int|null $codigo_empresa
 * @property string|null $codigo_rh
 * @property string|null $descricao_ppp
 * @property string|null $requisito
 * @property string|null $descricao_cargo
 * @property string|null $educacao
 * @property string|null $treinamento
 * @property string|null $habilidades
 * @property string|null $experiencias
 * @property string|null $descricao_local
 * @property string|null $observacao_aso
 * @property string|null $material_utilizado
 * @property string|null $mobiliario_utilizado
 * @property string|null $local_trabalho
 * @property int|null $codigo_gfip
 * @property int|null $codigo_funcao
 * @property int|null $codigo_cargo_similar
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\ClientesSetore[] $clientes_setores
 */
class Cargo extends Entity
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
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'codigo_cbo' => true,
        'codigo_cliente' => true,
        'codigo_empresa' => true,
        'codigo_rh' => true,
        'descricao_ppp' => true,
        'requisito' => true,
        'descricao_cargo' => true,
        'educacao' => true,
        'treinamento' => true,
        'habilidades' => true,
        'experiencias' => true,
        'descricao_local' => true,
        'observacao_aso' => true,
        'material_utilizado' => true,
        'mobiliario_utilizado' => true,
        'local_trabalho' => true,
        'codigo_gfip' => true,
        'codigo_funcao' => true,
        'codigo_cargo_similar' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'clientes_setores' => true,
    ];
}
