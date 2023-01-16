<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesMelhoria Entity
 *
 * @property int $codigo
 * @property int $codigo_origem_ferramenta
 * @property string $formulario_resposta
 * @property int $codigo_cliente_observacao
 * @property int $codigo_usuario_identificador
 * @property int $codigo_usuario_responsavel
 * @property int $codigo_pos_criticidade
 * @property int $codigo_acoes_melhorias_tipo
 * @property int $codigo_acoes_melhorias_status
 * @property \Cake\I18n\FrozenTime|null $prazo
 * @property string $descricao_desvio
 * @property string $descricao_acao
 * @property string $descricao_local_acao
 * @property \Cake\I18n\FrozenTime|null $data_conclusao
 * @property string|null $conclusao_observacao
 * @property bool|null $analise_implementacao_valida
 * @property bool|null $abrangente
 * @property bool|null $necessario_abrangencia
 * @property bool|null $necessario_eficacia
 * @property bool|null $necessario_implementacao
 * @property string|null $descricao_analise_implementacao
 * @property int|null $codigo_usuario_responsavel_analise_implementacao
 * @property \Cake\I18n\FrozenTime|null $data_analise_implementacao
 * @property bool|null $analise_eficacia_valida
 * @property string|null $descricao_analise_eficacia
 * @property int|null $codigo_usuario_responsavel_analise_eficacia
 * @property \Cake\I18n\FrozenTime|null $data_analise_eficacia
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $codigo_cliente_bu
 * @property int|null $codigo_cliente_opco
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 */
class AcoesMelhoria extends Entity
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
        'codigo_origem_ferramenta' => true,
        'formulario_resposta' => true,
        'codigo_cliente_observacao' => true,
        'codigo_usuario_identificador' => true,
        'codigo_usuario_responsavel' => true,
        'codigo_pos_criticidade' => true,
        'codigo_acoes_melhorias_tipo' => true,
        'codigo_acoes_melhorias_status' => true,
        'prazo' => true,
        'descricao_desvio' => true,
        'descricao_acao' => true,
        'descricao_local_acao' => true,
        'data_conclusao' => true,
        'conclusao_observacao' => true,
        'analise_implementacao_valida' => true,
        'descricao_analise_implementacao' => true,
        'codigo_usuario_responsavel_analise_implementacao' => true,
        'data_analise_implementacao' => true,
        'analise_eficacia_valida' => true,
        'descricao_analise_eficacia' => true,
        'codigo_usuario_responsavel_analise_eficacia' => true,
        'data_analise_eficacia' => true,
        'abrangente' => true,
        'necessario_abrangencia' => true,
        'necessario_eficacia' => true,
        'necessario_implementacao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_remocao' => true,
        'codigo_cliente_bu' => true,
        'codigo_cliente_opco' => true,
    ];
}
