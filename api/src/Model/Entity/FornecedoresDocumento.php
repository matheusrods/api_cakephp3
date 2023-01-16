<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresDocumento Entity
 *
 * @property int $codigo
 * @property int $codigo_fornecedor
 * @property int $codigo_tipo_documento
 * @property string $caminho_arquivo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $validado
 * @property \Cake\I18n\FrozenDate|null $data_validade
 * @property int|null $codigo_empresa
 */
class FornecedoresDocumento extends Entity
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
        'codigo_tipo_documento' => true,
        'caminho_arquivo' => true,
        'data_inclusao' => true,
        'validado' => true,
        'data_validade' => true,
        'codigo_empresa' => true,
    ];
}
