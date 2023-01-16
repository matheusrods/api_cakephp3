<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EnderecoCidade Entity
 *
 * @property int $codigo
 * @property int $codigo_endereco_estado
 * @property int|null $codigo_endereco_cep
 * @property int|null $codigo_correio
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property string|null $abreviacao
 * @property bool $invalido
 * @property string|null $ibge
 */
class EnderecoCidade extends Entity
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
        'codigo_endereco_estado' => true,
        'codigo_endereco_cep' => true,
        'codigo_correio' => true,
        'descricao' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'abreviacao' => true,
        'invalido' => true,
        'ibge' => true,
    ];
}
