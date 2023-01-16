<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichaPsicossocialResposta Entity
 *
 * @property int $codigo
 * @property string|null $resposta
 * @property int $ativo
 * @property int|null $ordem
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int $codigo_ficha_psicossocial
 * @property int $codigo_ficha_psicossocial_perguntas
 */
class FichaPsicossocialResposta extends AppEntity
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
        'resposta' => true,
        'ativo' => true,
        'ordem' => true,
        'data_inclusao' => true,
        'codigo_ficha_psicossocial' => true,
        'codigo_ficha_psicossocial_perguntas' => true
    ];
}
