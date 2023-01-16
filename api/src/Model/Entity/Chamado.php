<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Chamado Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property string $descricao
 * @property int $codigo_chamado_tipo
 * @property int $codigo_chamado_status
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_original
 * @property \Cake\I18n\FrozenTime|null $data_adiar_de
 * @property \Cake\I18n\FrozenTime|null $data_adiar_para
 * @property string $razao_adiar
 * @property \Cake\I18n\FrozenTime|null $data_cancelamento
 * @property string $razao_cancelamento
 * @property int $responsavel
 * @property string $descricao_levantamento
 */
class Chamado extends Entity
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
        'descricao' => true,
        'codigo_chamado_tipo' => true,
        'codigo_chamado_status' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'data_original' => true,
        'data_adiar_de' => true,
        'data_adiar_para' => true,
        'razao_adiar' => true,
        'data_cancelamento' => true,
        'razao_cancelamento' => true,
        'responsavel' => true,
        'descricao_levantamento' => true
    ];
}
