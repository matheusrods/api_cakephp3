<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichasClinica Entity
 *
 * @property int $codigo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string $incluido_por
 * @property \Cake\I18n\FrozenTime $hora_inicio_atendimento
 * @property \Cake\I18n\FrozenTime $hora_fim_atendimento
 * @property int $ativo
 * @property int $codigo_pedido_exame
 * @property int $codigo_medico
 * @property int|null $pa_sistolica
 * @property int|null $pa_diastolica
 * @property int|null $pulso
 * @property float|null $circunferencia_abdominal
 * @property int|null $peso_kg
 * @property int|null $peso_gr
 * @property int|null $altura_mt
 * @property int|null $altura_cm
 * @property float|null $circunferencia_quadril
 * @property int|null $parecer
 * @property int|null $parecer_altura
 * @property int|null $parecer_espaco_confinado
 * @property string|null $imc
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\Questo[] $questoes
 * @property \App\Model\Entity\Resposta[] $respostas
 */
class FichasClinica extends AppEntity
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
        'data_inclusao' => true,
        'incluido_por' => true,
        'hora_inicio_atendimento' => true,
        'hora_fim_atendimento' => true,
        'ativo' => true,
        'codigo_pedido_exame' => true,
        'codigo_medico' => true,
        'pa_sistolica' => true,
        'pa_diastolica' => true,
        'pulso' => true,
        'circunferencia_abdominal' => true,
        'peso_kg' => true,
        'peso_gr' => true,
        'altura_mt' => true,
        'altura_cm' => true,
        'circunferencia_quadril' => true,
        'parecer' => true,
        'parecer_altura' => true,
        'parecer_espaco_confinado' => true,
        'imc' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'observacao' => true,
        'questoes' => true,
        'respostas' => true
    ];
}
