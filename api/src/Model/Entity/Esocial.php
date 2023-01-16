<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Esocial Entity
 *
 * @property int $codigo
 * @property int $tabela
 * @property int|null $codigo_pai
 * @property string|null $codigo_descricao
 * @property string|null $descricao
 * @property string|null $coluna_adicional
 * @property string|null $coluna_adicional2
 * @property int $nivel
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property bool|null $ativo
 */
class Esocial extends AppEntity
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
        'tabela' => true,
        'codigo_pai' => true,
        'codigo_descricao' => true,
        'descricao' => true,
        'coluna_adicional' => true,
        'coluna_adicional2' => true,
        'nivel' => true,
        'data_inclusao' => true,
        'ativo' => true
    ];

    protected function _getDescricao($registro)
    {
        return $registro;
    }
}
