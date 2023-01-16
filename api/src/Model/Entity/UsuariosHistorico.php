<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuariosHistorico Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario
 * @property string $remote_addr
 * @property string $http_user_agent
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string|null $message
 * @property int $fail
 * @property int|null $codigo_empresa
 * @property \Cake\I18n\FrozenTime|null $data_logout
 * @property int|null $codigo_sistema
 */
class UsuariosHistorico extends Entity
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
        'remote_addr' => true,
        'http_user_agent' => true,
        'data_inclusao' => true,
        'message' => true,
        'fail' => true,
        'codigo_empresa' => true,
        'data_logout' => true,
        'codigo_sistema' => true,
    ];
}
