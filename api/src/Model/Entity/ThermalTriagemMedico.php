<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * ThermalTriagemMedico Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property string $cpf
 * @property string|null $nome
 * @property string $temperatura_medida
 * @property \Cake\I18n\FrozenTime $data_medicao
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $imagem_medicao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int $ativo
 */
class ThermalTriagemMedico extends AppEntity
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
        'cpf' => true,
        'nome' => true,
        'temperatura_medida' => true,
        'data_medicao' => true,
        'latitude' => true,
        'longitude' => true,
        'imagem_medicao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'ativo' => true,
    ];
}
