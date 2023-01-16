<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClienteProduto Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property int $codigo_produto
 * @property \Cake\I18n\FrozenTime|null $data_faturamento
 * @property int $codigo_motivo_bloqueio
 * @property bool $possui_contrato
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $qtd_premio_minimo
 * @property float $valor_premio_minimo
 * @property float|null $valor_taxa_corretora
 * @property float|null $valor_taxa_bancaria
 * @property \Cake\I18n\FrozenTime|null $data_inativacao
 * @property bool|null $pendencia_comercial
 * @property bool|null $pendencia_financeira
 * @property bool|null $pendencia_juridica
 * @property int|null $codigo_motivo_bloqueio_bkp
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property bool $premio_minimo_por_produto
 * @property int|null $codigo_motivo_bloqueio_bkp2
 * @property int|null $codigo_motivo_cancelamento
 * @property int|null $codigo_empresa
 *
 * @property \App\Model\Entity\Servico[] $servico
 * @property \App\Model\Entity\Servico2[] $servico2
 * @property \App\Model\Entity\ServicoLog[] $servico_log
 */
class ClienteProduto extends Entity
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
        'codigo_produto' => true,
        'data_faturamento' => true,
        'codigo_motivo_bloqueio' => true,
        'possui_contrato' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'qtd_premio_minimo' => true,
        'valor_premio_minimo' => true,
        'valor_taxa_corretora' => true,
        'valor_taxa_bancaria' => true,
        'data_inativacao' => true,
        'pendencia_comercial' => true,
        'pendencia_financeira' => true,
        'pendencia_juridica' => true,
        'codigo_motivo_bloqueio_bkp' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'premio_minimo_por_produto' => true,
        'codigo_motivo_bloqueio_bkp2' => true,
        'codigo_motivo_cancelamento' => true,
        'codigo_empresa' => true,
        'servico' => true,
        'servico2' => true,
        'servico_log' => true
    ];
}
