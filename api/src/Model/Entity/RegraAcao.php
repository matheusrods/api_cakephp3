<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RegraAcao Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property int $dias_rejeitar
 * @property int $dias_encaminhar
 * @property int $dias_prazo
 * @property int $status_acao_sem_prazo
 * @property int $dias_analise_implementacao
 * @property int $dias_analise_eficacia
 * @property int $dias_analise_abrangencia
 * @property int $dias_analise_cancelamento
 * @property int $dias_a_vencer
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $dias_a_aceitar
 */
class RegraAcao extends Entity
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
        'codigo_cliente' => true,
        'dias_rejeitar' => true,
        'dias_encaminhar' => true,
        'dias_prazo' => true,
        'status_acao_sem_prazo' => true,
        'dias_analise_implementacao' => true,
        'dias_analise_eficacia' => true,
        'dias_analise_abrangencia' => true,
        'dias_analise_cancelamento' => true,
        'dias_a_vencer' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'dias_a_aceitar' => true,
    ];
}
