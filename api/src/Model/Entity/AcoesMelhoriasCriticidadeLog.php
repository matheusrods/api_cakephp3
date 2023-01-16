<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesMelhoriasCriticidadeLog Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property string $cor
 * @property bool $ativo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acao_sistema
 * @property int $codigo_acao_melhoria_criticidade
 */
class AcoesMelhoriasCriticidadeLog extends Entity
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
        'acao_sistema' => true,
        'codigo_acao_melhoria_criticidade' => true,
        'descricao' => true,
        'cor' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
    ];
}
