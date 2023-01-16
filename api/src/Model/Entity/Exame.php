<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Exame Entity
 *
 * @property int $codigo
 * @property int $codigo_servico
 * @property int|null $codigo_rh
 * @property string $descricao
 * @property string|null $periodo_meses
 * @property string|null $periodo_apos_demissao
 * @property int|null $codigo_tabela_amb
 * @property int|null $codigo_tuss
 * @property int|null $codigo_ch
 * @property int|null $empresa_cliente
 * @property int|null $exame_auto
 * @property int|null $laboral
 * @property string|null $tela_resultado
 * @property string|null $referencia
 * @property string|null $unidade_medida
 * @property string|null $recomendacoes
 * @property string|null $sexo
 * @property string|null $conduta_exame
 * @property int|null $controla_validacoes
 * @property string|null $codigo_esocial
 * @property string|null $material_biologico
 * @property string|null $interpretacao_exame
 * @property \Cake\I18n\FrozenTime|null $data_incio_monitoracao
 * @property int|null $exame_excluido_convocacao
 * @property int|null $exame_excluido_ppp
 * @property int|null $exame_excluido_aso
 * @property int|null $exame_excluido_pcmso
 * @property int|null $exame_excluido_anual
 * @property int|null $exame_excluido_rac
 * @property int|null $exame_admissional
 * @property int|null $exame_periodico
 * @property int|null $exame_demissional
 * @property int|null $exame_retorno
 * @property int|null $exame_mudanca
 * @property string|null $periodo_idade
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool $ativo
 * @property string|null $qtd_periodo_idade
 * @property int|null $codigo_empresa
 * @property int|null $qualidade_vida
 * @property string|null $periodo_idade_2
 * @property string|null $qtd_periodo_idade_2
 * @property string|null $periodo_idade_3
 * @property string|null $qtd_periodo_idade_3
 * @property string|null $periodo_idade_4
 * @property string|null $qtd_periodo_idade_4
 * @property bool|null $exame_audiometria
 * @property int|null $exame_monitoracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_esocial_27
 *
 * @property \App\Model\Entity\ExamesLog[] $exames_log
 * @property \App\Model\Entity\ItensPedido[] $itens_pedidos
 * @property \App\Model\Entity\Pedido[] $pedidos
 * @property \App\Model\Entity\PropostasCredenciamento[] $propostas_credenciamento
 * @property \App\Model\Entity\Risco[] $riscos
 */
class Exame extends AppEntity
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
        'codigo_servico' => true,
        'codigo_rh' => true,
        'descricao' => true,
        'periodo_meses' => true,
        'periodo_apos_demissao' => true,
        'codigo_tabela_amb' => true,
        'codigo_tuss' => true,
        'codigo_ch' => true,
        'empresa_cliente' => true,
        'exame_auto' => true,
        'laboral' => true,
        'tela_resultado' => true,
        'referencia' => true,
        'unidade_medida' => true,
        'recomendacoes' => true,
        'sexo' => true,
        'conduta_exame' => true,
        'controla_validacoes' => true,
        'codigo_esocial' => true,
        'material_biologico' => true,
        'interpretacao_exame' => true,
        'data_incio_monitoracao' => true,
        'exame_excluido_convocacao' => true,
        'exame_excluido_ppp' => true,
        'exame_excluido_aso' => true,
        'exame_excluido_pcmso' => true,
        'exame_excluido_anual' => true,
        'exame_excluido_rac' => true,
        'exame_admissional' => true,
        'exame_periodico' => true,
        'exame_demissional' => true,
        'exame_retorno' => true,
        'exame_mudanca' => true,
        'periodo_idade' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'qtd_periodo_idade' => true,
        'codigo_empresa' => true,
        'qualidade_vida' => true,
        'periodo_idade_2' => true,
        'qtd_periodo_idade_2' => true,
        'periodo_idade_3' => true,
        'qtd_periodo_idade_3' => true,
        'periodo_idade_4' => true,
        'qtd_periodo_idade_4' => true,
        'exame_audiometria' => true,
        'exame_monitoracao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_esocial_27' => true,
        'exames_log' => true,
        'itens_pedidos' => true,
        'pedidos' => true,
        'propostas_credenciamento' => true,
        'riscos' => true,
        'codigo_servico_lyn' => true
    ];
}
