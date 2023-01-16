<?php
namespace App\Model\Entity;

use App\Model\Entity\PosEntity as Entity;

/**
 * PosAnexo Entity
 *
 * @property int $codigo
 * @property int $codigo_empresa
 * @property int $codigo_cliente
 * @property int $codigo_pos_ferramenta
 * @property string $arquivo_url
 * @property string|null $arquivo_url_curta
 * @property int|null $arquivo_tipo
 * @property string|null $arquivo_extensao
 * @property string|null $arquivo_tamanho_bytes
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property bool $ativo
 */
class PosAnexo extends Entity
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
        'codigo_empresa' => true,
        'codigo_cliente' => true,
        'codigo_pos_ferramenta' => true,
        'arquivo_url' => true,
        'arquivo_url_curta' => true,
        'arquivo_tipo' => true,
        'arquivo_extensao' => true,
        'arquivo_tamanho_bytes' => true,
        'arquivo_hash' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'ativo' => true,
    ];
}
