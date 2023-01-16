<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesMelhoriasAssociadaLog Entity
 *
 * @property int $codigo
 * @property int $codigo_acao_melhoria_principal
 * @property int $codigo_acao_melhoria_relacionada
 * @property int $tipo_relacao
 * @property bool|null $abrangente
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 * @property int|null $acao_sistema
 * @property int $codigo_acao_melhoria_associada
 */
class AcoesMelhoriasAssociadaLog extends Entity
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
        'codigo_acao_melhoria_principal' => true,
        'codigo_acao_melhoria_relacionada' => true,
        'tipo_relacao' => true,
        'abrangente' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_remocao' => true,
        'acao_sistema' => true,
        'codigo_acao_melhoria_associada' => true,
    ];
}
