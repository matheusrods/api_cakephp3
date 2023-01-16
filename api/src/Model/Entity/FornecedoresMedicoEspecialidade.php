<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FornecedoresMedicoEspecialidade Entity
 *
 * @property int $codigo
 * @property int $codigo_fornecedor
 * @property int $codigo_medico
 * @property int $codigo_especialidade
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_empresa
 */
class FornecedoresMedicoEspecialidade extends Entity
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
        'codigo_medico' => true,
        'codigo_especialidade' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_empresa' => true
    ];
}
