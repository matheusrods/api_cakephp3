<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResultadoCovidLog Entity
 *
 * @property int $codigo
 * @property int|null $codigo_resultado_covid
 * @property int|null $codigo_usuario
 * @property int|null $codigo_grupo_covid
 * @property int|null $passaporte
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $acao_sistema
 */
class ResultadoCovidLog extends Entity
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
        'codigo_resultado_covid' => true,
        'codigo_usuario' => true,
        'codigo_grupo_covid' => true,
        'passaporte' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'acao_sistema' => true,
    ];
}
