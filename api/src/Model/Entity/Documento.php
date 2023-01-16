<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Documento Entity
 *
 * @property string $codigo
 * @property int $codigo_pais
 * @property bool $tipo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_empresa
 *
 * @property \App\Model\Entity\Fornecedore[] $fornecedores
 * @property \App\Model\Entity\PropostasCredenciamento[] $propostas_credenciamento
 */
class Documento extends Entity
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
        'codigo_pais' => true,
        'tipo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_empresa' => true,
        'fornecedores' => true,
        'propostas_credenciamento' => true,
    ];
}
