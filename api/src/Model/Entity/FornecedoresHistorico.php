<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresHistorico Entity
 *
 * @property int $codigo
 * @property int|null $codigo_fornecedor
 * @property string|null $caminho_arquivo
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $ativo
 * @property int|null $codigo_empresa
 * @property string|null $observacao
 */
class FornecedoresHistorico extends Entity
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
        'codigo_fornecedor' => true,
        'caminho_arquivo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'ativo' => true,
        'codigo_empresa' => true,
        'observacao' => true,
    ];
}
