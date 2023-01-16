<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ghe Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property string $chave_ghe
 * @property string $aprho_parecer_tecnico
 * @property string $ghe_status
 * @property int|null $codigo_gerente_operacoes
 * @property int|null $codigo_ehs_tecnico
 * @property int|null $codigo_operador
 * @property \Cake\I18n\FrozenTime|null $aprovacao_gerente_operacoes
 * @property \Cake\I18n\FrozenTime|null $aprovacao_ehs_tecnico
 * @property string|null $descricao_divergencia
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $ativo
 * @property \Cake\I18n\FrozenTime|null $data_divergencia
 * @property int|null $divergencia_apontada_por
 */
class Ghe extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity()
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'codigo_cliente' => true,
        'chave_ghe' => true,
        'aprho_parecer_tecnico' => true,
        'ghe_status' => true,
        'codigo_gerente_operacoes' => true,
        'codigo_ehs_tecnico' => true,
        'codigo_operador' => true,
        'aprovacao_gerente_operacoes' => true,
        'aprovacao_ehs_tecnico' => true,
        'descricao_divergencia' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'ativo' => true,
        'data_divergencia' => true,
        'divergencia_apontada_por' => true
    ];
}
