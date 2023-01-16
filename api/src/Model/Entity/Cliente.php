<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Cliente Entity
 *
 * @property int $codigo
 * @property string $codigo_documento
 * @property int|null $codigo_corporacao
 * @property int|null $codigo_corretora
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property string|null $inscricao_estadual
 * @property string|null $ccm
 * @property float|null $iss
 * @property int|null $codigo_endereco_regiao
 * @property bool|null $regiao_tipo_faturamento
 * @property bool $ativo
 * @property bool $uso_interno
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property float|null $comissao_gestor
 * @property float|null $comissao_representante
 * @property string|null $cnae
 * @property int|null $codigo_gestor
 * @property \Cake\I18n\FrozenTime|null $data_inativacao
 * @property \Cake\I18n\FrozenTime|null $data_ativacao
 * @property int|null $codigo_area_atuacao
 * @property int|null $codigo_sistema_monitoramento
 * @property bool $obrigar_loadplan
 * @property bool $iniciar_por_checklist
 * @property bool $monitorar_retorno
 * @property int|null $temperatura_de
 * @property int|null $temperatura_ate
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $codigo_gestor_npe
 * @property int|null $codigo_regime_tributario
 * @property bool|null $utiliza_mopp
 * @property int|null $tempo_minimo_mopp
 * @property int|null $codigo_gestor_operacao
 * @property int|null $codigo_gestor_contrato
 * @property int|null $codigo_cliente_sub_tipo
 * @property string|null $suframa
 * @property int|null $codigo_seguradora
 * @property int|null $codigo_plano_saude
 * @property int|null $codigo_empresa
 * @property int|null $codigo_medico_pcmso
 * @property int|null $codigo_medico_responsavel
 * @property string|null $codigo_externo
 * @property string|null $codigo_documento_real
 * @property string|null $tipo_unidade
 * @property int|null $codigo_naveg
 * @property bool $e_tomador
 * @property int|null $aguardar_liberacao
 * @property int|null $flag_logo_lyn
 * @property string|null $cor_primaria
 * @property string|null $cor_secundaria
 * @property int|null $flag_logo_gestao_risco
 * @property int|null $flag_metodo_hazop
 * @property int|null $flag_pda
 * @property int|null $flag_swt
 * @property int|null $flag_obs
 *
 * @property \App\Model\Entity\Endereco[] $endereco
 * @property \App\Model\Entity\Operacao[] $operacao
 * @property \App\Model\Entity\Produto[] $produto
 * @property \App\Model\Entity\ProdutoServico[] $produto_servico
 * @property \App\Model\Entity\GruposEconomico[] $grupos_economicos
 */
class Cliente extends AppEntity
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
        'codigo_documento' => true,
        'codigo_corporacao' => true,
        'codigo_corretora' => true,
        'razao_social' => true,
        'nome_fantasia' => true,
        'inscricao_estadual' => true,
        'ccm' => true,
        'iss' => true,
        'codigo_endereco_regiao' => true,
        'regiao_tipo_faturamento' => true,
        'ativo' => true,
        'uso_interno' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'comissao_gestor' => true,
        'comissao_representante' => true,
        'cnae' => true,
        'codigo_gestor' => true,
        'data_inativacao' => true,
        'data_ativacao' => true,
        'codigo_area_atuacao' => true,
        'codigo_sistema_monitoramento' => true,
        'obrigar_loadplan' => true,
        'iniciar_por_checklist' => true,
        'monitorar_retorno' => true,
        'temperatura_de' => true,
        'temperatura_ate' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_gestor_npe' => true,
        'codigo_regime_tributario' => true,
        'utiliza_mopp' => true,
        'tempo_minimo_mopp' => true,
        'codigo_gestor_operacao' => true,
        'codigo_gestor_contrato' => true,
        'codigo_cliente_sub_tipo' => true,
        'suframa' => true,
        'codigo_seguradora' => true,
        'codigo_plano_saude' => true,
        'codigo_empresa' => true,
        'codigo_medico_pcmso' => true,
        'codigo_medico_responsavel' => true,
        'codigo_externo' => true,
        'codigo_documento_real' => true,
        'tipo_unidade' => true,
        'codigo_naveg' => true,
        'e_tomador' => true,
        'aguardar_liberacao' => true,
        'endereco' => true,
        'operacao' => true,
        'produto' => true,
        'produto_servico' => true,
        'grupos_economicos' => true,
        'flag_logo_lyn' => true,
        'cor_primaria' => true,
        'cor_secundaria' => true,
        'flag_logo_gestao_risco' => true,
        'flag_metodo_hazop' => true,
        'flag_pda' => true,
        'flag_swt' => true,
        'flag_obs' => true,
    ];


    protected function _getRazaoSocial($registro)
    {
        return $this->iconv($registro);
    }

    protected function _getNomeFantasia($registro)
    {
        return $this->iconv($registro);
    }

}
