<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LevantamentosChamado Entity
 *
 * @property int $codigo
 * @property int $codigo_chamado
 * @property int $codigo_cliente
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_adiamento
 * @property string|null $observacao
 * @property int|null $codigo_levantamento_chamado_status
 * @property int|null $codigo_usuario_gestor_operacao
 * @property int|null $codigo_usuario_tecnico_ehs
 * @property int|null $codigo_usuario_operador
 * @property \Cake\I18n\FrozenTime|null $data_inicio_avaliacao
 * @property string|null $companheiro_avaliacao
 * @property int|null $nota_avaliacao
 * @property string|null $descricao_avaliacao
 * @property \Cake\I18n\FrozenTime|null $data_fim_avaliacao
 * @property string $descricao
 *
 */
class LevantamentosChamado extends Entity
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
        'codigo_chamado' => true,
        'codigo_cliente' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_adiamento' => true,
        'observacao' => true,
        'codigo_levantamento_chamado_status' => true,
        'codigo_usuario_gestor_operacao' => true,
        'codigo_usuario_tecnico_ehs' => true,
        'codigo_usuario_operador' => true,
        'data_inicio_avaliacao' => true,
        'companheiro_avaliacao' => true,
        'nota_avaliacao' => true,
        'descricao_avaliacao' => true,
        'data_fim_avaliacao' => true,
        'descricao' => true
    ];
}
