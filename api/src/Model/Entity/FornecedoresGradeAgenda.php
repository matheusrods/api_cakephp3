<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresGradeAgenda Entity
 *
 * @property int $codigo
 * @property int $dia_semana
 * @property int $hora
 * @property int $capacidade_simultanea
 * @property int $tempo_consulta
 * @property int $codigo_fornecedor
 * @property int|null $codigo_lista_de_preco_produto_servico
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $ativo
 */
class FornecedoresGradeAgenda extends Entity
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
        'dia_semana' => true,
        'hora' => true,
        'capacidade_simultanea' => true,
        'tempo_consulta' => true,
        'codigo_fornecedor' => true,
        'codigo_lista_de_preco_produto_servico' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'ativo' => true
    ];
}
