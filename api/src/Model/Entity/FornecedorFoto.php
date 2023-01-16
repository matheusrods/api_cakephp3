<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\AppEntity;

/**
 * FornecedorFoto Entity
 *
 * @property int $codigo
 * @property int $codigo_fornecedor
 * @property string $caminho_arquivo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $status
 * @property string|null $descricao
 * @property int|null $codigo_empresa
 */
class FornecedorFoto extends AppEntity
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
        'status' => true,
        'descricao' => true,
        'codigo_empresa' => true
    ];
}
