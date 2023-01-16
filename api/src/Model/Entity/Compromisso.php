<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Compromisso Entity
 *
 * @property int $codigo
 * @property int|null $codigo_medico
 * @property int $hora_inicio
 * @property int $hora_fim
 * @property string $titulo
 * @property string $descricao
 * @property bool|null $ativo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenDate|null $data
 */
class Compromisso extends Entity
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
        'codigo_medico' => true,
        'hora_inicio' => true,
        'hora_fim' => true,
        'titulo' => true,
        'descricao' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data' => true
    ];
}
