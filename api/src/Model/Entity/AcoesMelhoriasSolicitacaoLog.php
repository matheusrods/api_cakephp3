<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesMelhoriasSolicitacaoLog Entity
 *
 * @property int $codigo
 * @property int $codigo_acao_melhoria_solicitacao
 * @property int|null $acao_sistema
 * @property int $codigo_acao_melhoria
 * @property int $codigo_acao_melhoria_solicitacao_tipo
 * @property int|null $codigo_novo_usuario_responsavel
 * @property int $codigo_usuario_solicitado
 * @property int $status
 * @property \Cake\I18n\FrozenTime|null $novo_prazo
 * @property string|null $justificativa_solicitacao
 * @property int|null $codigo_acao_melhoria_solicitacao_antecedente
 * @property string|null $justificativa_recusa
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $usuario_solicitado_tipo
 * @property bool|null $alteracao_sistema
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 */
class AcoesMelhoriasSolicitacaoLog extends Entity
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
        'codigo_acao_melhoria' => true,
        'codigo_acao_melhoria_solicitacao_tipo' => true,
        'codigo_novo_usuario_responsavel' => true,
        'codigo_usuario_solicitado' => true,
        'codigo_acao_melhoria_solicitacao_antecedente' => true,
        'status' => true,
        'novo_prazo' => true,
        'justificativa_solicitacao' => true,
        'justificativa_recusa' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'usuario_solicitado_tipo' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_remocao' => true,
        'alteracao_sistema' => true,
        'codigo_acao_melhoria_solicitacao' => true,
        'acao_sistema' => true,
    ];
}
