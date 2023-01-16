<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * PosCriticidade Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property string $cor
 * @property bool|null $ativo
 * @property int $codigo_cliente
 * @property int $codigo_pos_ferramenta
 * @property string|null $observacao
 * @property int $codigo_empresa
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $valor_inicio
 * @property int|null $valor_fim
 */
class PosCriticidade extends AppEntity
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
        'descricao' => true,
        'cor' => true,
        'ativo' => true,
        'codigo_cliente' => true,
        'codigo_pos_ferramenta' => true,
        'observacao' => true,
        'codigo_empresa' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'valor_inicio' => true,
        'valor_fim' => true,
    ];

    protected function _getDescricao($descricao)
    {
        return !is_null($descricao) ? utf8_decode(mb_convert_encoding($descricao, 'UTF-8')) : null;
    }
}
