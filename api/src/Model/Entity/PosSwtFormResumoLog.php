<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PosSwtFormResumoLog Entity
 *
 * @property int $codigo
 * @property int|null $codigo_form_resumo
 * @property int|null $codigo_form
 * @property int|null $codigo_cliente
 * @property int|null $codigo_empresa
 * @property \Cake\I18n\FrozenDate|null $data_obs
 * @property string|null $hora_obs
 * @property string|null $desc_atividade
 * @property int|null $codigo_cliente_localidade
 * @property int|null $codigo_cliente_bu
 * @property int|null $codigo_cliente_opco
 * @property string|null $descricao
 * @property int $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acao_sistema
 * @property int|null $codigo_form_respondido
 */
class PosSwtFormResumoLog extends Entity
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
        'codigo_form_resumo'        => true,
        'codigo_form'               => true,
        'codigo_cliente'            => true,
        'codigo_empresa'            => true,
        'data_obs'                  => true,
        'hora_obs'                  => true,
        'desc_atividade'            => true,
        'codigo_cliente_localidade' => true,
        'codigo_cliente_bu'         => true,
        'codigo_cliente_opco'       => true,
        'descricao'                 => true,
        'ativo'                     => true,
        'codigo_usuario_inclusao'   => true,
        'data_inclusao'             => true,
        'codigo_usuario_alteracao'  => true,
        'data_alteracao'            => true,
        'acao_sistema'              => true,
        'codigo_form_respondido'    => true,
    ];
}
