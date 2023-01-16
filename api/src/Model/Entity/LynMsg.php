<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * LynMsg Entity
 *
 * @property int $codigo
 * @property string|null $descricao
 * @property string|null $nome
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $ativo
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $titulo
 */
class LynMsg extends AppEntity
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
        'nome' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'titulo' => true,
    ];
}
