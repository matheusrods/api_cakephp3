<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PdaConfigRegraAcao Entity
 *
 * @property int $codigo
 * @property int $codigo_empresa
 * @property int $codigo_pda_config_regra
 * @property int $tipo_acao
 * @property int|null $ativo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $email
 * @property int|null $codigo_pda_tema_acoes
 */
class PdaConfigRegraAcao extends Entity
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
        'codigo_pda_config_regra' => true,
        'tipo_acao' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'email' => true,
        'codigo_pda_tema_acoes' => true,
    ];
}
