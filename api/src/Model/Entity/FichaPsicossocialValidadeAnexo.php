<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichaPsicossocialValidadeAnexo Entity
 *
 * @property int $codigo
 * @property int $codigo_ficha_psicossocial
 * @property \Cake\I18n\FrozenTime $data_validade
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 *
 * @property \App\Model\Entity\Resposta[] $respostas
 */
class FichaPsicossocialValidadeAnexo extends AppEntity
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
        'codigo_ficha_psicossocial' => true,
        'data_validade' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
    ];
}
