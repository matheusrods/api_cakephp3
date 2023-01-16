<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AgentesRisco Entity
 *
 * @property int $codigo
 * @property string|null $descricao_risco
 * @property string|null $descricao_exposicao
 * @property int|null $pessoas_expostas
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 */
class AgentesRisco extends Entity
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
        'descricao_risco' => true,
        'descricao_exposicao' => true,
        'pessoas_expostas' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_inclusao' => true,
        'data_remocao' => true
    ];
}
