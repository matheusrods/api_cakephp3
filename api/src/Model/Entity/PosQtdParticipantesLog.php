<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PosQtdParticipantesLog Entity
 *
 * @property int $codigo
 * @property int|null $codigo_pos_qtd_participante
 * @property int|null $codigo_empresa
 * @property int|null $codigo_pos_ferramenta
 * @property int|null $codigo_cliente
 * @property int $quantidade
 * @property int $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acao_sistema
 */
class PosQtdParticipantesLog extends Entity
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
        'codigo_pos_qtd_participante' => true,
        'codigo_empresa' => true,
        'codigo_pos_ferramenta' => true,
        'codigo_cliente' => true,
        'quantidade' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'acao_sistema' => true,
    ];
}
