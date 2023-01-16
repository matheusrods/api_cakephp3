<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClientesFornecedore Entity
 *
 * @property int $codigo
 * @property int $codigo_cliente
 * @property int $codigo_fornecedor
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $ativo
 */
class ClientesFornecedore extends Entity
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
        'codigo_fornecedor' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true
    ];
}
