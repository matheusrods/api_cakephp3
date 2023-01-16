<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuarioEndereco Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario_endereco_tipo
 * @property int $codigo_usuario
 * @property string|null $complemento
 * @property int|null $numero
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $codigo_empresa
 * @property string|null $cep
 * @property string|null $logradouro
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $estado_descricao
 * @property string|null $estado_abreviacao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class UsuarioEndereco extends Entity
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
        'codigo_usuario_endereco_tipo' => true,
        'codigo_usuario' => true,
        'complemento' => true,
        'numero' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'latitude' => true,
        'longitude' => true,
        'codigo_empresa' => true,
        'cep' => true,
        'logradouro' => true,
        'bairro' => true,
        'cidade' => true,
        'estado_descricao' => true,
        'estado_abreviacao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'descricao' => true,
        'endereco_completo' => true
    ];
}
