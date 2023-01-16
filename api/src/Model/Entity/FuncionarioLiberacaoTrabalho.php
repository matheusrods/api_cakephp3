<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;



/**
 * FuncionarioLiberacaoTrabalho Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property int|null $codigo_setor
 * @property int|null $codigo_cargo
 * @property int|null $codigo_funcionario
 * @property \Cake\I18n\FrozenDate|null $data_inicio_previsao
 * @property \Cake\I18n\FrozenDate|null $data_fim_previsao
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_func_setor_cargo
 */
class FuncionarioLiberacaoTrabalho extends AppEntity
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
        'codigo_setor' => true,
        'codigo_cargo' => true,
        'codigo_funcionario' => true,
        'data_inicio_previsao' => true,
        'data_fim_previsao' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_func_setor_cargo' => true,
    ];
}
