<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Setore Entity
 *
 * @property int $codigo
 * @property string|null $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool $ativo
 * @property int|null $codigo_cliente
 * @property int|null $codigo_empresa
 * @property string|null $codigo_rh
 * @property string|null $descricao_setor
 * @property string|null $observacao_aso
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\Caracteristica[] $caracteristicas
 */
class Setore extends Entity
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
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'codigo_cliente' => true,
        'codigo_empresa' => true,
        'codigo_rh' => true,
        'descricao_setor' => true,
        'observacao_aso' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'caracteristicas' => true,
    ];
}
