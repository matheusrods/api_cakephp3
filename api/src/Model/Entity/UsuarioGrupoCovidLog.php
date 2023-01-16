<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuarioGrupoCovidLog Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario_grupo_covid
 * @property int $codigo_usuario
 * @property int $codigo_grupo_covid
 * @property string $cpf
 * @property int $codigo_usuario_inclusao
 * @property int $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $data_alteracao
 * @property int $ativo
 * @property int|null $acao
 */
class UsuarioGrupoCovidLog extends Entity
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
        'codigo_usuario_grupo_covid' => true,
        'codigo_usuario' => true,
        'codigo_grupo_covid' => true,
        'cpf' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'ativo' => true,
        'acao' => true
    ];
}
