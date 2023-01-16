<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HazopsAgentesRisco Entity
 *
 * @property int $codigo
 * @property string|null $causa
 * @property string|null $consequencia
 * @property string|null $codigo_severidade
 * @property string|null $codigo_dimensao_risco
 * @property int|null $interacao_pessoas
 * @property int|null $historico_ocorrencia
 * @property string|null $controle_fisico
 * @property string|null $controle_fisico_opcao
 * @property int|null $controle_dependencia_comportamental
 * @property string|null $controle_dependencia
 * @property string|null $hazop_level_risco
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_arrtpa_ri
 * @property int|null $codigo_hazop_keyword_tipo
 */
class HazopsAgentesRisco extends Entity
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
        'causa' => true,
        'consequencia' => true,
        'codigo_severidade' => true,
        'codigo_dimensao_risco' => true,
        'interacao_pessoas' => true,
        'historico_ocorrencia' => true,
        'controle_fisico' => true,
        'controle_fisico_opcao' => true,
        'controle_dependencia_comportamental' => true,
        'controle_dependencia' => true,
        'hazop_level_risco' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'codigo_arrtpa_ri' => true,
        'codigo_hazop_keyword_tipo' => true
    ];
}
