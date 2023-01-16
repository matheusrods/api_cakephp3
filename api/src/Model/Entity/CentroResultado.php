<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CentroResultado Entity
 *
 * @property int $codigo
 * @property int|null $codigo_empresa
 * @property int|null $codigo_cliente_matriz
 * @property int|null $codigo_cliente_alocacao
 * @property string|null $codigo_externo_centro_resultado
 * @property string|null $nome_centro_resultado
 * @property int|null $codigo_cliente_bu
 * @property int|null $codigo_cliente_opco
 * @property int|null $ativo
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class CentroResultado extends Entity
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
        'codigo_empresa' => true,
        'codigo_cliente_matriz' => true,
        'codigo_cliente_alocacao' => true,
        'codigo_externo_centro_resultado' => true,
        'nome_centro_resultado' => true,
        'codigo_cliente_bu' => true,
        'codigo_cliente_opco' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
    ];
}
