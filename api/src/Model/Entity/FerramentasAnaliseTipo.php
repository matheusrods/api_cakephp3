<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FerramentasAnaliseTipo Entity
 *
 * @property int $codigo
 * @property int $codigo_metodo_tipo
 * @property string|null $ferramenta_analise_categoria
 * @property string|null $descricao
 * @property string|null $versao
 * @property string|null $ferramenta_analise_form
 * @property string|null $ferramenta_analise_regras
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $codigo_cliente
 */
class FerramentasAnaliseTipo extends Entity
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
        'codigo_metodo_tipo' => true,
        'ferramenta_analise_categoria' => true,
        'descricao' => true,
        'versao' => true,
        'ferramenta_analise_form' => true,
        'ferramenta_analise_regras' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'codigo_cliente' => true
    ];
}
