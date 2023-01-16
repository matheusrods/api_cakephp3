<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Produto Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool|null $ativo
 * @property string|null $codigo_naveg
 * @property string|null $codigo_ccusto_naveg
 * @property string|null $codigo_formula_naveg
 * @property bool|null $faturamento
 * @property string|null $codigo_formula_naveg_sp
 * @property bool $controla_volume
 * @property string|null $codigo_servico_prefeitura
 * @property float|null $formula_valor_acima_de
 * @property string|null $codigo_formula_naveg_sp_acima
 * @property string|null $codigo_formula_naveg_acima
 * @property float $valor_acima_irrf
 * @property float $percentual_irrf
 * @property float $percentual_irrf_acima
 * @property bool|null $mensalidade
 * @property int|null $codigo_empresa
 * @property int|null $codigo_antigo
 *
 * @property \App\Model\Entity\Cliente[] $cliente
 * @property \App\Model\Entity\ProdutoServico[] $produto_servico
 * @property \App\Model\Entity\ListasDePreco[] $listas_de_preco
 * @property \App\Model\Entity\Servico[] $servico
 * @property \App\Model\Entity\PropostasCredenciamento[] $propostas_credenciamento
 */
class Produto extends Entity
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
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'codigo_naveg' => true,
        'codigo_ccusto_naveg' => true,
        'codigo_formula_naveg' => true,
        'faturamento' => true,
        'codigo_formula_naveg_sp' => true,
        'controla_volume' => true,
        'codigo_servico_prefeitura' => true,
        'formula_valor_acima_de' => true,
        'codigo_formula_naveg_sp_acima' => true,
        'codigo_formula_naveg_acima' => true,
        'valor_acima_irrf' => true,
        'percentual_irrf' => true,
        'percentual_irrf_acima' => true,
        'mensalidade' => true,
        'codigo_empresa' => true,
        'codigo_antigo' => true,
        'cliente' => true,
        'produto_servico' => true,
        'listas_de_preco' => true,
        'servico' => true,
        'propostas_credenciamento' => true
    ];
}
