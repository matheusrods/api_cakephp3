<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PosSwtFormRespostaLog Entity
 *
 * @property int $codigo
 * @property int|null $codigo_form_resposta
 * @property int|null $codigo_form
 * @property int|null $codigo_form_questao
 * @property int|null $codigo_empresa
 * @property int|null $codigo_criticidade
 * @property string|null $resposta
 * @property string|null $motivo
 * @property int $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acao_sistema
 * @property int|null $codigo_form_respondido
 */
class PosSwtFormRespostaLog extends Entity
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
        'codigo_form_resposta' => true,
        'codigo_form' => true,
        'codigo_form_questao' => true,
        'codigo_empresa' => true,
        'codigo_criticidade' => true,
        'resposta' => true,
        'motivo' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'acao_sistema' => true,
        'codigo_form_respondido' => true,
    ];
}
