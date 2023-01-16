<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuariosMedicamentosLog Entity
 *
 * @property int $codigo
 * @property int $codigo_usuarios_medicamentos
 * @property int|null $codigo_medicamentos
 * @property int|null $codigo_usuario
 * @property int|null $frequencia_dias
 * @property int|null $frequencia_horarios
 * @property int|null $uso_continuo
 * @property string|null $dias_da_semana
 * @property int|null $frequencia_uso
 * @property string|null $horario_inicio_uso
 * @property int|null $quantidade
 * @property string|null $recomendacao_medica
 * @property string|null $foto_receita
 * @property int|null $frequencia_dias_intercalados
 * @property \Cake\I18n\FrozenDate|null $periodo_tratamento_inicio
 * @property \Cake\I18n\FrozenDate|null $periodo_tratamento_termino
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_apresentacao
 * @property int|null $ativo
 * @property int|null $acao
 */
class UsuariosMedicamentosLog extends Entity
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
        'codigo_usuarios_medicamentos' => true,
        'codigo_medicamentos' => true,
        'codigo_usuario' => true,
        'frequencia_dias' => true,
        'frequencia_horarios' => true,
        'uso_continuo' => true,
        'dias_da_semana' => true,
        'frequencia_uso' => true,
        'horario_inicio_uso' => true,
        'quantidade' => true,
        'recomendacao_medica' => true,
        'foto_receita' => true,
        'frequencia_dias_intercalados' => true,
        'periodo_tratamento_inicio' => true,
        'periodo_tratamento_termino' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'data_inclusao' => true,
        'codigo_apresentacao' => true,
        'ativo' => true,
        'acao' => true,
    ];
}
