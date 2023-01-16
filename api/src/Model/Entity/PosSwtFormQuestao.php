<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * PosSwtFormQuestao Entity
 *
 * @property int $codigo
 * @property int|null $codigo_form
 * @property int|null $codigo_form_titulo
 * @property int|null $codigo_empresa
 * @property int|null $codigo_cliente
 * @property string|null $questao
 * @property int|null $ordem
 * @property string|null $informacao
 * @property int $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $saiba_mais

 */
class PosSwtFormQuestao extends AppEntity
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
        'codigo_form' => true,
        'codigo_form_titulo' => true,
        'codigo_empresa' => true,
        'codigo_cliente' => true,
        'questao' => true,
        'ordem' => true,
        'informacao' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'saiba_mais' => true
    ];
}
