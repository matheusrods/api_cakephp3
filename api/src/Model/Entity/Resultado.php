<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Resultado Entity
 *
 * @property int $codigo
 * @property string|null $descricao
 * @property int $codigo_questionario
 * @property int $valor
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_usuario_alteracao
 * @property int $codigo_empresa
 *
 * @property \App\Model\Entity\AparelhosAudiometrico[] $aparelhos_audiometricos
 */
class Resultado extends AppEntity
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
        'codigo' => true,
        'descricao' => true,
        'codigo_questionario' => true,
        'valor' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_empresa' => true,
        'aparelhos_audiometricos' => true
    ];
}
