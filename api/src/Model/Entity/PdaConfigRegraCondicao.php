<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PdaConfigRegraCondicao Entity
 *
 * @property int $codigo
 * @property int $codigo_empresa
 * @property int $codigo_cliente
 * @property int $codigo_pda_config_regra
 * @property int|null $codigo_pda_tema_condicao
 * @property int|null $codigo_pda_tema_acoes
 * @property int|null $codigo_cliente_opco
 * @property int|null $codigo_cliente_bu
 * @property int|null $codigo_acoes_melhorias_status
 * @property int|null $codigo_origem_ferramentas
 * @property int|null $codigo_pos_criticidade
 * @property int|null $qtd_dias
 * @property string|null $condicao
 * @property int|null $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class PdaConfigRegraCondicao extends Entity
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
        'codigo_empresa' => true,
        'codigo_cliente' => true,
        'codigo_pda_config_regra' => true,
        'codigo_pda_tema_condicao' => true,
        'codigo_pda_tema_acoes' => true,
        'codigo_cliente_opco' => true,
        'codigo_cliente_bu' => true,
        'codigo_acoes_melhorias_status' => true,
        'codigo_origem_ferramentas' => true,
        'codigo_pos_criticidade' => true,
        'qtd_dias' => true,
        'condicao' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_cliente_unidade' => true,
    ];
}
