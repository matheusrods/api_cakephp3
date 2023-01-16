<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * UsuariosQuestionario Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario
 * @property int $codigo_questionario
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool|null $finalizado
 * @property \Cake\I18n\FrozenTime|null $concluido
 * @property int|null $codigo_empresa
 */
class UsuariosQuestionario extends AppEntity
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
        'codigo_usuario' => true,
        'codigo_questionario' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'finalizado' => true,
        'concluido' => true,
        'codigo_empresa' => true,
        'latitude'=>true,
        'longitude'=>true
    ];
}
