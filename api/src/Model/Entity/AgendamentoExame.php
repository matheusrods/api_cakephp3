<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * AgendamentoExame Entity
 *
 * @property int $codigo
 * @property int $hora
 * @property int $codigo_fornecedor
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $ativo
 * @property int $codigo_itens_pedidos_exames
 * @property \Cake\I18n\FrozenDate $data
 * @property int|null $codigo_lista_de_preco_produto_servico
 * @property int|null $codigo_empresa
 * @property int|null $codigo_medico
 */
class AgendamentoExame extends AppEntity
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
        'hora' => true,
        'codigo_fornecedor' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'ativo' => true,
        'codigo_itens_pedidos_exames' => true,
        'data' => true,
        'codigo_lista_de_preco_produto_servico' => true,
        'codigo_empresa' => true,
        'codigo_medico' => true
    ];
}
