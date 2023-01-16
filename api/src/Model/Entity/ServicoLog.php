<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServicoLog Entity
 *
 * @property int $codigo
 * @property int $codigo_servico
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool|null $ativo
 * @property string|null $tipo_servico
 * @property string|null $codigo_externo
 * @property int|null $codigo_empresa
 * @property int|null $codigo_antigo
 * @property int|null $codigo_classificacao_servico
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\ClienteProduto[] $cliente_produto
 * @property \App\Model\Entity\ListasDePrecoProduto[] $listas_de_preco_produto
 */
class ServicoLog extends Entity
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
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'tipo_servico' => true,
        'codigo_externo' => true,
        'codigo_empresa' => true,
        'codigo_antigo' => true,
        'codigo_classificacao_servico' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'cliente_produto' => true,
        'listas_de_preco_produto' => true
    ];
}
