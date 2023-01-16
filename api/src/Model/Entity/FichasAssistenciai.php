<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichasAssistenciai Entity
 *
 * @property int $codigo
 * @property int $codigo_pedido_exame
 * @property int $codigo_medico
 * @property int|null $pa_sistolica
 * @property int|null $pa_diastolica
 * @property int|null $pulso
 * @property float|null $circunferencia_abdominal
 * @property float|null $circunferencia_quadril
 * @property int|null $peso_kg
 * @property int|null $peso_gr
 * @property int|null $altura_mt
 * @property int|null $altura_cm
 * @property int|null $imc
 * @property int|null $parecer
 * @property int|null $parecer_altura
 * @property int|null $parecer_espaco_confinado
 * @property int|null $codigo_atestado
 * @property int $ativo
 * @property int $codigo_empresa
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $hora_inicio_atendimento
 * @property \Cake\I18n\FrozenTime $hora_fim_atendimento
 *
 * @property \App\Model\Entity\Questo[] $questoes
 * @property \App\Model\Entity\Resposta[] $respostas
 * @property \App\Model\Entity\TipoUso[] $tipo_uso
 */
class FichasAssistenciai extends AppEntity
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
        'codigo_medico' => true,
        'pa_sistolica' => true,
        'pa_diastolica' => true,
        'pulso' => true,
        'circunferencia_abdominal' => true,
        'circunferencia_quadril' => true,
        'peso_kg' => true,
        'peso_gr' => true,
        'altura_mt' => true,
        'altura_cm' => true,
        'imc' => true,
        'parecer' => true,
        'parecer_altura' => true,
        'parecer_espaco_confinado' => true,
        'codigo_atestado' => true,
        'ativo' => true,
        'codigo_empresa' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'hora_inicio_atendimento' => true,
        'hora_fim_atendimento' => true,
        'questoes' => true,
        'respostas' => true,
        'tipo_uso' => true
    ];
}
