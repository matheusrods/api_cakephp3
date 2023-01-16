<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FuncionarioSetoresCargo Entity
 *
 * @property int $codigo
 * @property \Cake\I18n\FrozenDate|null $data_inicio
 * @property \Cake\I18n\FrozenDate|null $data_fim
 * @property int|null $codigo_cliente
 * @property int|null $codigo_setor
 * @property int|null $codigo_cargo
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_empresa
 * @property int $codigo_cliente_funcionario
 * @property int|null $codigo_cliente_alocacao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $codigo_cliente_referencia
 */
class FuncionarioSetoresCargo extends Entity
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
        'data_inicio' => true,
        'data_fim' => true,
        'codigo_cliente' => true,
        'codigo_setor' => true,
        'codigo_cargo' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_empresa' => true,
        'codigo_cliente_funcionario' => true,
        'codigo_cliente_alocacao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_cliente_referencia' => true
    ];
}
