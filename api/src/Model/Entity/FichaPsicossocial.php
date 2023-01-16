<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichaPsicossocial Entity
 *
 * @property int $codigo
 * @property int $codigo_pedido_exame
 * @property string|null $total_sim
 * @property string|null $total_nao
 * @property int|null $codigo_empresa
 * @property bool|null $ativo
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $codigo_medico
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\Resposta[] $respostas
 */
class FichaPsicossocial extends AppEntity
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
        'total_sim' => true,
        'total_nao' => true,
        'codigo_empresa' => true,
        'ativo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_medico' => true,
        'data_alteracao' => true,
        'respostas' => true
    ];
}
